<?php
    $Parsedown = new Parsedown();
    $postsCount = count($posts);
    $commentsCount = count($comments);
    $answersCount = count($answers);
?>

<div class="profile-container">
    <div class="profile-info">
        <div class="info">
            <img src=<?= $gravatar ?> alt="profile-img">
            <div>
                <h1><?= $username ?></h1>
                <span class="counter font-semibold">
                    <p class="count">🍌</p>
                    <p>count: <?= $points ?></p>
                </span>
                <?php if ($currentUser === $username) : ?>
                    <div class="user-edit">
                        <a href="profile/edit">
                            <button>Edit</button>
                        </a>
                        <form action="login/logout" method="post">
                            <button>Logout</button>
                        </form>
                    </div>
                <?php endif; ?>
            </div>
        </div>

    </div>
    <div class="posts">
        <p class="font-semibold">Posts: <?= $postsCount ?></p>
        <?php foreach ($posts as $post) :
            $content = explode("\n", $post->content);
            $title = explode("\n", $post->content)[0];
            $content = implode("\n", array_slice($content, 2));
            $hasvoted = $currentUser !== null ? $vote->hasvotedPost($currentUser, $post->id) : null;
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
                <a href=<?= "profile?user=" . $post->username ?>>u/<?= $post->username ?></a>
                <div class="tag-container">
                    <?php if (isset($post->tag)) :
                        $tags = explode(",", $post->tag);
                        foreach ($tags as $tag) : ?>
                            <a href=<?= "post?tags=" . $tag?>><span><?= $tag ?></span></a>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                <a href="post?id= <?= $post->id ?>">
                    <?= $Parsedown->text($post->content) ?>
                </a>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <div class="grid grid-cols-2 gap-2">
        <div class="answers">
            <p class="font-semibold">Answers: <?= $answersCount ?></p>
            <?php
            foreach ($answers as $answer) :
                $hasvotedAnswer = $currentUser !== null ? $vote->hasVotedAnswerPost($currentUser, $answer->postid, $answer->id) : null;
                ?>
            <div class="answer">
                <div class="answer-points <?= $hasvotedAnswer === "1" ? "vote-up" : ($hasvotedAnswer === "-1" ? "vote-down" : null) ?>"" id="answer" data-voted=<?= $hasvotedAnswer ?>>
                    <button class="upvote answer-upvote" id="answer-upvote" data-post-id=<?= $answer->postid ?> data-answer-id=<?= $answer->id ?> data-username=<?= $answer->username ?>>
                        🍌
                    </button>
                    <p class="answer-upvotecount" id="answer-upvotecount"><?= $answer->score?></p>
                    <button class="downvote answer-downvote" id="answer-downvote" data-answer-id=<?= $answer->id ?>>
                        🍌
                    </button>
                </div>
                <a href=<?=  "post?id=" . $answer->postid ?>>
                    <div class="answer">
                        <?= $Parsedown->text($answer->content) ?>
                    </div>
                </a>
            </div>
            <?php endforeach; ?>
        </div>
        <div class="comments">
            <p class="font-semibold">Comments: <?= $commentsCount ?></p>
            <?php
            foreach ($comments as $comment) :
                $hasvotedComment = $currentUser !== null ? $vote->hasVotedCommentPost($currentUser, $comment->postid, $comment->answerid, $comment->id) : null;
                ?>
                <div class="comment">
                        <!-- har inte fixat så att det funkar -->
                        <div class="comment-points <?= $hasvotedComment === "1" ? "vote-up" : ($hasvotedComment === "-1" ? "vote-down" : null) ?>" id="comment" data-voted=<?= $hasvotedComment ?>>
                            <button class="upvote comment-upvote" id="comment-upvote" data-post-id=<?= $comment->postid ?> data-answer-id=<?= $comment->answerid ?> data-comment-id=<?= $comment->id ?> data-username=<?= $comment->username ?>>
                                🍌
                            </button>
                            <p class="comment-upvotecount" id="comment-upvotecount"><?= $comment->score?></p>
                            <button class="downvote comment-downvote" id="comment-downvote" data-comment-id=<?= $comment->id ?>>
                                🍌
                            </button>
                        </div>
                        <a href=<?=  "post?id=" . $comment->postid ?>>
                            <div class="comment">
                                <?= $Parsedown->text($comment->content) ?>
                            </div>
                        </a>
                    </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<?php if ($username !== null) : ?>
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
<script>
    const answerContaiers = Array.from(document.querySelectorAll(".answer-points"))

    const answerUpvoteButton = Array.from(document.querySelectorAll(".answer-upvote"))
    const answerDownvoteButton = Array.from(document.querySelectorAll(".answer-downvote"))
    const answerUpvoteCount = Array.from(document.querySelectorAll(".answer-upvotecount"))


    const voteAnswer = async (username, type, postid, answerid, container, upvoteCount) => {
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
                username,
                answerid,
                id: postid,
                vote: type
            })
        })
        
        if (res.ok) {
            const data = await res.json()
            upvoteCount.textContent = data.score;
        }
    }

    for (let i = 0; i < answerUpvoteButton.length; i++ ) {
        let upvoteButton = answerUpvoteButton[i]
        let downvoteButton = answerDownvoteButton[i]
        let upvoteCount = answerUpvoteCount[i]
        let contaier = answerContaiers[i]

        let postid = upvoteButton.dataset.postId
        let answerid = upvoteButton.dataset.answerId
        let username = upvoteButton.dataset.username

        upvoteButton.addEventListener("click", () => voteAnswer(username, 1, postid, answerid, contaier, upvoteCount))
        downvoteButton.addEventListener("click", () => voteAnswer(username, -1, postid, answerid, contaier, upvoteCount))
    }
</script>
<script>
    const commentContaiers = Array.from(document.querySelectorAll(".comment-points"))
    
    const commentUpvoteButton = Array.from(document.querySelectorAll(".comment-upvote"))
    const commentDownvoteButton = Array.from(document.querySelectorAll(".comment-downvote"))
    const commentUpvoteCount = Array.from(document.querySelectorAll(".comment-upvotecount"))

    const votecomment = async (username, type, postid, answerid, commentid, container, upvoteCount) => {
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
                username,
                commentid,
                answerid,
                id: postid,
                vote: type
            })
        })
        
        if (res.ok) {
            const data = await res.json()
            upvoteCount.textContent = data.score;
        }
    }

    for (let i = 0; i < commentUpvoteButton.length; i++ ) {
        let upvoteButton = commentUpvoteButton[i]
        let downvoteButton = commentDownvoteButton[i]
        let upvoteCount = commentUpvoteCount[i]
        let contaier = commentContaiers[i]

        let postid = upvoteButton.dataset.postId
        let answerid = upvoteButton.dataset.answerId
        let commentid = upvoteButton.dataset.commentId
        let username = upvoteButton.dataset.username

        upvoteButton.addEventListener("click", () => votecomment(username, 1, postid, answerid, commentid, contaier, upvoteCount))
        downvoteButton.addEventListener("click", () => votecomment(username, -1, postid, answerid, commentid, contaier, upvoteCount))
    }
</script>
<?php endif; ?>