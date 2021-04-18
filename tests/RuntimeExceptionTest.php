<?php

namespace saber\WorkWechat\Tests;

use RuntimeException;
use PHPUnit\Framework\TestCase;
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
        array_push($stack, 'foo');
        $this->assertEquals('foo', $stack[count($stack)-1]);
        $this->assertNotEmpty($stack);

        return $stack;
    }

    /**
     * @depends testPush
     */
    public function testPop(array $stack)
    {
        var_dump($stack);
        $this->assertEquals('foo', array_pop($stack));
        $this->assertEmpty($stack);
    }
}
