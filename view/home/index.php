<?php 

$Parsedown = new Parsedown();

?>

<div class="topusers">
<?php foreach ($topUsers as $key => $value) : ?>
    <a href=<?= "profile/" . $key ?>>
        <div class="topuser">
            <p><?= $key ?></p>
            <p>post/comment count: <?= $value ?></p>
        </div>
    </a>
<?php endforeach; ?>
</div>

<div class="toptags">
<?php foreach ($topTags as $key => $value) : ?>
    <a href=<?= "tags/" . $key ?>>
        <div class="toptags">
            <p><?= $key ?></p>
        </div>
    </a>
<?php endforeach; ?>
</div>

<?php foreach ($posts as $post) :

    $hasvoted = $username !== null ? $vote->hasvotedPost($username, $post->id) : null;
?>
    <div class="post">
        <div class="post-points <?= $hasvoted ?>" id="post" data-voted=<?= $hasvoted ?>>
            <p id="upvotecount"><?= $post->score?></p>
            <button class="upvote" id="upvote" data-post-id=<?= $post->id ?>>
                Upvote
            </button>
            <button class="downvote" id="downvote" data-post-id=<?= $post->id ?>>
                downvote
            </button>
        </div>
        <div class="post-data">
            <a href=<?= "profile/" . $post->username ?>>u/ <?= $post->username ?></a>
            <div class="tag-container">
                <?php if (isset($post->tag)) :
                    $tags = explode(",",$post->tag);
                    foreach ($tags as $tag) : ?>
                        <div class="tag">
                            <p><?= $tag ?></p>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <a href=<?= "post?id=" . $post->id ?> style="text-decoration: none; color: unset">
                <?= $Parsedown->text($post->content) ?>
            </a>
        </div>
    </div>
<?php endforeach; ?>
<?php if ($username !== null): ?>
    <script>
        const contaier = document.getElementById("post")
        
        const upvoteButton = document.getElementById("upvote")
        const downvoteButton = document.getElementById("downvote")
        const upvoteCount = document.getElementById("upvotecount")
        const downvoteCount = document.getElementById("downvotecount")
        
        let id = upvoteButton.dataset['postId']

        const vote = async (type) => {
            let voted = contaier.dataset['voted']
            
            let res = await fetch("votePost", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    id,
                    vote: type
                })
            })

            
            if (res.ok) {
                const data = await res.json()
                upvoteCount.textContent = data.score;
            }
        }
        
        upvoteButton.addEventListener("click", () => vote(1))
        downvoteButton.addEventListener("click", () => vote(-1))
    </script>
<?php endif; ?>