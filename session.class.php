<?php
class Session{
    private $db;
    private $Request;

    function __construct(Request $Request){
        $this->db = Db::getInstance()->getConnection();
        $this->Request = $Request;
    }

    function check(){


    }
}
?>