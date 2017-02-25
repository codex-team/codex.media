<?php defined('SYSPATH') or die('No direct script access.');

use CodexEditor\CodexEditor;

class Model_Page extends Model_preDispatch
{
    public $id              = 0;
    public $status          = 0;
    public $date            = '';
    public $uri             = '';
    public $author;
    public $id_parent       = 0;

    public $rich_view       = 0;
    public $dt_pin;
    public $is_menu_item    = 0;
    public $is_news_page    = 0;
    public $feed_key       = '';

    public $title           = '';
    public $content         = '';
    public $description     = '';
    public $blocks          = array();

    public $attachments     = array();
    public $files           = array();
    public $images          = array();

    const STATUS_SHOWING_PAGE = 0;
    const STATUS_HIDDEN_PAGE  = 1;
    const STATUS_REMOVED_PAGE = 2;

    const LIST_PAGES_NEWS     = 1;
    const LIST_PAGES_TEACHERS = 2;
    const LIST_PAGES_USERS    = 3;

    /** #TODO create one model_feed_pages */
    const FEED_KEY_NEWS           = 'news';
    const FEED_KEY_TEACHERS_BLOGS = 'teachers';
    const FEED_KEY_BLOGS          = 'all';

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

            try {

                $config = Kohana::$config->load('editor');
                $pageContent = new CodexEditor($this->content, $config);
                $this->content = $pageContent->getData();

            } catch (Exception $e) {

                throw new Kohana_Exception("Error in content structure" . $e->getMessage());

            }

            try {

                $pageConfig = json_decode($this->content);

                // get only blocks as array
                if (property_exists($pageConfig, 'data')) {
                    $this->blocks = $pageConfig->data;
                }

            } catch (Exception $e) {

                throw new Kohana_Exception("Error: data is not exist" . $e->getMessage());

            }

            $this->uri    = $this->getPageUri();
            $this->author = new Model_User($page_row['author']);
            $this->description = $this->getDescription();
        }

        return $this;
    }

    public function insert()
    {
        $page = Dao_Pages::insert()
            ->set('author',         $this->author->id)
            ->set('id_parent',      $this->id_parent)
            ->set('title',          $this->title)
            ->set('content',        $this->content)
            ->set('is_menu_item',   $this->is_menu_item)
            ->set('is_news_page',   $this->is_news_page)
            ->set('rich_view',      $this->rich_view)
            ->set('dt_pin',         $this->dt_pin);

        if ($this->is_menu_item) $page->clearcache('site_menu');

        $page = $page->execute();

        if ($page) return new Model_Page($page);
    }

    public function update()
    {
        $page = Dao_Pages::update()
            ->where('id', '=', $this->id)
            ->set('id',             $this->id)
            ->set('status',         $this->status)
            ->set('author',         $this->author->id)
            ->set('id_parent',      $this->id_parent)
            ->set('title',          $this->title)
            ->set('content',        $this->content)
            ->set('is_menu_item',   $this->is_menu_item)
            ->set('is_news_page',   $this->is_news_page)
            ->set('rich_view',      $this->rich_view)
            ->set('dt_pin',         $this->dt_pin);

        $page->clearcache('page:' . $this->id, array('site_menu'));

        return $page->execute();
    }

    public function setAsRemoved()
    {
        $this->status = self::STATUS_REMOVED_PAGE;
        $this->update();

        /** remove from feeds */
        $this->removePageFromFeeds();

        /* remove files */
        $files = Model_File::getPageFiles($this->id);

        foreach ($files as $file) {

            $file->is_removed = 1;
            $file->update();
        }

        /* remove comments */
        $comments = Model_Comment::getCommentsByPageId($this->id);

        foreach ($comments as $comment) {

            $comment->delete();
        }

        /* remove childs */
        $childrens = $this->getChildrenPagesByParent($this->id);

        foreach ($childrens as $page) {

            $page->setAsRemoved();
        }

        return true;
    }

    public static function getPages(
        $limit  = 0,
        $offset = 0,
        $status = 0,
        $pinned_news        = false,
        $without_menu_items = true
    ) {
        $pages_query = Dao_Pages::select()->where('status', '=', $status);

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


/** Feed functions */
    private function returnFeedModelByKey($key = '')
    {
        $feed = false;

        switch ($key) {

            case self::FEED_KEY_NEWS:
                $feed = new Model_Feed_News();
                break;

            case self::FEED_KEY_TEACHERS_BLOGS:
                $feed = new Model_Feed_Teachers();
                break;
        }

        return $feed ?: false;
    }

    /**
     * Add or remove page from feed by existing page in feed or by value
     *
     * @key feed key
     * @force_set_by_value value that we need to toggle for result
     */
    public function togglePageInFeed($key, $force_set_by_value = false)
    {
        $feed = self::returnFeedModelByKey($key);

        if (!$feed) return false;

        /** get way for this action. ADD or REMOVE page from feed */
        $remove_from_feed = $force_set_by_value === false ? $feed->isExist($this->id) : !$force_set_by_value;

        if ($remove_from_feed) {

            $feed->remove($this->id);
        } else {

            $feed->add($this->id);
        }
    }

    /**
     * Add page to feed by page's params
     */
    public function addPageToFeeds()
    {
        if ($this->is_news_page) {
            $feed = new Model_Feed_News();
            $feed->add($this->id);
        }

        if ($this->author->status >= Model_User::USER_STATUS_TEACHER) {
            $feed = new Model_Feed_Teachers();
            $feed->add($this->id);
        }

        $feed = new Model_Feed_All();
        $feed->add($this->id);
    }

    /**
     * Remove page from all feeds
     */
    public function removePageFromFeeds()
    {
        $feed = new Model_Feed_News();
        $feed->remove($this->id);

        $feed = new Model_Feed_Teachers();
        $feed->remove($this->id);

        $feed = new Model_Feed_All();
        $feed->remove($this->id);
    }
/***/

    /**
     * Функция находит первый блок paragraph и возвращает его в качестве превью
     *
     * #TODO возвращать и научиться обрабатывать блок(-и) любого типа с параметром cover = true
     */
    private function getDescription()
    {
        $blocks = $this->blocks;
        $description = '';

        if ($blocks) {

            foreach ($blocks as $block) {

                if ($block->type == 'paragraph') {

                    $description = $block->data->text ?: $description;

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
