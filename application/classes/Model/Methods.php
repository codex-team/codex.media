<?php defined('SYSPATH') or die('No direct script access.');

class Model_Methods extends Model
{

    const SOCIAL_VK = 1;
    const SOCIAL_FB = 2;
    const SOCIAL_TW = 3;

    public $IMAGE_SIZES_CONFIG = array(
        // первый параметр - вырезать квадрат (true) или просто ресайзить с сохранением пропрорций (false)

        'o'  => array(false , 1500, 1500 ),
        'b'  => array(true , 200 ),
        'm'  => array(true , 100 ),
        's'  => array(true , 50  ),
    );


    /**
     * Get or update site main information
     *
     * @author Taly
     *
     * @param int $info         if exist then update
     * @return array $this            returns array for global var $site_info
     */
    public function getSiteInfo(){

        $info = Dao_SiteInfo::select()
            ->order_by('id', 'DESC')
            ->limit(1)
            ->cached(Date::DAY, 'site_info')
            ->execute();

        $this->title        = $info['title'];
        $this->city         = $info['city'];
        $this->full_name    = $info['full_name'];
        $this->description  = $info['description'];

        $this->address      = $info['address'];
        $this->coordinates  = $info['coordinates'];
        $this->phone        = $info['phone'];
        $this->fax          = $info['fax'];
        $this->email        = $info['email'];

        $this->logo         = $info['logo'];

        return $this;

    }

    public function saveSiteInfo($info)
    {
        $db_request = Dao_SiteInfo::insert()->clearcache('site_info');

        foreach ($info as $name => $value) $db_request->set($name, $value);

        $db_request->execute();

        return True;
    }

    public function getSiteMenu()
    {
        $menu_pages = Dao_Pages::select()
            ->where('status', '=', 0)
            ->where('is_menu_item', '=', 1)
            ->order_by('id', 'ASC')
            ->cached(Date::MINUTE*5, 'site_menu')
            ->execute();

        return Model_Page::rowsToModels($menu_pages);
    }


    public function getUsers()
    {
        return DB::select()
                ->from('users')
                ->order_by('id','desc')
                ->cached(Date::MINUTE*1)
                ->execute()
                ->as_array();
    }


    public function getComments($page, $status = null, $cached = false )
    {
        if ( (int)$page < 1 ) return array();
        $comments = DB::select()->from('comments')
            ->where('page', '=', $page)
            ->where('is_removed', '=', 0);
        $comments->order_by('id','asc');
        if ($cached) {
            $comments->cached(Date::MINUTE*5);
        }
        $comments = $comments->execute()->as_array();
        if ($comments) return $comments;
        return array();
    }

    public function getCommentById( $id )
    {
        return DB::select()->from('comments')->where('id', '=', $id)->where('status', '<', 2)->execute()->current();
    }

    public function addComment( $data )
    {
        return DB::insert('comments', array_keys($data))->values(array_values($data))->execute();
    }

    public function removeComment( $uid , $id , $isAdmin = false )
    {
        $result = DB::delete('comments')->where('id', '=', $id);
        if ( !$isAdmin ) $result->where('uid', '=', $uid);
        return $result->execute() ? true : false ;
    }

    public function getCommentsCount($type, $target)
    {
        return (int)DB::select('id')->from('comments')->where('type','=',$type)->where('target','=',$target)->cached(Date::MINUTE / 4)->execute()->count();
    }

    public function getBlogPostComments($pid, $status = null, $feed = false, $cached = false)
    {
        return self::getComments( Controller_Comments::COMMENTS_TYPE_BLOG , $pid );
    }


    public function addFileToPage( $fields )
    {
        return current(DB::insert( 'files' , array_keys($fields) )->values(array_values($fields))->execute());
    }

    public function getPageFiles( $page_id )
    {
        return DB::select()
                ->from('files')
                ->where('page','=', $page_id)
                ->where('status', '=', 0)
                ->order_by('id','DESC')
                ->execute()
                ->as_array();
    }

