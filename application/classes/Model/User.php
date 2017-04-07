<?php defined('SYSPATH') or die('No direct script access.');

class Model_User extends Model
{
    public $id                  = 0;
    public $name                = '';
    public $bio                 = '';
    public $password            = '';
    public $photo               = '';
    public $photo_medium        = '';
    public $photo_big           = '';
    public $email               = '';
    public $isConfirmed         = 0;

    public $twitter             = '';
    public $twitter_name        = '';
    public $twitter_username    = '';
    public $vk                  = '';
    public $vk_name             = '';
    public $vk_uri              = '';
    public $facebook            = '';
    public $facebook_name       = '';

    public $role                = 0;

    public $dt_reg              = null;

    /** user's role */
    public $isTeacher           = false;
    public $isAdmin             = false;

    /** user's status */
    public $isBanned            = false;

    public $isOnline            = 0;
    public $lastOnline          = 0;

    private $status             = 0;

    /** User role constants */
    const ADMIN      = 3;
    const TEACHER    = 2;
    const REGISTERED = 1;
    const GUEST      = 0;

    /** User status constants */
    const BANNED   = 1;
    const STANDARD = 0;


    const USER_POSTS_LIMIT_PER_PAGE = 7; # Must be > 1

    /**
     * Model_User constructor.
     *
     * Model_User constructor could be called with $needle param.
     * If $needle consists valid user id or user email, model will be filled with user data
     *
     * @param string|int|null $needle
     */
    public function __construct($needle = null)
    {

        if (Valid::email($needle)) {
            self::getByEmail($needle);
        } elseif(Valid::digit($needle)) {
            self::get($needle);
        }

    }

    private function fillByRow($user)
    {
        if ($user) {

            /** Fill model by DB row */
            foreach ($user as $field => $value) {

                if (property_exists($this, $field)) {

                    $this->$field = $value;
                }
            }

            if (!$this->photo || !$this->photo_medium || !$this->photo_big){

                $this->photo        = '/public/app/img/default_ava_small.png';
                $this->photo_medium = '/public/app/img/default_ava.png';
                $this->photo_big    = '/public/app/img/default_ava_big.png';
            }

            $this->isTeacher        = $this->isTeacher();
            $this->isAdmin          = $this->isAdmin();
            $this->isBanned         = $this->isBanned();

            // $this->isOnline         = $this->redis->exists('user:'.$this->id.':online') ? 1 : 0;
            // $this->lastOnline       = self::getLastOnlineTimestamp();
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
            ->cached(5 * Date::MINUTE, 'user:' . $id)
            ->execute();

       return $this->fillByRow($user);

    }

    public function getByEmail($email) {

        $user = Dao_Users::select()
            ->where('email', '=', $email)
            ->limit(1)
            ->cached(5 * Date::MINUTE, 'user:email:' . $email)
            ->execute();

        return $this->fillByRow($user);

    }

    /**
     * @param $user_id
     * @param $fields
     * @return boolean
     */
    public function updateUser($user_id, $fields)
    {

        $user = Dao_Users::update()
            ->where('id', '=', $user_id)
            ->clearcache('user:' . $user_id);

        foreach ($fields as $name => $value) {
            $user->set($name, trim(htmlspecialchars($value)));
            $this->{$name} = $value;
        }

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

    /**
     * Updates user's photos
     * @param  string  $filename    new photo filename
     * @param  string  $path        file path
     * @return Boolean              update result
     */
    public function updatePhoto($filename, $path)
    {
        $fields = array(
            'photo'        => $path . 's_' . $filename,
            'photo_medium' => $path . 'm_' . $filename,
            'photo_big'    => $path . 'b_' . $filename
        );

        return $this->updateUser($this->id, $fields);
    }

    public function isAdmin()
    {
        if (!$this->id) return false;

        return $this->role == self::ADMIN;
    }

    public function isTeacher()
    {
        if (!$this->id) return false;

        return $this->role >= self::TEACHER;
    }


    public function getUserPages($offset = 0, $count = 0)
    {
        $pages = Dao_Pages::select()
            ->where('author', '=', $this->id)
            ->where('status', '=', Model_Page::STATUS_SHOWING_PAGE)
            ->order_by('id','DESC')
            ->offset($offset);

        if ($count) $pages->limit($count);

        $pages = $pages->execute();

        $models = Model_Page::rowsToModels($pages);

        return $models;
    }

    public static function getUsersList($role)
    {
        $teachers = Dao_Users::select()
            ->where('role', '>=', $role)
            ->order_by('id','ASC')
            ->cached(5 * Date::MINUTE, 'users_list:' . $role, array('users'))
            ->execute();

        return Model_User::rowsToModels($teachers);
    }

    public static function rowsToModels($users_rows)
    {
        $users = array();

        if (!empty($users_rows)) {

            foreach ($users_rows as $user_row) {

                $user = new Model_User();

                $user->fillByRow($user_row);

                array_push($users, $user);
            }
        }

        return $users;
    }

    /**
     * Returns TRUE if param $pass equal to user password
     *
     * @param $pass
     * @return bool
     */
    public function checkPassword($pass)
    {
        return $this->password == Controller_Auth_Base::createPasswordHash($pass);
    }

    /**
     * Returns TRUE if user with $field = $value exists
     *
     * @param $field
     * @param $value
     *
     * @return bool
     */
    public static function exists($field, $value) {

        $selection = Dao_Users::select($field)
            ->where($field, '=', $value)
            ->limit(1)
            ->execute();

        return  (bool) $selection;
    }

    public function isBanned()
    {
        return $this->status == self::BANNED;
    }

}