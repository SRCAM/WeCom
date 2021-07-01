<?php


namespace saber\WorkWechat\Tests;
use PHPUnit\Framework\TestCase;
use saber\WorkWechat\Core\Interfaces\TokenHandleInterface;
use saber\WorkWechat\Factory;

class TokenTest implements TokenHandleInterface
{

    public static function getInstance(): TokenHandleInterface
    {
        // TODO: Implement getInstance() method.
    }

    public function getAccessToken(): string
    {
        Factory::WorkWx()->behavior->setHttpClient()
        // TODO: Implement getAccessToken() method.
    }
}