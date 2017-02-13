<div class="island island--padded">

    <? /* Page info */ ?>
    <div class="article-information">
        <time class="article-information__time">
            <?= $methods->ftime(strtotime($page->date)) ?>
        </time>
        <a class="article-information__author" href="/user/<?= $page->author->id ?>">
            <img src="<?= $page->author->photo ?>" alt="<?= $page->author->name ?>">
            <?= $page->author->name ?>
        </a>
    </div>

    <? /* Page title */ ?>
    <h1 class="article-title">
    	<?= $page->title ?>
    </h1>

    <? /* Page content */ ?>
    <? if ($page->blocks_array): ?>
        <article class="article-content">
            <? for ($i = 0; $i < count($page->blocks_array); $i++): ?>
                <?= $page->blocks_array[$i]; ?>
            <? endfor ?>
        </article>
    <? endif ?>

    <? /* Child pages */ ?>
    <? if ($page->childrens): ?>
        <ul class="page_childrens clear <?= !$page->content ? 'page_childrens--empty-content' : '' ?>">
            <? foreach ($page->childrens as $children): ?>
                <li><a href="/p/<?= $children->id ?>/<?= $children->uri ?>"><?= $children->title ?></a></li>
            <? endforeach ?>
        </ul>
    <? endif ?>

    <? /* Manage page buttons */ ?>
    <? if ($can_modify_this_page): ?>
        <div class="action-line action-line__onpage clear">
            <? if ($page->author->id == $user->id ): ?>
                <a class="button iconic green" href="/p/save?id=<?= $page->id ?>"><i class="icon-pencil"></i>Редактировать</a>
                <a class="button iconic green" href="/p/save?parent=<?= $page->id ?>"><i class="icon-plus"></i>Вложенная страница</a>
            <? endif ?>
            <? if ($user->status == Model_User::USER_STATUS_ADMIN): ?>
                <a class="button iconic" href="/p/<?= $page->id ?>/<?= $page->uri ?>/promote?list=menu"><?= $page->is_menu_item ? 'убрать из меню' : 'добавить в меню' ?></i></a>
                <a class="button iconic" href="/p/<?= $page->id ?>/<?= $page->uri ?>/promote?list=news"><?= $page->is_news_page ? 'убрать из новостей' : 'добавить в новости' ?></a>
            <? endif ?>
            <a class="button js-approval-button" href="/p/<?= $page->id ?>/<?= $page->uri ?>/delete"><i class="icon-cancel"></i> Удалить</a>
        </div>
    <? endif ?>

</div>

<? /* Page's images block */ ?>
<? if ($page->images): ?>
    <div class="w_island images" style="margin: 5px 0 5px 5px">
        <? foreach ($page->images as $image): ?>
            <a href="/upload/page_images/o_<?= $image->filename ?>" target="_blank">
                <img src="/upload/page_images/b_<?= $image->filename ?>" class="page_image">
            </a>
        <? endforeach ?>
    </div>
<? endif ?>

<? /* Page's files block */ ?>
<? if ($page->files): ?>
    <div class="w_island files" style="margin: 5px 0 5px 5px">
    	<table class="page_files">
    		<? foreach ($page->files as $file): ?>
    			<tr>
    				<td class="ext"><span class="ext_tag"><?= $file->extension ?></span></td>
    				<td class="title"><a href="/file/<?= $file->file_hash_hex ?>"><?= $file->title ?></a></td>
    				<td>
    					<p class="size"><?= (int)$file->size < 1000 ? $file->size . PHP_EOL . 'КБ' : ceil($file->size / 1000) . PHP_EOL . 'МБ' ?></p>
    				</td>
    			</tr>
    		<? endforeach ?>
    	</table>
    </div>
<? endif ?>

<? /* Comments block */ ?>
<? if ($user->id): ?>

    <div class="island island--margined island--padded comment-form clearfix">

        <form action="/p/<?= $page->id ?>/<?= $page->uri ?>/add-comment" id="comment_form" method="POST">

            <img class="comment-form__photo" src="<?= $user->photo ?>" alt="<?= $user->name ?>">

            <?= Form::hidden('csrf', Security::token()); ?>
            <input id="add_comment_button" type="submit" class="comment-form__submit-button" value="Оправить" />

            <div class="constrain comment-form__text-wrapper">
                <textarea class="comment-form__text"  id="add_comment_textarea" name="add_comment_textarea"rows="1" placeholder="Ваш комментарий..."></textarea>
            </div>

            <input type="hidden" name="parent_id" value="0" id="parent_id"/>
            <input type="hidden" name="root_id" value="0" id="root_id"/>

            <span class="add_answer_to" id="add_answer_to"></span>
            <span class="cancel_answer hide" id="cancel_answer" name="cancel_answer"><i class="icon-cancel"></i></span>
        </form>

        <script>

            codex.docReady(function(){

                /**
                * Comments module
                */
                codex.comments.init();

            });

        </script>
    </div>

<? endif ?>

<div class="comments-list" id="page_comments">

    <? if ($page->comments): ?>
        <? foreach ($page->comments as $comment): ?>
            <div class="island island--padded comment_wrapper clear <?= $comment->parent_comment ? 'answer_wrapper' : 'island--margined' ?>"
                 id="comment_<?= $comment->id ?>">
                <a href="/user/<?= $comment->author->id ?>">
                    <img class="comment_left" src="<?= $comment->author->photo ?>">
                </a>
                <div class="comment_right">

                    <time>
                        <?= date_format(date_create($comment->dt_create), 'd F Y') ?>
                    </time>

                    <a href="/user/<?= $comment->author->id ?>" class="author_name">
                        <?= $comment->author->name ?>
                    </a>

                    <? if ($comment->parent_comment): ?>
                        <span class="to_user">
                            <i class="icon-right-dir"></i>
                            <?= $comment->parent_comment->author->name ?>
                        </span>
                    <? endif ?>


                    <p><?= $comment->text ?></p>

                    <? if ($user->id): ?>
                        <span class="answer_button" id="answer_button_<?= $comment->id ?>"
                              data-comment-id="<?= $comment->id ?>"
                              data-root-id="<?= $comment->root_id ?>">
                            <i class="icon-reply"></i>
                            Ответить
                        </span>
                    <? endif ?>

                    <? if ($user->id == $comment->author->id || $user->isAdmin): ?>
                        <a class="button--delete js-approval-button"
                           href="/p/<?= $page->id ?>/<?= $page->uri ?>/delete-comment/<?= $comment->id ?>">
                            Удалить
                        </a>
                    <? endif ?>
                </div>

            </div>
        <? endforeach ?>
    <? else: ?>
        <div class="empty-motivator">
            <? include(DOCROOT . "public/app/svg/comments.svg") ?>
            <p>Станьте первым, кто оставит <br/> комментарий к данному материалу.</p>
            <? if (!$user->id): ?>
                <a class="button master" href="/auth">Авторизоваться</a>
            <? endif ?>
        </div>
    <? endif ?>

</div>
