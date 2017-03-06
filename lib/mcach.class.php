<?php
class Mcach
{
    public $expiration;
    public $m;

    function __construct()
    {
        $this->expiration = MEMCACHE_TIMEOUT;
        $this->m = new Memcached();
        $this->m->addServer('localhost', 11211);
    }

    function setMulti($items)
    {
        $this->m->setMulti($items, $this->expiration);
    }

    function getMulti($keys)
    {
        $null = null;
        return $this->m->getMulti($keys, $null, Memcached::GET_PRESERVE_ORDER);
    }

    function set($key, $val)
    {
        $this->m->set($key, $val, $this->expiration);
    }

    function get($key)
    {
        return $this->m->get($key);
    }

    function delete($key)
    {
        return $this->m->delete($key);
    }

}
?>