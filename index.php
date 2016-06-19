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