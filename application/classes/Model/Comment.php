<?php defined('SYSPATH') OR die('No Direct Script Access');

Class Model_Comment extends Model_preDispatch
{
    public $id;
    public $author;
    public $text;
    public $page_id;
    public $root_id;
    public $parent_comment;
    public $dt_create;
    public $is_removed;
    
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
            ->limit(1)
            ->cached(Date::MINUTE * 30, 'comment:' . $id)
            ->execute();
        
        $model = new Model_Comment();
        
        return $model->fillByRow($comment_row);
    }

    /* 
    ** Добавляет комментарий в БД.
    */	
    public function insert()
    {
        $idAndRowAffected = Dao_Comments::insert()
            ->set('author',    $this->author['id'])
            ->set('text',      $this->text)
            ->set('page_id',   $this->page_id)
            ->set('root_id',   $this->root_id)
            ->set('parent_id', $this->parent_comment['id'])
            ->clearcache('page:' . $this->page_id)
            ->execute();
        
        if ($this->root_id == 0)
            Dao_Comments::update()
                ->where('id', '=', $idAndRowAffected)
                ->set('root_id', $idAndRowAffected)
                ->execute(); 
        
        if ($idAndRowAffected) {
            return true;
        } else {
            return false;
        }
    }

    /*
    ** Заполняет объект строкой из БД.
    */
    private function fillByRow($comment_row)
    {
        if (!empty($comment_row['id'])) {
            
            $this->id         = $comment_row['id'];
            $this->author     = new Model_User($comment_row['author']);
            $this->text       = $comment_row['text'];
            $this->page_id    = $comment_row['page_id'];
            $this->root_id    = $comment_row['root_id'];
            $this->dt_create  = $comment_row['dt_create'];
            $this->is_removed = $comment_row['is_removed'];
        }
        
        return $this;
    }
    
    public static function getCommentsByPageId($page_id)
    {
        $comments = array();

        $comment_rows = Dao_Comments::select()
            ->where('page_id', '=', $page_id)
            ->where('is_removed', '=', 0)
            ->order_by('root_id', 'ASC')
            ->cached(Date::MINUTE * 20, 'page:' . $page_id)
            ->execute();
        
        if ($comment_rows) {
            foreach ($comment_rows as $comment_row) {
                $parent = [];
                
                foreach ($comment_rows as $parent_row) {
                    if ($parent_row['id'] == $comment_row['parent_id']) {
                        $parent = [
                            "id"         => $parent_row['id'],
                            "author"     => new Model_User($parent_row['author']),
                            "text"       => $parent_row['text'],
                            "root_id"    => $parent_row['root_id'],
                            "dt_create"  => $parent_row['dt_create'],
                            "is_removed" => $parent_row['is_removed']
                        ];
                    }                        
                }
                    
                $comment = new Model_Comment();

                $comment->fillByRow($comment_row, $parent);
                
                $comment->parent_comment = $parent;

                array_push($comments, $comment);
            }
        }

        return $comments;
    }

    /*
    ** Удаляем комментарий и все его подкомментарии
    */
    public function delete()
    {
        Dao_Comments::update()
            ->where('id', '=', $this->id)
            ->set('is_removed', 1)
            ->clearcache('page:' . $this->page_id)
            ->execute(); 
            
        Dao_Comments::update()
            ->where('root_id', '=', $this->id)
            ->set('is_removed', 1)
            ->execute(); 
        
        Dao_Comments::update()
            ->where('parent_id', '=', $this->id)
            ->set('is_removed', 1)
            ->execute(); 
    }


}

?>