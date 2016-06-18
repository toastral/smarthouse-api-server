<?php
class App{
    private $db;
    private $Request;
    public  $a_res;

    function __construct(Request $Request){
        $this->db = Db::getInstance()->getConnection();
        $this->Request = $Request;
    }

    function processAuth(){
        $q="SELECT value FROM config WHERE name='SECRET'";
        $res = $this->db->query($q);
        $row = $res->fetch_assoc();
        if(sh1($row['SECRET']) == $this->Request->auth_hash){
           // удалить ip из таблицы block
           // удалить по device_id из таблицы session
           // сгенерировать session_id и добавить в таблицу session
           // $this->a_res[]=session_id
        }else{
            // проверить, есть ли ip в таблице block - если есть увеличить ему счетчик
            // сгенерировать ошибку - access denied
        }
    }
    function processPut(){
        $a_addr_for_put = array_keys($this->Request->a_addr_values);
        $q ='SELECT id, addr, value FROM current_data WHERE addr IN('.implode(',', $a_addr_for_put).'AND device_id='.$this->Request->device_id;
        $res = $this->db->query($q);
        $a_data_in_db = array();
        $a_addr_for_update = array();
        while($row = $res->fetch_assoc()){
            $a_data_in_db[$row['addr']]=$row;
            $a_addr_for_update[] = $row['addr'];
        }
        $a_addr_for_insert = array_diff($a_addr_for_put, $a_addr_for_update);

        if(count($a_addr_for_insert)){
            $q = "INSERT INTO current_data (device_id, addr, value, created) VALUES ";
            $a_q = array();
            foreach($a_addr_for_insert as $addr){
                $value = $this->Request->a_addr_values[$addr];
                $a_q[] = sprintf('( %d, %d, %d, %d)', $this->Request->device_id, $addr, $value, time());
            }
            $q = $q.implode(',', $a_q);
            $this->db->query($q);
        }

        if(count($a_addr_for_update)){
            $q = " UPDATE current_data SET device_id=%d, addr=%d, value=%d, created=%d WHERE id=%d; ";
            foreach($a_addr_for_update as $addr){
                $value = $this->Request->a_addr_values[$addr];
                $qq = sprintf($q, $this->Request->device_id, $addr, $value, time(), $a_data_in_db[$addr]['id']);
                $this->db->query($qq);
            }
        }


        // добавление в лог
        $q = "INSERT INTO log (device_id, addr, value, comment, created) VALUES ";
        $a_q = array();
        foreach($a_addr_for_put as $addr){
            $value = $this->Request->a_addr_values[$addr];
            $a_q[] = sprintf("( %d, %d, %d, '%s', %d)", $this->Request->device_id, $addr, $value, $this->Request->comment, time());
        }
        $q = $q.implode(',', $a_q);
        $this->db->query($q);

        // "подогрев" кэша
    }
    function processGet(){
        // выборка из кэша -> $a_res
        // формируем массив адресов, которых нет в кэше

        $q = 'SELECT addr, value FROM current_data WHERE addr IN('.implode(',', $this->Request->a_addr).') AND device_id='.$this->Request->device_id;
        $res = $this->db->query($q);
        while($row = $res->fetch_assoc()){
            $this->a_res[$row['addr']]=$row['value'];
        }
        // "подогрев" кэша только что выбранными значениями из БД
    }

    function processLog(Request $Request){
        $q = 'SELECT value, comment, created FROM log WHERE device_id='.$this->Request->device_id.' AND addr='.$this->Request->logaddr;
        $q.=' LIMIT '.$this->Request->loglimit;
        if($this->Request->logoffset) $q.=' OFFSET '.$this->Request->logoffset;
        $res = $this->db->query($q);
        while($row = $res->fetch_assoc()){
            $this->a_res[$row['addr']]=$row;
        }
    }

}
?>