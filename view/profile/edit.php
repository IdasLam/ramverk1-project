<?php

?>
<div class="profile">
    <img src=<?= $gravatar ?> alt="profile-img">
    <div class="edit-name">
        <h1><?= $username ?></h1>
        <a href="edit?type=username">🖊️</a>
    </div>
    <div class="edit-email">
        <p><?= $email ?></p>
        <a href="edit?type=email">🖊️</a>
    </div>
    <div class="edit-password">
        <p>Password</p>
        <a href="edit?type=password">🖊️</a>
    </div>
</div>