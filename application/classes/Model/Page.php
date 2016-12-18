<?php defined('SYSPATH') or die('No direct script access.');

class Model_Page extends Model_preDispatch
{
    public $id              = 0;
    public $type            = self::TYPE_USER_PAGE;
    public $status          = 0;
    public $id_parent       = 0;
    public $title           = '';
    public $content         = '';
    public $date            = '';
    public $is_menu_item    = 0;
    public $rich_view       = 0;
    public $dt_pin;
    public $uri             = '';
    public $author;
    public $parent;
    public $source_link     = '';
    public $feed_type       = '';

    public $description     = '';
    public $blocks          = array();

    public $attachments     = array();
    public $files           = array();
    public $images          = array();

    const TYPE_SITE_PAGE = 1;
    const TYPE_SITE_NEWS = 2;
    const TYPE_USER_PAGE = 3;

    const STATUS_SHOWING_PAGE = 0;
    const STATUS_HIDDEN_PAGE  = 1;
    const STATUS_REMOVED_PAGE = 2;

    const LIST_PAGES_NEWS     = 1;
    const LIST_PAGES_TEACHERS = 2;
    const LIST_PAGES_USERS    = 3;

    const FEED_TYPE_NEWS           = 'news';
    const FEED_TYPE_TEACHERS_BLOGS = 'teachers';
    const FEED_TYPE_BLOGS          = 'blogs';

    public function __construct($id = 0)
    {
        if (!$id) return;

        self::get($id);
    }

    public function get($id = 0)
    {
        $pageRow = Dao_Pages::select()
            ->where('id', '=', $id)
            ->limit(1)
            ->cached(Date::MINUTE * 30, 'page:' . $id)
            ->execute();

        return self::fillByRow($pageRow);
    }

    private function fillByRow($page_row)
    {
        if (!empty($page_row)) {

            foreach ($page_row as $field => $value) {

                if (property_exists($this, $field)) {

                    $this->$field = $value;
                }
            }

            $this->blocks = json_decode($this->content);

            $this->uri    = $this->getPageUri();
            $this->author = new Model_User($page_row['author']);

            $this->description = $this->getDescription();
        }

        return $this;
    }

    public function insert()
    {
        $this->content = json_encode($this->blocks);

        $page = Dao_Pages::insert()
            ->set('type',           $this->type)
            ->set('author',         $this->author->id)
            ->set('id_parent',      $this->id_parent)
            ->set('title',          $this->title)
            ->set('content',        $this->content)
            ->set('is_menu_item',   $this->is_menu_item)
            ->set('rich_view',      $this->rich_view)
            ->set('dt_pin',         $this->dt_pin)
            ->set('source_link',    $this->source_link);

        if ($this->is_menu_item) $page->clearcache('site_menu');

        $page = $page->execute();

        if ($page) return new Model_Page($page);
    }

    public function update()
    {
        $this->content = json_encode($this->blocks);

        return Dao_Pages::update()
            ->where('id', '=', $this->id)
            ->set('id',             $this->id)
            ->set('type',           $this->type)
            ->set('status',         $this->status)
            ->set('author',         $this->author->id)
            ->set('id_parent',      $this->id_parent)
            ->set('title',          $this->title)
            ->set('content',        $this->content)
            ->set('is_menu_item',   $this->is_menu_item)
            ->set('rich_view',      $this->rich_view)
            ->set('dt_pin',         $this->dt_pin)
            ->set('source_link',    $this->source_link)
            ->clearcache('page:' . $this->id, array('site_menu'))
            ->execute();
    }

    public function setAsRemoved()
    {
        $this->status = self::STATUS_REMOVED_PAGE;
        $this->update();

        /* remove files */
        $files = Model_File::getPageFiles($this->id);

        foreach ($files as $file) {

            $file->is_removed = 1;
            $file->update();
        }

        /* remove childs */
        $childrens = $this->getChildrenPagesByParent($this->id);

        foreach ($childrens as $page) {

            $page->setAsRemoved();
        }

        /* remove comments */
        $comments = Model_Comment::getCommentsByPageId($this->id);

        foreach ($comments as $comment) {

            $comment->delete();
        }

        $this->removePageFromFeed();

        return true;
    }

