<?php defined('SYSPATH') or die('No direct script access.');

class Dao_Base {

    protected $table = 'null';

    const USER_INSERT  = 1;
    const USER_UPDATE  = 2;
    const USER_SELECT  = 3;

    private $action;

    protected $limit     = 1;
    protected $cached    = 0;
    protected $keycached = null;
    protected $where     = array();
    protected $fields    = array();

    public static function insert()
    {
        $self = new static();
        $self->action = self::USER_INSERT;
        return $self;
    }

    public static function update()
    {
        $self = new static();
        $self->action = self::USER_UPDATE;
        return $self;
    }

    public static function select()
    {
        $self = new static();
        $self->action = self::USER_SELECT;
        return $self;
    }

    public function set($field, $value)
    {
        $this->fields[$field] = $value;
        return $this;
    }

    public function where($field, $value)
    {
        $this->where[$field] = $value;
        return $this;
    }

    public function limit($limit)
    {
        $this->limit = $limit;
        return $this;
    }

    public function cached($seconds, $key = null)
    {
        $this->cached = $seconds;
        if ($key) $this->keycached = $this->getKeyCached($key);
        return $this;
    }

    public function clearcache($key)
    {
        $keycached = $this->getKeyCached($key);
        Kohana_Cache::instance('memcache')->delete($keycached);
        return $this;
    }

    public function execute()
    {
        $result = false;
        if ($this->action == self::USER_INSERT) {
            $result = self::insertExecute();
        } elseif ($this->action == self::USER_UPDATE) {
            $result = self::updateExecute();
        } elseif ($this->action == self::USER_SELECT) {
            $result = self::selectExecute();
        }
        return $result;
    }

    private function insertExecute()
    {
        $insert = DB::insert($this->table, array_keys($this->fields))->values(array_values($this->fields))->execute();
        if ($insert) return current($insert);
        return false;
    }

    private function updateExecute()
    {
        if (!$this->where) throw new Exception('Попытка обновить все записи в таблице!');
        $update = DB::update($this->table)->set($this->fields);
        foreach($this->where as $key => $value) $update->where($key, '=', $value);
        $update = $update->execute();
        if ($update)  return $update;
        return false;
    }

    private function selectExecute()
    {
        $select = DB::select()->from($this->table);
        foreach($this->where as $key => $value) $select->where($key, '=', $value);

        if ($this->cached) {
            $keycache = $this->getKeyCached();
            $cache = Kohana_Cache::instance('memcache')->get($keycache);
            if ($cache !== null) {
                return $cache;
            }
        }

        if ($this->limit == 1) {
            $select = $select->execute()->current();
        } else {
            $select = $select->execute()->as_array();
        }

        if ($this->cached) {
            Kohana_Cache::instance('memcache')->set($keycache, $select, $this->cached);
        }

        if ($select) return $select;
        return false;
    }

    private function getKeyCached($key = null)
    {
        if ($this->keycached) return $this->keycached;

        $keyprefix = 'dbcache:' . $this->table . ':';
        if (!$key) {
            ksort($this->where);
            $key = sha1(json_encode($this->where));
        }

        $this->keycached = $keyprefix . $key;

        return $this->keycached;
    }

} 