<?php

namespace Anax\Controller;

use Anax\DI\DIFactoryConfig;
use PHPUnit\Framework\TestCase;

/**
 * Testclass.
 */
class WeatherControllerTest extends TestCase
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
        $controller = new WeatherController();
        $controller->setDI($this->di);

        // Test the controller action
        $res = $controller->indexAction();

        $this->assertInstanceOf("\Anax\Response\Response", $res);

        $body = $res->getBody();

        $exp = "| ramverk1</title>";
        $this->assertContains($exp, $body);
    }



    /**
     * Test the method "indexActionPost", valid input.
     */
    public function testIndexActionPost()
    {
        // Setup the controller
        $controller = new WeatherController();
        $controller->setDI($this->di);

        $request = $this->di->get("request");

        // JSON, history
        $request->setPost("location", "56.04,13.18");
        $request->setPost("format", "json");
        $request->setPost("when", "history");

        $res = $controller->indexActionPost();

        $exp = "Grindhus";

        $this->assertEquals($exp, $res[0]["content"]["location"]["address"]["isolated_dwelling"]);

        // JSON, forecast
        $request->setPost("location", "56.04,13.18");
        $request->setPost("format", "json");
        $request->setPost("when", "forecast");

        $res = $controller->indexActionPost();

        $exp = "Grindhus";
        $this->assertEquals($exp, $res[0]["content"]["location"]["address"]["isolated_dwelling"]);

        // HTML, history
        $request->setPost("location", "56.04,13.18");
        $request->setPost("format", "html");
        $request->setPost("when", "history");

        $res = $controller->indexActionPost();

        $this->assertInstanceOf("\Anax\Response\Response", $res);

        $body = $res->getBody();

        $exp = "Grindhus";
        $this->assertContains($exp, $body);

        $exp = "Föregående";
        $this->assertContains($exp, $body);

        // HTML, forecast
        $request->setPost("location", "194.47.150.9");
        $request->setPost("format", "html");
        $request->setPost("when", "forecast");

        $res = $controller->indexActionPost();

        $this->assertInstanceOf("\Anax\Response\Response", $res);

        $body = $res->getBody();

        $exp = "Trossö";
        $this->assertContains($exp, $body);

        $exp = "Kommande";
        $this->assertContains($exp, $body);
    }


    /**
     * Test the method "indexActionPost", invalid input.
     */
    public function testInvalidIndexActionPost()
    {
        // Setup the controller
        $controller = new WeatherController();
        $controller->setDI($this->di);

        $request = $this->di->get("request");

        $request->setPost("location", "invalid");
        $request->setPost("format", "html");
        $request->setPost("when", "history");

        $res = $controller->indexActionPost();

        $this->assertInstanceOf("\Anax\Response\Response", $res);

        $body = $res->getBody();

        $exp = "Ogiltig position";
        $this->assertContains($exp, $body);

        $exp = "Sök igen";
        $this->assertContains($exp, $body);
    }
}
