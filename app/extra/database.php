<?php
$file_path=APP_PATH.'extra'.DS.'database'.DS.SERVER_URL.'.php';
if(!file_exists($file_path))$file_path=APP_PATH.'extra'.DS.'database'.DS.'127.0.0.1'.'.php';
return require_once $file_path;
