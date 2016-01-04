<?php defined('SYSPATH') OR die('No Direct Script Access');

Class Model_Comments extends Model_preDispatch
{
	public $id;
	public $author;
	public $status;
	public $text;
	public $page_id;
	public $root_id;
	public $parent_id;
	public $dt_create;
	public $is_removed;
	public $author_name;
	public $parent_name;

	public function __construct()
	{
	}

	/** 
	 * Возвращает комментарий с указанным id из БД.
	 * Иначе возвращает пустой комментарий с id = 0.
	 */	
	public static function get($id = 0)
	{
		$comment_row = Dao_Comments::select()->where('id', '=', $id);

		$model = new Model_Comments();

		return $model->fillByRow($comment_row);
	}

	/** 
	 * Добавляет комментарий в БД.
	 */	
	public function insert()
	{
        $idAndRowAffected = Dao_Comments::insert()
                                ->set('author',     $this->author)
                                ->set('text',       $this->text)
                                ->set('page_id',    $this->page_id)
                                ->set('root_id',    $this->root_id)
                                ->set('parent_id',  $this->parent_id)
                                ->execute();
        
		if ($idAndRowAffected) {
			$comment = Dao_Comments::select()
				->where('id', '=', $idAndRowAffected[0])
                ->limit(1)
				->execute();

			$this->fillByRow($comment);
		}
	}

	/** 
	 * Заполняет объект строкой из БД.
	 */
	private function fillByRow($comment_row)
	{
		if (!empty($comment_row['id'])) {

            $this->id           = $comment_row['id'];
            $this->author       = $comment_row['author'];
            $this->status       = $comment_row['status'];
            $this->text         = $comment_row['text'];
            $this->page_id      = $comment_row['page_id'];
            $this->root_id      = $comment_row['root_id'];
            $this->parent_id    = $comment_row['parent_id'];
            $this->dt_create    = $comment_row['dt_create'];
            $this->is_removed   = $comment_row['is_removed'];   
            $this->author_name  = self::getAuthor($comment_row['author']);
            $this->parent_name  = self::getAuthor($comment_row['parent_id']);
        }

        return $this;
	}

	public static function getByPageId($page_id)
    {
        $comments = array();

        if (!empty($page_id)) {
            $comment_rows = Dao_Comments::select()
                ->where('page_id', '=', $page_id)
                ->where('is_removed', '=', 0)
                ->order_by('id', 'ASC')
                ->execute();
            
            if ($comment_rows) {
                foreach ($comment_rows as $comment_row) {
                    $comment = new Model_Comments();

                    $comment->fillByRow($comment_row);

                    array_push($comments, $comment);
                }
            }
        }

        return $comments;
    }
    
    /**
     * Получаем имя автора по id.
     */
    public static function getAuthor($id)
    {
        $model_user = new Model_User($id);
        return $model_user->name;
    }
    
    /**
     * Удаляем комментарий и все его подкомментарии
     */
    public function delete_comment($user)
    {
        // получаем id статьи для редиректа
        $comment = Dao_Comments::select('*')->where('id', '=', $this->id)->execute();
        $page_id = $comment[0]['page_id'];

        if ($this->author == $user->id)
        {
            Dao_Comments::update()
                ->where('id', '=', $this->id)
                ->where('parent_id', '=', $this->id)
                ->set('is_removed', 1)
                ->execute(); 
        }

        return $page_id;

    }


}

?>