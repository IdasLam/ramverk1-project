<?php

namespace Anax\Controller;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;

/**
 * A sample controller to show how a controller class can be implemented.
 * The controller will be injected with $di if implementing the interface
 * ContainerInjectableInterface, like this sample class does.
 * The controller is mounted on a particular route and can then handle all
 * requests for that mount point.
 *
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 */
class WeatherAPIController implements ContainerInjectableInterface
{
    use ContainerInjectableTrait;

    public function indexActionPost()
    {
        $dotenv = new \Symfony\Component\Dotenv\Dotenv();
        $dotenv->load(dirname(__DIR__, 2).'/.env');

        $input =  json_decode($this->di->request->getBody());

        $res = "Could not get weather report from lontitude and latitude.";

        if (gettype($input) === "object" && $input->lat && $input->lon) {
            // $city = trim($input->city);
            // $country = trim($input->country);
            $maxdays = date(strtotime('today - 30 days'));
            $APIKey = $_ENV["OPENWEATHERAPP"];
            $lat = trim($input->lat);
            $lon = trim($input->lon);

            $url = "https://api.openweathermap.org/data/2.5/onecall?lat={$lat}&lon={$lon}&exclude=minutely,hourly&appid={$APIKey}";

            $handler = curl_init();

            curl_setopt($handler, CURLOPT_URL, $url);
            curl_setopt($handler, CURLOPT_RETURNTRANSFER, true);

            $res = json_decode(curl_exec($handler));
            curl_close($handler);
        }

        return  [[
            "result" => $res,
        ]];
    }
}
