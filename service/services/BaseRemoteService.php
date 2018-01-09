<?php
namespace service\services;

use service\entity\User;
use service\org\util\TpString;
use think\Cache;
use think\Db;
use think\Log;
use service\algo\AlgoLogic;
use think\Request;


class BaseRemoteService
{
    protected  $api_auth_url;
    protected  $api_auth_key;
    protected $api_server_user;

    public function __construct()
    {
        $this->api_auth_url = config("api_auth_url");
        $this->api_auth_key = config("api_auth_key");
        $this->api_server_user=get_api_server_user();// config("api_server_user");
    }



}
