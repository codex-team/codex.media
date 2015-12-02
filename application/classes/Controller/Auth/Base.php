<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Auth_Base extends Controller_Base_preDispatch {

    const LOGIN_MAX_TRYING     = 5;
    const AUTH_PASSWORD_SALT = '4974275511a49f463986b6369217945d67518f45';

    const COOKIE_USER_ID = 'uid';
    const COOKIE_SESSION = 'sid';

    /**
    * Generates crypted password
    * @param string $password - original user password from input
    * @return string password hash
    * @author Savchenko Petr (vk.com/specc)
    */
    public static function createPasswordHash( $password )
    {
        return hash('sha256', self::AUTH_PASSWORD_SALT . $password );
    }

    /**
    * Opens user authentication session
    * @param int $uid
    * @param int $socialProvider - type of social provider ( get from config/social )
    * @author Savchenko Petr (vk.com/specc)
    */
    protected static function initAuthSession($uid , $socialProvider = 0, $autoLoginType = 0)
    {
        if (!$uid) return;

        // Если пользователь уже логинился, продливаем сессию, иначе создаем новую
        if ( $sid = Cookie::get(self::COOKIE_SESSION, false) ){
            $sessionCookie = $sid;
            $authSession   = Dao_AuthSessions::update()->where('uid', '=', $uid)->where('cookie', '=', $sid);
        } else {
            $sessionCookie = hash( 'sha256', openssl_random_pseudo_bytes(30) ); // генерим случайный отпечаток для распознавания сессии
            $authSession   = Dao_AuthSessions::insert();
        }

        $authSession->set('uid', $uid)
                    ->set('cookie', $sessionCookie)
                    ->set('useragent', Request::$user_agent)
                    ->set('ip', ip2long(Request::$client_ip))
                    ->set('dt_access' , DB::expr('now()'))
                    ->clearcache($uid);

        if ($socialProvider) $authSession->set('social_provider', $socialProvider);
        if ($autoLoginType) $authSession->set('autologin', $autoLoginType);

        $authSession    = $authSession->execute();
        $cookieLifeTime = time() + Date::YEAR * 2;

        Cookie::set(self::COOKIE_USER_ID , $uid, $cookieLifeTime);
        Cookie::set(self::COOKIE_SESSION , $sessionCookie, $cookieLifeTime);

    }

    /**
    * Inserts new user into DB. Calls from signup or social-auth.
    * @return int $uid - inserted user id
    * @author Savchenko Petr (vk.com/specc)
    */
    protected static function insertUser($fields)
    {
        $user = Dao_User::insert();
        foreach ($fields as $name => $value) $user->set($name, $value);
        return $user->execute();
    }


    /**
    * Checks user's authentication by session-cookie and uid-cookie
    * @return int|bool - UserId or FALSE
    * @author Savchenko Petr (vk.com/specc)
    */
    public static function checkAuth()
    {
        $uid          = (int)Cookie::get(self::COOKIE_USER_ID, '');
        $sid          = Cookie::get(self::COOKIE_SESSION, false);
        $userSessions = $sid ? Dao_AuthSessions::select()->where('uid', '=', $uid)->order_by('id','desc')->cached(Date::DAY, $uid)->execute() : array();

        if ($userSessions){

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

        return FALSE;

    }

    /**
    * Removes auth session by id, uid or cookie
    * @author Savchenko Petr (vk.com/specc)
    */
    public static function deleteSession($id = false , $uid = false, $sid = false)
    {
        if (!$id && !$uid && !$sid) {
            $uid = (int)Cookie::get(self::COOKIE_USER_ID, '');
            $sid = Cookie::get(self::COOKIE_SESSION, false);
        }

        $query = Dao_AuthSessions::delete();

        if ($id) $query = $query->where('id', '=', $id);
        if ($uid) $query = $query->where('uid', '=', $uid);
        if ($sid) $query = $query->where('cookie', '=', $sid);

        $query->clearcache($uid)->execute();

    }

    /**
    * @todo: save session-id to prolongate auth-session with login
    * @author Savchenko Petr (vk.com/specc)
    */
    protected static function clearAuthCookie()
    {
        Cookie::delete(self::COOKIE_USER_ID);
        Cookie::delete(self::COOKIE_SESSION);
    }
}
