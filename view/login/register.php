<h1>Register</h1>
<?php
    if (isset($createError)) : ?>
        <div class="createError">
            <p>Oops! <?= $createError ?></p>
        </div>
    
<?php endif; ?>
<form action="signup" method="post">
    <label for="username">Username</label>
    <input name="username "type="text">
    <label for="email">email</label>
    <input name="email "type="email">
    <label for="password">Password</label>
    <input name="password "type="password">
    <button>Register</button>
</form>