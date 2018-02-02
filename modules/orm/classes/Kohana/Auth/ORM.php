<?php defined('SYSPATH') or die('No direct access allowed.');
/**
 * ORM Auth driver.
 *
 * @package    Kohana/Auth
 *
 * @author     Kohana Team
 * @copyright  (c) 2007-2012 Kohana Team
 * @license    http://kohanaframework.org/license
 */
class Kohana_Auth_ORM extends Auth
{
    /**
     * Checks if a session is active.
     *
     * @param mixed $role Role name string, role ORM object, or array with role names
     *
     * @return bool
     */
    public function logged_in($role = null)
    {
        // Get the user from the session
        $user = $this->get_user();

        if (! $user) {
            return false;
        }

        if ($user instanceof Model_User and $user->loaded()) {
            // If we don't have a roll no further checking is needed
            if (! $role) {
                return true;
            }

            if (is_array($role)) {
                // Get all the roles
                $roles = ORM::factory('Role')
                            ->where('name', 'IN', $role)
                            ->find_all()
                            ->as_array(null, 'id');

                // Make sure all the roles are valid ones
                if (count($roles) !== count($role)) {
                    return false;
                }
            } else {
                if (! is_object($role)) {
                    // Load the role
                    $roles = ORM::factory('Role', ['name' => $role]);

                    if (! $roles->loaded()) {
                        return false;
                    }
                }
            }

            return $user->has('roles', $roles);
        }
    }

    /**
     * Logs a user in.
     *
     * @param string $username
     * @param string $password
     * @param bool   $remember enable autologin
     * @param mixed  $user
     *
     * @return bool
     */
    protected function _login($user, $password, $remember)
    {
        if (! is_object($user)) {
            $username = $user;

            // Load the user
            $user = ORM::factory('User');
            $user->where($user->unique_key($username), '=', $username)->find();
        }

        if (is_string($password)) {
            // Create a hashed password
            $password = $this->hash($password);
        }

        // If the passwords match, perform a login
        if ($user->has('roles', ORM::factory('Role', ['name' => 'login'])) and $user->password === $password) {
            if ($remember === true) {
                // Token data
                $data = [
                    'user_id' => $user->pk(),
                    'expires' => time() + $this->_config['lifetime'],
                    'user_agent' => sha1(Request::$user_agent),
                ];

                // Create a new autologin token
                $token = ORM::factory('User_Token')
                            ->values($data)
                            ->create();

                // Set the autologin cookie
                Cookie::set('authautologin', $token->token, $this->_config['lifetime']);
            }

            // Finish the login
            $this->complete_login($user);

            return true;
        }

        // Login failed
        return false;
    }

    /**
     * Forces a user to be logged in, without specifying a password.
     *
     * @param mixed $user                   username string, or user ORM object
     * @param bool  $mark_session_as_forced mark the session as forced
     *
     * @return bool
     */
    public function force_login($user, $mark_session_as_forced = false)
    {
        if (! is_object($user)) {
            $username = $user;

            // Load the user
            $user = ORM::factory('User');
            $user->where($user->unique_key($username), '=', $username)->find();
        }

        if ($mark_session_as_forced === true) {
            // Mark the session as forced, to prevent users from changing account information
            $this->_session->set('auth_forced', true);
        }

        // Run the standard completion
        $this->complete_login($user);
    }

    /**
     * Logs a user in, based on the authautologin cookie.
     *
     * @return mixed
     */
    public function auto_login()
    {
        if ($token = Cookie::get('authautologin')) {
            // Load the token and user
            $token = ORM::factory('User_Token', ['token' => $token]);

            if ($token->loaded() and $token->user->loaded()) {
                if ($token->user_agent === sha1(Request::$user_agent)) {
                    // Save the token to create a new unique token
                    $token->save();

                    // Set the new token
                    Cookie::set('authautologin', $token->token, $token->expires - time());

                    // Complete the login with the found data
                    $this->complete_login($token->user);

                    // Automatic login was successful
                    return $token->user;
                }

                // Token is invalid
                $token->delete();
            }
        }

        return false;
    }

    /**
     * Gets the currently logged in user from the session (with auto_login check).
     * Returns $default if no user is currently logged in.
     *
     * @param mixed $default to return in case user isn't logged in
     *
     * @return mixed
     */
    public function get_user($default = null)
    {
        $user = parent::get_user($default);

        if ($user === $default) {
            // check for "remembered" login
            if (($user = $this->auto_login()) === false) {
                return $default;
            }
        }

        return $user;
    }

    /**
     * Log a user out and remove any autologin cookies.
     *
     * @param bool $destroy    completely destroy the session
     * @param bool $logout_all remove all tokens for user
     *
     * @return bool
     */
    public function logout($destroy = false, $logout_all = false)
    {
        // Set by force_login()
        $this->_session->delete('auth_forced');

        if ($token = Cookie::get('authautologin')) {
            // Delete the autologin cookie to prevent re-login
            Cookie::delete('authautologin');

            // Clear the autologin token from the database
            $token = ORM::factory('User_Token', ['token' => $token]);

            if ($token->loaded() and $logout_all) {
                // Delete all user tokens. This isn't the most elegant solution but does the job
                $tokens = ORM::factory('User_Token')->where('user_id', '=', $token->user_id)->find_all();

                foreach ($tokens as $_token) {
                    $_token->delete();
                }
            } elseif ($token->loaded()) {
                $token->delete();
            }
        }

        return parent::logout($destroy);
    }

    /**
     * Get the stored password for a username.
     *
     * @param mixed $user username string, or user ORM object
     *
     * @return string
     */
    public function password($user)
    {
        if (! is_object($user)) {
            $username = $user;

            // Load the user
            $user = ORM::factory('User');
            $user->where($user->unique_key($username), '=', $username)->find();
        }

        return $user->password;
    }

    /**
     * Complete the login for a user by incrementing the logins and setting
     * session data: user_id, username, roles.
     *
     * @param object $user user ORM object
     */
    protected function complete_login($user)
    {
        $user->complete_login();

        return parent::complete_login($user);
    }

    /**
     * Compare password with original (hashed). Works for current (logged in) user
     *
     * @param string $password
     *
     * @return bool
     */
    public function check_password($password)
    {
        $user = $this->get_user();

        if (! $user) {
            return false;
        }

        return ($this->hash($password) === $user->password);
    }
} // End Auth ORM
