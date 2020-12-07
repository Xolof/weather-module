<?php

namespace Xolof\WeatherModule;

use \Exception;

/**
 * A class to validate Ip-adresses.
 */
class IpGeoTagger
{
    private $keyHolder;
    private $baseURL;

    public function __construct($keyHolder, $baseURL)
    {
        $this->keyHolder = $keyHolder;
        $this->baseURL = $baseURL;
    }

    public function geoTagIp($ipAdress)
    {
        $key = $this->keyHolder->getKey();

        $url = $this->baseURL . "/" . $ipAdress . "?access_key=" . $key;

        try {
            $curlRequest = curl_init();

            curl_setopt($curlRequest, CURLOPT_RETURNTRANSFER, true);

            curl_setopt($curlRequest, CURLOPT_URL, $url);

            $json = curl_exec($curlRequest);

            if ($json === false) {
                throw new Exception(curl_error($curlRequest), curl_errno($curlRequest));
            }

            curl_close($curlRequest);
        } catch (Exception $e) {
            trigger_error(
                sprintf(
                    'Curl failed with error #%d: %s',
                    $e->getCode(),
                    $e->getMessage()
                ),
                E_USER_ERROR
            );
        }

        $apiResult = json_decode($json);

        return $apiResult;
    }
}
