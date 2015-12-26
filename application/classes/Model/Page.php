<?php defined('SYSPATH') or die('No direct script access.');

class Model_Page extends Model_preDispatch
{
    public $id              = 0;
    public $type            = '';
    public $status          = '';
    public $id_parent       = '';
    public $title           = '';
    public $content         = '';
    public $date            = '';
    public $author          = '';
    public $is_menu_item    = '';

    public $uri             = '';

    public $parent;

    public function __construct(){}

    private function fillByRow($page_row)
    {
        if (!empty($page_row))
        {
            $this->id              = $page_row['id'];
            $this->type            = $page_row['type'];
            $this->status          = $page_row['status'];
            $this->id_parent       = $page_row['id_parent'];
            $this->title           = $page_row['title'];
            $this->content         = $page_row['content'];
            $this->date            = $page_row['date'];
            $this->author          = $page_row['author'];
            $this->is_menu_item    = $page_row['is_menu_item'];

            $this->uri             = self::getPageUri();
        }

        return $this;
    }

    public function insert()
    {
        $page = DB::insert('pages', array('type', 'author', 'id_parent', 'title', 'content', 'is_menu_item'))
            ->values(array(
                    $this->type,
                    $this->author,
                    $this->id_parent,
                    $this->title,
                    $this->content,
                    $this->is_menu_item
                    ))
            ->execute();

        if ($page)
        {
            return $this->get($page[0]);
        }
    }

    public function update()
    {
        return DB::update('pages')->where('id', '=', $this->id)->set(array(
            'id'            => $this->id,
            'type'          => $this->type,
            'author'        => $this->author,
            'id_parent'     => $this->id_parent,
            'title'         => $this->title,
            'content'       => $this->content,
            'is_menu_item'  => $this->is_menu_item,
            ))->execute();
    }

    public function delete()
    {
        DB::update('pages')->where('id', '=', $this->id)
                ->set(array('status' => 2))
                ->execute();

        return true;
    }

    public static function get($id = 0)
    {
        $page = DB::select()
            ->from('pages')
            ->where('id', '=', $id)
            ->limit(1)
            ->execute()
            ->current();

        $model = new Model_Page();

        return $model->fillByRow($page);
    }

    public static function getPages( $type = 0, $limit = 0, $offset = 0, $status = 0)
    {
        $pages_query = DB::select()->from('pages')->where('status', '=', $status);

        if ($type)      $pages_query->where('type', '=', $type);
        if ($limit)     $pages_query->limit($limit);
        if ($offset)    $pages_query->offset($offset);

        $pages_rows = $pages_query->order_by('id','DESC')->execute()->as_array();

        return self::rowsToModels($pages_rows);
    }

    public static function rowsToModels($page_rows)
    {
        $pages = array();

        if (!empty($page_rows))
        {
            foreach ($page_rows as $page_row)
            {
                $page = new Model_Page();

                $page->fillByRow($page_row);

                array_push($pages, $page);
            }
        }

        return $pages;
    }

    public static function getChildrenPagesByParent( $id_parent )
    {
        $query = DB::select()
            ->from('pages')
            ->where('status', '=', 0)
            ->where('id_parent','=', $id_parent)
            ->order_by('id','ASC')
            ->execute()
            ->as_array();

        return self::rowsToModels($query);
    }

    public function getPageUri()
    {
        $title = $this->title;

        $title = Model_Methods::rus2translit($title);

        return strtolower($title);
    }

}