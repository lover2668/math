<?php
namespace app\teacher\controller;
class Index extends Base
{

    function index()
    {
        return $this->fetch();
    }
    public function main(){
        return $this->fetch();
    }
}