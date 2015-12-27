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
    public $isTeacher           = false;
    public $isAdmin             = false;

    public $isOnline            = 0;
    public $lastOnline          = 0;

    const USER_STATUS_ADMIN         = 2;
    const USER_STATUS_TEACHER       = 1;
    const USER_STATUS_REGISTERED    = 0;
    const USER_STATUS_BANNED        = -1;

    public function __construct($uid = null)
    {
        parent::__construct();
        if ( !$uid ) return;

        $user = self::get($uid);

        self::fillByRow($user);
    }

    public function fillByRow($user)
    {
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
            $this->isTeacher        = $this->isTeacher();
            $this->isAdmin          = $this->isAdmin();

            $this->isOnline         = $this->redis->exists('user:'.$this->id.':online') ? 1 : 0;
            $this->lastOnline       = self::getLastOnlineTimestamp();

            if (!$user || $user['id'] != (int)Cookie::get(Controller_Auth_Base::COOKIE_USER_ID, '')) $this->isMe = false;
        }
    }

    /** Check for user emain uniqueness */
    public function hasUniqueEmail($email)
    {
        $arr = Dao_Users::select('id')
                    ->where('email', '=', $email)
                    ->limit(1)
                    ->execute();
        if (!$arr) return true;
        return false;
    }

    public function get($id)
    {
        $user = Dao_Users::select()
                    ->where('id', '=', $id)
                    ->limit(1)
                    ->cached(Date::DAY, 'user:' . $id)
                    ->execute();

        return self::fillByRow($user);
    }

    public function updateUser($user_id, $fields)
    {
        $user = Dao_Users::update()
                ->where('id', '=', $user_id)
                ->clearcache('user:' . $user_id);

        foreach ($fields as $name => $value) $user->set($name, $value);

        return $user->execute();
    }

    public function getLastOnlineTimestamp()
    {
        return (int)$this->redis->get('user:'.$this->id.':online:timestamp');
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

    public function setUserStatus($act)
    {

        switch ($act) {
            case 'rise'    :
                $status = Model_User::USER_STATUS_TEACHER;
                break;
            case 'ban'     :
                $status = Model_User::USER_STATUS_BANNED;
                break;
            case 'degrade' :
            case 'unban'   :
                $status = Model_User::USER_STATUS_REGISTERED;
                break;
            default        :
                $status = FALSE;
        }

        if ($status)
        {
            $this->status = $status;

            return Dao_Users::update()
                ->where('id', '=', $this->id)
                ->set('status', $status)
                ->execute();
        }

        return FALSE;
    }

    public function isAdmin()
    {
        if (!$this->id) return false;
        if ($this->status == self::USER_STATUS_ADMIN) return true;
        return false;
    }

    public function isTeacher()
    {
        if (!$this->id) return false;
        if ($this->status >= self::USER_STATUS_TEACHER) return true;
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


    public function getUserPages($id_parent = 0)
    {
        $pages = Dao_Pages::select()
                    ->where('author', '=', $this->id)
                    ->where('status', '=', 0)
                    ->where('type', '=', Model_Page::TYPE_USER_PAGE)
                    ->where('id_parent', '=', $id_parent)
                    ->order_by('id','DESC')
                    ->execute();

        return Model_Page::rowsToModels($pages);
    }

}