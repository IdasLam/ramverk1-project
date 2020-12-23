<?php 

$Parsedown = new Parsedown();

if (isset($username)) :
?>
    <div class="write-post">
        <img src=<?= $gravatar ?> alt="profile-img">
        <form action="post/post">
            <input type="text" name="tags" placeholder="Tags, comma seperated">
            <textarea name="content" cols="20" rows="10" placeholder="Markdown supported"></textarea>
            <button>Post</button>
        </form>
    </div>

<?php endif; ?>
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
    $content = explode("\n", $post->content);
    $title = explode("\n", $post->content)[0];
    $content = implode("\n", array_slice($content, 2));
    
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
            <p>u/<?= $post->username ?></p>
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
            <a href="" style="text-decoration: none; color: unset">
                <?= $Parsedown->text($title)?>
                <?= $Parsedown->text($content) ?>
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