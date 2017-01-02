<div class="w_island w_island_centercol">

    <? /* Page title */ ?>
    <h1 class="page_title">
    	<?= $page->title ?>
    </h1>

    <? /* Page info */ ?>
    <div class="page-information">
        <time class="page-information__time"><?= $methods->ftime(strtotime($page->date)) ?></time>
        <a class="page-information__author" href="/user/<?= $page->author->id ?>">
            <img src="<?= $page->author->photo ?>" alt="<?= $page->author->name ?>">
            <span class="page-information__author_name"><?= $page->author->name ?></span>
        </a>
    </div>

    <? /* Page content */ ?>
    <? if ($page->blocks_array): ?>
        <article class="page_content">
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
            <a class="textbutton js-approval-button" href="/p/<?= $page->id ?>/<?= $page->uri ?>/delete"><i class="icon-cancel"></i> Удалить</a>
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
<div class="page_comments w_island" style="margin: 5px 0 5px 5px" id="page_comments">

    <? if ($page->comments): ?>
        <? foreach ($page->comments as $comment): ?>
            <div class="comment_wrapper clear <?= $comment->parent_comment ? 'answer_wrapper' : '' ?>"
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
                        <a class="delete_button js-approval-button"
                           href="/p/<?= $page->id ?>/<?= $page->uri ?>/delete-comment/<?= $comment->id ?>">
                            Удалить
                        </a>
                    <? endif ?>
                </div>

            </div>
        <? endforeach ?>
    <? else: ?>
        <div class="empty_motivatior">
            <i class="icon_nocomments"></i><br/>
            Станьте первым, кто оставит свой комментарий к данному материалу.
            <? if (!$user->id): ?>
                <br/>
                <a class="button main" href="/auth">Авторизоваться</a>
            <? endif ?>
        </div>
    <? endif ?>

    <? if($user->id): ?>
        <form action="/p/<?= $page->id ?>/<?= $page->uri ?>/add-comment" id="comment_form" method="POST" class="comment_form mt20">
            <?= Form::hidden('csrf', Security::token()); ?>
            <textarea id="add_comment_textarea" name="add_comment_textarea" rows="5"></textarea>
            <input type="hidden" name="parent_id" value="0" id="parent_id"/>
            <input type="hidden" name="root_id" value="0" id="root_id"/>
            <input id="add_comment_button" disabled type="submit" value="Оставить комментарий" />
            <span id="add_answer_to" class="add_answer_to"></span>
            <span class="cancel_answer" id="cancel_answer" name="cancel_answer"><i class="icon-cancel"></i></span>
        </form>
    <? endif ?>

</div>

<script src="/public/js/comment.js"></script>
