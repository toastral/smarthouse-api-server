<?php
class App
{
    private $db;
    private $Request;
    public  $a_res = array('st' => 1);

    const ACCESS_DENIED = 3000;

    function __construct(Request $Request)
    {
        $this->db = new DB();
        $this->Request = $Request;
    }
    function processAuth()
    {
        $Secretword = new Secretword();
        $a_hash = $Secretword->getWordByDevId($this->Request->dev_id);

        $ip = $_SERVER['REMOTE_ADDR'];
        $Maccess = new Maccess();
        if(in_array($this->Request->auth_code, $a_hash)) {
            $Maccess->delIp($ip);

            $dev_id = $this->Request->dev_id;
            $session_id = Utills::generateSessionHash($dev_id);
            $this->a_res['session_id']=$session_id;

            $Msess = new Msess();
            $Msess->setSession($dev_id, $session_id);

            $Maccess->delIp($ip);
        }else{
            $Maccess->incrementAccessCountByIp($ip);
            $access_count = $Maccess->getAccessCountByIp($ip);
            if($access_count > NUM_OF_AUTH_ATTEMPS_BEFORE_BLOCK) {
                $Maccess->delIp($ip);
                $Mblock = new Mblock();
                $Mblock->setIp($ip);
            }
            throw new MyException("ACCESS DENIED", self::ACCESS_DENIED);
        }
    }

    function processPut()
    {
        $dev_id         = $this->Request->dev_id;
        $a_addr_vals    = $this->Request->a_addr_vals;
        $a_addr         = array_keys($a_addr_vals);

        $q ='SELECT id, addr, value FROM current_data WHERE addr IN('.implode(',', $a_addr).') AND device_id='.$dev_id;
        $res = $this->db->qry($q);
        $a_data_in_db = array();
        $a_addr_for_update = array();

        if($res->num_rows) {
            while($row = $res->fetch_assoc()){
                $a_data_in_db[$row['addr']]=$row;
                $a_addr_for_update[] = $row['addr'];
            }
        }

        $a_addr_for_insert = array_diff($a_addr, $a_addr_for_update);

        if(count($a_addr_for_insert)) {
            $q = "INSERT INTO current_data (device_id, addr, value, comment, created) VALUES ";
            $a_q = array();
            foreach($a_addr_for_insert as $addr){
                $value = $a_addr_vals[$addr];
                $a_q[] = sprintf("( %d, %d, %d, '%s',%d)", $dev_id, $addr, $value, $this->Request->comment, time());
            }
            $q = $q.implode(',', $a_q);
            $this->db->qry($q);
        }

        if(count($a_addr_for_update)) {
            $q = " UPDATE current_data SET device_id=%d, addr=%d, value=%d, comment='%s', created=%d WHERE id=%d; ";
            foreach($a_addr_for_update as $addr){
                $qq = sprintf($q, $dev_id, $addr, $a_addr_vals[$addr], $this->Request->comment, time(), $a_data_in_db[$addr]['id']);
                $this->db->qry($qq);
            }
        }

        // добавление в лог
        $q = "INSERT INTO log (device_id, addr, value, comment, created) VALUES ";
        $a_q = array();
        foreach($a_addr as $addr){
            $a_q[] = sprintf("( %d, %d, %d, '%s', %d)", $dev_id, $addr, $a_addr_vals[$addr], $this->Request->comment, time());
        }
        $q = $q.implode(',', $a_q);
        $this->db->qry($q);

        // "подогрев" кэша
        $M = new Mcurdata();
        //$M->setData($dev_id, $a_addr_vals, $this->Request->comment);
    }

    function processGet()
    {
        $dev_id = $this->Request->dev_id;
        // выборка из кэша -> $a_res
        $M = new Mcurdata();
        // получим все регистры из кэша
        $a_data_cach = $M->getData($dev_id);
        $a_addr_cach = array_keys($a_data_cach);

        // получим регистры, которых нет в кэше
        $a_addr = array_diff($this->Request->a_addr, $a_addr_cach);
        if(count($a_addr)) {
            $q = 'SELECT addr, value, comment FROM current_data WHERE addr IN('.implode(',', $a_addr).') AND device_id='.$dev_id;
            $res = $this->db->qry($q);
            while($row = $res->fetch_assoc()){
                $a_data_cach[$row['addr']] = array($row['value'], $row['comment']);
                // "подогрев" кэша только что выбранными значениями из БД
                //                $M->setRow($dev_id, $row['addr'], $row['value'], $row['comment']);
            }
        }
        // формируем ответ
        foreach($this->Request->a_addr as $addr){
            if(!isset($a_data_cach[$addr])) { continue;
            }
            list($val, $comment) = $a_data_cach[$addr];
            $this->a_res[base_convert($addr, 10, 16)]=array( base_convert($val, 10, 16), $comment );
        }
    }

    function processLog()
    {
        $dev_id = $this->Request->dev_id;
        $addr = $this->Request->log_addr;

        $q = 'SELECT addr, value, comment, created FROM log WHERE device_id='.$dev_id.' AND addr='.$addr." ORDER BY id DESC";
        $q.=' LIMIT '.$this->Request->log_limit;
        if($this->Request->log_offset) { $q.=' OFFSET '.$this->Request->log_offset;
        }
        $res = $this->db->qry($q);

        $this->a_res['addr'] = base_convert($addr, 10, 16);
        $this->a_res['device_id'] = base_convert($dev_id, 10, 16);

        $a_log = array();
        while($row = $res->fetch_assoc()){
            $val = base_convert($row['value'], 10, 16);
            $a_log[$row['created']]=$val.';'.$row['comment'];
        }
        $this->a_res['log'] = $a_log;
    }

}
?>