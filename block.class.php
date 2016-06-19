<?php
class Block{
    const IP_IS_BLOCKED = 4000;
    function __construct(){}
    function check(){
        $Mblock = new Mblock();
        $ip = $_SERVER['SERVER_ADDR'];
        if($Mblock->isIpBlack($ip)) throw new MyException("Ip is blocked", self::IP_IS_BLOCKED);
    }
}
?>