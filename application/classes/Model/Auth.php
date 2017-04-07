<?php

class Model_Auth extends Model_preDispatch
{

    const TYPE_EMAIL_CONFIRM = 'confirm';
    const TYPE_EMAIL_RESET   = 'reset';
    const TYPE_EMAIL_CHANGE  = 'change';

    const EMAIL_SUBJECTS = array(
        self::TYPE_EMAIL_CONFIRM => 'Добро пожаловать на ',
        self::TYPE_EMAIL_RESET => 'Сброс пароля на ',
        self::TYPE_EMAIL_CHANGE => 'Смена пароля на ',
    );

    /**
     * Salt should be in .env file, but if it doesn't, we use this fallback salt
     */
    const DEFAULT_EMAIL_HASH_SALT = 'OKexL2iOXbhoJFw1Flb8';

    public $user;

    public function __construct($user = null)
    {

        if ($user && $user->id) {
            $this->user = $user;
        }

        parent::__construct();

    }


    /**
     * Adds pair hash => id to redis and sends email with link
     *
     * @param $type - email type
     */
    public function sendEmail($type) {

        $hash = $this->addHash($type);

        $message = View::factory('templates/emails/auth/' . $type, array('user' => $this->user, 'hash' => $hash));

        $email = new Email();
        return $email->send(
            [$this->user->email],
            [$GLOBALS['SITE_MAIL'], $_SERVER['HTTP_HOST']],
            self::EMAIL_SUBJECTS[$type] . $_SERVER['HTTP_HOST'],
            $message,
            false
        );

    }

    /**
     * Generates hash and adds it to redis
     *
     * @param $type
     * @return string
     */
    private function addHash($type) {

        $key_prefix = Arr::get($_SERVER, 'REDIS_PREFIX', 'codex.org:') . 'hashes:';
        $hash = self::getHashByUser($this->user);

        $key = $key_prefix . $type . $hash;

        $this->redis->setex($key, Date::DAY,  $this->user->id);

        return $hash;

    }

    /**
     * Gets user id from redis by hash
     *
     * @param $hash
     * @return string
     */
    public function getUserIdByHash($hash, $type) {

        $key_prefix = Arr::get($_SERVER, 'REDIS_PREFIX', 'codex.org:') . 'hashes:';
        $key        = $key_prefix . $type . $hash;

        $id = $this->redis->get($key);

        return $id;

    }

    public function deleteHash($hash, $type) {

        $key_prefix = Arr::get($_SERVER, 'REDIS_PREFIX', 'codex.org:') . 'hashes:';
        $key        = $key_prefix . $type . $hash;

        $this->redis->del($key);

    }

    public function getHashByUser($user) {

        $salt = Arr::get($_SERVER, 'EMAIL_HASH_SALT', self::DEFAULT_EMAIL_HASH_SALT);

        return hash('sha256', $user->id . $salt . $user->email);

    }

    public function checkIfEmailWasSent($type) {

        $hash = $this->getHashByUser($this->user);

        return (bool) $this->getUserIdByHash($hash, $type);

    }

}
