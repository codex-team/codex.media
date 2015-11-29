<?php defined('SYSPATH') or die('No direct script access.');

class Model_User extends Model_preDispatch
{
    public $id            = 0;
    public $username      = '';
    public $real_name     = '';
    public $password      = '';
    public $photo         = '';
    public $photo_medium  = '';
    public $photo_big     = '';
    public $email         = '';
    
    public $twitter       = '';
    public $twitter_name  = '';
    public $vk            = '';
    public $vk_name       = '';
    public $facebook      = '';
    public $facebook_name = '';
    
    public $status        = 0;
    
    public $isMe          = true;
    
    public $isOnline      = 0;
    public $lastOnline    = 0;

    public function __construct($uid = null)
    {
        parent::__construct();        
        if ( !$uid ) return;

        $user = $this->getUserInfo($uid);        
        
        if ($user) {
            
            $this->id        = $user['id'];
            $this->username  = strip_tags($user['username']);
            $this->real_name = strip_tags($user['real_name']);
            $this->password  = $user['password'];
            
            $this->photo         = trim($user['photo'])          ? strip_tags($user['photo'])         : '/public/img/default_ava_small.png' ;
            $this->photo_medium  = trim($user['photo_medium'])   ? strip_tags($user['photo_medium'])  : '/public/img/default_ava.png';
            $this->photo_big     = trim($user['photo_big'])      ? strip_tags($user['photo_big'])     : '/public/img/default_ava_big.png';

            $this->email         = strip_tags($user['email']);
            $this->twitter       = strip_tags($user['twitter']);
            $this->twitter_name  = strip_tags($user['twitter_name']);
            $this->vk            = strip_tags($user['vk']);
            $this->vk_name       = strip_tags($user['vk_name']);
            $this->facebook      = strip_tags($user['facebook']);
            $this->facebook_name = strip_tags($user['facebook_name']);

            $this->status = $user['status'];

            $this->isOnline = $this->redis->exists('user:'.$this->id.':online') ? 1 : 0;
            $this->lastOnline = self::getLastOnlineTimestamp();
            
            if (!$user || $user['id'] != (int)Cookie::get('uid', '')) $this->isMe = false;
        }
    }

    public function getLastOnlineTimestamp()
    {
        return (int)$this->redis->get('user:'.$this->id.':online:timestamp');
    }

    public function getUserInfo($uid, $update = false)
    {
        if ($update) {
            Kohana_Cache::instance('memcache')->delete('user_model:' . $uid);
        } else {
            if ($cache = Kohana_Cache::instance('memcache')->get('user_model:' . $uid)) {
                return $cache;
            } else {
                $user_model = DB::select()->from('users')->where('id', '=', $uid)->limit(1)->execute()->current();
                Kohana_Cache::instance('memcache')->set('user_model:' . $uid, $user_model, Date::DAY);
                return $user_model;
            }
        }
    }

    public function setAuthCookie($id)
    {
        $id = (int)$id;
        Cookie::set('uid', $id, Date::MONTH);
        Cookie::set('hr', sha1('dfhgga23'.$id.'dfhshgf23'), Date::MONTH);
    }


    public function saveAvatar($file)
    {
        if ($file && $file['tmp_name']) {
            $img = new Model_Image($file['tmp_name']);

            if (!$img) {
                return false;
            }

            if(!is_dir('upload/profile/'))
                mkdir('upload/profile/');

            $file_name = uniqid("", false).'.jpg';
            $img->best_fit(400,400)->save('upload/profile/l_'.$file_name);
            $img->square_crop(100)->save('upload/profile/m_'.$file_name);
            $img->square_crop(50)->save('upload/profile/s_'.$file_name);

            $arr = DB::update('users')->set(array('photo' => 'upload/profile/s_'.$file_name, 'photo_medium' => 'upload/profile/m_'.$file_name, 'photo_big' => 'upload/profile/l_'.$file_name))->where('id', '=', $this->id)->execute();
            if ($arr) {
                $this->photo = 'upload/profile/s_'.$file_name;
                $this->photo_medium = 'upload/profile/m_'.$file_name;
                $this->photo_big = 'upload/profile/l_'.$file_name;
                $this->getUserInfo($this->id, true);
            }
        }
    }


    public function isAdmin()
    {
        if (!$this->id) return false;
        if (array_search($this->id, $this->admins) !== false) return true;
        return false;
    }

    public function searchUsersByString( $string , $limit = 10 )
    {

        if ( $string ){

            $users = DB::select( 'id', 'real_name', 'photo' )
                            ->from('users')
                            ->where( 'real_name' , 'LIKE' , '%' . $string . '%' )
                            ->or_where( 'twitter_name' , 'LIKE' , '%' . $string . '%' )
                            ->or_where( 'twitter' , 'LIKE' , '%' . $string . '%' )
                            ->or_where( 'vk_name' , 'LIKE' , '%' . $string . '%' )
                            ->or_where( 'facebook_name' , 'LIKE' , '%' . $string . '%' )
                            ->limit(  $limit  )
                            ->cached( Date::DAY * 5 )
                            ->execute()
                            ->as_array();
        
        } else {

            return false;

        }

        if ($users) return $users;
        return array();
    }


    public function setUserStatus($status)
    {
        return DB::update('users')->set(array('status' => $status))->where('id','=', $this->id)->execute();
    }

}