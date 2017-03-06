<?php
require 'config.php';
require 'db.class.php';
$time_limit = time()-LOG_TIMEOUT;
$db = new DB();
$res = $db->qry("DELETE FROM log WHERE created < $time_limit");
?>