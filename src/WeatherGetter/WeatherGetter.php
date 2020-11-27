<?php

namespace Anax\WeatherGetter;

use \Exception;
use \DateTime;

/**
 * A class to validate Ip-adresses.
 */
class WeatherGetter
{
    private $keyHolder;

    public function __construct($keyHolder)
    {
        $this->keyHolder = $keyHolder;
    }

    public function getForecast($coordinates)
    {
        $key = $this->keyHolder->getKey();

        $coordinates = preg_replace("/\s+/", "", $coordinates);

        $lat = substr(explode(",", $coordinates)[0], 0, 5);
        $lon = substr(explode(",", $coordinates)[1], 0, 5);

        $url = "https://api.openweathermap.org/data/2.5/forecast?lat=$lat&lon=$lon&units=metric&mode=json&appid=" . $key;

        $res = $this->forecastCurl($url, $lat, $lon);

        if (in_array(null, $res)) {
            throw new Exception("Error Processing Request", 1);
        }

        return $res;
    }

    public function getHistory($coordinates)
    {
        // Get the last 5 days
        $timeStamps = [];

        for ($i = 1; $i <= 5; $i++) {
            $date = date('Y-m-d', strtotime(" -{$i} day"));

            $dateTime = new DateTime($date);

            // Get timestamp and add an hour to get to the correct date.
            $timeStamp = $dateTime->getTimestamp() + 3600;

            $timeStamps[] = $timeStamp;
        }

        $coordinates = preg_replace("/\s+/", "", $coordinates);

        $lat = substr(explode(",", $coordinates)[0], 0, 5);
        $lon = substr(explode(",", $coordinates)[1], 0, 5);

        $url = "https://api.openweathermap.org/data/2.5/onecall/timemachine?lat=$lat&lon=$lon&units=metric&dt=";

        $res = $this->historyCurl($timeStamps, $url, $lat, $lon);

        if (in_array(null, $res)) {
            throw new Exception("Error Processing Request", 1);
        }

        return $res;
    }

    private function historyCurl(array $timeStamps, string $url, $lat, $lon) : array
    {
        try {
            $accessKey = $this->keyHolder->getKey();

            $options = [
                CURLOPT_RETURNTRANSFER => true,
            ];

            // Add all curl handlers and remember them
            // Initiate the multi curl handler
            $multiHandler = curl_multi_init();
            $chAll = [];

            foreach ($timeStamps as $timeStamp) {
                $finalURL = "$url" . $timeStamp . "&appid=" . $accessKey;
                $ch = curl_init($finalURL);
                curl_setopt_array($ch, $options);
                curl_multi_add_handle($multiHandler, $ch);
                $chAll[] = $ch;
            }

            // Get the locations name
            $locationURL = "https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=$lat&lon=$lon";
            $locationCh = curl_init($locationURL);

            $userAgent = 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.2 (KHTML, like Gecko) Chrome/22.0.1216.0 Safari/537.2';
            curl_setopt($locationCh, CURLOPT_USERAGENT, $userAgent);
            curl_setopt_array($locationCh, $options);
            // curl_setopt($ch, CURLOPT_REFERER, $_SERVER['HTTP_REFERER']);

            curl_multi_add_handle($multiHandler, $locationCh);
            $chAll[] = $locationCh;

            // Execute all queries simultaneously,
            // and continue when all are complete
            $running = null;
            do {
                curl_multi_exec($multiHandler, $running);
            } while ($running);

            // Close the handles
            foreach ($chAll as $ch) {
                curl_multi_remove_handle($multiHandler, $ch);
            }
            curl_multi_close($multiHandler);

            // All of our requests are done, we can now access the results
            $response = [];
            foreach ($chAll as $ch) {
                $data = curl_multi_getcontent($ch);
                $response[] = json_decode($data, true);
            }

            return $response;
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
    }

    private function forecastCurl($url, $lat, $lon)
    {
        try {
            $options = [
                CURLOPT_RETURNTRANSFER => true,
            ];

            // Add all curl handlers and remember them
            // Initiate the multi curl handler
            $multiHandler = curl_multi_init();
            $chAll = [];

            $weatherCh = curl_init($url);
            curl_setopt_array($weatherCh, $options);
            curl_multi_add_handle($multiHandler, $weatherCh);
            $chAll[] = $weatherCh;

            // Get the locations name
            $locationURL = "https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=$lat&lon=$lon";
            $locationCh = curl_init($locationURL);
            $userAgent = 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.2 (KHTML, like Gecko) Chrome/22.0.1216.0 Safari/537.2';
            curl_setopt($locationCh, CURLOPT_USERAGENT, $userAgent);
            curl_setopt_array($locationCh, $options);
            // curl_setopt($ch, CURLOPT_REFERER, $_SERVER['HTTP_REFERER']);

            curl_multi_add_handle($multiHandler, $locationCh);
            $chAll[] = $locationCh;

            // Execute all queries simultaneously,
            // and continue when all are complete
            $running = null;
            do {
                curl_multi_exec($multiHandler, $running);
            } while ($running);

            // Close the handles
            foreach ($chAll as $ch) {
                curl_multi_remove_handle($multiHandler, $ch);
            }
            curl_multi_close($multiHandler);

            // All of our requests are done, we can now access the results
            $response = [];
            foreach ($chAll as $ch) {
                $data = curl_multi_getcontent($ch);
                $response[] = json_decode($data, true);
            }

            return $response;
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
    }
}
