<?php
    $Parsedown = new Parsedown();
    $postsCount = count($posts);
    $commentsCount = count($comments);
    $answersCount = count($answers);
?>

<div class="profile-container">
    <div class="profile">
        <img src=<?= $gravatar ?> alt="profile-img">
        <h1><?= $username ?></h1>

        <?php if ($currentUser === $username) : ?>
            <a href="profile/edit">
                <button>Edit</button>
            </a>
            <form action="login/logout" method="post">
                <button>Logout</button>
            </form>
        <?php endif; ?>
    </div>
    <div class="posts">
        <p>Posts:</p>
        <p>Total posts: <?= $postsCount ?></p>
        <?php foreach($posts as $post): 
            $content = explode("\n", $post->content);
            $title = explode("\n", $post->content)[0];
            $content = implode("\n", array_slice($content, 2));    
        ?>
        <a href=<?= "post?id=" . $post->id ?>>
            <div class="post">
                <?= $Parsedown->text($title) ?>
                <?= $Parsedown->text($content) ?>
            </div>
        </a>
        <?php endforeach; ?>
    </div>
    <div class="answers">
        <p>Answers:</p>
        <p>Total answers: <?= $answersCount ?></p>
        <?php foreach($answers as $answer) : ?>
        <a href=<?=  "post?id=" . $answer->postid ?>>
            <div class="answer">
                <?= $Parsedown->text($answer->content) ?>
            </div>
        </a>
        <?php endforeach; ?>
    </div>
    <div class="comments">
        <p>Comments:</p>
        <p>Total comments: <?= $commentsCount ?></p>
        <?php foreach($comments as $comment) : ?>
        <a href=<?=  "post?id=" . $comment->postid ?>>
            <div class="comment">
                <?= $Parsedown->text($comment->content) ?>
            </div>
        </a>
        <?php endforeach; ?>
    </div>
</div>