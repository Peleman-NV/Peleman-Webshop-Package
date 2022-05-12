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

    /**
     * Undocumented function
     * @dataProvider booleanProvider
     * @param boolean $a
     * @param boolean $b
     * @param boolean $expected
     * @return void
     */
    public function testDataProvision(bool $a, bool $b, bool $expected): void
    {
        $this->assertEquals($expected, ($a === $b), "error with data: {$a} equals {$b} should equivalate to {$expected}");
    }

    public function booleanProvider(): array
    {
        return array(
            [true, true, true],
            [true, false, false],
            [false, true, false],
            [false, false, true],
        );
    }
}
