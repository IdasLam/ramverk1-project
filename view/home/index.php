<p>helelo</p>


<?php var_dump($posts) ?>
<?php foreach($posts as $post) : ?>
    <div class="post">
        <div class="points">
            <p><?= intval($post->upvote) - intval($post->downvote)?></p>
        </div>
        <p></p>
    </div>
<?php endforeach; ?>