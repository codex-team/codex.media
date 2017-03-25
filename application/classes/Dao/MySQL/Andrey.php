<?php defined('SYSPATH') or die('No direct script access.');

class Dao_MySQL_Andrey extends Dao_MySQL_Base {

    public function __construct()
    {
        $config_name = 'database';

        $config = Kohana::$config->load($config_name);

        try {

            $this->db_name = $config->default['connection']['database'];

        } catch (Exception $e) {

            $message = "Config file $config_name was not found";

            throw new Exception($message, 0);

        };

    }

}
