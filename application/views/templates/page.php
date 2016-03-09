<div class="breadcrumb" itemscope itemtype="http://data-vocabulary.org/Breadcrumb">

    <? if($navigation[0]->type != Model_Page::TYPE_USER_PAGE || $navigation[0]->is_menu_item == 1): ?>
        <a class="nav_chain" href="/" itemprop="url"><span itemprop="title">Главная</span></a>
    <? else: ?>
        <a class="nav_chain" href="/user/<?= $page->author->id ?>" itemprop="url"><span itemprop="title"><?= $page->author->name ?></span></a>
    <? endif ?>

    <? foreach ($navigation as $navig_page): ?> »
        <? if ($navig_page->id != $page->id): ?>
            <a href="/p/<?= $navig_page->id ?>/<?= $navig_page->uri ?>" itemprop="title" class="nav_chain">
                <?= $navig_page->title ?>
            </a>
        <? else: ?>
            <span itemprop="title" class="nav_chain">
                <?= $navig_page->title ?>
            </span>
        <? endif ?>
    <? endforeach ?>

    <? if( $can_modify_this_page ): ?>
        <div class="fl_r actions">
            <a class="textbutton" href="/p/<?= $page->id ?>/<?= $page->uri ?>/delete"><i class="icon-cancel"></i> Удалить</a>
            <a class="button iconic green" href="/p/<?= $page->id ?>/<?= $page->uri ?>/edit"><i class="icon-pencil"></i> Редактировать</a>
        </div>
    <? endif ?>

</div>

<h1 class="page_title">
	<?= $page->title ?>
</h1>
<article class="page_content">
	<?= $page->content ?>
</article>

<? if ($page->childrens): ?>
    <ul class="page_childrens clear">
        <? foreach ($page->childrens as $children): ?>
            <li><a href="/p/<?= $children->id ?>/<?= $children->uri ?>"><?= $children->title ?></a></li>
        <? endforeach ?>
    </ul>
<? endif; ?>
<? if ( $can_modify_this_page ): ?>
    <a class="button iconic green add_children_btn" href="/p/<?= $page->id ?>/<?= $page->uri ?>/add-page">
        <i class="icon-plus"></i>
        Вложенная страница
    </a>
<? endif; ?>
<? if (isset($files) && $files): ?>
    <div class="files">
    	<table class="page_files">
    		<? foreach ($files as $file): ?>
    			<tr>
    				<td class="ext"><span class="ext_tag"><?= $file['extension'] ?></span></td>
    				<td class="title"><?= $file['title'] ?></td>
    				<td>
    					<p class="size"><?= (int)$file['size'] < 1000 ? $file['size'] . PHP_EOL . 'КБ' : ceil($file['size'] / 1000) . PHP_EOL . 'МБ' ?></p>
    				</td>
    			</tr>
    		<? endforeach ?>
    	</table>
    </div>
<? endif; ?>
<div class="page_comments">

	<h3>Комментарии</h3>
	<? if($user->id): ?>
        <? if ($comments): ?>
            <? foreach ($comments as $comment): ?>
                <? if ($comment->parent_id != 0): ?>
                    <div class="comment_wrapper answer_wrapper">
                <? else: ?>
                    <div class="comment_wrapper">
                <? endif; ?>
                    <img class="comment_left" src="<?= $comment->author_photo ?>">
                    <div class="comment_right">
                        <h4>
                            <?= $comment->author_name ?>
                            <?= $comment->id ?>
                        </h4>
                        <span class="to_user">
                            <? if ($comment->parent_id != 0): ?>
                                <img src="/public/img/answer_arrow.png"> 
                                <?= $comment->parent_id ?>
                            <? endif; ?>
                        </span>
                        <time>
                            <?= date_format(date_create($comment->dt_create), 'd F Y') ?>
                        </time>
                        <p><?= $comment->text ?></p>
                        <a class="answer" onclick="answer(<?= $comment->id ?>, '<?= $comment->author_name ?>')">
                            <img src="/public/img/reply_icon.png">
                            Ответить
                        </a>
                        <? if ($user->id == $comment->author || $user->isAdmin): ?>
                            <a class="delete" href="/p/<?= $page->id ?>/<?= $page->uri ?>/delete-comment/<?= $comment->id ?>">
                                Удалить
                            </a>
                        <? endif; ?>
                    </div>
                    <div class="clearfix"></div>
                </div>
            <? endforeach; ?>
        <? else: ?>
            <p>Нет комментариев.</p>
        <? endif; ?>
    
        <form action="/p/<?= $page->id ?>/<?= $page->uri ?>/add-comment" method="POST" class="add_comment_form mt20">
            <textarea name="text" rows="6"></textarea>
            <input type="hidden" name="parent_id" value="0" id="answer_to_comment"/>
            <input id="comment_button" type="submit" value="Оставить комментарий" />
            <span id="comment_answer"></span>
        </form>
    <? else: ?>
        <p>Комментарии доступны только зарегистрированным пользователям.</p>
    <? endif; ?>
</div>
