<?php

namespace Anax\Controller;

use Anax\DI\DIFactoryConfig;
use PHPUnit\Framework\TestCase;

/**
 * Testclass.
 */
class ValidateIpPageControllerTest extends TestCase
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
     * Test the route "index".
     */
    public function testIndexAction()
    {
        // Setup the controller
        $controller = new ValidateIpPageController();
        $controller->setDI($this->di);

        // Test the controller action
        $res = $controller->indexAction();

        $this->assertInstanceOf("\Anax\Response\Response", $res);

        $body = $res->getBody();

        $exp = "| ramverk1</title>";
        $this->assertContains($exp, $body);
    }



    /**
     * Test the method "articleActionPost" with an invalid Ip.
     */
    public function testInvalidArticleActionPost()
    {
        // Setup the controller
        $controller = new ValidateIpPageController();
        $controller->setDI($this->di);

        $request = $this->di->get("request");

        $request->setPost("ip", "456.invalid.ip");

        $res = $controller->articleActionPost();

        $this->assertInstanceOf("\Anax\Response\Response", $res);

        $body = $res->getBody();

        $exp = "is invalid";
        $this->assertContains($exp, $body);
    }



    /**
     * Test the method "articleActionPost" with a valid Ipv4.
     */
    public function testValidArticleActionPostIpv4()
    {
        // Setup the controller
        $controller = new ValidateIpPageController();
        $controller->setDI($this->di);

        $request = $this->di->get("request");

        $request->setPost("ip", "194.47.150.9");

        $res = $controller->articleActionPost();

        $this->assertInstanceOf("\Anax\Response\Response", $res);

        $body = $res->getBody();

        $exp = "is a valid IPv4 address.";
        $this->assertContains($exp, $body);
    }


    /**
     * Test the method "articleActionPost" with a valid Ipv6.
     */
    public function testValidArticleActionPostIpv6()
    {
        // Setup the controller
        $controller = new ValidateIpPageController();
        $controller->setDI($this->di);

        $request = $this->di->get("request");

        $request->setPost("ip", "2001:0db8:85a3:0000:0000:8a2e:0370:7334");

        $res = $controller->articleActionPost();

        $this->assertInstanceOf("\Anax\Response\Response", $res);

        $body = $res->getBody();

        $exp = "is a valid IPv6 address.";
        $this->assertContains($exp, $body);
    }
}
