<?php


$redis = new Redis();
$redis->connect('127.0.0.1', 6379);
$count = $redis->exists('count') ? $redis->get('count') : 1;
echo $count;
$redis->set('count', ++$count);
$count = $redis->get("count");

echo "----<br>----";
var_dump($count);


?>