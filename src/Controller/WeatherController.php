<?php

namespace Anax\Controller;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;
use Anax\IpValidator\IpValidator;
use Anax\LatlonValidator\LatlonValidator;
use Anax\IpGeoTagger\IpGeoTagger;
use Anax\IpGetter\IpGetter;
use Anax\WeatherGetter\WeatherGetter;
use Anax\WeatherFormatter\WeatherFormatter;
use \stdClass;
use \Exception;

/**
* A controller for getting weather info.
 */
class WeatherController implements ContainerInjectableInterface
{
    use ContainerInjectableTrait;

    public function indexAction()
    {
        $page = $this->di->get("page");
        $page->add("anax/v2/weather-form/weather-form");
        return $page->render([
            "title" => "Weather",
        ]);
    }

    public function apiInfoAction()
    {
        $page = $this->di->get("page");
        $page->add("anax/v2/weather-api-info/default");
        return $page->render([
            "title" => "Dokumentation för API",
        ]);
    }

    public function checkKeys($location, $format, $when)
    {
        if (!$location || !$format || !$when) {
            return false;
        }

        if (!($format == "json" || $format == "html")) {
            return false;
        }

        if (!($when == "forecast" || $when == "history")) {
            return false;
        }

        return true;
    }

    public function indexActionPost()
    {
        $location = trim(htmlentities($this->di->request->getPost("location")));
        $format = htmlentities($this->di->request->getPost("format"));
        $when = htmlentities($this->di->request->getPost("when"));

        if (!$this->checkKeys($location, $format, $when)) {
            return json_encode([["error" => "Invalid input. Check your request."]]);
        }

        $url = $this->di->get("url");

        $ipValidator = new IpValidator();

        $latlonValidator = new LatlonValidator();

        $coordinates = null;

        // Check if location is an IP
        $ipType = $ipValidator->validateIp($location);
        if ($ipType) {
            // Locate the ip-adress using Ipstack.
            $keyHolder = $this->di->get("geotag-key");

            $geoTagger = new IpGeoTagger($keyHolder, "http://api.ipstack.com");

            $ipInfo = $geoTagger->geoTagIp($location);

            $coordinates = "$ipInfo->latitude,$ipInfo->longitude";
        } else if ($latlonValidator->validateLatlon($location)) {
            $coordinates = $location;
        } else {
            $invalidInfo = new stdClass();
            $invalidInfo->message = "<h3>Ogiltig position.</h3><a href='" . $url->create("weather") . "'>Sök igen</a>";
            $data["content"] = $invalidInfo->message;

            $page = $this->di->get("page");
            $page->add("anax/v2/article/default", $data);
            return $page->render([
                "title" => "Ogiltig position",
            ]);
        }

        if ($coordinates) {
            $dataAndView = $this->getData($coordinates, $when);
            $data = $dataAndView["data"];
            $view = $dataAndView["view"];
        }

        if ($format == "html") {
            // Send response as HTML
            $page = $this->di->get("page");
            $page->add("anax/v2/weather-results/$view", $data);
            return $page->render([
                "title" => "Resultat för väder",
            ]);
        } else if ($format == "json") {
            // Send response as JSON
            return [$data, 200];
        }
    }

    private function getData($coordinates, $when)
    {
        $keyHolder = $this->di->get("weather-key");

        $weatherGetter = new WeatherGetter($keyHolder);
        $weatherFormatter = new WeatherFormatter();

        try {
            if ($when == "forecast") {
                $rawData = $weatherGetter->getForecast($coordinates);
                $data["content"] = $weatherFormatter->formatForecast($rawData);
                $view = "forecast-results";
            } else if ($when == "history") {
                $rawData = $weatherGetter->getHistory($coordinates);
                $data["content"] = $weatherFormatter->formatHistory($rawData);
                $view = "history-results";
            }
            return ["data" => $data, "view" => $view];
        } catch (\Exception $e) {
            $invalidInfo = new stdClass();
            $invalidInfo->message = "<h3>Anropet misslyckades</h3><a href='" . $url->create("weather") . "'>Sök igen</a>";
            $data["content"] = $invalidInfo->message;

            // Send response as HTML
            $page = $this->di->get("page");
            $page->add("anax/v2/article/default", $data);
            return $page->render([
                "title" => "Anropet misslyckades",
            ]);
        }
    }
}
