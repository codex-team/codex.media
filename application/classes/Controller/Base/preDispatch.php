<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Base_preDispatch extends Controller_Template
{
    /** Wrapper template name */
    public $template = 'main';

    /** Data to pass into view */
    public $view = array();

    /**
     * The before() method is called before your controller action.
     * In our template controller we override this method so that we can
     * set up default values. These variables are then available to our
     * controllers if they need to be modified.unsubscribeEmail
     */
    public function before()
    {
        if (!$this->request->param('iframe-embedding')) {

            $this->response->headers("X-Frame-Options", "SAMEORIGIN");
        }

        /** Disallow requests from other domains */
        if (Kohana::$environment === Kohana::PRODUCTION) {

            if ((Arr::get($_SERVER, 'SERVER_NAME') != 'alpha.difual.com') &&
                (Arr::get($_SERVER, 'SERVER_NAME') != 'ifmo.su')) {

                exit();
            }
        }

        parent::before();

        $site = Kohana::$config->load('main.site');

        $GLOBALS['SITE_NAME']        = $site['name'];
        $GLOBALS['SITE_SLOGAN']      = $site['slogan'];
        $GLOBALS['SITE_DESCRIPTION'] = $site['description'];
        $GLOBALS['SITE_MAIL']        = $site['mail'];
        $GLOBALS['SITE_SUPPORT']     = $site['support'];
        $GLOBALS['FROM_ACTION']      = $this->request->action();

        // XSS clean in POST and GET requests
        self::XSSfilter();

        $this->setGlobals();

        if ($this->auto_render) {

            // Initialize empty values
            $this->template->title       = $this->title = $GLOBALS['SITE_NAME'] . ': ' . $GLOBALS['SITE_SLOGAN'];
            $this->template->keywords    = '';
            $this->template->description = '';
            $this->template->content     = '';
        }
    }

    /**
     * The after() method is called after your controller action.
     * In our template controller we override this method so that we can
     * make any last minute modifications to the template before anything
     * is rendered.
     */
    public function after()
    {
        //echo View::factory('profiler/stats');

        if ($this->auto_render) {

            if ( $this->title )       $this->template->title       = $this->title;
            if ( $this->description ) $this->template->description = $this->description;
        }

        parent::after();
    }

    private function setGlobals()
    {
        /** Methods */
        $this->methods = new Model_Methods();
        View::set_global('methods', $this->methods);

        /** Site info from Settings */
        View::set_global('site_info', Model_Settings::getAll());

        /** Site menu pages */
        View::set_global('site_menu', Model_Methods::getSiteMenu());

        /** Modules */
        $this->redis = $this->_redis();
        View::set_global('redis', $this->redis);

        $this->memcache = $memcache = Cache::instance('memcacheimp');
        View::set_global('memcache', $memcache);

        /** Session */
        $this->session = Session::instance();

        $uid  = Controller_Auth_Base::checkAuth();
        $this->user = new Model_User($uid ?: 0);
        View::set_global('user', $this->user);

    }

    public function userOnline()
    {
        if ($this->user->id) {

            $this->user->isOnline = 1;

            if (!$this->redis->exists('user:'.$this->user->id.':online')) {

                $this->redis->set('user:'.$this->user->id.':online', $this->user->id, Date::MINUTE);
                $this->redis->set('user:'.$this->user->id.':online:timestamp', time());
            }
        }
    }


    /**
    * Sanitizes GET and POST params
    * @uses HTMLPurifier
    */
    public function XSSfilter()
    {
        $exceptions = array( 'long_desc' , 'blog_text', 'long_description' , 'content' ); // Исключения для полей с визуальным редактором

        foreach ($_POST as $key => $value) {

            $value = stripos( $value, 'سمَـَّوُوُحخ ̷̴̐خ ̷̴̐خ ̷̴̐خ امارتيخ ̷̴̐خ') !== false ? '' : $value ;

            if (in_array($key, $exceptions) === false) {

                $_POST[$key] = Security::xss_clean(HTML::chars($value));

            } else {

                $_POST[$key] = strip_tags(trim($value), '<br><em><del><p><a><b><strong><i><strike><blockquote><ul><li><ol><img><tr><table><td><th><span><h1><h2><h3><iframe>' );
            }
        }

        foreach ($_GET  as $key => $value) {

            $value = stripos( $value, 'سمَـَّوُوُحخ ̷̴̐خ ̷̴̐خ ̷̴̐خ امارتيخ ̷̴̐خ') !== false ? '' : $value;

            $_GET[$key] = Security::xss_clean(HTML::chars($value));
        }
    }

    public static function _redis()
    {
        if (!class_exists("Redis")) return null;

        $redis_config = Kohana::$config->load('redis');

        $redis_host = Arr::get($redis_config, 'host', '127.0.0.1');
        $redis_port = Arr::get($redis_config, 'port', '6379');
        $redis_pass = Arr::get($redis_config, 'password', '');
        $redis_db   = Arr::get($redis_config, 'database', '0');

        $redis = new Redis();
        $redis->connect($redis_host, $redis_port);
        $redis->auth($redis_pass);
        $redis->select($redis_db);

        return $redis;
    }
}
