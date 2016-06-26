<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');

include 'config.php';
include 'lib/utills.class.php';
include 'lib/myexception.class.php';
include 'lib/db.class.php';
include 'lib/request.class.php';
include 'lib/app.class.php';
include 'lib/session.class.php';
include 'lib/block.class.php';
include 'lib/answer.class.php';
include 'lib/mcach.class.php';
include 'lib/maccess.class.php';
include 'lib/maddr.class.php';
include 'lib/mblock.class.php';
include 'lib/msess.class.php';
include 'lib/mcurdata.class.php';
include 'lib/secretword.class.php';
//добавим в базу 2 device_id

$dev_id_a_hex = '0F1B';
$dev_id_b_hex = '0013';

$dev_id_a_dec = base_convert($dev_id_a_hex, 16, 10);
$dev_id_b_dec = base_convert($dev_id_b_hex, 16, 10);

$Secretword = new Secretword();

//создадим 4 аккаунта с секретными словами - по 2 на каждый $dev_id
$secret_a_1 = $Secretword->db->db->real_escape_string('123 dfad sdasd');
$secret_a_2 = $Secretword->db->db->real_escape_string(' #45wq ');
$secret_b_1 = $Secretword->db->db->real_escape_string('3erlk -!&$#*(&');
$secret_b_2 = $Secretword->db->db->real_escape_string('!$ @^# _-) (13 2');

$a_secret_id = array();
$Secretword->insert($dev_id_a_dec, $secret_a_1);
$a_secret_id[] = $Secretword->db->db->insert_id;

$Secretword->insert($dev_id_a_dec, $secret_a_2);
$a_secret_id[] = $Secretword->db->db->insert_id;

$Secretword->insert($dev_id_b_dec, $secret_b_1);
$a_secret_id[] = $Secretword->db->db->insert_id;

$Secretword->insert($dev_id_b_dec, $secret_b_1);
$a_secret_id[] = $Secretword->db->db->insert_id;



$host = 'http://127.0.0.1';
/*
$url = $host.'/auth/i-'.$dev_id_a_hex.'/'.sha1($secret_a_1);
echo $url."\n";
$response = file_get_contents($url);
$o_response = json_decode($response);
if(!isset($o_response->st)){
    echo "Test fail - not found o_response->st\n";
    var_dump($o_response);
}else{
    echo "Test ok - found o_response->st\n";
}
if(!isset($o_response->session_id)){
    echo "Test fail - not found o_response->session_id\n";
    var_dump($o_response);
}else{
    echo "Test ok - not found o_response->session_id\n";
}
*/

//$session_a = $o_response->session_id;
$session_a = '5e134c4ed63bdb2166830da2fd2edd8b';
echo "$session_a\n";

// делаем запрос на получение регистра 0xAAAA
$reg_a_hex = 'AAAA';
$reg_a_val_hex = '1010';
$comment_a = '123qwdqqdqwe';

$reg_b_hex = '0BBB';
$reg_b_val_hex = '0505';
$comment_b = ' d3OOPPOededwedwedwedOPKJ';

$url_get =  $host.'/get/i-'.$dev_id_a_hex.'/'.$reg_a_hex.'/s-'.$session_a;
echo "$url_get\n";
$response = file_get_contents($url_get);
var_dump($response);
$o_response = json_decode($response);
if(!isset($o_response->st)){
    echo "#1 Test fail - not found o_response->st\n";
    var_dump($o_response);
}else{
    echo "#1 Test ok - found o_response->st\n";
}

// делаем запрос на вставку регистра 0xAAAA
$url_put =  $host.'/put/i-'.$dev_id_a_hex.'/'.$reg_a_hex.$reg_a_val_hex.'/c-'.$comment_a.'/s-'.$session_a;
echo "$url_put\n";
$response = file_get_contents($url_put);
var_dump($response);
$o_response = json_decode($response);
if(!isset($o_response->st)){
    echo "#2 Test fail - not found o_response->st\n";
    var_dump($o_response);
}else{
    echo "#2 Test ok - found o_response->st\n";
}


echo "$url_get\n";
$response = file_get_contents($url_get);
var_dump($response);
$o_response = json_decode($response);

if(!isset($o_response->st)){
    echo "#3 Test fail - not found o_response->st\n";
    var_dump($o_response);
}else{
    echo "#3 Test ok - found o_response->st\n";
    var_dump($o_response);
}

if(!isset($o_response->aaaa)){
    echo "#4 Test fail - not found o_response->aaaa\n";
}else echo "#4 Test ok\n";
if(!isset($o_response->aaaa[1])){
    echo "#5 Test fail - not found o_response->aaaa[1]\n";
}else echo "#5 Test ok\n";

if(strtoupper($o_response->aaaa[0]) != $reg_a_val_hex){
    echo "#6 Test fail - not equal o_response->aaaa[1] and $reg_a_val_hex\n";
}else echo "#6 Test ok\n";

if($o_response->aaaa[1] != $comment_a){
    echo "#7 Test fail - not equal o_response->aaaa[1] and $comment_a\n";
}else echo "#7 Test ok\n";


///////////////// попробуем записать в чужое устройство
// делаем запрос на вставку регистра 0xAAAA
$url_put =  $host.'/put/i-'.$dev_id_b_hex.'/'.$reg_a_hex.$reg_a_val_hex.'/c-'.$comment_a.'/s-'.$session_a;
echo "$url_put\n";
$response = file_get_contents($url_put);
var_dump($response);
$o_response = json_decode($response);
if(!isset($o_response->error)){
    echo "#8 Test fail - not found o_response->error\n";
    var_dump($o_response);
}else{
    echo "#8 Test ok - found o_response->error\n";
}


foreach($a_secret_id as $id){
    $Secretword->delete($id);
}

$db = $Secretword->db->db;

$db->query('DELETE * FROM current_data WHERE device_id='.$dev_id_a_dec);
$db->query('DELETE * FROM current_data WHERE device_id='.$dev_id_b_dec);

$db->query('DELETE * FROM log WHERE device_id='.$dev_id_a_dec);
$db->query('DELETE * FROM log WHERE device_id='.$dev_id_b_dec);




class Loader{
    function download($url, $ref){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_VERBOSE, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:45.0) Gecko/20100101 Firefox/45.0');
        curl_setopt($ch, CURLOPT_REFERER, $ref);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 40);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }
}

?>