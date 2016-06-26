<?php
class Secretword{
    public $db;

    function __construct(){
        $this->db = new DB();
    }

    function getWordByDevId($dev_id){
        $q = 'SELECT secret FROM secret_word WHERE device_id=' . $dev_id;
        $res = $this->db->qry($q);
        $a_hash = array();

        if ($res->num_rows) {
            while ($row = $res->fetch_assoc()) {
                $a_hash[] = sha1($row['secret']);
            }
        }
        return $a_hash;
    }

    function getRowsByDevId($dev_id){
        $q = 'SELECT * FROM secret_word WHERE device_id=' . $dev_id;
        $res = $this->db->qry($q);
        $a_res = array();

        if ($res->num_rows) {
            while ($row = $res->fetch_assoc()) {
                $a_res[] = $row;
            }
        }
        return $a_res;
    }

    function getTotal(){
        $q = 'SELECT COUNT(id) count_id FROM secret_word';
        $res = $this->db->qry($q);
        $row = $res->fetch_assoc();
        return $row['count_id'];
    }

    function getRows($offset, $limit=5){
        $q = 'SELECT * FROM secret_word ORDER by id DESC LIMIT '.$limit.' OFFSET '.$offset.' ';
        $res = $this->db->qry($q);
        $a_res = array();
        if ($res->num_rows) {
            while ($row = $res->fetch_assoc()) {
                $a_res[] = $row;
            }
        }
        return $a_res;
    }

    function insert($dev_id, $secret){
        $q = "INSERT INTO secret_word (device_id, secret, created) VALUE (".$dev_id.", '".$secret."', ".time().")";
        $this->db->qry($q);
    }

    function delete($id){
        $q = "DELETE FROM secret_word WHERE id=".$id;
        $this->db->qry($q);
    }
}