<?php
/**
 * Created by PhpStorm.
 * User: yuan
 * Date: 16/5/12
 * Time: 下午3:09
 */
namespace service\lib;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitClientService
{
    private $connection;
    private $channel;
    private $exchange_describe = "math_logs";
    private $exchange_type="direct";
    private $rabbitmq_host = "rabbit.classba.cn";
    private $rabbitmq_port = 5672;
    private $rabbitmq_user = "admin";
    private $rabbitmq_password = "projectx2015";
    private $delivery_mode = 1;

    /**
     * RabbitLogService constructor.
     */
    public function __construct()
    {
        $this->rabbitmq_host  = config('rabbitmq_host') ? : $this->rabbitmq_host;
        $this->rabbitmq_port  = config('rabbitmq_port') ? : $this->rabbitmq_port;
        $this->rabbitmq_user  = config('rabbitmq_user') ? : $this->rabbitmq_user;
        $this->rabbitmq_password  = config('rabbitmq_password') ? : $this->rabbitmq_password;
        $this->exchange_describe = config('exchange_describe') ? : $this->exchange_describe;


        $this->connection = new AMQPStreamConnection($this->rabbitmq_host,$this->rabbitmq_port, $this->rabbitmq_user, $this->rabbitmq_password);
        $this->channel = $this->connection->channel();
        $this->channel->exchange_declare($this->exchange_describe, $this->exchange_type, false, false, false);
    }

    /**
     * @param $topic
     * @param $message
     */
    public function publish($topic,$message)
    {
        $option = array(
            'delivery_mode'=>$this->delivery_mode
        );
        $msg = new AMQPMessage($message,$option);
        $this->channel->basic_publish($msg, $this->exchange_describe, $topic);
        $this->channel->close();
        $this->connection->close();
    }
}


?>
