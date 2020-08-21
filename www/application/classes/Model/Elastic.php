<?php defined('SYSPATH') or die('No direct script access.');

use Elasticsearch\ClientBuilder;

class Model_Elastic extends Model
{
    private $client;
    public $index;

    public function __construct()
    {
        $elastic_config = Kohana::$config->load('elastic');

        $elastic_host = Arr::get($elastic_config, 'host', 'elasticsearch');
        $elastic_port = Arr::get($elastic_config, 'port', '9200');
        $this->index = Arr::get($elastic_config, 'index', 'codex-media');

        /**
         * Create & configure elastic instance
         */
        $this->client = ClientBuilder::create()->setHosts(
            [$elastic_host . ':' . $elastic_port]
        )->build();
    }

    /**
     * @param $type - entity type (table in elastic db)
     * @param $id - entity id
     * @param $entity - entity content to store
     */
    public function create($type, $id, $entity)
    {
        $this->client->index([
            'index' => $this->index,
            'type' => $type,
            'id' => $id,
            'body' => $entity,
        ]);
    }

    /**
     * @param $type - entity type (table in elastic db)
     * @param $id - entity id
     *
     * @return array - found entity with provided id
     */
    public function get($type, $id)
    {
        return $this->client->get([
            'index' => $this->index,
            'type' => $type,
            'id' => $id
        ]);
    }

    /**
     * @param $type - entity type (table in elastic db)
     * @param $size - maximum search results to return
     * @param $field - in what entity field to search
     * @param $word - occurrence of what word to search
     *
     * @return array - search result
     */
    public function searchByField($type, $size, $field, $word)
    {
        return $this->client->search(
            [
                'index' => $this->index,
                'size' => $size,
                'type' => $type,
                'body'  => [
                    'query' => [
                        'match' => [
                            $field => '*' . $word . '*'
                        ]
                    ]
                ]
            ]
        );
    }

    /**
     * @param $type - entity type (table in elastic db)
     * @param $id - entity id to delete
     */
    public function delete($type, $id)
    {
        $this->client->delete([
            'index' => $this->index,
            'type' => $type,
            'id' => $id,
        ]);
    }

    /**
     * @param $type - entity type (table in elastic db)
     * @param $id - entity id
     * @param $entity - entity content to update
     */
    public function update($type, $id, $entity)
    {
        $this->delete($type, $id);
        $this->create($type, $id, $entity);
    }

    /**
     * This deletes all entities of specified type
     *
     * @param $type - entity type (table in elastic db)
     */
    public function deleteAllOfType($type)
    {
        $this->client->deleteByQuery([
            'index' => $this->index,
            'type' => $type,
            'body' => [
                'query' => [
                    'match_all' => (object) []
                ]
            ]
        ]);
    }
}
