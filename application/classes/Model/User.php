<?php defined('SYSPATH') or die('No direct script access.');

class Model_User extends Model_preDispatch
{
    public $id                  = 0;
    public $name                = '';
    public $password            = '';
    public $photo               = '';
    public $photo_medium        = '';
    public $photo_big           = '';
    public $email               = '';

    public $twitter             = '';
    public $twitter_name        = '';
    public $twitter_username    = '';
    public $vk                  = '';
    public $vk_name             = '';
    public $vk_uri              = '';
    public $facebook            = '';
    public $facebook_name       = '';

    public $status              = 0;

    public $isMe                = true;

    public $isOnline            = 0;
    public $lastOnline          = 0;

    public function __construct($uid = null)
    {
        parent::__construct();
        if ( !$uid ) return;

        $user = $this->getUserInfo($uid);

        if ($user) {

            $this->id               = $user['id'];
            $this->name             = strip_tags($user['name']);
            $this->password         = $user['password'];

            $this->photo            = trim($user['photo'])          ? strip_tags($user['photo'])         : '/public/img/default_ava_small.png' ;
            $this->photo_medium     = trim($user['photo_medium'])   ? strip_tags($user['photo_medium'])  : '/public/img/default_ava.png';
            $this->photo_big        = trim($user['photo_big'])      ? strip_tags($user['photo_big'])     : '/public/img/default_ava_big.png';

            $this->email            = strip_tags($user['email']);
            $this->twitter          = strip_tags($user['twitter']);
            $this->twitter_name     = strip_tags($user['twitter_name']);
            $this->twitter_username = strip_tags($user['twitter_username']);
            $this->vk               = strip_tags($user['vk']);
            $this->vk_uri           = strip_tags($user['vk_uri']);
            $this->vk_name          = strip_tags($user['vk_name']);
            $this->facebook         = strip_tags($user['facebook']);
            $this->facebook_name    = strip_tags($user['facebook_name']);

            $this->status           = $user['status'];

            $this->isOnline         = $this->redis->exists('user:'.$this->id.':online') ? 1 : 0;
            $this->lastOnline       = self::getLastOnlineTimestamp();

            if (!$user || $user['id'] != (int)Cookie::get(Controller_Auth_Base::COOKIE_USER_ID, '')) $this->isMe = false;
        }
    }

    /** Check for user emain uniqueness */
    public function hasUniqueEmail($email)
    {
        $arr = DB::select('id')->from('users')->where('email', '=', $email)->limit(1)->execute()->current();
        if (!$arr) return true;
        return false;
    }

    /**
     *  Update user fields
     *  @author Alexander Demyashev
     *  @return bool
     */
    public function updateUser($user_id, $fields)
    {
        $user = Dao_User::update()->where('id', '=', $user_id);
        foreach ($fields as $name => $value) $user->set($name, $value);
        return $user->execute();
    }

    public function getLastOnlineTimestamp()
    {
        return (int)$this->redis->get('user:'.$this->id.':online:timestamp');
    }

    public function getUserInfo($uid, $update = false) 
    {
        return Dao_User::select()->where('id', '=', $uid)->limit(1)->cached(Date::DAY, $uid)->execute(); 
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
        if ($this->status == Controller_User::USER_STATUS_ADMIN) return true;
        return false;
    }

    public function isTeacher()
    {
        if (!$this->id) return false;
        if ($this->status >= Controller_User::USER_STATUS_TEACHER) return true;
        return false;
    }


    public function searchUsersByString( $string , $limit = 10 )
    {

        if ( $string ){

            $users = DB::select( 'id', 'name', 'photo' )
                            ->from('users')
                            ->where( 'name' , 'LIKE' , '%' . $string . '%' )
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


    public function getUserPages($user_id, $type = Controller_Pages::TYPE_USER_PAGE, $id_parent = 0)
    {
        $pages = DB::select()->from('pages')
                    ->where('author', '=', $user_id)
                    ->where('type', '=', $type)
                    ->where('id_parent', '=', $id_parent)
                    ->order_by('id','DESC')
                    ->cached(Date::MINUTE*0);

        return $pages->execute()->as_array();
    }

}