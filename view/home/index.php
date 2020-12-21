<?php 

$Parsedown = new Parsedown();
?>

<?php var_dump($posts) ?>
<?php foreach ($posts as $post) : 
    $content = explode("\n", $post->content);
    $title = explode("\n", $post->content)[0];
    $content = implode("\n", array_slice($content, 2));
?>
    <div class="post">
        <div class="post-points">
            <p id="upvotecount"><?= $post->upvote?></p>
            <button id="upvote" data-post-id=<?= $post->id ?>>
                Upvote
            </button>
            <button id="downvote" data-post-id=<?= $post->id ?>>
                downvote
            </button>
            <p id="downvotecount"><?= $post->downvote?></p>
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
                <?= $content ?>
            </a>
        </div>
    </div>
<?php endforeach; ?>

<script>
    const upvoteButton = document.getElementById("upvote")
    const downvoteButton = document.getElementById("downvote")
    const upvoteCount = document.getElementById("upvotecount")
    const downvotecount = document.getElementById("downvotecount")
    
    let id = upvoteButton.dataset['postId']

    const upvote = async () => {
        let res = await fetch("votePost", {
            method: 'POST',
            headers: {
            'Content-Type': 'application/json'
            },
            body: JSON.stringify({id, "votetype": "upvote"})
        })
        
        if (res.ok) {
            const data = await res.json()
            upvoteCount.textContent = data.upvote
        }
    }
    
    const downvote = async () => {
        let res = await fetch("votePost", {
            method: 'POST',
            headers: {
            'Content-Type': 'application/json'
            },
            body: JSON.stringify({id, "votetype": "downvote"})
        })
        
        if (res.ok) {
            const data = await res.json()
            downvotecount.textContent = data.downvote
        }
    }

    upvoteButton.addEventListener("click", upvote)
    downvoteButton.addEventListener("click", downvote)
</script>