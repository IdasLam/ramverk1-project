<h1>Register</h1>
<?php
    if ($di->session->get("loggedin")) :
        if (isset($createError)) : ?>
            <div class="error">
                <p>Oops! <?= $createError ?></p>
            </div>
    
<?php endif; ?>
        <form action="signup" method="post">
            <label for="username">Username</label>
            <input name="username" type="text" required>
            <label for="email">email</label>
            <input name="email" type="email" required>
            <label for="password">Password</label>
            <input name="password" type="password" required>
            <button>Register</button>
        </form>
<?php endif; ?>