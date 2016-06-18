<?php
class Block{
    private $db;
    private $Request;

    function __construct(Request $Request){
        $this->db = Db::getInstance()->getConnection();
        $this->Request = $Request;
    }

    function check(){
        // получить строку где ip=$_SERVER['REMOTE_ADDR'] и count>3 из таблицы block
        // если такой записи нет, ничего не делаем
        // если записть есть ACCESS DENIED
    }
}
?>