    public static function getPages(
        $type   = 0,
        $limit  = 0,
        $offset = 0,
        $status = 0,
        $pinned_news        = false,
        $without_menu_items = true
    ) {
        $pages_query = Dao_Pages::select()->where('status', '=', $status);

        if ($type)               $pages_query->where('type', '=', $type);
        if ($limit)              $pages_query->limit($limit);
        if ($offset)             $pages_query->offset($offset);
        if ($pinned_news)        $pages_query->order_by('dt_pin', 'DESC');
        if ($without_menu_items) $pages_query->where('is_menu_item', '=', 0);

        $pages_rows = $pages_query->order_by('id','DESC')->execute();

        return self::rowsToModels($pages_rows);
    }

    public static function rowsToModels($page_rows)
    {
        $pages = array();

        if (!empty($page_rows)) {

            foreach ($page_rows as $page_row) {

                $page = new Model_Page();

                $page->fillByRow($page_row);

                array_push($pages, $page);
            }
        }

        return $pages;
    }

    public static function getChildrenPagesByParent($id_parent)
    {
        $query = Dao_Pages::select()
            ->where('status', '=', self::STATUS_SHOWING_PAGE)
            ->where('id_parent','=', $id_parent)
            ->order_by('id','ASC')
            ->execute();

        return self::rowsToModels($query);
    }

    private function getPageUri()
    {
        $title = $this->title;

        $title = Model_Methods::getUriByTitle($title);

        return strtolower($title);
    }

    public static function getSiteMenu()
    {
        $menu_pages = Dao_Pages::select()
            ->where('status', '=', 0)
            ->where('is_menu_item', '=', 1)
            ->order_by('id', 'ASC')
            ->cached(Date::MINUTE*5, 'site_menu', array('site_menu'))
            ->execute();

        return self::rowsToModels($menu_pages);
    }

    private function getFeedType()
    {
        if ($this->type == self::TYPE_SITE_NEWS)
            return self::FEED_TYPE_NEWS;

        if ($this->author->status >= Model_User::USER_STATUS_TEACHER)
            return self::FEED_TYPE_TEACHERS_BLOGS;

        return self::FEED_TYPE_BLOGS;
    }

    public function addPageToFeed()
    {
        $this->feed_type = $this->getFeedType();

        switch ($this->feed_type) {
            case self::FEED_TYPE_NEWS:
                $feed = new Model_Feed_News();
                $feed->add($this->id, $this->date);
                break;

            case self::FEED_TYPE_TEACHERS_BLOGS:
                $feed = new Model_Feed_Teachers();
                $feed->add($this->id, $this->date);
                break;

            default: break;
        }

        $feed = new Model_Feed_All();

        $feed->add($this->id, $this->date);
    }

    public function removePageFromFeed()
    {
        $this->feed_type = $this->getFeedType();

        switch ($this->feed_type) {
            case self::FEED_TYPE_NEWS:
                $feed = new Model_Feed_News();
                $feed->remove($this->id);
                break;

            case self::FEED_TYPE_TEACHERS_BLOGS:
                $feed = new Model_Feed_Teachers();
                $feed->remove($this->id);
                break;

            default: break;
        }

        $feed = new Model_Feed_All();
        $feed->remove($this->id);
    }

    /**
     * Функция находит первый блок paragraph и возвращает его в качестве превью
     *
     * #TODO возвращать и научиться обрабатывать блок любого типа с параметром cover = true
     */
    private function getDescription()
    {
        $blocks = $this->blocks;
        $description = 'описание отсутствует';

        if ($blocks) {

            foreach ($blocks as $block) {

                if ($block->type == 'paragraph') {

                    $description = $block->data->text;

                    break;
                }
                /**
                 * Поиск блока с параметром cover = true
                 */
                /*
                if (property_exists($block, 'cover')) {

                    if ($block->cover == True) {

                        $description = $block->data->text;
                    }
                }
                */
            }
        }

        return $description;
    }
}
