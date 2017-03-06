<?php
class Maddr extends Mcach
{
    function __construct()
    {
        parent::__construct();
        $this->expiration = MEMCACHE_TIMEOUT_ADDR;
    }
    function setRegs($dev_id, $a_addr_vals)
    {
        $a_addr_vals_mcach = $this->get($dev_id);
        foreach($a_addr_vals as $addr => $val){
            $a_addr_vals_mcach[$addr] = $val;
        }
        $this->set($dev_id, $a_addr_vals_mcach);
    }

    function getAddr($dev_id)
    {
        $a = $this->get($dev_id);
        if(!is_array($a)) {  $a = array();
        }
        return $a;
    }
}
?>

