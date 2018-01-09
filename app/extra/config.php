<?php
$file_path=APP_PATH.'extra'.DS.'config'.DS.SERVER_URL.'.php';
if(!file_exists($file_path))$file_path=APP_PATH.'extra'.DS.'config'.DS.'127.0.0.1'.'.php';
return require_once $file_path;
