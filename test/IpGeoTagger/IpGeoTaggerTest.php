<?php

namespace Anax\IpGeoTagger;

use Anax\DI\DIFactoryConfig;
use PHPUnit\Framework\TestCase;
use Anax\KeyHolder\KeyHolder;
use \Exception;

/**
 * Testclass.
 */
class IpGeoTaggerTest extends TestCase
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

        // Use a different cache dir for unit test
        $di->get("cache")->setPath(ANAX_INSTALL_PATH . "/test/cache");

        $this->di = $di;
    }

    /**
     * Test geoTagIp
     */
    public function testGeoTagIp()
    {
        $keyHolder = $this->di->get("geotag-key");

        $tagger = new IpGeoTagger($keyHolder, "http://api.ipstack.com");

        $res = $tagger->geoTagIp("194.47.150.9");

        $this->assertEquals("ipv4", $res->type);
    }

    /**
     * Test geoTagIp with invalid key
     */
    public function testGeoTagIpInvalidKey()
    {
        $keyHolder = new KeyHolder();
        $keyHolder->setKey("invalidKey");

        $tagger = new IpGeoTagger($keyHolder, "http://api.ipstack.com");

        $res = $tagger->geoTagIp("194.47.150.9");

        $this->assertEquals(false, $res->success);
    }

    /**
     * Test geoTagIp with error
     */
    public function testGeoTagIpInvalidURL()
    {
        $errorHappened = false;
        try {
            $keyHolder = new KeyHolder();
            $keyHolder->setKey("invalidKey");

            $tagger = new IpGeoTagger($keyHolder, "http://invalid.foo");

            $tagger->geoTagIp("194.47.150.9");
        } catch (\Exception $e) {
            $errorHappened = true;
            $this->assertEquals(
                $e->getMessage(),
                "Curl failed with error #6: Could not resolve host: invalid.foo"
            );
        }
        $this->assertTrue($errorHappened);
    }
}
