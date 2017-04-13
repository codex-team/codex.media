<?php defined('SYSPATH') or die('No direct script access.');

class Kohana_Exception extends Kohana_Kohana_Exception {

    public static function _handler($e)
    {
        if (Kohana::$environment === Kohana::PRODUCTION) {

            self::$error_view = 'templates/errors/500';
            self::formatErrorForTelegrams($e);

        }

        return parent::_handler($e);
    }

    /**
     * Compose error trace for Telegram
     * @param Exception $e - kohana exception object
     */
    private static function formatErrorForTelegrams( $e )
    {
        $protocol = HTTP::$protocol == 'HTTP' ? 'http://' : 'https://';
        if (!empty(Request::current())){
            $path = $protocol . Arr::get($_SERVER, 'SERVER_NAME') . Request::current()->url();
        } else {
            $path = '';
        }
        $telegramMsg = '⚠️ ' . $e->getMessage() . '';
        $telegramMsg .= PHP_EOL .  PHP_EOL . $e->getFile() . ': ' . $e->getLine() .  PHP_EOL . PHP_EOL;
        $telegramMsg .= $path;
        Model_Methods::sendBotNotification($telegramMsg);

    }
}