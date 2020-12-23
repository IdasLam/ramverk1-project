<?php
    $Parsedown = new Parsedown();
    
    $hasvoted = $username !== null ? $vote->hasvotedPost($username, $post->id) : null;

    // function findComment($commentid) {
    //     $commentsdb

    // }
?>
<div class="post">
    <div class="post-points <?= $hasvoted ?>" id="post" data-voted=<?= $hasvoted ?>>
        <p id="upvotecount"><?= $posts->score?></p>
        <button class="upvote" id="upvote" data-post-id=<?= $posts->id ?>>
            Upvote
        </button>
        <button class="downvote" id="downvote" data-post-id=<?= $posts->id ?>>
            downvote
        </button>
    </div>
    <div class="post-data">
        <a href=<?= "profile/" . $posts->username ?>>u/ <?= $posts->username ?></a>
        <div class="tag-container">
            <?php if (isset($posts->tag)) :
                $tags = explode(",",$posts->tag);
                foreach ($tags as $tag) : ?>
                    <div class="tag">
                        <p><?= $tag ?></p>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <a href=<?= "post?id=" . $posts->id ?> style="text-decoration: none; color: unset">
            <?= $Parsedown->text($posts->content) ?>
        </a>
    </div>
    <div class="comments">
        <p>Comments</p>
        <?php foreach ($comments as $comment) :?>
            <div class="comment">
                <a href=<?= "profile/" . $comment->username ?>>u/ <?= $comment->username ?></a>
                <?= $Parsedown->text($comment->content) ?>
            </div>
            <?php 

            
            ?>
        <?php endforeach; ?>
    </div>
</div>

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