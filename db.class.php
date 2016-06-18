<?php
/**
$db = DbSingleton::getInstance()->getConnection();
 */
class Db{
    private $db;
    private static $_instance;

    public static function getInstance() {
        if(!self::$_instance) self::$_instance = new self();
        return self::$_instance;
    }
    public function __construct($db_host, $db_user, $db_pass, $db_name) {
        $this->db = new mysqli($db_host, $db_user, $db_pass, $db_name);
        if(mysqli_connect_error()) {
            throw new Exception("Mysql connect failure: (" . $this->db->errno . ") " . $this->db->error);
        }
    }
    public function getConnection() {
        return $this->db;
    }
    function query($q){
        $res = $this->db->query($q);
        if(!$res){
            throw new Exception("Mysql query failure: (" . $this->db->errno . ") " . $this->db->error);
        }
        return $res;
    }
}