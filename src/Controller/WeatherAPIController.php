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

        $res = null;
        
        if (gettype($input) === "object" && $input->city && $input->country) {
            $city = trim($input->city);
            $country = trim($input->country);
            $maxdays = date(strtotime('today - 30 days'));
            $APIKey = $_ENV["OPENWEATHERAPP"];

            $url = "http://history.openweathermap.org/data/2.5/history/city?q={$city},{$country}&type=hour&end={$maxdays}&appid={$APIKey}";

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
