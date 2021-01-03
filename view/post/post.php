<?php
    $Parsedown = new Parsedown();
    
    $hasvoted = $username !== null ? $vote->hasvotedPost($username, $posts->id) : null;
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
        <a href=<?= "profile?user=" . $posts->username ?>>u/ <?= $posts->username ?></a>
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
    <?php if ($username !== null): ?>
    <div class="write-answer">
        <form action="comment/answer" method="post">
            <label for="content">Wrte Your Answer</label>
            <textarea name="content" cols="30" rows="10"></textarea>
            <input type="hidden" name="postid" value=<?= $id ?>>
            <button>Submit</button>
        </form>
    </div>
    <?php endif; ?>
    <div class="comments">
        <p>Answers</p>
        <?php foreach ($answers as $answer) :
            $hasvotedAnswer = $username !== null ? $vote->hasVotedAnswerPost($username, $posts->id, $answer->id) : null;    
        ?>
            <div class="answer">
                <div class="answer-points <?= $hasvotedAnswer ?>" id="answer" data-voted=<?= $hasvotedAnswer ?>>
                    <p class="answer-upvotecount" id="answer-upvotecount"><?= $answer->score?></p>
                    <button class="upvote answer-upvote" id="answer-upvote" data-post-id=<?= $posts->id ?> data-answer-id=<?= $answer->id ?>>
                        Upvote
                    </button>
                    <button class="downvote answer-downvote" id="answer-downvote" data-answer-id=<?= $answer->id ?>>
                        downvote
                    </button>
                </div>

                <img src=<?= $usersdb->getGravatar($answer->username) ?> alt=<?= $answer->username . "-profile-img" ?>>
                <div>
                    <a href=<?= "profile/" . $answer->username ?>>u/ <?= $answer->username ?></a>
                    <?= $Parsedown->text($answer->content) ?>
                </div>
                <button class="reply" id="reply" data-reply-comment-id=<?= $answer->id ?>>reply</button>
                <?php if ($username !== null): ?>
                <div class=<?= "reply-form-" . $answer->id?> style="display: none;">
                    <img src=<?= $gravatar ?> alt="profile-img">
                    <form action="comment/comment" method="post">
                        <label for="content">Comment</label>
                        <textarea name="content" cols="30" rows="10"></textarea>
                        <input type="hidden" name="postid" value=<?= $id ?>>
                        <input type="hidden" name="answerid" value=<?= $answer->id ?>>
                        <button>Reply</button>
                    </form>
                </div>
                <?php
                endif;
                $comments = $commentsdb->postComments($id, $answer->id);
                foreach ($comments as $comment) :
                $hasvotedComment = $username !== null ? $vote->hasVotedCommentPost($username, $posts->id, $answer->id, $comment->id) : null;
                ?>
                <div class="comment">
                    <!-- har inte fixat så att det funkar -->
                    <div class="comment-points <?= $hasvotedComment ?>" id="comment" data-voted=<?= $hasvotedComment ?>>
                        <p class="comment-upvotecount" id="comment-upvotecount"><?= $comment->score?></p>
                        <button class="upvote comment-upvote" id="comment-upvote" data-post-id=<?= $posts->id ?> data-answer-id=<?= $answer->id ?> data-comment-id=<?= $comment->id ?>>
                            Upvote
                        </button>
                        <button class="downvote comment-downvote" id="comment-downvote" data-comment-id=<?= $comment->id ?>>
                            downvote
                        </button>
                    </div>
                    <img src=<?= $usersdb->getGravatar($answer->username) ?> alt=<?= $answer->username . "-profile-img" ?>>
                    <div>
                        <a href=<?= "profile/" . $comment->username ?>>u/ <?= $comment->username ?></a>
                        <?= $Parsedown->text($comment->content) ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
    
        <?php endforeach; ?>
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


        const voteAnswer = async (type, postid, answerid, container, upvoteCount) => {
            // console.log(container.dataset)
            let voted = container.dataset.voted
            
            let res = await fetch("votePost", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
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

            upvoteButton.addEventListener("click", () => voteAnswer(1, postid, answerid, contaier, upvoteCount))
            downvoteButton.addEventListener("click", () => voteAnswer(-1, postid, answerid, contaier, upvoteCount))
        }
    </script>
    <script>
        const commentContaiers = Array.from(document.querySelectorAll(".comment-points"))
        
        const commentUpvoteButton = Array.from(document.querySelectorAll(".comment-upvote"))
        const commentDownvoteButton = Array.from(document.querySelectorAll(".comment-downvote"))
        const commentUpvoteCount = Array.from(document.querySelectorAll(".comment-upvotecount"))

        const votecomment = async (type, postid, answerid, commentid, container, upvoteCount) => {
            let voted = container.dataset.voted
            
            let res = await fetch("votePost", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
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

            upvoteButton.addEventListener("click", () => votecomment(1, postid, answerid, commentid, contaier, upvoteCount))
            downvoteButton.addEventListener("click", () => votecomment(-1, postid, answerid, commentid, contaier, upvoteCount))
        }
    </script>
    <script>
        const replies = Array.from(document.querySelectorAll(".reply"))

        replies.map((reply) => {
            let replyCommentId = reply.dataset.replyCommentId

            let form = document.querySelector(".reply-form-" + replyCommentId)
            reply.addEventListener("click", () => {form.style.display = "block"})
        })
    </script>
<?php endif; ?>