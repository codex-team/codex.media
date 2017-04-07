<?php

/**
 * Class Model_Email
 *
 * Singleton
 * Uses Send Grid Api
 */

use SendGrid\Email;
use SendGrid\Mail;
use SendGrid\Content;

class Model_Email {

    private $apiKey  = null;
    private $sender  = null;

    private function __construct() {}

    private function __clone() {}

    protected static $_instance = null;


    public static function instance() {

        if (self::$_instance === null) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    public function configure($sender, $apiKey) {

        $this->apiKey  = $apiKey;
        $this->sender = new Email($sender['title'], $sender['email']);

    }

    /**
     * @param $to [Array] - must have 2 fields:
     *     - username
     *     - email
     *
     * @param $subject [String] - Mail title
     * @param $content [Array]
     *      - format
     *      - message
     *
     * @return mixed
     */
    public function send($receiver, $subject, $content) {

        $receiver = new Email($receiver['name'], $receiver['email']);
        $content  = new Content($content['format'], $content['message']);
        $mail     = new Mail($this->sender, $subject, $receiver, $content);

        $sendGrid = new SendGrid($this->apiKey);
        $response = $sendGrid->client->mail()->send()->post($mail);

        return $response;
    }
}