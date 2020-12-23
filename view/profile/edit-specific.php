<div class="profile">
    <?php if (isset($error)) : ?>
        <div class="edit-error">
            <p>Opps!</p>
            <p><?= $error ?></p>
        </div>
    <?php endif; ?>

    <img src=<?= $gravatar ?> alt="profile-img">
    <h1><?= $username ?></h1>
    <p><?= $email ?></p>
    <div class="edit">
        <p>Edit <?= $edit ?></p>
        <form action="edit" method="post">
            <?php if ($edit === "username") : ?>
            <input type="text" name=<?= $edit ?> placeholder=<?= $username ?>>
            <?php elseif ($edit === "email") : ?>
            <input type="text" name=<?= $edit ?> placeholder=<?= $email ?>>
            <?php else : ?>
            <input type="text" name=<?= $edit ?>>
            <?php endif; ?>
            <button name="edit" value=<?= $edit ?>>Edit</button>
        </form>
    </div>
</div>