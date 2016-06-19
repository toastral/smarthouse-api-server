<?php
class Request{
    const REQUEST_URI_ERROR = 1000;
    const REQUEST_PARSE_DEVICE_ID_ERROR = 1001;
    const REQUEST_PARSE_ADDRS_ERROR = 1003;
    const REQUEST_PARSE_ADDR_VALUES_ERROR = 1004;
    const REQUEST_PARSE_SESSION_ID_ERROR = 1005;
    const REQUEST_PARSE_AUTH_CODE_ERROR = 1006;

    public $path;

    public $auth_code;

    public $cmd ='';
    public $dev_id;
    public $ans_type    ='json';
    public $a_addr      = array();
    public $a_addr_vals = array();
    public $session_id = '';
    public $comment = '';

    public $log_addr;
    public $log_limit;
    public $log_offset;

    function __construct(){
        if(strlen(@$_GET['path']) <=0 || strlen(@$_GET['cmd']) <= 0) throw new MyException("REQUEST_URI Error", self::REQUEST_URI_ERROR);
        $this->cmd=$_GET['cmd'];

        preg_match("!^(.*?)[/]?$!is", $_GET['path'], $patt); // clear backslash in end uri
        $this->path = $patt[1];

        switch($this->cmd){
            case 'auth':
                $this->parseAuth();
                break;
            case 'put':
                $this->parsePut();
                break;
            case 'log':
                $this->parseLog();
                break;
            default: // get
                $this->parseGet();
                break;
        }
    }

    function parseAuth(){
        $this->parseDevId();
        $this->parseAuthCode();
    }

    function parseGet(){
        $this->parseDevId();
        $this->parseAnsType();
        $this->parseRegAddrs();
        $this->parseSessionId();

    }

    function parsePut(){
        $this->parseDevId();
        $this->parseAnsType();
        $this->parseRegAddrVals();
        $this->parseSessionId();
        $this->parseComment();
    }

    function parseAuthCode(){
        if(!preg_match("!/([^/]+)$!", $this->path, $patt)) throw new MyException("Not found auth code", self::REQUEST_PARSE_AUTH_CODE_ERROR);
        $this->auth_code = $patt[1];
    }

    function parseDevId(){
        if(!preg_match("!/(i-([\w]{4}))!", $this->path, $patt)) throw new MyException("Not found device id", self::REQUEST_PARSE_DEVICE_ID_ERROR);
        $this->dev_id = base_convert($patt[2], 16, 10);
    }
    function parseAnsType(){
        $this->type = 'text';
        if(preg_match("!/t-(json|text)!", $this->path, $patt)){
            $this->ans_type = $patt[1];
        }
    }
    function parseRegAddrs(){
        if(!preg_match_all("!/([\w]{4})!i", $this->path, $patt)) throw new MyException("Not found addr", self::REQUEST_PARSE_ADDRS_ERROR);
        while($reg = array_shift($patt[1])){
            $this->a_addr[] = base_convert($reg, 16, 10);
        }
    }
    function parseRegAddrVals(){
        if(!preg_match_all("!/([\w]{8})!i", $this->path, $patt)) throw new MyException("Not found addr and value", self::REQUEST_PARSE_ADDR_VALUES_ERROR);
        while($reg = array_shift($patt[1])){
            preg_match("!([\w]{4})([\w]{4})!", $reg, $p);
            $this->a_addr_vals[base_convert($p[1], 16, 10)] = base_convert($p[2], 16, 10);
        }
    }
    function parseSessionId(){
        if(!preg_match("!/s-([\w]+)!i", $this->path, $patt)) throw new MyException("Not found session id", self::REQUEST_PARSE_SESSION_ID_ERROR);
        $this->session_id = $patt[1];
    }
    function parseComment(){
        if(preg_match("!/c-([\w\s_]+)!i", $this->path, $patt)){
            $this->comment = $patt[1];
        }
    }
}
?>

