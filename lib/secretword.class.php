<?php
class Secretword{
    private $db;

    function __construct(){
        $this->db = new DB();
    }

    function getWordByDevId($dev_id){
        $q = 'SELECT secret FROM secret_word WHERE device_id=' . $dev_id;
        $res = $this->db->qry($q);
        $a_hash = array();

        if ($res->num_rows) {
            while ($row = $res->fetch_assoc()) {
                $a_hash = sha1($row['secret']);
            }
        }
        return $a_hash;
    }
}