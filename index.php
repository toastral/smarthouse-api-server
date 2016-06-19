<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');

include 'config.php';
include 'utills.class.php';
include 'myexception.class.php';
include 'db.class.php';
include 'request.class.php';
include 'app.class.php';
include 'session.class.php';
include 'block.class.php';
include 'answer.class.php';
include 'mcach.class.php';
include 'maccess.class.php';
include 'maddr.class.php';
include 'mblock.class.php';
include 'msess.class.php';

/*
$expiration = 24*60*60*7; // 7 дней
$m = new Memcached();
$m->addServer('localhost', 11211);
//$m->set('foo', 100, $expiration);
$new_foo = $m->get('foo3');
if(!$new_foo) $new_foo=0;
$new_foo++;
$m->set('foo3', $new_foo, $expiration);
var_dump($m->get('foo3'));
$items = array(
    11 => 22,
    'key1' => 'value1',
    'key2' => 'value2',
    'key3' => 'value3',
    'foo2'  => 200,
    1000   =>  45321,
    'device' => array('1233'=> '123123', 123123=> 123123, 'adq'=>12312)
);
$m->setMulti($items, $expiration);
$keys = array(11, 'foo', 'key1', 'key2', 'zet', 'key3', 1000, 'device');
$got = $m->getMulti($keys, $null, Memcached::GET_PRESERVE_ORDER);
print_r($got);
var_dump($m->get('foo'));
exit;
*/

try{

    $Request = new Request();

    $App        = new App($Request);
    $Session    = new Session($Request);
    $Block      = new Block();
    $Answer     = new Answer();

    $Block->check();

    switch($Request->cmd){
        case 'auth':
            $App->processAuth();
            $Answer->show($App->a_res);
            break;
        case 'put':
            $Session->check();
            $App->processPut();
            $Answer->show($App->a_res);
            break;
        case 'log':
            $Session->check();
            $App->processLog();
            $Answer->show($App->a_res);
            break;
        default: // 'get'
            $Session->check();
            $App->processGet();
            $Answer->show($App->a_res);
            break;
    }
}catch(MyException $e){
    echo json_encode( array('error' => $e->getMessage(), 'code' => $e->getMyCode()) );
}
?>