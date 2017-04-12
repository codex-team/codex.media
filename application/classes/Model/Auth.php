<?php

class Model_Auth extends Model_preDispatch
{

    const TYPE_EMAIL_CONFIRM = 'confirm';
    const TYPE_EMAIL_RESET   = 'reset';
    const TYPE_EMAIL_CHANGE  = 'change';

    const EMAIL_SUBJECTS = array(
        self::TYPE_EMAIL_CONFIRM => 'Добро пожаловать на ',
        self::TYPE_EMAIL_RESET   => 'Сброс пароля на ',
        self::TYPE_EMAIL_CHANGE  => 'Смена пароля на ',
    );

    /**
     * Salt should be in .env file, but if it doesn't, we use this fallback salt
     */
    const DEFAULT_EMAIL_HASH_SALT = 'OKexL2iOXbhoJFw1Flb8';

    public $user;

    /**
     * Model_Auth constructor.
     * @param $user [Array] - necessary user fields: id, name, email
     */
    public function __construct($user = array())
    {
        $this->user = $user;
        parent::__construct();
    }

    /**
     * Adds pair hash => id to redis and sends email with link
     *
     * @param string $type - email type
     * @return integer - number of emails sent
     */
    public function sendEmail($type) {

        $hash = $this->addHash($type);

        $message = View::factory('templates/emails/auth/' . $type , array('user' => $this->user, 'hash' => $hash));

        return Model_Email::instance()->send(
            array(
                'name'  => $this->user['name'],
                'email' => $this->user['email']
            ),
            self::EMAIL_SUBJECTS[$type] . $_SERVER['HTTP_HOST'],
            array(
                'format'  => 'text/plain',
                'message' => $message
            )
        );
    }

    /**
     * Generates hash and adds it to redis
     *
     * @param $type - hash type
     * @return string $hash
     */
    private function addHash($type) {

        $key_prefix = Arr::get($_SERVER, 'REDIS_PREFIX', 'codex.org:') . 'hashes:';
        $hash = $this->makeHashByUserData($this->user['id'], $this->user['email']);

        $key = $key_prefix . $type . $hash;

        $this->redis->setex($key, Date::DAY,  $this->user['id']);

        return $hash;

    }

    /**
     * Gets user id from redis by hash
     *
     * @param $hash
     * @param $type - hash type
     * @return integer - user id
     */
    public function getUserIdByHash($hash, $type) {

        $key_prefix = Arr::get($_SERVER, 'REDIS_PREFIX', 'codex.org:') . 'hashes:';
        $key        = $key_prefix . $type . $hash;

        $id = $this->redis->get($key);

        return $id;
    }

    /**
     * Removes pair hash => id from redis
     *
     * @param $hash
     * @param $type - hash type
     */
    public function deleteHash($hash, $type) {

        $key_prefix = Arr::get($_SERVER, 'REDIS_PREFIX', 'codex.org:') . 'hashes:';
        $key        = $key_prefix . $type . $hash;

        $this->redis->del($key);

    }

    /**
     * Makes hash using user id and email
     *
     * @param $id
     * @param $email
     * @return string $hash
     */
    public function makeHashByUserData($id, $email) {

        $salt = Arr::get($_SERVER, 'EMAIL_HASH_SALT', self::DEFAULT_EMAIL_HASH_SALT);

        return hash('sha256', $id . $salt . $email);

    }

    /**
     * Checks if pair hash => id in redis. Uses data in $this->user
     *
     * @param $type - hash type
     * @return bool
     */
    public function checkIfEmailWasSent($type) {

        $hash = $this->makeHashByUserData($this->user['id'], $this->user['email']);

        return (bool) $this->getUserIdByHash($hash, $type);

    }

}
