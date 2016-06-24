<?php
class Mcurdata extends Mcach{
    function __construct(){
        parent::__construct();
        $this->expiration = MEMCACHE_TIMEOUT_ADDR;
    }
    function setData($dev_id, $a_addr_vals, $comment){
        $a_addr_vals_mcach = $this->get($dev_id);
        foreach($a_addr_vals as $addr => $val){
            $a_addr_vals_mcach[$addr] = array($val, $comment);
        }
        $this->set($dev_id, $a_addr_vals_mcach);
    }

    function setRow($dev_id, $addr, $val, $comment){
        $a_addr_vals_mcach = $this->get($dev_id);
        $a_addr_vals_mcach[$addr] = array($val, $comment);
        $this->set($dev_id, $a_addr_vals_mcach);
    }

    function getData($dev_id){
        $a = $this->get($dev_id);
        if(!is_array($a))  $a = array();
        return $a;
    }


}
?>