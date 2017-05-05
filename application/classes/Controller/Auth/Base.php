<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Auth_Base extends Controller_Base_preDispatch
{
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
    public static function createPasswordHash($password)
    {
        return hash('sha256', self::AUTH_PASSWORD_SALT . $password);
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

        $log = Log::instance();

        if ($sid = Cookie::get(self::COOKIE_SESSION, false)) {

            $sessionCookie = $sid;
            $authSession   = Dao_AuthSessions::update()->where('uid', '=', $uid)->where('cookie', '=', $sid);

            $log->add(Log::DEBUG, 'Auth for logined user :uid. Need to update session witd sid :sid', array(
                ':uid' => $uid,
                ':sid' => $sessionCookie
            ));

        } else {

            $sessionCookie = hash('sha256', openssl_random_pseudo_bytes(30)); // генерим случайный отпечаток для распознавания сессии
            $authSession = Dao_AuthSessions::insert();

            $log->add(Log::DEBUG, 'Auth for not authorized user :uid. Create new session with sid :sid', array(
                ':uid' => $uid,
                ':sid' => $sessionCookie
            ));

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
        $cookieLifeTime = Date::MONTH;

        $log->add(Log::DEBUG, 'New auth session - :result', array(
            ':result' => json_encode(array('result' => $authSession))
        ));

        $uidSetting = Cookie::set(self::COOKIE_USER_ID , $uid, $cookieLifeTime);
        $sidSetting = Cookie::set(self::COOKIE_SESSION , $sessionCookie, $cookieLifeTime);

        $log->add(Log::DEBUG, 'New cookie set: ' . PHP_EOL . 'uid :uid ' . PHP_EOL . 'sid :sid' . PHP_EOL, array(
            ':uid' => $uidSetting,
            ':sid' => $sidSetting
        ));
    }

    /**
    * Inserts new user into DB. Calls from signup or social-auth.
    * @return int $uid - inserted user id
    * @author Savchenko Petr (vk.com/specc)
    */
    protected static function insertUser($fields)
    {
        $user = Dao_Users::insert();

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

        Log::instance()->add(Log::DEBUG, 'Check auth method. uid[:uid] sid [:sid] foundSessions[:count]', array(
            ':uid' => $uid,
            ':sid' => $sid,
            ':count' => count($userSessions),
        ));

        if ($userSessions) {

            foreach ($userSessions as $session) {

                if ($sid == $session['cookie'] && !$session['dt_close']) {

                    Log::instance()->add(Log::DEBUG, 'Current :uid session found! :session', array(
                        ':uid'  => $uid,
                        ':session' => json_encode($session)
                    ));

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
     * Check whether to delete the social network is possible\
     * @author  Alexander Demyashev
     *
     * @var    string   $uid
     * @return bool     TRUE / FALSE
     */
    public static function rightToUnbindSocial($uid)
    {
        $security = Dao_Users::select(['email', 'password'])->where('id', '=', $uid)->limit(1)->execute();
        $socials  = Dao_Users::select(['twitter', 'facebook', 'vk'])->where('id', '=', $uid)->limit(1)->execute();

        if (!empty($security['email']) && !empty($security['password'])) {

            return true;

        }

        return count(array_filter($socials)) > 1;
    }

    /**
    * Removes auth session by id, uid or cookie
    * @author Savchenko Petr (vk.com/specc)
    */
    public static function deleteSession($id = false , $uid = false, $sid = false)
    {
        if (!$id && !$uid && !$sid) {

            $uid = (int) Cookie::get(self::COOKIE_USER_ID, '');
            $sid = Cookie::get(self::COOKIE_SESSION, false);
        }

        if ( ! ($id || $uid || $sid) ) {

            /** Debug */
            $message = array(
                'id'  => $id,
                'uid' => $uid,
                'sid' => $sid
            );

            Model_Services_Telegram::sendBotNotification($telegramMsg);
            /***/

            return;
        }

        $query = Dao_AuthSessions::delete();

        if ($id)  $query = $query->where('id', '=', $id);
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