    public function updateFile( $id,  $fields )
    {
        $query = DB::update( 'files' );
        foreach ($fields as $name => $value) {
            $query->set(array($name => $value));
        }
        return $query->where('id','=',$id)->execute();
    }


    public function saveImage( $file , $path )
    {
        /**
         *   Проверки на  Upload::valid($file) OR Upload::not_empty($file) OR Upload::size($file, '8M') делаются в контроллере.
         */

        if (!Upload::type($file, array('jpg', 'jpeg', 'png', 'gif'))) return FALSE;

        if (!is_dir($path)) mkdir($path);

        if ( $file = Upload::save($file, NULL, $path) ){

            $filename = uniqid("", false).'.jpg';

            $image = Image::factory($file);

            foreach ($this->IMAGE_SIZES_CONFIG as $prefix => $sizes) {

                $isSquare = !!$sizes[0];
                $width    = $sizes[1];
                $height   = !$isSquare ? $sizes[2] : $width;

                $image->background('#fff');

                // Вырезание квадрата
                if ( $isSquare ){

                    if ( $image->width >= $image->height ) {
                        $image->resize( NULL , $height, true );
                    } else {
                        $image->resize( $width , NULL, true );
                    }

                    $image->crop( $width, $height );

                    /**
                     *   Для работы с этим методом нужно перекомпилировать php c bundled GD
                     *   http://www.maxiwebs.co.uk/gd-bundled/compilation.php
                     *   http://www.howtoforge.com/recompiling-php5-with-bundled-support-for-gd-on-ubuntu
                     */

                    // $image->sharpen(1.5);

                } else {

                    if ( $image->width > $width || $image->height > $height  ) {
                        $image->resize( $width , $height , true );
                    }

                }

                $image->save($path . $prefix . '_' . $filename);

            }

            // Delete the temporary file
            unlink($file);

            return $filename;
        }

        return FALSE;
    }

    public function saveFile( $file , $path )
    {
        /**
         *   Проверки на  Upload::valid($file) OR Upload::not_empty($file) OR Upload::size($file, '8M') делаются в контроллере.
         */


        if (!is_dir($path)) mkdir($path);

        $ext      = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $filename = uniqid() . '.' . $ext;

        if ( $file = Upload::save($file, $filename, $path) ){

            // Delete the temporary file
            unlink($file);

            return $filename;
        }

        return FALSE;
    }


    /* Надо будет выпилить - устаревает функция */

    public function ftime($timestamp, $long = false , $need_time = true , $short_month = false)
    {
        if ($long && !$need_time) {
            return $this->rusDate("j F Y", $timestamp);
        }

        $time = time() - $timestamp;
        if ( date('d-m-Y', time()) == date('d-m-Y', $timestamp) ) {
            return 'сегодня в ' . date('H:i', $timestamp);
        } elseif ( date('d-m-Y', strtotime("-1 day")) == date('d-m-Y', $timestamp) ) {
            return 'вчера в ' . date('H:i', $timestamp);
        } elseif( $long ) { // если нужна полная дата
            if ( $short_month ){
                return $this->rusDate("j M Y в H:i", $timestamp);
            } else {
                return $this->rusDate("j F Y в H:i", $timestamp);
            }
        } elseif ( $time > Date::MONTH && $time < Date::YEAR ) {
            return round($time / Date::MONTH) . ' ' . self::num_decline(round($time / Date::MONTH), 'месяц','месяца','месяцев') .  ' назад';
        } elseif ( $time > Date::YEAR ) {
            return $this->rusDate("j F Y", $timestamp);
        } else {
            return round($time / Date::DAY) . ' ' . self::num_decline(round($time / Date::DAY), 'день','дня','дней') .' назад';
        }
    }

