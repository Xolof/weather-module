<?php

namespace Xolof\WeatherModule;

use Anax\DI\DIFactoryConfig;
use PHPUnit\Framework\TestCase;

/**
 * Testclass.
 */
class IpValidatorTest extends TestCase
{
    /**
     * Prepare before each test.
     */
    protected function setUp()
    {
        global $di;

        // Setup di
        $di = new DIFactoryConfig();
        $di->loadServices(ANAX_INSTALL_PATH . "/config/di");
        $di->loadServices(ANAX_INSTALL_PATH . "/test/config/di");

        // Use a different cache dir for unit test
        $di->get("cache")->setPath(ANAX_INSTALL_PATH . "/test/cache");

        $this->di = $di;
    }

    /**
     * Test validateIp IPv4
     */
    public function testValidateIpv4()
    {
        $validator = new IpValidator();

        $res = $validator->validateIp("194.47.150.9");

        $this->assertEquals("IPv4", $res);
    }

    /**
     * Test validateIp IPv6
     */
    public function testValidateIpv6()
    {
        $validator = new IpValidator();

        $res = $validator->validateIp("2001:0db8:85a3:0000:0000:8a2e:0370:7334");

        $this->assertEquals("IPv6", $res);
    }

    /**
     * Test validateIp invalid ip
     */
    public function testValidateIpInvalid()
    {
        $validator = new IpValidator();

        $res = $validator->validateIp("an invalid ip adress");

        $this->assertEquals(false, $res);
    }
}
