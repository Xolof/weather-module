<?php

namespace Anax\WeatherGetter;

use Anax\DI\DIFactoryConfig;
use PHPUnit\Framework\TestCase;

/**
 * Testclass.
 */
class WeatherGetterTest extends TestCase
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
     * Test getForecast
     */
    public function testGetForecast()
    {
        $keyHolder = $this->di->get("weather-key");

        $weatherGetter = new WeatherGetter($keyHolder);

        $res = $weatherGetter->getForecast("46.50,12.50");

        $this->assertEquals("200", $res["0"]["cod"]);
    }

    /**
     * Test getHistory
     */
    public function testGetHistory()
    {
        $keyHolder = $this->di->get("weather-key");

        $weatherGetter = new WeatherGetter($keyHolder);

        $res = $weatherGetter->getHistory("46.50,12.50");

        $this->assertEquals("Italia", $res["5"]["address"]["country"]);
    }

    /**
     * Test getForecast with error
     */
    public function testGetForecastInvalid()
    {
        $keyHolder = $this->di->get("weather-key");

        $weatherGetter = new WeatherGetter($keyHolder);

        $res = $weatherGetter->getForecast("-200,-200");

        $this->assertEquals("400", $res["0"]["cod"]);
    }

    /**
     * Test getHistory with error
     */
    public function testGetHistoryInvalid()
    {
        $keyHolder = $this->di->get("weather-key");

        $weatherGetter = new WeatherGetter($keyHolder);

        $res = $weatherGetter->getHistory("-200,-200");

        $this->assertEquals("400", $res["0"]["cod"]);
    }
}