    public function ltime($timestamp)
    {
        $time = time();
        $ltime = $time - $timestamp;

        if ($ltime < Date::HOUR) {
            $timeToMinute = round($ltime / Date::MINUTE);
            return  $timeToMinute . PHP_EOL . self::num_decline($timeToMinute, 'минуту','минуты','минут') . ' назад';
        } elseif ( date('dmY', $time) == date('dmY', $timestamp) ) {
            return 'сегодня в ' . date('H:i', $timestamp);
        } elseif ( date('dmY', strtotime("-1 day")) == date('dmY', $timestamp) ) {
            return 'вчера в ' . date('H:i', $timestamp);
        } else {
            return $this->rusDate("j M Y в H:i", $timestamp);
        }
    }

    public function dater($timestamp, $type)
    {

        $time = time() - $timestamp;

        if ( $type == 'extra_short' ) {

            if ( date('d-m-Y', time()) == date('d-m-Y', $timestamp) ) {
                return date('H:i', $timestamp);
            } else {
                return round($time / Date::DAY) . ' дн.';
            }

        }
    }

    public function rusDate()
    {
        $translate = array(
            "am"        => "дп",
            "pm"        => "пп",
            "AM"        => "ДП",
            "PM"        => "ПП",
            "Monday"    => "Понедельник",
            "Mon"       => "Пн",
            "Tuesday"   => "Вторник",
            "Tue"       => "Вт",
            "Wednesday" => "Среда",
            "Wed"       => "Ср",
            "Thursday"  => "Четверг",
            "Thu"       => "Чт",
            "Friday"    => "Пятница",
            "Fri"       => "Пт",
            "Saturday"  => "Суббота",
            "Sat"       => "Сб",
            "Sunday"    => "Воскресенье",
            "Sun"       => "Вс",
            "January"   => "Января",
            "Jan"       => "Янв",
            "February"  => "Февраля",
            "Feb"       => "Фев",
            "March"     => "Марта",
            "Mar"       => "Мар",
            "April"     => "Апреля",
            "Apr"       => "Апр",
            "May"       => "Мая",
            "May"       => "Мая",
            "June"      => "Июня",
            "Jun"       => "Июн",
            "July"      => "Июля",
            "Jul"       => "Июл",
            "August"    => "Августа",
            "Aug"       => "Авг",
            "September" => "Сентября",
            "Sep"       => "Сен",
            "October"   => "Октября",
            "Oct"       => "Окт",
            "November"  => "Ноября",
            "Nov"       => "Ноя",
            "December"  => "Декабря",
            "Dec"       => "Дек",
            "st"        => "ое",
            "nd"        => "ое",
            "rd"        => "е",
            "th"        => "ое"
        );

        if (func_num_args() > 1) {
            $timestamp = func_get_arg(1);
            return strtr(date(func_get_arg(0), $timestamp), $translate);
        } else {
            return strtr(date(func_get_arg(0)), $translate);
        }
    }

    public function num_decline($num, $nominative, $genitive_singular, $genitive_plural)
    {
        if($num > 10 && ( floor(($num % 100) / 10) )  == 1){
                return $genitive_plural;
        } else {
            switch($num % 10){
                case 1: return $nominative;
                case 2: case 3: case 4: return $genitive_singular;
                case 5: case 6: case 7: case 8: case 9: case 0: return $genitive_plural;
            }
        }
    }

    public function short( $string = '', $limit = 999999 ){

        if ( strlen($string) > $limit ):
            return Kohana_UTF8::substr($string, 0, $limit) . '...';
        else:
            return $string;
        endif;

    }

