<?php

namespace Anax\Controller;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;

function fetcher($ip, $url)
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
        $data = ["validatedIp" => null];
        
        if ($this->di->request->getBaseUrl() == null) {
            $url = "http://web/htdocs/ip-validator";
        } else {
            $url = $this->di->request->getBaseUrl() . "/ip-validator";
        }

        if ($input) {
            $res = json_decode(fetcher($input, $url));
            $city = $res->city;
            $country = $res->country;

            $weather->init($city, $country);
        }
        
        var_dump($weather->getRaw());
        // echo $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
    
        $this->di->get('page')->add('weather', $data);

        if ($json) {
            return fetcher($json, $url);
        }

        // echo $thi->di->has("Weather");

        // $weather->test();
        
        return $this->di->get('page')->render([
            "title" => "IP Validator"
        ]);
    }
}
