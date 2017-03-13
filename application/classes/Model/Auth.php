<?php

class Model_Auth extends Model_preDispatch
{

    const CONFIRMATION_HASHES_KEY = 'codex.org.confirmation.hashes';

    /**
     * Adds pair hash => id to redis and sends email with confirmation link
     *
     * @param $user
     */
    public function sendConfirmationEmail($user) {

        $hash = $this->generateConfirmationHash($user);

        $message = View::factory('templates/emails/confirm', array('user' => $user, 'hash' => $hash));

        $email = new Email();
        return $email->send($user->email, $GLOBALS['SITE_MAIL'], "Добро пожаловать на ".$_SERVER['HTTP_HOST']."!", $message, true);

    }

    /**
     * Generates confirmation hash and adds it to redis
     *
     * @param $user
     * @return string
     */
    private function generateConfirmationHash($user) {

        $salt = Arr::get($_SERVER, 'SALT', 'thisIsSalt');

        $hash = hash('sha256', $user->id . $salt . $user->email);

        $this->redis->hset(self::CONFIRMATION_HASHES_KEY, $hash, $user->id);

        return $hash;

    }

    /**
     * Gets user id from redis by confirmation hash
     *
     * @param $hash
     * @return string
     */
    public function getUserIdByConfirmationHash($hash) {

        $id = $this->redis->hGet(self::CONFIRMATION_HASHES_KEY, $hash);

        $this->redis->hDel(self::CONFIRMATION_HASHES_KEY, $hash);

        return $id;

    }

}