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

    /**
     * Page cover URL
     * @var null
     */
    public $cover = null;

    public $url             = 0;

    public $rich_view       = 0;
    public $dt_pin;
    public $isPinned        = false;
    public $feed_key        = '';

    public $isNewsPage;

    public $title           = '';
    /**
     * JSON with page data
     *
     * @var string
     */
    public $content         = '';
    public $description     = '';

    /**
     * Array of blocks classes
     *
     * @var array
     */
    public $blocks          = array();

    public $parent          = null;
    public $children        = array();
    public $comments        = array();

    public $commentsCount   = 0;

    /** post_id in the public's wall or 0  */
    public $isPostedInVK    = 0;

    const STATUS_SHOWING_PAGE = 0;
    const STATUS_HIDDEN_PAGE  = 1;
    const STATUS_REMOVED_PAGE = 2;

    const LIST_PAGES_NEWS     = 1;
    const LIST_PAGES_TEACHERS = 2;
    const LIST_PAGES_USERS    = 3;

    public function __construct($id = 0, $escapeHTML = false)
    {
        if (!$id) return;

        self::get($id);

        if ($this->id) {
            $this->content = $this->validateContent();
            $this->blocks = $this->getBlocks($escapeHTML);
            $this->description = $this->getDescription();
        }

    }

    public function get($id = 0)
    {
        $pageRow = Dao_Pages::select()
            ->where('id', '=', $id)
            ->limit(1)
            ->cached(Date::MINUTE * 5, 'page:' . $id)
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
            $this->isNewsPage = $this->isNewsPage();

            $this->parent = new Model_Page($this->id_parent);

            $this->url = '/p/' . $this->id . ($this->uri ? '/' . $this->uri : '');
            $this->commentsCount = $this->getCommentsCount();

            $this->isPostedInVK = Model_Services_Vk::getPostIdByArticleId($this->id);

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
            ->set('cover',          $this->cover)
            ->set('rich_view',      $this->rich_view)
            ->set('dt_pin',         $this->dt_pin);

        $pageId = $page->execute();

        if ($pageId) return new Model_Page($pageId);
    }

    public function update()
    {
        $page = Dao_Pages::update()
            ->where('id', '=', $this->id)
            ->set('id',             $this->id)
            ->set('status',         $this->status)
            ->set('id_parent',      $this->id_parent)
            ->set('title',          $this->title)
            ->set('cover',          $this->cover)
            ->set('content',        $this->content)
            ->set('rich_view',      $this->rich_view)
            ->set('dt_pin',         $this->dt_pin);

        $page->clearcache('page:' . $this->id, array('site_menu'));

        $page->execute();

        return $this;
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
        $this->getChildrenPages();

        foreach ($this->children as $page) {

            $page->setAsRemoved();
        }

        return true;
    }

    public static function rowsToModels($page_rows)
    {
        $pages = array();

        if (!empty($page_rows)) {

            foreach ($page_rows as $page_row) {

                $page = new Model_Page();

                $page->fillByRow($page_row);

                $page->content = $page->validateContent();
                $page->blocks = $page->getBlocks(true);
                $page->description = $page->getDescription();

                array_push($pages, $page);
            }
        }

        return $pages;
    }

    public function getChildrenPages()
    {
        $query = Dao_Pages::select()
            ->where('status', '=', self::STATUS_SHOWING_PAGE)
            ->where('id_parent','=', $this->id)
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


    /**
     * Return array of blocks classes from JSON object stored in $this->content
     *
     * @param Boolean $escapeHTML  pass TRUE to escape HTML entities
     *
     * @throws Kohana_Exception  error thrown by CodeXEditor vendor module
     * @return Array - list of page blocks
     */
    public function getBlocks($escapeHTML = false)
    {
        $config = Kohana::$config->load('editor');

        try {

            $CodexEditor = new CodexEditor($this->content, $config);

            return $CodexEditor->getBlocks($escapeHTML);

        } catch (Exception $e) {

            throw new Kohana_Exception("CodexEditor: " . $e->getMessage());

        }

    }

    /**
     * Pass JSON with page data through CodexEditor class for validate blocks data
     *
     * @param bool $escapeHTML  - if TRUE, escapes HTML entities
     * @return string           - JSON with validated data
     * @throws Kohana_Exception   if data is not valid
     */
    public function validateContent($escapeHTML = false) {

        $config = Kohana::$config->load('editor');

        try {

            $CodexEditor = new CodexEditor($this->content, $config);
            return $CodexEditor->getData($escapeHTML);

        } catch (Exception $e) {

            throw new Kohana_Exception("CodexEditor: " . $e->getMessage());

        }
    }

    /**
     * Adds page to feed
     *
     * @param string $type - feed type
     */
    public function addToFeed($type = Model_Feed_Pages::TYPE_ALL) {

        $feed = new Model_Feed_Pages($type);
        $feed->add($this->id);

    }

    /**
     * Removes page from feed
     *
     * @param string $type - feed type
     */
    public function removeFromFeed($type = Model_Feed_Pages::TYPE_ALL) {

        $feed = new Model_Feed_Pages($type);
        $feed->remove($this->id);

    }

    public function toggleFeed($type = Model_Feed_Pages::TYPE_ALL) {

        $feed = new Model_Feed_Pages($type);

        if (!$feed->isExist($this->id)) {
            $this->addToFeed($type);
        } else {
            $this->removeFromFeed($type);
        }

    }


    /**
     * Checks if page in site menu
     *
     * @return bool
     */
    public function isMenuItem() {

        $feed = new Model_Feed_Pages(Model_Feed_Pages::TYPE_MENU);

        return $feed->isExist($this->id);

    }

    /**
     * Checks if page in news feed
     *
     * @return bool
     */
    public function isNewsPage() {

        $feed = new Model_Feed_Pages(Model_Feed_Pages::TYPE_MAIN);

        return $feed->isExist($this->id);

    }

    /**
     * Remove page from all feeds
     *
     * TODO: реализовать изящнее. Может, сделать статичный метод в Model_Feed_Pages
     */
    public function removePageFromFeeds()
    {
        $this->removeFromFeed(Model_Feed_Pages::TYPE_MAIN);
        $this->removeFromFeed(Model_Feed_Pages::TYPE_ALL);
        $this->removeFromFeed(Model_Feed_Pages::TYPE_TEACHERS);
        $this->removeFromFeed(Model_Feed_Pages::TYPE_MENU);
    }


    /**
     * Функция находит первый блок paragraph и возвращает его в качестве превью
     *
     * #TODO возвращать и научиться обрабатывать блок(-и) любого типа с параметром cover = true
     */
    public function getDescription()
    {
        $blocks = $this->blocks;

        if ($blocks) {

            foreach ($blocks as $block) {

                if ($block['type'] == 'paragraph') {

                    return $block['data']['text'];

                }
            }
        }

        return '';

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

    public function getComments() {
        return Model_Comment::getCommentsByPageId($this->id);
    }

    public function getUrlToParentPage()
    {
        if ($this->id_parent != 0) {

            return '/p/' . $this->parent->id . '/' . $this->parent->uri;

        } elseif (!$this->isMenuItem()) {

            return '/user/' . $this->author->id;

        } else {

            return '/';
        }
    }

    /**
     * Checks if $user can modify page
     *
     * @param Model_User $user
     * @return bool
     */
    public function canModify($user) {

        if ($user->isAdmin()) {
            return true;
        }

        if (!$user->id) {
            return false;
        }

        if ($this->parent->id && $this->parent->author->id != $user->id) {
            return false;
        }

        if ($this->id && $this->author->id != $user->id) {
            return false;
        }

        return true;

    }

}
