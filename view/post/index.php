<?php 

$Parsedown = new Parsedown();

if (isset($username)) :
?>
    <div class="write-post">
        <img src=<?= $gravatar ?> alt="profile-img">
        <form action="post/newpost" method="post">
            <input type="text" name="tags" placeholder="Tags, comma seperated">
            <textarea name="content" cols="20" rows="10" placeholder="Markdown supported" required></textarea>
            <button>Post</button>
        </form>
    </div>

<?php endif; ?>

<?php foreach ($posts as $post) :

$hasvoted = $username !== null ? $vote->hasvotedPost($username, $post->id) : null;
?>
<div class="post">
    <div class="post-points <?= $hasvoted ?>" id="post" data-voted=<?= $hasvoted ?>>
        <p class="upvotecount" id="upvotecount"><?= $post->score?></p>
        <button class="upvote" id="upvote" data-post-id=<?= $post->id ?>>
            Upvote
        </button>
        <button class="downvote" id="downvote" data-post-id=<?= $post->id ?>>
            downvote
        </button>
    </div>
    <div class="post-data">
        <a href=<?= "profile?user=" . $post->username ?>>u/ <?= $post->username ?></a>
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
    // const contaier = document.getElementById("post")
    const contaiers = Array.from(document.querySelectorAll(".post-points"))
    
    // const upvoteButton = document.getElementById("upvote")
    // const downvoteButton = document.getElementById("downvote")
    // const upvoteCount = document.getElementById("upvotecount")
    
    const upvote = Array.from(document.querySelectorAll(".upvote"))
    const downvote = Array.from(document.querySelectorAll(".downvote"))
    const upvoteCounter = Array.from(document.querySelectorAll(".upvotecount"))

    const vote = async (type, id, container, upvoteCount) => {
        console.log(container)
        let voted = container.dataset.voted
        
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