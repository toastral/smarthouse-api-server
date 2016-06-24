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