<div>
    <h1>IP validator</h1>
    <p>The rest API will accept a JSON parameter in the body that containes the ip. <code>"ip": "216.58.211.142"</code>.</p>
    <p>It will in turn return something like this:</p>
    <code>
    {
        "ip":"216.58.211.142",
        "type":"IPv4",
        "valid":true,
        "domain":"arn09s10-in-f142.1e100.net",
        "city":"Mountain View",
        "region":"California",
        "country":"US",
        "loc":"37.4056,-122.0775"
    }
    </code>
    <h2>Test ip</h2>
    <a href="ip?ip-json=216.58.211.142">216.58.211.142</a>
    <a href="ip?ip-json=194.47.150.9">194.47.150.9</a>
    <a href="ip?ip-json=12312312312">12312312312</a>
</div>
<form method="get">
    <h1>Test your ip here</h1>
    <input type="text" name="ip-input" placeholder="ip adress" value=<?= $_SERVER['REMOTE_ADDR'] ?>>
    <button>Check</button>

<?php
if ($validatedIp) {
    ?>
    <h2>Results</h2>
    <div id="information">
        <table style={}>
            <tr>
                <th>IP</th>
                <th>Type</th>
                <th>Valid</th>
                <th>Domain</th>
            </tr>
            <tr>
                <td><?= $validatedIp->ip ?></td>
                <td><?= $validatedIp->type ?></td>
                <td><?= $validatedIp->valid ? "true" : "false" ?></td>
                <td><?= $validatedIp->domain ?? "null" ?></td>
            </tr>
            <tr>
                <th>City</th>
                <th>Region</th>
                <th>Country</th>
                <th>Loc</th>
            </tr>
            <tr>
                <td><?= $validatedIp->city ?? "null" ?></td>
                <td><?= $validatedIp->region ?? "null" ?></td>
                <td><?= $validatedIp->country ?? "null" ?></td>
                <td><?= $validatedIp->loc ?? "null" ?></td>
            </tr>
        </table>
        <h3>JSON format</h3>
        <a href="ip?ip-json=<?= $validatedIp->ip ?>"><?= $validatedIp->ip ?></a>
        <p id="data">
        <code style="max-width:50%;overflow-wrap:anywhere;">
            <?php echo json_encode($validatedIp) ?>
        </code>
        </p>
    </div>
    <?php
}
?>
</form>
<!-- <script>
    const container = document.getElementById("data");
    const queryString = window.location.search;
    const urlParams = new URLSearchParams(queryString);
    const ip = urlParams.get("ip-input");
    console.log(ip)


    const fetchData = async () => {
        const data = {
            "ip": ip
        };

        const response = await fetch("ip-validator", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify(data)
        });

        const result = JSON.parse(await response.text());

        container.textContent = JSON.stringify(result);
    }

    if (ip) {
        fetchData();
    }

</script> -->