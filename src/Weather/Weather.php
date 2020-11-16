<?php
namespace Anax\Weather;

class Weather
{
    private $res;

    public function init(string $city, string $country)
    {
        if ($_SERVER['SERVER_NAME'] == "localhost") {
            $url = "http://web/htdocs/ip-validator";
        } else {
            $url = $_SERVER['SERVER_NAME'] . "htdocs/weatherAPI";
        }

        $data = array('city' => $city, "country" => $country);

        $options = array(
            'http' => array(
                'header'  => "Content-type: application/json\r\n",
                'method'  => 'POST',
                'content' => json_encode($data)
            )
        );
        $context  = stream_context_create($options);
        $this->res = file_get_contents($url, false, $context);
    }

    public function getRaw()
    {
        return $this->res;
    }
}

