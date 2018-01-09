<?php

define('APP_PATH', __DIR__ . '/teacher/app/');
define('SERVER_URL', isset($_SERVER['SERVER_NAME'])?$_SERVER['SERVER_NAME']:$_SERVER['HTTP_HOST']);
require __DIR__ . '/teacher/www/index.php';

?>
