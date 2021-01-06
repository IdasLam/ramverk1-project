<?php
    $Parsedown = new Parsedown();
    
    $hasvoted = $username !== null ? $vote->hasvotedPost($username, $posts->id) : null;
?>
<div class="post">
    <div class="post-points <?= $hasvoted === "1" ? "vote-up" : ($hasvoted === "-1" ? "vote-down" : null) ?>" id="post" data-voted=<?= $hasvoted ?>>
        <button class="upvote" id="upvote" data-post-id=<?= $posts->id ?> data-username=<?= $posts->username ?>>
            🍌
        </button>
        <p id="upvotecount"><?= $posts->score?></p>
        <button class="downvote" id="downvote" data-post-id=<?= $posts->id ?>>
            🍌
        </button>
    </div>
    <div class="post-data individual-post">
        <a href=<?= "profile?user=" . $posts->username ?>>u/<?= $posts->username ?></a>
        <div class="tag-container">
            <?php if (isset($posts->tag)) :
                $tags = explode(",",$posts->tag);
                foreach ($tags as $tag) : ?>
                    <a href=<?= "post?tags=" . $tag?>><span><?= $tag ?></span></a>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <p>
            <?= $Parsedown->text($posts->content) ?>
        </p>
    </div>
    <div class="answer-comment-field">
        <?php if ($username !== null): ?>
        <div class="write-answer">
            <form action="comment/answer" method="post">
                <label for="content" class="font-semibold">Write Your Answer</label>
                <textarea name="content" cols="30" rows="10"></textarea>
                <input type="hidden" name="postid" value=<?= $id ?>>
                <button>Submit</button>
            </form>
        </div>
        <?php endif; ?>
        <div class="comments">
            <form action="" method="get" class="sorter">
                <label for="sort-by">sort by</label>
                <input type="hidden" name="id" value=<?= $posts->id ?>>
                <select name="sort-by">
                    <option value="default">default</option>
                    <option value="latest">latest</option>
                    <option value="oldest">oldest</option>
                    <option value="upvotes">upvotes</option>
                    <option value="controversial">controversial</option>
                </select>
                <button class="sort-button">Sort</button>
            </form>
            <p class="font-semibold">Answers: <?= count($answers) ?> </p>
            <?php foreach ($answers as $answer) :
                $hasvotedAnswer = $username !== null ? $vote->hasVotedAnswerPost($username, $posts->id, $answer->id) : null;    
            ?>
            <div class="answer<?= isset($username) ? " poster" : ($posts->answer === $answer->id ? " marked-answer-grid" : null) ?>">
                <?php if ($username === $posts->username): ?>
                    <div class="mark-answer">
                        <form action="post/markAnswer" method="post">
                        <input type="hidden" name="postid" value=<?= $id ?>>
                        <input type="hidden" name="currentAnswer" value=<?= $posts->answer?>>
                        <button name="answerid" value=<?= $answer->id ?>><?= $posts->answer === $answer->id ? "✅" : "✔️" ?></button>
                    </form>
                </div>
                <?php endif; ?>
                <div class="answer-points <?= $hasvotedAnswer === "1" ? "vote-up" : ($hasvotedAnswer === "-1" ? "vote-down" : null) ?>"" id="answer" data-voted=<?= $hasvotedAnswer ?>>
                    <button class="upvote answer-upvote" id="answer-upvote" data-post-id=<?= $posts->id ?> data-answer-id=<?= $answer->id ?> data-username=<?= $answer->username ?>>
                        🍌
                    </button>
                    <p class="answer-upvotecount" id="answer-upvotecount"><?= $answer->score?></p>
                    <button class="downvote answer-downvote" id="answer-downvote" data-answer-id=<?= $answer->id ?>>
                        🍌
                    </button>
                </div>
                <div>
                    <div class="answer-profile">
                        <img src=<?= $usersdb->getGravatar($answer->username) ?> alt=<?= $answer->username . "-profile-img" ?>>
                        <div>
                            <a href=<?= "profile?user=" . $answer->username ?>>u/<?= $answer->username ?></a>
                            <?= $Parsedown->text($answer->content) ?>
                        </div>
                    </div>
                    <?php 
                        $comments = $commentsdb->postComments($id, $answer->id);
                    ?>
                    <div class="profile-extra">
                        <div class="comment-count">
                            <p class="font-semibold">comments: <?= count($comments) ?></p>
                        </div>
                        <?php if ($username !== null): ?>
                            <button class="reply font-semibold" id="reply" data-reply-comment-id=<?= $answer->id ?>>reply</button>
                        <?php endif; ?>
                    </div>
                    <?php if ($username !== null): ?>
                    <div class="reply-form <?= "reply-form-" . $answer->id?>" style="display: none;">
                        <img src=<?= $gravatar ?> alt="profile-img">
                        <form action="comment/comment" method="post">
                            <label class="font-semibold" for="content">Comment</label>
                            <textarea name="content" cols="30" rows="10"></textarea>
                            <input type="hidden" name="postid" value=<?= $id ?>>
                            <input type="hidden" name="answerid" value=<?= $answer->id ?>>
                            <button>Reply</button>
                        </form>
                    </div>
                    <?php
                    endif;
                    ?>
                </div>
                <?php if ($username !== $posts->username && $posts->answer === $answer->id): ?>
                <p class="marked-answer">✅</p>
                <?php endif; ?>
                <?php
                foreach ($comments as $comment) :
                $hasvotedComment = $username !== null ? $vote->hasVotedCommentPost($username, $posts->id, $answer->id, $comment->id) : null;
                ?>
                <div class="comment">
                    <!-- har inte fixat så att det funkar -->
                    <div class="comment-points <?= $hasvotedComment === "1" ? "vote-up" : ($hasvotedComment === "-1" ? "vote-down" : null) ?>"" id="comment" data-voted=<?= $hasvotedComment ?>>
                        <button class="upvote comment-upvote" id="comment-upvote" data-post-id=<?= $posts->id ?> data-answer-id=<?= $answer->id ?> data-comment-id=<?= $comment->id ?> data-username=<?= $comment->username ?>>
                            🍌
                        </button>
                        <p class="comment-upvotecount" id="comment-upvotecount"><?= $comment->score?></p>
                        <button class="downvote comment-downvote" id="comment-downvote" data-comment-id=<?= $comment->id ?>>
                            🍌
                        </button>
                    </div>
                    <div class="comment-info">
                        <img src=<?= $usersdb->getGravatar($answer->username) ?> alt=<?= $answer->username . "-profile-img" ?>>
                        <div>
                            <a href=<?= "profile/" . $comment->username ?>>u/<?= $comment->username ?></a>
                            <?= $Parsedown->text($comment->content) ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<?php if ($username !== null): ?>
    <script>
        const contaier = document.getElementById("post")
        
        const upvoteButton = document.getElementById("upvote")
        const downvoteButton = document.getElementById("downvote")
        const upvoteCount = document.getElementById("upvotecount")
        
        let id = upvoteButton.dataset['postId']

        const vote = async (type) => {
            let voted = contaier.dataset['voted']

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
                let data = await res.json()
                upvoteCount.textContent = data.score;
            }
        }
        
        upvoteButton.addEventListener("click", () => vote(1))
        downvoteButton.addEventListener("click", () => vote(-1))
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
    <script>
        const replies = Array.from(document.querySelectorAll(".reply"))

        replies.map((reply) => {
            let replyCommentId = reply.dataset.replyCommentId

            let form = document.querySelector(".reply-form-" + replyCommentId)
            reply.addEventListener("click", () => {form.style.display = "grid"})
        })
    </script>
<?php endif; ?>