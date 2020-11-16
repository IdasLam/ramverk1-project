<div>
    <h1>Weather</h1>
    <p>Get the weather forcast from previous 30 days.</p>
</div>
<form method="get">
    <h2>Input your ip here</h2>
    <input type="text" name="ip-input" placeholder="ip adress" value=<?= $_SERVER['REMOTE_ADDR'] ?>>
    <button>Check</button>
</form>