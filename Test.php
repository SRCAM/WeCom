<?php

interface tokenHander{

    public function getToken():string ;
}


class Test  implements tokenHander
{

    private $token;

    private function __construct()
    {
        $this->swa();

    }


    public function swa(){
        $this->token = a;
    }

    public function swb(){
        $this->token = b;
    }
    public function  swc(){
        $this->token = c;
    }

    public function getToken(): string
    {
        // TODO: Implement getToken() method.
    }
}