<?php

class Model_Auth extends Model_preDispatch
{

    const TYPE_EMAIL_CONFIRM = 'confirmation';
    const TYPE_EMAIL_RESET   = 'reset';

    /**
     * Salt should be in .env file, but if it doesn't, we use this fallback salt
     */
    const DEFAULT_EMAIL_HASH_SALT = 'OKexL2iOXbhoJFw1Flb8';

    private $HASHES_KEYS = array(
        self::TYPE_EMAIL_CONFIRM => 'codex.org.confirmation.hashes',
        self::TYPE_EMAIL_RESET  => 'codex.org.reset.hashes'
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
            [$GLOBALS['SITE_MAIL'], "no-reply"],
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
            [$GLOBALS['SITE_MAIL'], "no-reply"],
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

        $salt = Arr::get($_SERVER, 'SALT', self::DEFAULT_EMAIL_HASH_SALT);

        $hash = hash('sha256', $this->user->id . $salt . $this->user->email);

        $this->redis->hSet($this->HASHES_KEYS[$type], $hash, $this->user->id);

        return $hash;

    }

    /**
     * Gets user id from redis by confirmation hash
     *
     * @param $hash
     * @return string
     */
    public function getUserIdByHash($hash, $type) {

        $id = $this->redis->hGet($this->HASHES_KEYS[$type], $hash);

        return $id;

    }

    public function deleteHash($hash, $type) {
        $this->redis->hDel($this->HASHES_KEYS[$type], $hash);
    }

}
