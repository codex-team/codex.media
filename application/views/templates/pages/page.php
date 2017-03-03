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
                <a class="button iconic green" href="/p/writing?id=<?= $page->id ?>"><i class="icon-pencil"></i>Редактировать</a>
                <a class="button iconic green" href="/p/writing?parent=<?= $page->id ?>"><i class="icon-plus"></i>Вложенная страница</a>
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

    <div class="comment-form__island island island--margined clearfix">
        <?= View::factory('templates/comments/new-comment-form', array('page' => $page, 'user' => $user)); ?>
    </div>

    <script>

        codex.docReady(function(){

            /**
            * Comments module
            */
            codex.comments.init();

        });

    </script>

<? endif ?>

<?= View::factory('templates/comments/comments-list', array('page' => $page, 'user' => $user)); ?>
