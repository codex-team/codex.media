<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Auth_Base extends Controller_Base_preDispatch
{
    const LOGIN_MAX_TRYING = 5;
    const AUTH_PASSWORD_SALT = '4974275511a49f463986b6369217945d67518f45';

    const COOKIE_USER_ID = 'uid';
    const COOKIE_SESSION = 'sid';

    /**
     * Generates crypted password
     *
     * @param string $password - original user password from input
     *
     * @return string password hash
     *
     * @author Savchenko Petr (vk.com/specc)
     */
    public static function createPasswordHash($password)
    {
        return hash('sha256', self::AUTH_PASSWORD_SALT . $password);
    }

    /**
     * Opens user authentication session
     *
     * @param int   $uid
     * @param int   $socialProvider - type of social provider ( get from config/social )
     * @param mixed $autoLoginType
     *
     * @author Savchenko Petr (vk.com/specc)
     */
    protected static function initAuthSession($uid, $socialProvider = 0, $autoLoginType = 0)
    {
        if (!$uid) {
            return;
        }

        $sid = Cookie::get(self::COOKIE_SESSION, false);

        $isSessionExistInDB = (bool) Dao_AuthSessions::select()->where('uid', '=', $uid)->where('cookie', '=', $sid)->execute();

        if ($sid && $isSessionExistInDB) {
            $sessionCookie = $sid;
            $authSession = Dao_AuthSessions::update()->where('uid', '=', $uid)->where('cookie', '=', $sid);
        } else {
            $sessionCookie = hash('sha256', openssl_random_pseudo_bytes(30)); // генерим случайный отпечаток для распознавания сессии
            $authSession = Dao_AuthSessions::insert();
        }

        $authSession->set('uid', $uid)
                    ->set('cookie', $sessionCookie)
                    ->set('useragent', Request::$user_agent)
                    ->set('ip', ip2long(Request::$client_ip))
                    ->set('dt_access', DB::expr('now()'))
                    ->clearcache($uid);

        if ($socialProvider) {
            $authSession->set('social_provider', $socialProvider);
        }
        if ($autoLoginType) {
            $authSession->set('autologin', $autoLoginType);
        }

        $authSession = $authSession->execute();
        $cookieLifeTime = Date::YEAR * 100;

        $uidSetting = Cookie::set(self::COOKIE_USER_ID, $uid, $cookieLifeTime);
        $sidSetting = Cookie::set(self::COOKIE_SESSION, $sessionCookie, $cookieLifeTime);
    }

    /**
     * Inserts new user into DB. Calls from signup or social-auth.
     *
     * @author Savchenko Petr (vk.com/specc)
     *
     * @param mixed $fields
     *
     * @return int $uid - inserted user id
     */
    protected static function insertUser($fields)
    {
        $user = Dao_Users::insert();

        foreach ($fields as $name => $value) {
            $user->set($name, $value);
        }

        return $user->execute();
    }

    /**
     * Checks user's authentication by session-cookie and uid-cookie
     *
     * @return int|bool - UserId or FALSE
     *
     * @author Savchenko Petr (vk.com/specc)
     */
    public static function checkAuth()
    {
        $uid = (int) Cookie::get(self::COOKIE_USER_ID, '');
        $sid = Cookie::get(self::COOKIE_SESSION, false);
        $userSessions = $sid ? Dao_AuthSessions::select()->where('uid', '=', $uid)->order_by('id', 'desc')->cached(Date::DAY, $uid)->execute() : [];

        if ($userSessions) {
            foreach ($userSessions as $session) {
                if ($sid == $session['cookie'] && !$session['dt_close']) {
                    return $uid;
                    break;
                }
            }

            // If no one session equals sid, means fraud. Delete all cookies
            Cookie::delete(self::COOKIE_SESSION);
            Cookie::delete(self::COOKIE_USER_ID);
        }

        return false;
    }

    /**
     * Check whether to delete the social network is possible\
     *
     * @author  Alexander Demyashev
     *
     * @var string $uid
     *
     * @param mixed $uid
     *
     * @return bool TRUE / FALSE
     */
    public static function rightToUnbindSocial($uid)
    {
        $security = Dao_Users::select(['email', 'password'])->where('id', '=', $uid)->limit(1)->execute();
        $socials = Dao_Users::select(['twitter', 'facebook', 'vk'])->where('id', '=', $uid)->limit(1)->execute();

        if (!empty($security['email']) && !empty($security['password'])) {
            return true;
        }

        return count(array_filter($socials)) > 1;
    }

    /**
     * Removes auth session by id, uid or cookie
     *
     * @author Savchenko Petr (vk.com/specc)
     *
     * @param mixed $id
     * @param mixed $uid
     * @param mixed $sid
     */
    public static function deleteSession($id = false, $uid = false, $sid = false)
    {
        if (!$id && !$uid && !$sid) {
            $uid = (int) Cookie::get(self::COOKIE_USER_ID, '');
            $sid = Cookie::get(self::COOKIE_SESSION, false);
        }

        if (! ($id || $uid || $sid)) {

            /** Debug */
            $message = [
                'id' => $id,
                'uid' => $uid,
                'sid' => $sid
            ];

            $user_id = Controller_Auth_Base::checkAuth();

            $protocol = HTTP::$protocol == 'HTTP' ? 'http://' : 'https://';
            if (!empty(Request::current())) {
                $path = $protocol . Arr::get($_SERVER, 'SERVER_NAME') . Request::current()->url();
            } else {
                $path = '';
            }

            return;
        }

        $query = Dao_AuthSessions::delete();

        if ($id) {
            $query = $query->where('id', '=', $id);
        }
        if ($uid) {
            $query = $query->where('uid', '=', $uid);
        }
        if ($sid) {
            $query = $query->where('cookie', '=', $sid);
        }

        $query->clearcache($uid)->execute();
    }

    /**
     * @todo: save session-id to prolongate auth-session with login
     *
     * @author Savchenko Petr (vk.com/specc)
     */
    protected static function clearAuthCookie()
    {
        Cookie::delete(self::COOKIE_USER_ID);
        Cookie::delete(self::COOKIE_SESSION);
    }
}
