<?php

class Model_Services_Telegram extends Model_preDispatch
{
    public static function sendBotNotification($text)
    {
        /** Get config for telegram bot notifications */
        $telegramConfigFilename = 'telegram-notification';
        $telegramConfig = Kohana::$config->load($telegramConfigFilename);

        /** Error if there is no config file */
        if (!property_exists($telegramConfig, 'url')) {

            throw new Kohana_Exception("No $telegramConfigFilename config file was found!");

            return;
        }

        $url = $telegramConfig->url;

        /** Error if no URL in the config file */
        if (!$url) {

            throw new Kohana_Exception("URL for telegram notifications was not found.");

            return;
        }

        /** Set up params for request */
        $params = array(
            'message' => $text
        );

        /** Get response */
        $response = Model_Methods::sendPostRequest($url, $params);

        return $response ? true : false;
    }
}
