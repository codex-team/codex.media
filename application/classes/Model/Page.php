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

    public function __construct($page_id = null)
    {
        parent::__construct();
        if ( !$page_id ) return;

        $page = $this->getPage($page_id);

        if ($page)
        {
            $this->id              = $page['id'];
            $this->type            = $page['type'];
            $this->status          = $page['status'];
            $this->id_parent       = $page['id_parent'];
            $this->title           = $page['title'];
            $this->content         = $page['content'];
            $this->date            = $page['date'];
            $this->author          = $page['author'];
            $this->is_menu_item    = $page['is_menu_item'];

            $this->uri             = self::getPageUri();
        }
    }

    public function getPage($id)
    {
        return DB::select()->from('pages')->where('id', '=', $id)->limit(1)->execute()->current();
    }

    public function updatePage($page_id, $fields)
    {
        $page = DB::update()->where('id', '=', $page_id);
        foreach ($fields as $name => $value) $page->set($name, $value);
        return $page->execute();
    }

    public function getPageUri()
    {
        # todo
        return 'uri-page';
    }

}