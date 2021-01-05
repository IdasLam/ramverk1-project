<?php 

$Parsedown = new Parsedown();

if (isset($username)) :
?>
<p class="font-semibold">Ask a question:</p>
<div class="write-post">
    <div>
        <img src=<?= $gravatar ?> alt="profile-img">
        <a href=<?= "profile?user=" . $username ?>>u/<?= $username ?></a>
    </div>
    <form action="post/newpost" method="post">
        <input type="text" name="tags" placeholder="Tags, comma seperated">
        <textarea name="content" cols="20" rows="10" placeholder="Markdown supported" required></textarea>
        <button>Post</button>
    </form>
</div>

<?php endif; ?>

<p class="font-semibold">Latest posts:</p>
<?php foreach ($posts as $post) :

$hasvoted = $username !== null ? $vote->hasvotedPost($username, $post->id) : null;
?>
<div class="post">
    <div class="post-points <?= $hasvoted === "1" ? "vote-up" : ($hasvoted === "-1" ? "vote-down" : null) ?>" id="post" data-voted=<?= $hasvoted ?>>
        <button class="upvote" id="upvote" data-post-id=<?= $post->id ?>>
            🍌
        </button>
        <p class="upvotecount" id="upvotecount"><?= $post->score?></p>
        <button class="downvote" id="downvote" data-post-id=<?= $post->id ?>>
            🍌
        </button>
    </div>
    <div class="post-data">
        <a href=<?= "profile?user=" . $post->username ?>>u/ <?= $post->username ?></a>
        <div class="tag-container">
            <?php if (isset($post->tag)) :
                $tags = explode(",",$post->tag);
                foreach ($tags as $tag) : ?>
                    <a href=<?= "post?tags=" . $tag?>><span><?= $tag ?></span></a>
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
    const contaiers = Array.from(document.querySelectorAll(".post-points"))
    
    const upvote = Array.from(document.querySelectorAll(".upvote"))
    const downvote = Array.from(document.querySelectorAll(".downvote"))
    const upvoteCounter = Array.from(document.querySelectorAll(".upvotecount"))

    const vote = async (type, id, container, upvoteCount) => {
        let voted = container.dataset.voted

        if (voted === type) {
            container.classList = "post-points"
        }
        
        if (type === -1) {
            container.classList.toggle("vote-down")
            container.classList.remove("vote-up")
        } else {
            container.classList.toggle("vote-up")
            container.classList.remove("vote-down")
        }
        
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

    for (let i = 0; i < upvote.length; i++ ) {
        let upvoteButton = upvote[i]
        let downvoteButton = downvote[i]
        let upvoteCount = upvoteCounter[i]
        let container = contaiers[i]

        let id = upvoteButton.dataset.postId

        upvoteButton.addEventListener("click", () => vote(1, id, container, upvoteCount))
        downvoteButton.addEventListener("click", () => vote(-1, id, container, upvoteCount))
    }
</script>
<?php endif; ?>