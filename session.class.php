<?php
class Session{
    private $Request;
    const NOT_VALID_SESSION_ID = 2000;

    function __construct(Request $Request){
        $this->Request = $Request;
    }

    function check(){
        $dev_id = $this->Request->dev_id;
var_dump($dev_id);
        $M = new Msess();
        $mcach_session_id = $M->getSession($dev_id);
var_dump($mcach_session_id);
        if( strlen($mcach_session_id)<=0
            ||
            $mcach_session_id != $this->Request->session_id
            )
            throw new MyException("Not valid session id", self::NOT_VALID_SESSION_ID);
    }
}
?>