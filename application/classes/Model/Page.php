<?php defined('SYSPATH') or die('No direct script access.');

use CodexEditor\CodexEditor;

class Model_Page extends Model
{
    public $id              = 0;
    public $status          = 0;
    public $date            = '';
    public $uri             = '';
    public $author;
    public $id_parent       = 0;

    public $url             = 0;

    public $rich_view       = 0;
    public $dt_pin;
    public $is_menu_item    = 0;
    public $is_news_page    = 0;
    public $feed_key       = '';

    public $title           = '';
    public $description     = '';

    /**
     * JSON with blocks
     * @var string
     */
    public $content = '';

    /**
     * Array of Blocks classes
     * @var array
     */
    public $blocks = array();

    public $commentsCount   = 0;
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

            $this->uri    = $this->getPageUri();
            $this->author = new Model_User($page_row['author']);
            $this->url = '/p/' . $this->id . ($this->uri ? '/' . $this->uri : '');
            $this->commentsCount = $this->getCommentsCount();
        }

        return $this;
    }

    /**
     * Fill $this->blocks from JSON object stored in $this->content
     *
     * @param Boolean $escapeHTML  pass TRUE to escape HTML entities
     *
     * @throws Kohana_Exception  error thrown by CodeXEditor vendor module
     * @return Array - list of page blocks
     */
    public function getBlocks($escapeHTML = false)
    {
        $config = Kohana::$config->load('editor');

        $blocksJSON = '';
        $blocks = array();

        try {

            $CodexEditor = new CodexEditor($this->content, $config);
            $blocksJSON  = $CodexEditor->getData($escapeHTML);

            $blocks = json_decode($blocksJSON);

            if (json_last_error()) {
                throw new \Exception('Wrong JSON format: ' . json_last_error_msg());
            }

            if (property_exists($blocks, 'data')) {
                return $blocks->data;
            } else {
                throw new Kohana_Exception("Error: 'data' is not exist. " . $e->getMessage());
            }

        } catch (Exception $e) {

            throw new Kohana_Exception("CodexEditor: " . $e->getMessage());

        }

        return array();
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

    /**
     * Функция находит первый блок paragraph и возвращает его в качестве превью
     */
    public function getDescription()
    {
        if (empty($this->blocs)){
            $this->blocks = $this->getBlocks();
        }

        $description = '';

        foreach ($this->blocks as $block) {

            if ($block->type == 'paragraph') {

                $description = $block->data->text;

                break;
            }
        }

        return $description;
    }

    /**
     * Returns comments count for current page
     * Uses cache with TAG 'comment:by:page:<PAGE_ID>' that clears in comments insertion/deletion
     * @return int comments count
     */
    public function getCommentsCount()
    {
        $cache = Cache::instance('memcacheimp');
        $cacheKey = 'comments:count:by:page:' . $this->id;

        $cached = $cache->get($cacheKey);

        if ( $cached) {
            return $cached;
        }

        $count = DB::select('id')->from('comments')
            ->where('page_id', '=', $this->id)
            ->where('is_removed', '=', 0)
            ->execute()
            ->count();

        $cache->set($cacheKey, $count, array('comments:by:page:' . $this->id), Date::MINUTE * 5);

        return $count;
    }
}
