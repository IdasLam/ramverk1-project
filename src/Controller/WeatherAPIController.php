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

    public function indexActionGet()
    {
        $dotenv = new \Symfony\Component\Dotenv\Dotenv();
        $dotenv->load(dirname(__DIR__, 2).'/.env');

        $input =  json_decode($this->di->request->getBody());
        $type = "none";
        $valid = false;
        $domain = null;
        $ip = $city = $region = $country = $loc = $result2 = null;
        
        
        if (gettype($input) === "object" && $input->ip) {
            $ip = trim($input->ip);
            
            if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
                $type = "IPv4";
                $valid = true;
            } elseif (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
                $valid = true;
                $type = "IPv6";
            }
            
            if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) || filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
                $domain = gethostbyaddr($ip);
                
                $url = "ipinfo.io/{$ip}?token={$_ENV["IPINFO_TOKEN"]}";

                $handler = curl_init();

                curl_setopt($handler, CURLOPT_URL, $url);
                curl_setopt($handler, CURLOPT_RETURNTRANSFER, true);

                $result2 = json_decode(curl_exec($handler));
                curl_close($handler);

                if ($result2 != null && !isset($result2->bogon)) {
                    $city = $result2->city;
                    $region = $result2->region;
                    $country = $result2->country;
                    $loc = $result2->loc;
                }

            }
        }

        return [[
            "ip" => $ip,
            "type" => $type,
            "valid" => $valid,
            "domain" => $domain,
            "city" => $city,
            "region" => $region,
            "country" => $country,
            "loc" => $loc,
        ]];
    }
}
