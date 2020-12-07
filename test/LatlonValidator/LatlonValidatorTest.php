<?php

namespace Xolof\WeatherModule;

use Anax\DI\DIFactoryConfig;
use PHPUnit\Framework\TestCase;

/**
 * Testclass.
 */
class LatlonValidatorTest extends TestCase
{
    /**
     * Test valid validateLatlon
     */
    public function testValidValidateLatlon()
    {
        $validator = new LatlonValidator();

        $res1 = $validator->validateLatlon("56.04, 13.18");
        $res2 = $validator->validateLatlon("56.04,13.18");

        $this->assertEquals(true, $res1);
        $this->assertEquals(true, $res2);
    }

    /**
     * Test invalid validateLatlon
     */
    public function testInvalidValidateLatlon()
    {
        $validator = new LatlonValidator();

        $res = $validator->validateLatlon("1nv4l19");

        $this->assertEquals(false, $res);
    }
}
