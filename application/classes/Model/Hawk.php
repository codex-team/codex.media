<?php defined('SYSPATH') or die('No direct script access.');


/**
 * Hawk PHP Errors Sender
 *
 * Put project token to $_SERVER['HAWK_TOKEN'].
 *
 * Simple usage:
 * @example Model_Hawk::Log($exception);
 */
class Model_Hawk extends Model
{
    const catcherUrl = 'https://hawk.so/catcher/php';

    /**
     * Public function for logging server error.
     *
     * @example Model_Hawk::Log($e);
     *
     * @param $exception      Error you want to save
     * @return                Hawk server response
     *                        or FALSE if $_SERVER['HAWK_TOKEN'] does not exist
     */
    public static function Log($exception) {

        $token = Arr::get($_SERVER, 'HAWK_TOKEN', FALSE);

        /**
         * If no config file was found then ignore sending
         */
        if ($token === FALSE) return FALSE;


        $data = array(
            "error_type" => $exception->getCode() ? : E_ERROR,
            "error_description" => $exception->getMessage(),
            "error_file" => $exception->getFile(),
            "error_line" => $exception->getLine(),
            "error_context" => array(),
            "debug_backtrace" => debug_backtrace(),
            'http_params' => $_SERVER,
            "access_token" => $token,
            "GET" => $_GET,
            "POST" => $_POST
        );

        return self::send($data);
    }

    /**
     * Send package with error data to Hawk service.
     * Private param $_url should be defined.
     *
     * @param $data     Array with error info
     * @return          Hawk server response
     */
    private static function send($data) {

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, self::catcherUrl);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $server_output = curl_exec($ch);
        curl_close($ch);

        return $server_output;
    }
}
