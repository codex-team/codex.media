<?php defined('SYSPATH') or die('No direct script access.');

use Elasticsearch\ClientBuilder;
class Elastic
{
    public static $instance;

    protected function __construct() {
        if (class_exists(static::class)) {
            return;
        }

        self::init();
    }

    private static function init() {
        $elastic_config = Kohana::$config->load('elastic');

        $elastic_host = Arr::get($elastic_config, 'host', 'elasticsearch');
        $elastic_port = Arr::get($elastic_config, 'port', '9200');

        self::$instance = ClientBuilder::create()->setHosts(
            [$elastic_host . ':' . $elastic_port]
        )->build();
    }

    public static function getInstance() {
        if (self::$instance != null) {
            return self::$instance;
        }

        self::init();

        return self::$instance;
    }

    public static function create($index, $type, $entity) {
        $client = self::getInstance();
        $client->index([
            'index' => $index,
            'type' => $type,
            'body' => $entity,
        ]);
    }

    public static function get($index, $id) {
        $client = self::getInstance();
        return $client->get([
            'index' => $index,
            'id'    => $id
        ]);
    }

    public static function delete($index, $id) {
        $client = self::getInstance();
        $client->delete([
            'index' => $index,
            'id' => $id,
        ]);
    }

    public static function update($index, $id, $entity) {
        $client = self::getInstance();
        $client->update([
            'index' => $index,
            'id'    => $id,
            'body'  => [
                'doc' => $entity
            ]
        ]);
    }

    public static function deleteAllOfType($index, $type) {
        $client = self::getInstance();
        $client->deleteByQuery([
            'index' => $index,
            'type' => $type,
            'body' => [
                'query' => [
                    'match_all' => (object)[]
                ]
            ]
        ]);
    }
}
