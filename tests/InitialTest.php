<?php

declare(strict_types=1);

namespace PWP\Tests;

use PHPUnit\Framework\TestCase;

class InitialTest extends TestCase
{
    public function testBasicTestingOperation(): void
    {
        $this->assertTrue(true, "most basic test failed., true is not asserted as true.");
    }
}
