<?php
//Base URL
define('BASE_URL',"http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
//Application URL
define('APP_URL',dirname(dirname(__FILE__)));
// Application Name
define('APP_NAME','PHP_MVC_FRAMEWORK');
// DB config
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', 'root');
define('DB_NAME', 'framework');

