<?php

/**
 * Class Model_Email
 *
 * Singleton
 * Uses Send Grid Api
 */

use SendGrid\Content;
use SendGrid\Email;
use SendGrid\Mail;

class Model_Email
{
    private $apiKey = null;
    private $sender = null;

    private function __construct()
    {
        $config = Kohana::$config->load('email');

        $this->sender = new Email(Arr::get($config, 'senderName'), Arr::get($config, 'senderEmail'));
        $this->apiKey = Arr::get($config, 'apiKey');
    }

    private function __clone()
    {
    }

    protected static $_instance = null;

    public static function instance()
    {
        if (self::$_instance === null) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    /**
     * @param $to [Array] - must have 2 fields:
     *     - username
     *     - email
     * @param $subject [String] - Mail title
     * @param $content [Array]
     *      - format
     *      - message
     * @param mixed $receiver
     *
     * @return mixed
     */
    public function send($receiver, $subject, $content)
    {
        $receiver = new Email($receiver['name'], $receiver['email']);
        $content = new Content($content['format'], $content['message']);
        $mail = new Mail($this->sender, $subject, $receiver, $content);

        $sendGrid = new SendGrid($this->apiKey);
        $response = $sendGrid->client->mail()->send()->post($mail);

        return $response;
    }
}
