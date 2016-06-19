<?php
class Session{
    private $Request;
    const NOT_VALID_SESSION_ID = 2000;

    function __construct(Request $Request){
        $this->Request = $Request;
    }

    function check(){
        $dev_id = $this->Request->dev_id;

        $ip = $_SERVER['SERVER_ADDR'];
        $Maccess = new Maccess();

        $M = new Msess();
        $mcach_session_id = $M->getSession($dev_id);
        if( strlen($mcach_session_id)<=0
            ||
            $mcach_session_id != $this->Request->session_id
            ){
                $Maccess->incrementAccessCountByIp($ip);
                $access_count = $Maccess->getAccessCountByIp($ip);
                if($access_count > NUM_OF_AUTH_ATTEMPS_BEFORE_BLOCK){
                    $Maccess->delIp($ip);
                    $Mblock = new Mblock();
                    $Mblock->setIp($ip);
                }
                throw new MyException("Not valid session id", self::NOT_VALID_SESSION_ID);
        }else{
            $Maccess->delIp($ip);
        }

    }
}
?>