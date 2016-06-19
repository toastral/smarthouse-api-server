<?php
class Maccess extends Mcach{
    const ACCESS_ID = 'access_id';
    function __construct(){
        parent::__construct();
        $this->expiration = MEMCACHE_TIMEOUT_ACCESS_IP;
    }
    function incrementAccessCountByIp($ip){
        $a_access = $this->get(self::ACCESS_ID);
        if(!isset($a_access[$ip])) $a_access[$ip]=0;
        $a_access[$ip]++;
        $this->set(self::ACCESS_ID, $a_access);
    }

    function getAccessCountByIp($ip){
        $a_access = $this->get(self::ACCESS_ID);
        if(!isset($a_access[$ip])) return 0;
        return $a_access[$ip];
    }
    function delIp($ip){
        $a_access = $this->get(self::ACCESS_ID);
        unset($a_access[$ip]);
        $this->set(self::ACCESS_ID, $a_access);
    }
}
?>