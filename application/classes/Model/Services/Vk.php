<?php

class Model_Services_Vk extends Model_preDispatch
{
    public static function postToSocialPublicPage($text)
    {
        $configFilename = 'social-public-pages-keys';
        $config = Kohana::$config->load($configFilename);
        if (!property_exists($config, 'vk')) {
            throw new Kohana_Exception("No $configFilename config file was found!");

            return;
        }
        $group_id = $config->vk['group_id'];
        $admin_key = $config->vk['admin_key'];
        if (!$group_id || !$admin_key) {
            throw new Kohana_Exception("Invalid configuration of $configFilename config file ");

            return;
        }

        $params = array(
            'message' => $text,
            'owner_id' => $group_id,
            'from_group' => 1,
            'access_token' => $admin_key
        );

        $url = "https://api.vk.com/method/wall.post";

        $response = Model_Methods::sendPostRequest($url, $params);

        return $response ? true : false;
    }
}
