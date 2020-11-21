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
            $APIKey = $_ENV["OPENWEATHERAPP"];
            $lat = trim($input->lat);
            $lon = trim($input->lon);

            $urls = ["https://api.openweathermap.org/data/2.5/onecall?lat={$lat}&lon={$lon}&exclude=minutely,hourly&units=metric&appid={$APIKey}"];

            for ($day = 1; $day < 6; $day++) {
                $date = date(strtotime("today - {$day} days"));
                
                $urls[] = "https://api.openweathermap.org/data/2.5/onecall/timemachine?lat={$lat}&lon={$lon}&dt={$date}&units=metric&&appid={$APIKey}";
            }

            $multi = curl_multi_init();
            $channels = array();
            
            // Loop through the URLs, create curl-handles
            // and attach the handles to our multi-request
            foreach ($urls as $url) {
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_HEADER, false);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            
                curl_multi_add_handle($multi, $ch);
            
                $channels[$url] = $ch;
            }
            
            // While we're still active, execute curl
            $active = null;
            do {
                $mrc = curl_multi_exec($multi, $active);
            } while ($mrc == CURLM_CALL_MULTI_PERFORM);
            
            while ($active && $mrc == CURLM_OK) {
                // Wait for activity on any curl-connection
                if (curl_multi_select($multi) == -1) {
                    continue;
                }
            
                // Continue to exec until curl is ready to
                // give us more data
                do {
                    $mrc = curl_multi_exec($multi, $active);
                } while ($mrc == CURLM_CALL_MULTI_PERFORM);
            }
            
            // Loop through the channels and retrieve the received
            // content, then remove the handle from the multi-handle
            $res = [];

            foreach ($channels as $channel) {
                array_push($res, json_decode(curl_multi_getcontent($channel)));
                curl_multi_remove_handle($multi, $channel);
            }
            
            // Close the multi-handle and return our results
            curl_multi_close($multi);
            
            foreach ($res as $result) {
                if ($result == $res[0]) {
                    continue;
                }
    
                $history[] = $result->hourly;
            }

            $res = $res[0] !== "Could not get weather report from lontitude and latitude." ? $res[0] : "Could not get weather report from lontitude and latitude.";
        }


        return  [[
            "forcast" => $res,
            "history" => $history ?? null
        ]];
    }
}
