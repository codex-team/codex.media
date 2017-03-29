<?php

class Model_Auth extends Model_preDispatch
{

    const TYPE_EMAIL_CONFIRM = 'confirmation';
    const TYPE_EMAIL_RESET   = 'reset';
    const TYPE_EMAIL_CHANGE  = 'change';

    /**
     * Salt should be in .env file, but if it doesn't, we use this fallback salt
     */
    const DEFAULT_EMAIL_HASH_SALT = 'OKexL2iOXbhoJFw1Flb8';

    private $HASHES_KEYS = array(
        self::TYPE_EMAIL_CONFIRM => 'confirmation',
        self::TYPE_EMAIL_RESET   => 'reset',
        self::TYPE_EMAIL_CHANGE  => 'change'
    );

    public $user;

    public function __construct($user = null)
    {

        if ($user && $user->id) {
            $this->user = $user;
        }

        parent::__construct();

    }

    /**
     * Adds pair hash => id to redis and sends email with confirmation link
     *
     * @param $user
     */
    public function sendConfirmationEmail() {

        $hash = $this->generateHash(self::TYPE_EMAIL_CONFIRM);

        $message = View::factory('templates/emails/auth/confirm', array('user' => $this->user, 'hash' => $hash));

        $email = new Email();
        return $email->send(
            [$this->user->email],
            [$GLOBALS['SITE_MAIL'], $_SERVER['HTTP_HOST']],
            "Добро пожаловать на ".$_SERVER['HTTP_HOST'],
            $message,
            false
        );

    }

    /**
     * Adds pair hash => id to redis and sends email with reset link
     *
     * @param $user
     */
    public function sendResetPasswordEmail() {

        $hash = $this->generateHash(self::TYPE_EMAIL_RESET);

        $message = View::factory('templates/emails/auth/reset', array('user' => $this->user, 'hash' => $hash));

        $email = new Email();
        return $email->send(
            [$this->user->email],
            [$GLOBALS['SITE_MAIL'], $_SERVER['HTTP_HOST']],
            "Сброс пароля на ".$_SERVER['HTTP_HOST'],
            $message,
            false
        );


    }

    public function sendChangePasswordEmail() {

        $hash = $this->generateHash(self::TYPE_EMAIL_CHANGE);

        $message = View::factory('templates/emails/auth/change', array('user' => $this->user, 'hash' => $hash));

        $email = new Email();
        return $email->send(
            [$this->user->email],
            [$GLOBALS['SITE_MAIL'], $_SERVER['HTTP_HOST']],
            "Сброс пароля на ".$_SERVER['HTTP_HOST'],
            $message,
            false
        );

    }

    /**
     * Generates confirmation hash and adds it to redis
     *
     * @param $user
     * @return string
     */
    private function generateHash($type) {

        $key_prefix = Arr::get($_SERVER, 'REDIS_PREFIX', 'codex.org:') . 'hashes:';
        $key        = $key_prefix . $this->HASHES_KEYS[$type];

        $salt = Arr::get($_SERVER, 'EMAIL_HASH_SALT', self::DEFAULT_EMAIL_HASH_SALT);

        $hash = hash('sha256', $this->user->id . $salt . $this->user->email);

        $this->redis->hSet($key, $hash, $this->user->id);

        return $hash;

    }

    /**
     * Gets user id from redis by confirmation hash
     *
     * @param $hash
     * @return string
     */
    public function getUserIdByHash($hash, $type) {

        $key_prefix = Arr::get($_SERVER, 'REDIS_PREFIX', 'codex.org:') . 'hashes:';
        $key        = $key_prefix . $this->HASHES_KEYS[$type];

        $id = $this->redis->hGet($key, $hash);

        return $id;

    }

    public function deleteHash($hash, $type) {

        $key_prefix = Arr::get($_SERVER, 'REDIS_PREFIX', 'codex.org:') . 'hashes:';
        $key        = $key_prefix . $this->HASHES_KEYS[$type];

        $this->redis->hDel($key, $hash);
    }

}
