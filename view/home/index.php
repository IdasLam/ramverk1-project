<?php 

$Parsedown = new Parsedown();
?>

<?php var_dump($posts) ?>
<?php foreach($posts as $post) : 
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
            <p><?= $post->downvote?></p>
        </div>
        <a href="" style="text-decoration: none; color: unset">
        <div class="post-data">
            <?= $Parsedown->text($title)?>
            <?= $content ?>
        </div>
        </a>
    </div>
<?php endforeach; ?>

<script>
    const upvoteButton = document.getElementById("upvote")
    const upvoteCount = document.getElementById("upvotecount")
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

    upvoteButton.addEventListener("click", upvote)
</script>