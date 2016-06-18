<?php
class Request{
    const REQUEST_URI_ERROR = 1000;
    const REQUEST_PARSE_DEVICE_ID_ERROR = 1001;
    const REQUEST_PARSE_TYPE_ERROR = 1002;
    const REQUEST_PARSE_REG_ADDRS_ERROR = 1003;
    const REQUEST_PARSE_REG_ADDR_VALUES_ERROR = 1004;
    const REQUEST_PARSE_SESSION_ID_ERROR = 1005;
    const REQUEST_PARSE_EXT_INFO_ERROR = 1006;
    public $path;

    public $device_id;
    public $type ='';
    public $a_reg_addr = array();
    public $a_reg_addr_values = array();
    public $session_id;
    public $ext_info;

    public $logaddr;
    public $loglimit;
    public $logoffset;

    function __construct(){
        if(strlen(@$_GET['path']) <=0 || strlen(@$_GET['cmd']) <= 0) throw new MyException("REQUEST_URI Error", self::REQUEST_URI_ERROR);
        $this->type=$_GET['cmd'];

        preg_match("!^(.*?)[/]?$!is", $_GET['path'], $patt); // clear backslash in end uri
        $this->path = $patt[1];

        switch($this->type){
            case 'auth':
                $this->parseAuth();
                break;
            case 'post':
                $this->parsePost();
                break;
            case 'getlog':
                $this->parsePost();
                break;
            default: // get
                $this->parseGet();
                break;
        }
    }
    function parseGet(){
        $this->parseDeviseId();

    }
    function parseDeviseId(){
        if(!preg_match("|/(i-([\w]{4}))|", $this->path, $patt)) throw new MyException("Not found device id", self::REQUEST_PARSE_DEVICE_ID_ERROR);
        $this->device_id = $patt[2];
    }
    function parseType(){
        if(!preg_match("|/(t-(json|text)|", $this->path, $patt)) throw new MyException("Not found device id", self::REQUEST_PARSE_TYPE_ERROR);
        $this->type = $patt[2];
    }
    function parseRegAddrs(){
        if(!preg_match_all("|/([\w]{4})|i", $this->path, $patt)) throw new MyException("Not found device id", self::REQUEST_PARSE_REG_ADDRS_ERROR);
        while($reg = array_shift($patt[1])){
            $this->a_reg_addr[] = $reg;
        }
    }
    function parseRegAddrValues(){
        if(!preg_match_all("|/([\w]{8}))|i", $this->path, $patt)) throw new MyException("Not found device id", self::REQUEST_PARSE_REG_ADDR_VALUES_ERROR);
        while($reg = array_shift($patt[1])){
            preg_match("|/(\w){4}(\w){4}|", $this->path, $p)
            $this->a_reg_addr_values[$p[1]] = $p[2];
        }
    }
    function parseSessionId(){
        if(!preg_match("|/(s-([\w]+))|i", $this->path, $patt)) throw new MyException("Not found device id", self::REQUEST_PARSE_SESSION_ID_ERROR);
        $this->session_id = $patt[2];
    }
    function parseExtInfo(){
        if(!preg_match("|/(e-([\w\s_]+))|i", $this->path, $patt)) throw new MyException("Not found device id", self::REQUEST_PARSE_EXT_INFO_ERROR);
        $this->ext_info = $patt[2];
    }
}
?>

