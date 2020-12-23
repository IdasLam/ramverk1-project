<?php
    $Parsedown = new Parsedown();
    $postsCount = count($posts);
    $commentsCount = count($comments);
?>

<div class="profile-container">
    <div class="profile">
        <img src=<?= $gravatar ?> alt="profile-img">
        <h1><?= $username ?></h1>
        <form action="edit" method="get">
            <button>Edit</button>
        </form>
        <form action="login/logout" method="post">
            <button>Logout</button>
        </form>
    </div>
    <div class="posts">
        <p>Posts:</p>
        <p>Total posts: <?= $postsCount ?></p>
        <?php foreach($posts as $post): 
            $content = explode("\n", $post->content);
            $title = explode("\n", $post->content)[0];
            $content = implode("\n", array_slice($content, 2));    
        ?>
        <a href=<?= "post/" . $post->id ?>>
            <div class="post">
                <?= $Parsedown->text($title) ?>
                <?= $Parsedown->text($content) ?>
            </div>
        </a>
        <?php endforeach; ?>
    </div>
    <div class="comments">
        <p>Answers & Comments:</p>
        <p>Total answers & comments: <?= $commentsCount ?></p>
        <?php foreach($comments as $comment) : ?>
        <a href=<?=  "post/" . $comment->postid ?>>
            <div class="comment">
                <?= $Parsedown->text($comment->content) ?>
            </div>
        </a>
        <?php endforeach; ?>
    </div>
</div>