<?php
    if ($di->session->get("userId") !== null) {
        $di->response->redirect("home");
    }
?>

<h1>login</h1>
<?php if ($di->session->get("loginError") !== null) : ?>
    <div class="error">
        <p><?= $di->session->get("loginError") ?></p>
    </div>
<?php endif; ?>
<form action="login/userlogin" method="post">
    <label for="username">Username</label>
    <input name="username" type="text" required>
    <label for="password">Password</label>
    <input name="password" type="password" required>
    <button>Login</button>
</form>
<button onclick="location.href = 'login/register';">Register</button>