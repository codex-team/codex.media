<?php defined('SYSPATH') OR die('No Direct Script Access');

Class Model_Comment extends Model_preDispatch
{
    public $id             = 0;
    public $author         = null;
    public $text           = '';
    public $page_id        = 0;
    public $root_id        = 0;
    public $parent_id      = 0;
    public $parent_comment = null;
    public $dt_create      = null;
    public $is_removed     = 0;

    const USER_COMMENTS_LIMIT_PER_PAGE = 7; # Must be > 1

    public function __construct($id = 0)
    {
        if ($id) return self::get($id);

        return false;
    }

    /**
     * Возвращает комментарий с указанным id из БД.
     * Иначе возвращает пустой комментарий с id = 0.
     */
    public static function get($id = 0)
    {
        $comment_row = Dao_Comments::select()
            ->where('id', '=', $id)
            ->limit(1)
            ->cached(Date::MINUTE * 5, 'comment:' . $id)
            ->execute();

        $model = new Model_Comment();

        return $model->fillByRow($comment_row);
    }

    /**
     * Заполняет объект строкой из БД.
     */
    private function fillByRow($comment_row)
    {
        if (!empty($comment_row['id'])) {
            $this->id         = $comment_row['id'];
            $this->author     = new Model_User($comment_row['user_id']);
            $this->text       = $comment_row['text'];
            $this->page_id    = $comment_row['page_id'];
            $this->root_id    = $comment_row['root_id'];
            $this->parent_id  = $comment_row['parent_id'];
            $this->dt_create  = $comment_row['dt_create'];
            $this->is_removed = $comment_row['is_removed'];
            $this->page       = new Model_Page($this->page_id);
        }

        return $this;
    }

    /**
     * Добавляет комментарий в БД.
     */
    public function insert()
    {
        $idAndRowAffected = Dao_Comments::insert()
            ->set('user_id',   $this->author['id'])
            ->set('text',      $this->text)
            ->set('page_id',   $this->page_id)
            ->set('root_id',   $this->root_id)
            ->set('parent_id', $this->parent_comment['id'])
            ->clearcache('comments_page:' . $this->page_id, array('comments:by:page:' . $this->page_id))
            ->execute();

        if ($this->root_id == 0) {
            Dao_Comments::update()
                ->where('id', '=', $idAndRowAffected)
                ->set('root_id', $idAndRowAffected)
                ->execute();
        }

        return $idAndRowAffected;
    }

    public static function getCommentsByPageId($page_id)
    {
        $comment_rows = Dao_Comments::select()
            ->where('page_id', '=', $page_id)
            ->where('is_removed', '=', 0)
            ->order_by('id', 'ASC')
            ->cached(Date::MINUTE * 5, 'comments_page:' . $page_id, array('comments:by:page:' . $page_id))
            ->execute();

        return self::rowsToModels($comment_rows, true);
    }

    public static function getCommentsByUserId($user_id, $pagination_number = 1)
    {
        $offset = ($pagination_number - 1) * self::USER_COMMENTS_LIMIT_PER_PAGE;

        $comment_rows = Dao_Comments::select()
            ->where('user_id', '=', $user_id)
            ->where('is_removed', '=', 0)
            ->order_by('id', 'DESC')
            ->offset($offset)
            ->limit(self::USER_COMMENTS_LIMIT_PER_PAGE + 1)
            ->execute();

        $models = self::rowsToModels($comment_rows, true);

        $next_page = false;

        if (count($models) > self::USER_COMMENTS_LIMIT_PER_PAGE) {

            $next_page = true;
            unset($models[self::USER_COMMENTS_LIMIT_PER_PAGE]);
        }

        return [
            "models" => $models,
            "next_page" => $next_page
        ];
    }

    /**
     * Получает родительский комментарий из списка всех комментариев по переданному parent_id
     *
     * @var $allComments массив комментариев, где проводится поиск
     * @var $parent_id   id родительского комментария
     */
    private static function getParentForCommentFromCommentsArray($allComments, $parent_id)
    {
        $parent = array();

        foreach ($allComments as $parent_row) {

            if ($parent_id == $parent_row['id']) {

                $parent = new Model_Comment;

                return $parent->fillByRow($parent_row);
            }
        }

        return false;
    }

    /**
     * Возвращает массив моделей комментариев
     *
     * @var $comment_rows
     * @var $add_parent (boolean) добавлять ли в модели информацию о родительских комментариях
     */
    private static function rowsToModels($comment_rows, $add_parent = false)
    {
        $comments = array();

        if ($comment_rows) {

            foreach ($comment_rows as $comment_row) {

                $comment = new Model_Comment();

                $comment->fillByRow($comment_row);

                /* добавление информации о родительском комментарии */
                if ($add_parent) {

                    $parent = array();
                    $parent = self::getParentForCommentFromCommentsArray($comment_rows, $comment_row['parent_id']);
                    $comment->parent_comment = $parent;
                }

                array_push($comments, $comment);
            }
        }

        return $comments;
    }

    /**
     * Получаем массив моделей подкомментариев
     */
    public function getSubcomments()
    {
        $subcomments = Dao_Comments::select()
            ->where('parent_id', '=', $this->id)
            ->where('is_removed', '=', 0)
            ->order_by('id', 'ASC')
            ->execute();

        return self::rowsToModels($subcomments);
    }

    /**
     * Удаляет комментарий и все его подкомментарии
     */
    public function delete($with_subcomments = false)
    {
        /* удалить сам комментарий */
        Dao_Comments::update()
            ->where('id', '=', $this->id)
            ->set('is_removed', 1)
            ->clearcache('comments_page:' . $this->page_id, array('comments:by:page:' . $this->page_id))
            ->execute();

        /* удалить подкомментарии */
        if ($with_subcomments) {

            $subcomments = $this->getSubcomments();

            foreach ($subcomments as $subcomment) {

                $subcomment->delete(true);
            }
        }
    }
}
