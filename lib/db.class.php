<?php
class DB{
    public $db;

    function __construct(){
        $this->db = DbSingleton::getInstance()->getConnection();
    }
    function qry($q){
        $res = $this->db->query($q);
        if(!$res){
            throw new MyException("Mysql query failure: (" . $this->db->errno . ") " . $this->db->error."\n query: ".$q);
        }
        return $res;
    }
}

class DbSingleton{
    private $_db;
    private static $_instance;

    public static function getInstance() {
        if(!self::$_instance) self::$_instance = new self();
        return self::$_instance;
    }

    private function __construct() {
        $this->_db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if(mysqli_connect_error()) {
            throw new MyException("Mysql connect failure: (" . $this->_db->connect_errno . ") " . $this->_db->connect_error);
        }
    }

    public function getConnection() {
        return $this->_db;
    }
}
?>