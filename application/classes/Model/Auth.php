<?php

class Model_Auth extends Model_preDispatch
{

    private $HASHES_KEYS = array(
        'confirmation' => 'codex.org.confirmation.hashes',
        'reset'        => 'codex.org.reset.hashes'
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

        $hash = $this->generateHash('confirmation');

        $message = View::factory('templates/emails/confirm', array('user' => $this->user, 'hash' => $hash));

        $email = new Email();
        return $email->send($this->user->email, $GLOBALS['SITE_MAIL'], "Добро пожаловать на ".$_SERVER['HTTP_HOST']."!", $message, true);

    }

    public function sendResetPasswordEmail() {

        $hash = $this->generateHash('reset');

        $message = View::factory('templates/emails/reset', array('user' => $this->user, 'hash' => $hash));

        $email = new Email();
        return $email->send($this->user->email, $GLOBALS['SITE_MAIL'], "Сброс пароля на ".$_SERVER['HTTP_HOST']."!", $message, true);


    }

    /**
     * Generates confirmation hash and adds it to redis
     *
     * @param $user
     * @return string
     */
    private function generateHash($type) {

        $salt = Arr::get($_SERVER, 'SALT', 'thisIsSalt');

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