    public function specc_short( $string = '' , $limit = 999999 ){

        // $string = 'You <br> gonna be <h2>all right</h2><img src="/public/img/favicon.png" /> So go take <a href="/">down</a> the cross.';
        // $limit = 30;

        $inside_tag  = $inside_close_tag = $insede_alone_tag = $tag_opened = false;
        $char = $ret = '';
        $real_count = 0; // Количество обычных символов (не в тегах), к которому нам и надо стремиться
        $is_trimmed = false;

        // echo 'Char number -> char number without tags -> Char ; Parameters <br>';

        for ( $i = 0 ; $i < strlen($string); $i++ ){

            $char = mb_substr( $string , $i , 1 ) ;
            // echo  $i . '  -> ' . $real_count . '   ->   \'' . $char . '\'' . ' : ';

            if ( $char == '<' ){

                $inside_tag = true;
                // echo ' opened!'; echo $tag_opened ? ' [tag opened] ' : ' [not tag opened] ' ;

                $ret .= $char;        // 1 - символ открытия тэга. Нужен

                $substr = mb_substr( $string, $i+1 , 5);

                if ( $tag_opened && mb_substr( $string ,  $i + 1 , 1 ) == '/' ){

                    $inside_close_tag = true;
                    // echo " inside_close_tag";

                } elseif (  stripos( $substr , 'img') !== false || stripos( $substr , 'br') !== false || stripos( $substr , 'hr') !== false ) {

                    $insede_alone_tag = true;
                    // echo ' inside alone tag ';



                }


            } else if ( $char == '>' ){

                $inside_tag = false;
                // echo ' closed! ' ;

                $ret .= $char;  // 4 - символ закрытия тэга

                if ( ! $insede_alone_tag ) $tag_opened = true;

                if ( $inside_close_tag || $insede_alone_tag ){

                    $tag_opened = false;
                    $insede_alone_tag = false;
                    $inside_close_tag = false;

                }

                // echo $tag_opened ? 'tag opened' : 'not tag opened' ;


                if ( !$tag_opened && $real_count >= $limit - 1 ){

                    // echo '<br> STOPPED AFTER CLOSED TAG';
                    break;

                }


            } else {

                    if ( ! $tag_opened ){

                        if ( $inside_tag ){

                            // echo " in tag ";
                            $ret .= $char; // 2 - символы внутри тэга. Нужны

                        } else {

                            // echo('*');

                                if ( $real_count <= $limit - 1 ) {

                                    $real_count++;
                                    $ret .= $char;     // 3 - Обычные сиволы - не внутри тэга и открытых тегов нет.

                                } else {

                                    if ( $char != ' ' && $char != '\n'){ // даем закончить слово

                                        $real_count++;
                                        $ret .= $char;

                                    } else {

                                        $ret .= ' …';
                                        $is_trimmed = true;
                                        // echo '<br/>';
                                        break;

                                    }

                                }

                        }

                    } else {

                        if ( $inside_tag ){ // Мы внутри закрывающего тега

                            // echo ' in tag ';

                        } else {

                            $real_count++;

                        }

                        $ret .= $char; // Сиволы до закрытия тегаи. Берем всегда, независимо от лимита.

                    }

            }

            // echo '<br>';

        }

        // echo Debug::vars( $ret );
        // exit();

        // echo '<br/><br/>-------------<br><br>';

        return array( 'text' => $ret , 'changed' => $is_trimmed );
    }

    public function auto_link_urls($text)
    {
        // Find and replace all http/https/ftp/ftps links that are not part of an existing html anchor
        // $text = preg_replace_callback('~\b(?<!href="|">)(?:ht|f)tps?://[-.a-zA-Z#\d\&\?=\/%а-яА-Я]+~i', 'self::_auto_link_urls_callback1', $text);
        $text = preg_replace_callback('~\b(?<!href="|">)(?:ht|f)tps?://[-\w\.#\d\&\?=\/%\:_]+\b~i', 'self::_auto_link_urls_callback1', $text);

        // Find and replace all naked www.links.com (without http://)
        return preg_replace_callback('~\b(?<!://|">)www\.[-a-zA-Z\d]+\.[a-z]{2,6}[-\w\/\?=&%\d#\:_]*\b~i', 'self::_auto_link_urls_callback2', $text);
    }

    public function _auto_link_urls_callback1($matches)
    {
        return HTML::anchor(UTF8::clean($matches[0], Kohana::$charset));
    }

