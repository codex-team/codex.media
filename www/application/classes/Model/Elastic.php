<?php defined('SYSPATH') or die('No direct script access.');

use Elasticsearch\ClientBuilder;
class Model_Elastic extends Model
{
    private $client;
    public $index;

    public function __construct() {
        $elastic_config = Kohana::$config->load('elastic');

        $elastic_host = Arr::get($elastic_config, 'host', 'elasticsearch');
        $elastic_port = Arr::get($elastic_config, 'port', '9200');
        $this->index = Arr::get($elastic_config, 'index', 'codex-media');

        $this->client = ClientBuilder::create()->setHosts(
            [$elastic_host . ':' . $elastic_port]
        )->build();
    }

    public function create($type, $id, $entity) {
        $this->client->index([
            'index' => $this->index,
            'type' => $type,
            'id' => $id,
            'body' => $entity,
        ]);
    }

    public function get($id) {
        return $this->client->get([
            'index' => $this->index,
            'id'    => $id
        ]);
    }

    public function delete($id) {
        $this->client->delete([
            'index' => $this->index,
            'id' => $id,
        ]);
    }

    public function update($type, $id, $entity) {
        $this->delete($id);
        $this->create($type, $id, $entity);
    }

    public function deleteAllOfType($type) {
        $this->client->deleteByQuery([
            'index' => $this->index,
            'type' => $type,
            'body' => [
                'query' => [
                    'match_all' => (object)[]
                ]
            ]
        ]);
    }
}
