<div class="comment-form island island--margined clearfix">

    <form action="/p/<?= $page->id ?>/<?= $page->uri ?>/add-comment" id="comment_form" method="POST">

        <img class="comment-form__photo" src="<?= $user->photo ?>" alt="<?= $user->name ?>">

        <?= Form::hidden('csrf', Security::token()); ?>
        <input class="comment-form__submit-button" id="add_comment_button" type="submit" value="Оправить" />

        <div class="constrain">
            <textarea class="comment-form__text js-autoresizable" required id="add_comment_textarea" name="add_comment_textarea" rows="1" placeholder="Ваш комментарий..."></textarea>
        </div>

        <input type="hidden" name="parent_id" value="0" id="parent_id"/>
        <input type="hidden" name="root_id" value="0" id="root_id"/>

        <? /*
        <span class="add_answer_to" id="add_answer_to"></span>
        <span class="cancel_answer hide" id="cancel_answer" name="cancel_answer"><i class="icon-cancel"></i></span>
        */ ?>

    </form>

</div>
