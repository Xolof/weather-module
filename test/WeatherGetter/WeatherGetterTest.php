<?php

namespace Xolof\WeatherModule;

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

        $weatherGetter = new WeatherGetter(
            $keyHolder,
            "https://api.openweathermap.org/data/2.5/forecast?lat=",
            "https://api.openweathermap.org/data/2.5/onecall/timemachine?lat=",
            "https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat="
        );

        $res = $weatherGetter->getForecast("46.50,12.50");

        $this->assertEquals("200", $res["0"]["cod"]);
    }

    /**
     * Test getHistory
     */
    public function testGetHistory()
    {
        $keyHolder = $this->di->get("weather-key");

        $weatherGetter = new WeatherGetter(
            $keyHolder,
            "https://api.openweathermap.org/data/2.5/forecast?lat=",
            "https://api.openweathermap.org/data/2.5/onecall/timemachine?lat=",
            "https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat="
        );

        $res = $weatherGetter->getHistory("46.50,12.50");

        $this->assertEquals("Italia", $res["5"]["address"]["country"]);
    }

    /**
     * Test getForecast with invalid coordinates
     */
    public function testGetForecastInvalidCoordinates()
    {
        $keyHolder = $this->di->get("weather-key");

        $weatherGetter = new WeatherGetter(
            $keyHolder,
            "https://api.openweathermap.org/data/2.5/forecast?lat=",
            "https://api.openweathermap.org/data/2.5/onecall/timemachine?lat=",
            "https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat="
        );

        $res = $weatherGetter->getForecast("-200,-200");

        $this->assertEquals("400", $res["0"]["cod"]);
    }

    /**
     * Test getHistory with invalid coordinates
     */
    public function testGetHistoryInvalidCoordinates()
    {
        $keyHolder = $this->di->get("weather-key");

        $weatherGetter = new WeatherGetter(
            $keyHolder,
            "https://api.openweathermap.org/data/2.5/forecast?lat=",
            "https://api.openweathermap.org/data/2.5/onecall/timemachine?lat=",
            "https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat="
        );

        $res = $weatherGetter->getHistory("-200,-200");

        $this->assertEquals("400", $res["0"]["cod"]);
    }

    /**
     * Test getForecast with invalid URL
     */
    public function testGetForecastInvalidURL()
    {
        $errorHappened = false;
        try {
            $keyHolder = $this->di->get("weather-key");

            $weatherGetter = new WeatherGetter(
                $keyHolder,
                "Invalid forecast URL",
                "Invalid URL",
                "Invalid URL"
            );

            $weatherGetter->getForecast("0,0");
        } catch (\Exception $e) {
            $errorHappened = true;
        }
        $this->assertTrue($errorHappened);
    }

    /**
     * Test getHistory with invalid URL
     */
    public function testGetHistoryInvalidURL()
    {
        $errorHappened = false;
        try {
            $keyHolder = $this->di->get("weather-key");

            $weatherGetter = new WeatherGetter(
                $keyHolder,
                "Invalid URL",
                "Invalid URL",
                "Invalid URL"
            );

            $weatherGetter->getHistory("0,0");
        } catch (\Exception $e) {
            $errorHappened = true;
        }
        $this->assertTrue($errorHappened);
    }
}
