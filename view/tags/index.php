<?php

?>

<div class="tag-page">
    <div class="toptags">
        <p class="font-semibold">Current top tags</p>
        <?php foreach ($topTags as $key => $value) :
            if ($key !== "") :
                ?>
            <a href=<?= "post?tags=" . $key ?>>
                <div class="toptags">
                    <p><?= $key ?></p>
                </div>
            </a>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
    <p class="font-semibold">Search tags</p>
    <form action="post/searchTag" method="get">
        <input name="search" type="text" placeholder="Tags, comma seperated">
        <button>Search</button>
    </form>
</div>