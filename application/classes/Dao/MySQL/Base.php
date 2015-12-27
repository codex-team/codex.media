<?php defined('SYSPATH') or die('No direct script access.');

class Dao_MySQL_Base {

    /**
    * @todo Workaround memcache keys confilct with multiple sites on single server. Ex: key 'Dao_Users:1' may be sets on different sites.
    */

    protected $table = 'null';

    const INSERT  = 1;
    const UPDATE  = 2;
    const SELECT  = 3;
    const DELETE  = 4;

    protected $cache_key = 'Dao_MySQL_Base';

    private $action;

    private $memcache;

    protected $limit     = 0;
    protected $offset    = 0;
    protected $lifetime  = 0;
    protected $keycached = null;
    protected $tagcached = null;
    protected $where     = array();
    protected $where_in  = array();
    protected $order_by  = null;
    protected $fields    = array();
    protected $join      = array();
    protected $last_join = null;
    protected $select_fields = array();

    public static function insert()
    {
        $self = new static();
        $self->action = self::INSERT;
        return $self;
    }

    public static function update()
    {
        $self = new static();
        $self->action = self::UPDATE;
        return $self;
    }

    public static function select($select_fields = array())
    {
        $self = new static();
        $self->action = self::SELECT;
        $self->select_fields = $select_fields;
        return $self;
    }

    public static function delete()
    {
        $self = new static();
        $self->action = self::DELETE;
        return $self;
    }

    public function set($field, $value)
    {
        $this->fields[$field] = $value;
        return $this;
    }

    public function where($field, $operand, $value)
    {
        if (!$this->last_join) {
            $this->where[$field] = array($operand => $value);
        } else {
            $this->join[$this->last_join]['where'][] = array($field, $operand, $value);
        }
        return $this;
    }

    public function where_in($field, $value)
    {
        $this->where_in[$field] = $value;
        return $this;
    }

    public function join($table)
    {
        $this->last_join = $table;
        return $this;
    }

    public function on($column1, $operator, $column2)
    {
        $this->join[$this->last_join]['on'] = array($column1, $operator, $column2);
        return $this;
    }

    public function order_by($field, $value)
    {
        $this->order_by[$field] = $value;
        return $this;
    }

    /**
    * If limit = 1 , result will be present with current() cursor,
    * otherwise uses as_array()
    */
    public function limit($limit)
    {
        $this->limit = $limit;
        return $this;
    }

    public function offset($offset)
    {
        $this->offset = $offset;
        return $this;
    }

    /**
    * Add cache to result
    * If no $key and no $tags passed , it caches with default Kohana Database_Result_Cached object
    * @param int $seconds - key lifetime
    * @param string $key - if you want to cache with Memcache
    * @param array $tags - array of Tags for multiple cached keys. Uses modules/cache/classes/Cache/Memcacheimp.php Class
    */
    public function cached($seconds, $key = null, array $tags = null)
    {
        $this->lifetime = $seconds;
        if ($key) $this->keycached = $this->cache_key .':'. $key;
        if ($tags) $this->tagcached = $tags;
        return $this;
    }

    public function clearcache($key = null, array $tags = null)
    {
        /** @var Cache_Memcacheimp $memcache */
        $memcache = $this->getMemcacheInstance();

        if ($key) {
            $full_key = $this->cache_key .':'. $key;
            $memcache->delete(mb_strtolower($full_key));
        }

        if ($tags) foreach ($tags as $tag) $memcache->delete_tag($tag);

        return $this;
    }
    
    private function getCacheKey()
    {
        return 't:' . $this->table.
        ':f:' . json_encode($this->select_fields) .
        ':w:' . json_encode($this->where) .
        ':j:' . json_encode($this->join) .
        ':l:' . $this->limit .
        ':o:' . $this->offset .
        ':s:' . json_encode($this->order_by);
    }

    public function execute($selectKey = null)
    {
        $result = false;
        if ($this->action == self::INSERT) {
            $result = self::insertExecute();
        } elseif ($this->action == self::UPDATE) {
            $result = self::updateExecute();
        } elseif ($this->action == self::SELECT) {
            $result = self::selectExecute($selectKey);
        } elseif ($this->action == self::DELETE) {
            $result = self::deleteExecute();
        }
        return $result;
    }

    private function insertExecute()
    {
        $insert = DB::insert($this->table, array_keys($this->fields))->values(array_values($this->fields))->execute();
        if ($insert) return current($insert);
        return false;
    }

    private function deleteExecute()
    {
        $delete = DB::delete($this->table);
        foreach($this->where as $key => $value) $delete->where($key, key($value), current($value));
        return $delete->execute();
    }

    private function updateExecute()
    {
        if (!$this->where) throw new Exception('Попытка обновить все записи в таблице!');
        $update = DB::update($this->table)->set($this->fields);
        foreach($this->where as $key => $value) $update->where($key, key($value), current($value));
        $update = $update->execute();
        if ($update)  return $update;
        return false;
    }

    private function selectExecute($selectKey)
    {
        /** @var Cache_Memcacheimp $memcache */
        $memcache = $this->getMemcacheInstance();

        /** Проверяем есть ли кэш в мемкэше */
        if ($this->lifetime) {
            if ($this->keycached || $this->tagcached) {

                if (!$this->keycached) $this->keycached = sha1($this->getCacheKey());

                try {
                    $cache = $memcache->get(mb_strtolower($this->keycached));
                } catch (Exception $e) {
                    $cache = null;
                }

                if ($cache !== null) {
                    return $cache;
                }
            }
        }

        /** Собираем конструктор MySQL */
        $select = DB::select_array((array)$this->select_fields)->from($this->table);

        foreach($this->where as $key => $value) $select->where($key, key($value), current($value));
        foreach($this->where_in as $key => $value) $select->where($key, 'IN', $value);

        if ($this->limit) $select->limit($this->limit);
        if ($this->offset) $select->offset($this->offset);

        if ($this->order_by) {
            foreach ($this->order_by as $field => $sort) {
                $select->order_by($field, $sort);
            }
        }

        if ($this->join) {
            foreach ($this->join as $table => $params) {
                $select->join($table)->on($params['on'][0], $params['on'][1], $params['on'][2]);
                if (!empty($params['where'])) {
                    foreach ($params['where'] as $where_params) {
                        $select->where($where_params[0], $where_params[1], $where_params[2]);
                    }
                }
            }
        }

        /** Проверяет нужно ли кэшировать кохановским способом */
        if ($this->lifetime && (!$this->keycached && !$this->tagcached)) {
            $select->cached($this->lifetime);
        }

        $select = $select->execute();

        if ( $this->limit === 1 ) {
            $select = $select->current();
        } else {
            $select = $select->as_array($selectKey);
        }

        if ($this->lifetime && $this->keycached) {
            $memcache->set(mb_strtolower($this->keycached), ($select ? $select : array()), $this->tagcached, $this->lifetime);
        }

        if ($select) return (array)$select;
        return false;
    }

    private function getMemcacheInstance()
    {
        if (!$this->memcache) $this->memcache = Cache::instance('memcacheimp');
        return $this->memcache;
    }

}
