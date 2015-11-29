<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Base_preDispatch extends Controller_Template
{
    /**
     *  Default template
     */
    public $template = 'index';

    /**
     *  Container for template params
     */
    public $view = array();

    public function before()
    {
        if ($this->_ajax())  {
            $this->template = 'ajax';
            $this->ajax     = TRUE;
        }

        parent::before();

        // XSS clean in POST and GET requests
        self::XSSfilter();

        // load site params
        $config = Kohana::$config->load('main');
        $session = $this->session = Session::instance();

        $GLOBALS['SITE_NAME']   = $config['site']['name'];
        $GLOBALS['SITE_SLOGAN'] = $config['site']['slogan'];

        // global action
        $GLOBALS['FROM_ACTION'] = $this->request->action();

        // methods
        $this->methods = new Model_Methods();
        View::set_global('methods', $this->methods);

        // modules
        $this->redis = $this->_redis();
        View::set_global('redis', $this->redis);

        $this->memcache = $memcache = Cache::instance('memcache');
        View::set_global('memcache', $memcache);

        $this->user = ( Auth::instance()->get_user() !== NULL) ? Auth::instance()->get_user() : FALSE;
        
        View::set_global('user', $this->user);

        if ($this->auto_render) {
            // Initialize empty values
            $this->template->title = $this->title = $GLOBALS['SITE_NAME'] . ': ' . $GLOBALS['SITE_SLOGAN'];
            $this->template->keywords = '';
            $this->template->description = '';
            $this->template->content  = '';
            $this->template->seo_image  = '';
            $this->template->styles   = array();
            $this->template->scripts  = array();
        }
    }

    public function after()
    {
        if ($this->auto_render) {
            if ( $this->title ) {
                $this->template->title = $this->title;
            }
        }

        parent::after();
    }

    public function XSSfilter()
    {
        $exceptions     = array( 'long_desc' , 'blog_text', 'long_description' , 'content' ); // Исключения для полей с визуальным редактором
        $skipHTMLFilter = array( 'html_content' );

        foreach ($_POST as $key => $value){
            
            $value = stripos( $value, 'سمَـَّوُوُحخ ̷̴̐خ ̷̴̐خ ̷̴̐خ امارتيخ ̷̴̐خ') !== false ? '' : $value ;
            
            if ( in_array($key, $exceptions) === false ){
                if ($key == 'html_content') {
                    $_POST[$key] = Security::xss_clean( trim($value) );
                } else {
                    $_POST[$key] = Security::xss_clean(HTML::chars($value));
                }                    
            } else {
                $_POST[$key] = Security::xss_clean( strip_tags(trim($value), '<br><em><del><p><a><b><strong><i><strike><blockquote><ul><li><ol><img><tr><table><td><th><span><h1><h2><h3><iframe>' ));    
            }
        }
        foreach ($_GET  as $key => $value) {
            $value = stripos( $value, 'سمَـَّوُوُحخ ̷̴̐خ ̷̴̐خ ̷̴̐خ امارتيخ ̷̴̐خ') !== false ? '' : $value ;
            $_GET[$key] = Security::xss_clean(HTML::chars($value));
        }
    }

    public function _transliterate_to_ascii($str, $case = 0)
    {
        static $utf8_lower_accents = NULL;
        static $utf8_upper_accents = NULL;

        if ($case <= 0)
        {
            if ($utf8_lower_accents === NULL)
            {
                $utf8_lower_accents = array(
                    'a' => 'a',  'o' => 'o',  'd' => 'd',  '?' => 'f',  'e' => 'e',  's' => 's',  'o' => 'o',
                    '?' => 'ss', 'a' => 'a',  'r' => 'r',  '?' => 't',  'n' => 'n',  'a' => 'a',  'k' => 'k',
                    's' => 's',  '?' => 'y',  'n' => 'n',  'l' => 'l',  'h' => 'h',  '?' => 'p',  'o' => 'o',
                    'u' => 'u',  'e' => 'e',  'e' => 'e',  'c' => 'c',  '?' => 'w',  'c' => 'c',  'o' => 'o',
                    '?' => 's',  'o' => 'o',  'g' => 'g',  't' => 't',  '?' => 's',  'e' => 'e',  'c' => 'c',
                    's' => 's',  'i' => 'i',  'u' => 'u',  'c' => 'c',  'e' => 'e',  'w' => 'w',  '?' => 't',
                    'u' => 'u',  'c' => 'c',  'o' => 'o',  'e' => 'e',  'y' => 'y',  'a' => 'a',  'l' => 'l',
                    'u' => 'u',  'u' => 'u',  's' => 's',  'g' => 'g',  'l' => 'l',  '?' => 'f',  'z' => 'z',
                    '?' => 'w',  '?' => 'b',  'a' => 'a',  'i' => 'i',  'i' => 'i',  '?' => 'd',  't' => 't',
                    'r' => 'r',  'a' => 'a',  'i' => 'i',  'r' => 'r',  'e' => 'e',  'u' => 'u',  'o' => 'o',
                    'e' => 'e',  'n' => 'n',  'n' => 'n',  'h' => 'h',  'g' => 'g',  'd' => 'd',  'j' => 'j',
                    'y' => 'y',  'u' => 'u',  'u' => 'u',  'u' => 'u',  't' => 't',  'y' => 'y',  'o' => 'o',
                    'a' => 'a',  'l' => 'l',  '?' => 'w',  'z' => 'z',  'i' => 'i',  'a' => 'a',  'g' => 'g',
                    '?' => 'm',  'o' => 'o',  'i' => 'i',  'u' => 'u',  'i' => 'i',  'z' => 'z',  'a' => 'a',
                    'u' => 'u',  '?' => 'th', '?' => 'dh', '?' => 'ae', 'µ' => 'u',  'e' => 'e',  '?' => 'i',
                    'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd', 'е' => 'e', 'ё' => 'yo', 'ж' => 'zh',
                    'з' => 'z', 'и' => 'i', 'й' => 'j', 'к' => 'k', 'л' => 'l', 'м' => 'm', 'н' => 'n', 'о' => 'o',
                    'п' => 'p', 'р' => 'r', 'с' => 's', 'т' => 't', 'у' => 'u', 'ф' => 'f', 'х' => 'h', 'ц' => 'c',
                    'ч' => 'ch', 'ш' => 'sh', 'щ' => 'shh', 'ъ' => '?', 'ы' => 'y', 'ь' => '?', 'э' => 'e', 'ю' => 'ju',
                    'я' => 'ja',
                );
            }

            $str = str_replace(
                array_keys($utf8_lower_accents),
                array_values($utf8_lower_accents),
                $str
            );
        }

        if ($case >= 0)
        {
            if ($utf8_upper_accents === NULL)
            {
                $utf8_upper_accents = array(
                    'A' => 'A',  'O' => 'O',  'D' => 'D',  '?' => 'F',  'E' => 'E',  'S' => 'S',  'O' => 'O',
                    'A' => 'A',  'R' => 'R',  '?' => 'T',  'N' => 'N',  'A' => 'A',  'K' => 'K',  'E' => 'E',
                    'S' => 'S',  '?' => 'Y',  'N' => 'N',  'L' => 'L',  'H' => 'H',  '?' => 'P',  'O' => 'O',
                    'U' => 'U',  'E' => 'E',  'E' => 'E',  'C' => 'C',  '?' => 'W',  'C' => 'C',  'O' => 'O',
                    '?' => 'S',  'O' => 'O',  'G' => 'G',  'T' => 'T',  '?' => 'S',  'E' => 'E',  'C' => 'C',
                    'S' => 'S',  'I' => 'I',  'U' => 'U',  'C' => 'C',  'E' => 'E',  'W' => 'W',  '?' => 'T',
                    'U' => 'U',  'C' => 'C',  'O' => 'O',  'E' => 'E',  'Y' => 'Y',  'A' => 'A',  'L' => 'L',
                    'U' => 'U',  'U' => 'U',  'S' => 'S',  'G' => 'G',  'L' => 'L',  '?' => 'F',  'Z' => 'Z',
                    '?' => 'W',  '?' => 'B',  'A' => 'A',  'I' => 'I',  'I' => 'I',  '?' => 'D',  'T' => 'T',
                    'R' => 'R',  'A' => 'A',  'I' => 'I',  'R' => 'R',  'E' => 'E',  'U' => 'U',  'O' => 'O',
                    'E' => 'E',  'N' => 'N',  'N' => 'N',  'H' => 'H',  'G' => 'G',  'D' => 'D',  'J' => 'J',
                    'Y' => 'Y',  'U' => 'U',  'U' => 'U',  'U' => 'U',  'T' => 'T',  'Y' => 'Y',  'O' => 'O',
                    'A' => 'A',  'L' => 'L',  '?' => 'W',  'Z' => 'Z',  'I' => 'I',  'A' => 'A',  'G' => 'G',
                    '?' => 'M',  'O' => 'O',  'I' => 'I',  'U' => 'U',  'I' => 'I',  'Z' => 'Z',  'A' => 'A',
                    'U' => 'U',  '?' => 'Th', '?' => 'Dh', '?' => 'Ae', 'I' => 'I',
                    'А' => 'A', 'Б' => 'B', 'В' => 'V', 'Г' => 'G', 'Д' => 'D', 'Е' => 'E', 'Ё' => 'Yo', 'Ж' => 'Zh',
                    'З' => 'Z', 'И' => 'I', 'Й' => 'J', 'К' => 'K', 'Л' => 'L', 'М' => 'M', 'Н' => 'N', 'О' => 'O',
                    'П' => 'P', 'Р' => 'R', 'С' => 'S', 'Т' => 'T', 'У' => 'U', 'Ф' => 'F', 'Х' => 'Kh', 'Ц' => 'Tc',
                    'Ч' => 'Ch', 'Ш' => 'Sh', 'Щ' => 'Sch', 'Ъ' => '\'', 'Ы' => 'Y', 'Ь' => '', 'Э' => 'E', 'Ю' => 'Ju',
                    'Я' => 'Ja'
                );
            }

            $str = str_replace(
                array_keys($utf8_upper_accents),
                array_values($utf8_upper_accents),
                $str
            );
        }

        return $str;
    }

    public function validText($str)
    {
        return Valid::regex(self::_transliterate_to_ascii($str), "/^[-,A-ZА-Я0-9_@\s]+$/i");
    }

    public function rus_lat($text)
    {
        $arr = array(
            'ье' => 'ie',
            'ья' => 'iya',
            'ьи' => 'ii',
            'ъе' => 'je',
            'ъи' => 'ji',
            'ью' => 'iu',
            'ьё' => 'iyo',
            'а' => 'a',
            'б' => 'b',
            'в' => 'v',
            'г' => 'g',
            'д' => 'd',
            'е' => 'e',
            'ё' => 'yo',
            'ж' => 'zh',
            'з' => 'z',
            'и' => 'i',
            'й' => 'j',
            'к' => 'k',
            'л' => 'l',
            'м' => 'm',
            'н' => 'n',
            'о' => 'o',
            'п' => 'p',
            'р' => 'r',
            'с' => 's',
            'т' => 't',
            'у' => 'u',
            'ф' => 'f',
            'х' => 'h',
            'ц' => 'ts',
            'ч' => 'ch',
            'ш' => 'sh',
            'щ' => 'sch',
            'ъ' => '',
            'ы' => 'i',
            'ь' => '',
            'э' => 'e',
            'ю' => 'yu',
            'я' => 'ya',
            ' ' => '_',
            '/' => '_',
            '%' => '_',
            '^' => '_',
            ':' => '_',
            ';' => '_',
            '*' => '_',
            '?' => '_',
            '&' => '_',
            '$' => '_',
            '#' => '_',
            '@' => '_',
            '!' => '_',
            ']' => '_',
            '[' => '_',
            '}' => '_',
            '{' => '_',
            '"' => '_',
            "'" => '_',
            '>' => '_',
            '<' => '_',
            '.' => '_',
            ',' => '_',
            '-' => '_',
        );
        $text = strtr(mb_strtolower($text), $arr);
        return $text;
    }

    public static function _ajax()
    {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest');
    }

    public static function _redis()
    {
        $redis = new Redis();
        $redis->connect('127.0.0.1', 6379);
        $redis->auth('21gJs32hv3ks');
        $redis->select(0);
        return $redis;
    }
    
}
