<?php

namespace saber\WorkWechat\Tests;

use Monolog\Test\TestCase;
use saber\WorkWechat\Factory;

class RuntimeExceptionTest extends TestCase
{
    public function testEmpty()
    {
//       testEmpty $data = Factory::WorkWx([])->linked_corp->getPermList();
//        $stack = [];
//        $this->assertArrayNotHasKey("data",$data);
//        return $stack;
    }

    /**
     * @depends testEmpty
     */
    public function testPush(array $stack)
    {
        return $stack;
    }

}
