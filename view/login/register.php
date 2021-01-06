<h1>Register</h1>
<?php
    if ($di->session->get("loggedin") === null) :
?>
    <div class="register-page">
        <?php if (isset($createError)) : ?>
            <div class="error">
                <p class="font-semibold">Oops! <?= $createError ?></p>
            </div>
    
        <?php endif; ?>
        <form action="signup" method="post">
            <label for="username">Username</label>
            <input name="username" type="text" required>
            <label for="email">Email</label>
            <input name="email" type="email" required>
            <label for="password">Password</label>
            <input name="password" type="password" required>
            <button>Register</button>
        </form>
    </div>
<?php endif; ?>