<div>
    <h1>Weather</h1>
    <p>Get the weather forcast for the coming week.</p>
    <p>The rest API, at endpint <code>http://www.student.bth.se/~idla18/dbwebb-kurser/ramverk1/me/redovisa/htdocs/weatherAPI</code> will accept a JSON parameter in the body containing longitude and latitude. <code>{"lon": "56.1616", "lat": "15.5866"}</code>.</p>
    <p>It will in turn return a detailed response for the current and upcoming weather.</p>
</div>
<form method="get">
    <h2>Input your ip here</h2>
    <input type="text" name="ip-input" placeholder="ip adress" value="194.47.150.9">
    <button>Check</button>
</form>
<div>
<?php
    if (isset($valid) && $valid == false) { ?>
        <p>Could not get weather based on ip.</p>
<?php
    } elseif (isset($res)) { ?>
        <h3>Raw JSON format</h3>
        <a href="weather?ip-json=<?= $ip ?>"><?= $ip ?></a>
        <h3>Summerised weather for <?= $city . ", " . $region . ", " . $country ?>:</h3>
        <div style="display: grid; grid-template-columns: repeat(3, 1fr);">
<?php
        foreach ($res as $day) { ?>
            <div>
                <h4><?= $day["day"] . " " . $day["date"] ?></h4>
                <p>Forcast: <?= $day["weather"] ?></p>
            </div>
<?php
        }
    }
?>
    </div>
</div>