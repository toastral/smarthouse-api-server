<?php
class Msess extends Mcach
{
    const SESS_ID = 'sess_id';
    function __construct()
    {
        parent::__construct();
        $this->expiration = MEMCACHE_TIMEOUT_SESSION;
    }
    function setSession($dev_id, $session_id)
    {
        $a_sess = $this->get(self::SESS_ID);
        $a_sess[$dev_id] = $session_id;
        $this->set(self::SESS_ID, $a_sess);
    }
    function getSession($dev_id)
    {
        $a_sess = $this->get(self::SESS_ID);
        if(isset($a_sess[$dev_id])) { return $a_sess[$dev_id];
        }
        return '';
    }
    function delSession($dev_id)
    {
        $a_sess = $this->get(self::SESS_ID);
        unset($a_sess[$dev_id]);
        $this->set(self::SESS_ID, $a_sess);
    }
}
?>

