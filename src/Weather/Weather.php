<?php
namespace Anax\Weather;

class Weather
{
    private $res = null;

    public function init(string $loc)
    {
        if ($_SERVER['SERVER_NAME'] == "localhost") {
            $url = "http://web/htdocs/weatherAPI";
        } else {
            $url = $_SERVER['SERVER_NAME'] . "htdocs/weatherAPI";
        }

        $loc = explode(",", $loc);
        $lat = $loc[0];
        $lon = $loc[1];

        $data = array("lat" => $lat, "lon" => $lon);

        $options = array(
            'http' => array(
                'header'  => "Content-type: application/json\r\n",
                'method'  => 'POST',
                'content' => json_encode($data)
            )
        );
        $context  = stream_context_create($options);
        $this->res = json_decode(file_get_contents($url, false, $context))->result;
    }

    public function getRaw()
    {
        return json_encode($this->res);
    }

    public function getWeek()
    {
        if ($this->res != null) {
            $current = $this->res->current;
            $week = $this->res->daily;

            // var_dump($this->res);
            // var_dump($current, $week);

            $currentWeek = [];
            $currentWeek[] = [
                "day" => date('D', $current->dt),
                "date" => date('d/m', $current->dt),
                "weather" => $current->weather[0]->description,
            ];


            foreach ($week as $day) {
                if ($day == $week[0]) {
                    continue;
                }

                $currentWeek[] = [
                    "day" => date('D', $day->dt),
                    "date" => date('d/m', $day->dt),
                    "weather" => $day->weather[0]->description,
                ];
            }

            return $currentWeek;
        }
    }
}

