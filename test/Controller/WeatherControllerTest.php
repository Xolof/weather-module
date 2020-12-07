<?php

namespace Xolof\WeatherModule;

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
        $di->loadServices(ANAX_INSTALL_PATH . "/test/config/di");

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

        $exp = "<h2>Väder</h2>";
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
     * Test the method "indexActionPost", invalid location.
     */
    public function testIndexActionPostInvalidLocation()
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


    /**
     * Test the method "indexActionPost", no parameters.
     */
    public function testIndexActionPostNoParams()
    {
        // Setup the controller
        $controller = new WeatherController();
        $controller->setDI($this->di);

        $request = $this->di->get("request");

        $request->setPost("location", null);
        $request->setPost("format", null);
        $request->setPost("when", null);

        $res = $controller->indexActionPost();

        $exp = "Invalid input. Check your request.";
        $this->assertContains($exp, $res);
    }


    /**
    * Test apiInfoAction
    */
    public function testApiInfoAction()
    {
        // Setup the controller
        $controller = new WeatherController();
        $controller->setDI($this->di);

        $res = $controller->apiInfoAction();

        $this->assertInstanceOf("\Anax\Response\Response", $res);

        $body = $res->getBody();

        $exp = "Dokumentation för API";
        $this->assertContains($exp, $body);
    }

    /**
    * Test checkKeys
    */
    public function testCheckKeys()
    {
        // Setup the controller
        $controller = new WeatherController();
        $controller->setDI($this->di);

        // No parameters
        $res = $controller->checkKeys(null, null, null);
        $this->assertEquals($res, false);

        // $format is not json or html
        $res = $controller->checkKeys("some location", "xml", "history");
        $this->assertEquals($res, false);

        // $when has an invalid value
        $res = $controller->checkKeys("some location", "json", "invalid time");
        $this->assertEquals($res, false);
    }
}
