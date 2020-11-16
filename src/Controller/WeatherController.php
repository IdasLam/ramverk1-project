<?php

namespace Weather\Controller;

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
        $viewData;
        $data = ["validatedIp" => null];
        
        if ($this->di->request->getBaseUrl() == null) {
            $url = "http://web/htdocs/ip-validator";
        } else {
            $url = $this->di->request->getBaseUrl() . "/ip-validator";
        }

        if ($input) {
            $viewData = fetcher($input, $url);
            $data = ["validatedIp" => json_decode($viewData)];
        }
        

        $this->di->get('page')->add('weather', $data);

        if ($json) {
            return fetcher($json, $url);
        }

        
        return $this->di->get('page')->render([
            "title" => "IP Validator"
        ]);
    }
}
