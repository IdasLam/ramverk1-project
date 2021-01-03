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
            <!-- har inte fixat så att det funkar -->
                <div class="answer-points <?= $hasvotedAnswer ?>" id="answer" data-voted=<?= $hasvotedAnswer ?>>
                    <p id="upvotecount"><?= $posts->score?></p>
                    <button class="upvote" id="answer-upvote" data-answer-id=<?= $answer->id ?>>
                        Upvote
                    </button>
                    <button class="downvote" id="answer-downvote" data-answer-id=<?= $answer->id ?>>
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
                $hasvotedAnswer = $username !== null ? $vote->hasVotedCommentPost($username, $posts->id, $answer->id, $comment->id) : null;    
                
                ?>
                <div class="comment">
                    <!-- har inte fixat så att det funkar -->
                    <div class="answer-points <?= $hasvotedAnswer ?>" id="comment" data-voted=<?= $hasvotedAnswer ?>>
                        <p id="upvotecount"><?= $comment->score?></p>
                        <button class="upvote" id="comment-upvote" data-comment-id=<?= $comment->id ?>>
                            Upvote
                        </button>
                        <button class="downvote" id="comment-downvote" data-comment-id=<?= $comment->id ?>>
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
    <script>
        const replies = Array.from(document.querySelectorAll(".reply"))

        replies.map((reply) => {
            let replyCommentId = reply.dataset.replyCommentId

            let form = document.querySelector(".reply-form-" + replyCommentId)
            reply.addEventListener("click", () => {form.style.display = "block"})
        })
    </script>
<?php endif; ?>