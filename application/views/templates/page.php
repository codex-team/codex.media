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
<div class="page_comments" id="page_comments">
    
    <h3>Комментарии</h3>
    <? if ($comments): ?>
        <? foreach ($comments as $comment): ?>
            <div class="comment_wrapper <?= $comment->parent_comment['id'] ? 'answer_wrapper' : '' ?>" 
                 id="comment_<?= $comment->id ?>">
                <img class="comment_left" src="<?= $comment->author->photo ?>">
                <div class="comment_right">
                    <b>
                        <?= $comment->author->name ?>
                    </b>
                    <? if ($comment->parent_comment['id']): ?>
                        <span class="to_user">
                            <!-- Временная заглушка вместо шрифтовой иконки -->
                            <div class="dummy_icon"></div>
                            <?= $comment->parent_comment['author']->name ?>
                        </span>
                    <? endif; ?>
                    <time>
                        <?= date_format(date_create($comment->dt_create), 'd F Y') ?>
                    </time>
                    <p><?= $comment->text ?></p>
                    <a class="answer_button" onclick="comments.answer(<?= $comment->id ?>, 
                                                                      <?= $comment->root_id ?>,
                                                                      '<?= $comment->author->name ?>')">
                        <!-- Временная заглушка вместо шрифтовой иконки -->
                        <div class="dummy_icon"></div>
                        Ответить
                    </a>
                    <? if ($user->id == $comment->author->id || $user->isAdmin): ?>
                        <a class="delete_button" href="/p/<?= $page->id ?>/<?= $page->uri ?>/delete-comment/<?= $comment->id ?>">
                            Удалить
                        </a>
                    <? endif; ?>
                </div>
                    
            </div>
        <? endforeach; ?>
    <? else: ?>
        <p class="dummy_text">Здесь пока нет комментариев.</p>
    <? endif; ?>
    <? if($user->id): ?>    
        <form action="/p/<?= $page->id ?>/<?= $page->uri ?>/add-comment" id="comment_form" method="POST" class="comment_form mt20">
            <?= Form::hidden('csrf', Security::token()); ?>
            <textarea oninput="comments.enable_button()" id="text_field" name="text_field" rows="6"></textarea>
            <input type="hidden" name="parent_id" value="0" id="parent_id"/>
            <input type="hidden" name="root_id" value="0" id="root_id"/>
            <input id="comment_button" disabled type="submit" value="Оставить комментарий" />
            <span id="comment_answer" class="comment_answer"></span>
            <span class="cancel_answer" id="cancel_answer" onclick="comments.close_answer()"></span>
        </form>
    <? else: ?>
        <p class="dummy_text"><a href="/auth">Присоединяйтесь к сообществу</a>, чтобы оставлять комментарии.</p>
    <? endif; ?>
</div>
