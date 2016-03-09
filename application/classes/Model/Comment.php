<?php defined('SYSPATH') OR die('No Direct Script Access');

Class Model_Comment extends Model_preDispatch
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
    public $author_photo;
    public $parent_name;
    
    public function __construct()
    {
    }

    /* 
    ** Возвращает комментарий с указанным id из БД.
    ** Иначе возвращает пустой комментарий с id = 0.
    */	
    public static function get($id = 0)
    {
        $comment_row = Dao_Comments::select()
            ->where('id', '=', $id)
            ->limit(1)->execute();
        
        $model = new Model_Comment();
        
        return $model->fillByRow($comment_row);
    }

    /* 
    ** Добавляет комментарий в БД.
    */	
    public function insert()
    {
        $idAndRowAffected = Dao_Comments::insert()
            ->set('author',    $this->author)
            ->set('text',      $this->text)
            ->set('page_id',   $this->page_id)
            ->set('root_id',   $this->root_id)
            ->set('parent_id', $this->parent_id)
            ->execute();
        
        if ($idAndRowAffected) {
            $comment = Dao_Comments::select()
                ->where('id', '=', $idAndRowAffected[0])
                ->limit(1)
                ->execute();
            
            $this->fillByRow($comment);
        }
    }

    /*
    ** Заполняет объект строкой из БД.
    */
    private function fillByRow($comment_row)
    {
        if (!empty($comment_row['id'])) {
            $author            = self::getAuthor($comment_row['author']);
            $this->id          = $comment_row['id'];
            $this->author      = $comment_row['author'];
            $this->status      = $comment_row['status'];
            $this->text        = $comment_row['text'];
            $this->page_id     = $comment_row['page_id'];
            $this->root_id     = $comment_row['root_id'];
            $this->parent_id   = $comment_row['parent_id'];
            $this->dt_create   = $comment_row['dt_create'];
            $this->is_removed  = $comment_row['is_removed'];   
            $this->author_name = $author[0];
            $this->author_photo = $author[1];
            $this->parent_name = self::getAuthorByCommentId($comment_row['parent_id']);
        }
        
        return $this;
    }
    
    public static function getCommentsByPageId($page_id)
    {
        $comments = array();
        
        $comments_tree = array();

        if (!empty($page_id)) {
            $comment_rows = Dao_Comments::select()
                ->where('page_id', '=', $page_id)
                ->where('is_removed', '=', 0)
                ->order_by('id', 'ASC')
                ->execute();
            
            if ($comment_rows) {
                foreach ($comment_rows as $comment_row) {
                    $comment = new Model_Comment();

                    $comment->fillByRow($comment_row);

                    array_push($comments, $comment);                    
                }
                
                foreach ($comments as $comment) {
                    if (!in_array($comment, $comments_tree))
                        array_push($comments_tree, $comment);
                    
                    foreach ($comments as $comment_second) {
                        if ($comment_second->parent_id == $comment->id)
                            array_push($comments_tree, $comment_second);
                    }
                }
            }
        }

        return $comments_tree;
    }
    
    /*
    ** Получаем имя и фото автора по его id.
    */
    public static function getAuthor($user_id)
    {
        $model_user = new Model_User($user_id);
        $author = array($model_user->name, $model_user->photo);
        return $author;
    }
    
    /*
    ** Получаем имя и фото автора по id комментария.
    */
    public static function getAuthorByCommentId($id)
    {
        $comment = self::get($id);
        $author = self::getAuthor($comment->author);
        return $author[0];
    }
    
    /*
    ** Удаляем комментарий и все его подкомментарии
    */
    public function delete()
    {
        Dao_Comments::update()
            ->where('id', '=', $this->id)
            ->set('is_removed', 1)
            ->execute(); 
            
        Dao_Comments::update()
            ->where('parent_id', '=', $this->id)
            ->set('is_removed', 1)
            ->execute(); 
    }


}

?>