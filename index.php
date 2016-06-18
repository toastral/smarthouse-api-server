<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');

include 'config.php';
include 'myexception.class.php';
include 'db.class.php';
include 'request.class.php';
include 'app.class.php';

$Db = new Db($db_host, $db_user, $db_pass, $db_name);

try{
    $App = new App();
    $Request = $Request->get();
    switch($Request->type){
        case 'post':
            $App->save($Request);
            $App->echoAnswer();
            break;
        case 'get_history':
            $App->getHistory($Request);
            $App->echoAnswer();
            break;
        default: // get_current_data
            $App->getCurrent($Request);
            $App->echoAnswer();
            break;
    }
}catch(MyException $e){
    // echo Exception
    // $e->type
    echo "Catch :".$e->getMessage()."\n";
    echo "Code :".$e->getCode()."\n";
}

?>