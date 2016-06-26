<?php
define('DB_HOST',"localhost");
define('DB_PORT',"3306");
define('DB_USER',"root");
define('DB_PASS',"mysqlroot");
define('DB_NAME',"smart");

// админка
define('ADMIN_LOGIN',"admin");
define('ADMIN_PASS',"admin");

define('NUM_OF_AUTH_ATTEMPS_BEFORE_BLOCK', "5"); // кол-во попыток авторизации перед блокировкой ip

define('LOG_TIMEOUT',"259200"); // три дня

define('MEMCACHE_TIMEOUT_ADDR',"604800"); // неделя
define('MEMCACHE_TIMEOUT_SESSION',"86400"); // сутки
define('MEMCACHE_TIMEOUT_ACCESS_IP',"3600"); // один час
define('MEMCACHE_TIMEOUT_BLOCK_IP',"3600"); // один час

define('MEMCACHE_TIMEOUT',"86400"); // таймаут по умолчанию

?>