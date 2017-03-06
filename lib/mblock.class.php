<?php
class Mblock extends Mcach
{
    const BLOCK_ID = 'block_id';
    function __construct()
    {
        parent::__construct();
        $this->expiration = MEMCACHE_TIMEOUT_BLOCK_IP;
    }

    function setIp($ip)
    {
        $a_block = $this->get(self::BLOCK_ID);
        $a_block[$ip] = 1;
        $this->set(self::BLOCK_ID, $a_block);
    }
    function isIpBlack($ip)
    {
        $a_block = $this->get(self::BLOCK_ID);
        if(isset($a_block[$ip])) { return true;
        }
        return false;
    }

    function delIp($ip)
    {
        $a_block = $this->get(self::BLOCK_ID);
        unset($a_block[$ip]);
        $this->set(self::BLOCK_ID, $a_block);
    }
}
?>
