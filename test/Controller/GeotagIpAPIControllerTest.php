<?php

namespace Anax\Controller;

use Anax\DI\DIFactoryConfig;
use PHPUnit\Framework\TestCase;

/**
 * Testclass.
 */
class GeoTagIpAPIControllerTest extends TestCase
{
    // Create the di container.
    protected $di;

    /**
     * Prepare before each test.
     */
    protected function setUp()
    {
        global $di;

        // Setup di
        $di = new DIFactoryConfig();
        $di->loadServices(ANAX_INSTALL_PATH . "/config/di");

        // Use a different cache dir for unit test
        $di->get("cache")->setPath(ANAX_INSTALL_PATH . "/test/cache");

        $this->di = $di;
    }


    /**
     * Test the method "indexActionPost" with an invalid Ip.
     */
    public function testInvalidIndexActionPost()
    {
        // Setup the controller
        $controller = new GeoTagIpAPIController();
        $controller->setDI($this->di);

        $request = $this->di->get("request");

        $request->setPost("ip", "456.invalid.ip");

        $res = $controller->indexActionPost();

        $exp = false;

        $this->assertEquals($exp, $res[0][0]["valid"]);
    }



    /**
     * Test the method "indexActionPost" with a valid Ipv4.
     */
    public function testValidIndexActionPostIpv4()
    {
        // Setup the controller
        $controller = new GeoTagIpAPIController();
        $controller->setDI($this->di);

        $request = $this->di->get("request");

        $request->setPost("ip", "194.47.150.9");

        $res = $controller->indexActionPost();

        $exp = true;
        $this->assertEquals($exp, $res[0][0]["valid"]);
    }


    /**
     * Test the method "indexActionPost" with a valid Ipv6.
     */
    public function testValidIndexActionPostIpv6()
    {
        // Setup the controller
        $controller = new GeoTagIpAPIController();
        $controller->setDI($this->di);

        $request = $this->di->get("request");

        $request->setPost("ip", "2001:0db8:85a3:0000:0000:8a2e:0370:7334");

        $res = $controller->indexActionPost();

        $exp = true;
        $this->assertEquals($exp, $res[0][0]["valid"]);
    }
}
