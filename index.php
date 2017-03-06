<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');

require 'config.php';
require 'lib/utills.class.php';
require 'lib/myexception.class.php';
require 'lib/db.class.php';
require 'lib/request.class.php';
require 'lib/app.class.php';
require 'lib/session.class.php';
require 'lib/block.class.php';
require 'lib/answer.class.php';
require 'lib/mcach.class.php';
require 'lib/maccess.class.php';
require 'lib/maddr.class.php';
require 'lib/mblock.class.php';
require 'lib/msess.class.php';
require 'lib/mcurdata.class.php';
require 'lib/secretword.class.php';

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
    echo json_encode(array('error' => $e->getMessage(), 'code' => $e->getMyCode()));
}
?>