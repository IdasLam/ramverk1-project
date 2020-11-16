<div>
    <h1>Weather</h1>
</div>
<form method="get">
    <h2>Input your ip here</h2>
    <input type="text" name="ip-input" placeholder="ip adress" value=<?= $_SERVER['REMOTE_ADDR'] ?>>
    <button>Check</button>
</form>