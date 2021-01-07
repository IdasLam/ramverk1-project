<div class="profile">
    <img src=<?= $gravatar ?> alt="profile-img">
    <div class="edit grid gap-4">
        <h1><?= $username ?></h1>
        <p><?= $email ?></p>
        <div class="edit">
        <?php if (isset($error)) : ?>
            <div class="edit-error font-bold">
                <p>Ops! <?= $error ?></p>
            </div>
        <?php endif; ?>
            <p>New <?= $edit ?>:</p>
            <form action="edit" method="post">
                <?php if ($edit === "username") : ?>
                <input type="text" name="new" placeholder=<?= $username ?>>
                <?php elseif ($edit === "email") : ?>
                <input type="text" name="new" placeholder=<?= $email ?>>
                <?php else : ?>
                <input type="text" name="new">
                <?php endif; ?>
                <button name="edit" value=<?= $edit ?>>Edit</button>
            </form>
        </div>
    </div>
</div>