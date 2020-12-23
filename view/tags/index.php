<?php

?>

<div class="tag-page">
    <div class="toptags">
    <p>Current top tags</p>
        <?php foreach ($topTags as $key => $value) : ?>
            <a href=<?= "tags/" . $key ?>>
                <div class="toptags">
                    <p><?= $key ?></p>
                </div>
            </a>
        <?php endforeach; ?>
    </div>

    <form action="post/searchTag" method="get">
        <input name="search" type="text" placeholder="Tags, comma seperated">
        <button>Search</button>
    </form>
</div>