<?php 

$Parsedown = new Parsedown();

?>

<div class="home-container">
    <div class="post-container">
        <p class="font-semibold">Latest posts</p>
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
                    <?php 
                    if (isset($post->tag)) :
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
    </div>
    <div>
        <div class="p-2.5">
            <p class="font-semibold">Topusers</p>
            <div class="topusers">
                <?php foreach ($topUsers as $key => $value) : ?>
                <a href=<?= "profile?user=" . $key ?>>
                    <span class="topuser-user">
                        <p><?= $key ?></p>
                        <p>🍌<?= $value ?></p>
                    </span>
                </a>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="p-2.5">
            <p class="font-semibold">Top tags</p>
            <div class="toptags">
            <?php foreach ($topTags as $key => $value) : ?>
                <a href=<?= "post?tags=" . $key ?>>
                    <span class="toptags-tag">
                        <p><?= $key ?></p>
                    </span>
                </a>
            <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
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
            let voted = parseInt(container.dataset.voted)

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