<?php

declare(strict_types=1);

namespace PWP\Tests;

use PHPUnit\Framework\TestCase;

class Initial_Test extends TestCase
{
    public function test_basic_testing_operation(): void
    {
        $this->assertTrue(true, "most basic test failed., true is not asserted as true.");
    }

    public function boolean_provider(): array
    {
        return array(
            [true, true, true],
            [true, false, false],
            [false, true, false],
            [false, false, true],
        );
    }

    /**
     * Undocumented function
     * @dataProvider boolean_provider
     * @param boolean $a
     * @param boolean $b
     * @param boolean $expected
     * @return void
     */
    public function test_data_provision(bool $a, bool $b, bool $expected): void
    {
        $this->assertEquals($expected, ($a === $b), "error with data: {$a} equals {$b} should equivalate to {$expected}");
    }
}
