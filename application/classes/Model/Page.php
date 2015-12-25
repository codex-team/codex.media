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

    public $parent          = array();

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
        $page = DB::insert('pages')
            ->set('type', $this->type)
            ->set('id_parent', $this->id_parent)
            ->set('author', $this->author)
            ->set('title', $this->title)
            ->set('content', $this->content)
            ->set('is_menu_item', $this->is_menu_item)
            ->execute();

        if ($page)
        {
            $this->fillByRow($this->get($page));
        }
    }

    public function update($page_id, $fields)
    {
        $page = DB::update()->where('id', '=', $page_id);
        foreach ($fields as $name => $value) $page->set($name, $value);
        return $page->execute();
    }

    public static function get($id = 0)
    {
        $page = DB::select()
            ->from('pages')
            ->where('id', '=', $id)
            ->execute()
            ->current();

        $model = new Model_Page();

        return $model->fillByRow($page);
    }

    public static function getPages( $type = 0, $limit = 0, $offset = 0, $status = 0)
    {
        $pages_query = DB::select()->from('pages')->where('status', '=', $status);

        if ($type) $pages_query->where('type', '=', $type);
        if ($limit) $pages_query->limit($limit);
        if ($offset) $pages_query->offset($offset);

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
        $query = DB::select('id')
            ->from('pages')
            ->where('status', '=', 0)
            ->where('id_parent','=', $id_parent)
            ->order_by('id','ASC')
            ->cached(Date::MINUTE*0)
            ->execute();

        return self::rowsToModels($query);
    }

    public static function getPageUri()
    {
        # todo
        return 'uri-page';
    }

}