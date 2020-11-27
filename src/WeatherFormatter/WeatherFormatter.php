<?php

namespace Anax\WeatherFormatter;

use \DateTime;

/**
 * A class to validate Ip-adresses.
 */
class WeatherFormatter
{
    public function formatHistory($data)
    {
        $json = [];
        $json["weather"] = [];
        $json["location"] = $data[5];

        $weather = array_slice($data, 0, 5);

        foreach ($weather as $day) {
            $date = date("D Y-m-d", $day["current"]["dt"]);
            $json["weather"][$date] = [];

            // It seems like the time for sunrise and sunset is not changing in the API:s data.
            // $json[$date]["sunrise"] = date("Y-m-d H:m", $day["current"]["sunrise"]);
            // $json[$date]["sunset"] = date("Y-m-d H:m", $day["current"]["sunset"]);
            $json["weather"][$date]["hours"] = [];

            foreach ($day["hourly"] as $hour) {
                $newHour = [];
                $newHour["time"] = date("H:m", $hour["dt"]);
                $newHour["wind"] = $hour["wind_speed"];
                $newHour["description"] = $hour["weather"][0]["description"];
                $newHour["temperature"] = $hour["temp"];
                $json["weather"][$date]["hours"][] = $newHour;
            }
        }

        return $json;
    }

    public function formatForecast($data)
    {
        $json = [];
        $json["weather"] = [];

        // $json["sunrise"] = date("H:m", $data->city->sunrise);
        // $json["sunset"] = date("H:m", $data->city->sunset);

        foreach ($data[0]["list"] as $item) {
            $day = date("D Y-m-d", $item["dt"]);

            if (!array_key_exists($day, $json)) {
                $json["weather"][$day] = [];
            }
        }

        foreach ($data[0]["list"] as $item) {
            $arr = [];

            $arr["day"] = date("D Y-m-d", $item["dt"]);
            $arr["time"] = date("H:m", $item["dt"]);
            $arr["temp"] = $item["main"]["temp"];
            $arr["feels_like"] = $item["main"]["feels_like"];
            $arr["description"] = $item["weather"][0]["description"];
            $arr["wind"] = $item["wind"]["speed"];

            $json["weather"][date("D Y-m-d", $item["dt"])][] = $arr;
        }

        $json["location"] = $data[1];

        return $json;
    }
}