    public function _auto_link_urls_callback2($matches)
    {
        return HTML::anchor('http://'.$matches[0], $matches[0]);
    }

    public function renderShareButton($data, $type, $target)
    {
        $result = '';

        $data['data_type'] = $type;
        $data['data_target'] = $target;
        $data['sharingData'] = $data;

        $stats = new Model_Stats();

        $data['social'] = array(
            self::SOCIAL_VK => $stats->redis->get( $stats->getRedisKey(Controller_Ajax::getShareKey($type, $target, self::SOCIAL_VK), Model_Stats::TYPE_HIT_SHARE_BUTTON, 0) ),
            self::SOCIAL_FB => $stats->redis->get( $stats->getRedisKey(Controller_Ajax::getShareKey($type, $target, self::SOCIAL_FB), Model_Stats::TYPE_HIT_SHARE_BUTTON, 0) ),
            self::SOCIAL_TW => $stats->redis->get( $stats->getRedisKey(Controller_Ajax::getShareKey($type, $target, self::SOCIAL_TW), Model_Stats::TYPE_HIT_SHARE_BUTTON, 0) ),
        );

        $result = View::factory('/share/buttons', $data)->render();

        return $result;
    }

    public function makeCorrectUrl( $string )
    {
        return preg_match('/^(?:ht|f)tps?:\/\//', $string) ? $string : 'http://' . $string;
    }

    /**
     * Транслитерация кириллицы
     * @param string $string - строка с киррилицей
     */
    public static function rus2translit($string) {
        $converter = array(
            'а' => 'a',   'б' => 'b',   'в' => 'v',
            'г' => 'g',   'д' => 'd',   'е' => 'e',
            'ё' => 'e',   'ж' => 'zh',  'з' => 'z',
            'и' => 'i',   'й' => 'y',   'к' => 'k',
            'л' => 'l',   'м' => 'm',   'н' => 'n',
            'о' => 'o',   'п' => 'p',   'р' => 'r',
            'с' => 's',   'т' => 't',   'у' => 'u',
            'ф' => 'f',   'х' => 'h',   'ц' => 'c',
            'ч' => 'ch',  'ш' => 'sh',  'щ' => 'sch',
            'ь' => "",    'ы' => 'y',   'ъ' => "",
            'э' => 'e',   'ю' => 'yu',  'я' => 'ya',

            'А' => 'A',   'Б' => 'B',   'В' => 'V',
            'Г' => 'G',   'Д' => 'D',   'Е' => 'E',
            'Ё' => 'E',   'Ж' => 'Zh',  'З' => 'Z',
            'И' => 'I',   'Й' => 'Y',   'К' => 'K',
            'Л' => 'L',   'М' => 'M',   'Н' => 'N',
            'О' => 'O',   'П' => 'P',   'Р' => 'R',
            'С' => 'S',   'Т' => 'T',   'У' => 'U',
            'Ф' => 'F',   'Х' => 'H',   'Ц' => 'C',
            'Ч' => 'Ch',  'Ш' => 'Sh',  'Щ' => 'Sch',
            'Ь' => "",    'Ы' => 'Y',   'Ъ' => "",
            'Э' => 'E',   'Ю' => 'Yu',  'Я' => 'Ya',
        );

        // translit
        $converted_string = strtr($string, $converter);

        return $converted_string;
    }

    public static function getUriByTitle($string)
    {
        // заменяем все кириллические символы на латиницу
        $converted_string = self::rus2translit($string);

        // заменяем все не цифры и не буквы на дефисы
        $converted_string = preg_replace("/[^0-9a-zA-Z]/", "-", $converted_string);

        // заменяем несколько дефисов на один
        $converted_string = preg_replace('/-{2,}/', '-', $converted_string);

        // отсекаем лишние дефисы по краям
        $converted_string = trim($converted_string, '-');

        return $converted_string;

    }

    public static function isAjax()
    {
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest'))
        {
            return true;
        }

        return false;
    }

}
