<?php

namespace Anax\Controller;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;

function fetcher2($ip, $url)
{
    $data = array('ip' => $ip);

    $options = array(
        'http' => array(
            'header'  => "Content-type: application/json\r\n",
            'method'  => 'POST',
            'content' => json_encode($data)
        )
    );
    $context  = stream_context_create($options);
    return file_get_contents($url, false, $context);
}

/**
 * A sample controller to show how a controller class can be implemented.
 * The controller will be injected with $di if implementing the interface
 * ContainerInjectableInterface, like this sample class does.
 * The controller is mounted on a particular route and can then handle all
 * requests for that mount point.
 *
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 */
class WeatherController implements ContainerInjectableInterface
{
    use ContainerInjectableTrait;

    /**
     * @var string $db a sample member variable that gets initialised
     */
    private $db = "not active";
    public $valid = "";

    public function indexActionGet()
    {
        $input =  $this->di->request->getGet('ip-input');
        $json =  $this->di->request->getGet('ip-json');
        $weather = $this->di->get("weather");
        $data = ["ip" => $input];
        
        if ($_SERVER['SERVER_NAME'] == "localhost" || $this->di->request->getBaseUrl() == null) {
            $url = "http://web/htdocs/ip-validator";
        } else {
            $url = $this->di->request->getBaseUrl() . "/ip-validator";
        }

        if ($input || $json) {
            $res = json_decode(fetcher2($input ?? $json, $url));

            if (isset($res->loc)) {
                $data["city"] = $res->city;
                $data["region"] = $res->region;
                $data["country"] = $res->country;
                $loc = $res->loc;

                $loc = explode(",", $loc);
                $lat = $loc[0];
                $lon = $loc[1];

                $data["lon"] = $lon;
                $data["lat"] = $lat;

                $weather->init($lon, $lat);
            }
        }

        if ($input) {
            if (isset($res->loc)) {
                $data["valid"] = true;
                $data["res"] = $weather->getWeek();
                $data["history"] = $weather->getHistory();
            } elseif (isset($res->loc) == false) {
                $data["valid"] = false;
            }
        }
        
        if ($json) {
            return [[json_decode($weather->getRaw())]];
        }

        $this->di->get('page')->add('weather', $data);
        
        return $this->di->get('page')->render([
            "title" => "Weather"
        ]);
    }
}
