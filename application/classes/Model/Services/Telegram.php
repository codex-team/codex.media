<?php

class Model_Services_Telegram extends Model_preDispatch
{
    public static function sendBotNotification($text)
    {
        $telegramConfigFilename = 'telegram-notification';
        $telegramConfig = Kohana::$config->load($telegramConfigFilename);
        if (!property_exists($telegramConfig, 'url')) {
            throw new Kohana_Exception("No $telegramConfigFilename config file was found!");

            return;
        }
        
        $url = $telegramConfig->url;
        if (!$url) {
            throw new Kohana_Exception("URL for telegram notifications was not found.");

            return;
        }

        $params = array(
            'message' => $text
        );

        $response = Model_Methods::sendPostRequest($url, $params);

        return $response ? true : false;
    }
}
