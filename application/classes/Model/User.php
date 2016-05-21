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
    public $phone               = '';

    public $twitter             = '';
    public $twitter_name        = '';
    public $twitter_username    = '';
    public $vk                  = '';
    public $vk_name             = '';
    public $vk_uri              = '';
    public $facebook            = '';
    public $facebook_name       = '';

    public $status              = 0;

    public $dt_reg              = null;

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

            /** Fill model by DB row */
            foreach ($user as $field => $value) {
                if (property_exists($this, $field)) {
                    $this->$field = $value;
                }
            }

            if (!$this->photo || !$this->photo_medium || !$this->photo_big){
                $this->photo        = '/public/img/default_ava_small.png';
                $this->photo_medium = '/public/img/default_ava.png';
                $this->photo_big    = '/public/img/default_ava_big.png';
            }

            $this->isTeacher        = $this->isTeacher();
            $this->isAdmin          = $this->isAdmin();

            $this->isOnline         = $this->redis->exists('user:'.$this->id.':online') ? 1 : 0;
            $this->lastOnline       = self::getLastOnlineTimestamp();
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
                    ->cached(Date::HOUR, 'user:' . $id)
                    ->execute();

        return self::fillByRow($user);
    }

    public function updateUser($user_id, $fields)
    {
        $user = Dao_Users::update()
                ->where('id', '=', $user_id)
                ->clearcache('user:' . $user_id);

        foreach ($fields as $name => $value) $user->set($name, trim(htmlspecialchars($value)));

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

    public function setUserStatus($status)
    {
        $this->status = $status;

        Dao_Users::update()
            ->where('id', '=', $this->id)
            ->set('status', $status)
            ->clearcache('user:' . $this->id)
            ->clearcache('teachers')
            ->execute();

        $this->isTeacher        = $this->isTeacher();
        $this->isAdmin          = $this->isAdmin();


        return true;
    }

    public function saveAvatar($file, $path)
    {
        $model = new Model_Methods();
        $filename = $model->saveImage($file, $path);

        $fields = array(
            'photo'        => $path . 's_' . $filename,
            'photo_medium' => $path . 'm_' . $filename,
            'photo_big'    => $path . 'b_' . $filename
            );

        $this->updateUser($this->id, $fields);
    }

    public function isAdmin()
    {
        if (!$this->id) return false;
        return $this->status == self::USER_STATUS_ADMIN;
    }

    public function isTeacher()
    {
        if (!$this->id) return false;
        return $this->status >= self::USER_STATUS_TEACHER;
    }


    public function getUserPages($id_parent = 0)
    {
        $pages = Dao_Pages::select()
                    ->where('author', '=', $this->id)
                    ->where('status', '=', Model_Page::STATUS_SHOWING_PAGE)
                    ->where('type', '=', Model_Page::TYPE_USER_PAGE)
                    ->where('id_parent', '=', $id_parent)
                    ->order_by('id','DESC')
                    ->execute();

        return Model_Page::rowsToModels($pages);
    }

    public static function getUsersList($status)
    {
        $teachers = Dao_Users::select()
                        ->where('status', '>=', $status)
                        ->order_by('id','ASC')
                        ->cached(Date::HOUR, 'users_list:' . $status)
                        ->execute();

        return Model_User::rowsToModels($teachers);
    }

    public static function rowsToModels($users_rows)
    {
        $users = array();

        if (!empty($users_rows))
        {
            foreach ($users_rows as $user_row)
            {
                $user = new Model_User();

                $user->fillByRow($user_row);

                array_push($users, $user);
            }
        }

        return $users;
    }

}
