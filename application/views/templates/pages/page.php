<article class="article island island--padded">

    <? if (!empty($page->parent->id)): ?>
        <div class="article__parent">
            <a href="/p/<?= $page->parent->id ?>/<?= $page->parent->uri?>">
                <? include(DOCROOT . "public/app/svg/arrow-left.svg") ?>
                <?= $page->parent->title ?>
            </a>
        </div>
    <? endif ?>

    <? /* Page info */ ?>
    <header class="article__information">

        <time class="article__time">
            <a href="<?= $page->url ?>">
                <?= $methods->ftime(strtotime($page->date)) ?>
            </a>
        </time>

        <a class="article__author" href="/user/<?= $page->author->id ?>">
            <img src="<?= $page->author->photo ?>" alt="<?= $page->author->name ?>">
            <?= $page->author->name ?>
        </a>

        <a class="article__comments-counter" href="<?= $page->url ?>#comments">
            <? include(DOCROOT . "public/app/svg/comment.svg") ?>
            <? if ($page->commentsCount > 0): ?>
                <?= count($page->commentsCount) . PHP_EOL . $methods->num_decline($page->commentsCount, 'комментарий', 'комментария', 'комментариев'); ?>
            <? else: ?>
                Комментировать
            <? endif ?>
        </a>

    </header>

    <? /* Page title */ ?>
    <h1 class="article__title">
    	<?= $page->title ?>
    </h1>

    <? /* Page content */ ?>
    <? if ($page->blocks_array): ?>
        <div class="article__content">
            <? for ($i = 0; $i < count($page->blocks_array); $i++): ?>
                <?= $page->blocks_array[$i]; ?>
            <? endfor ?>
        </div>
    <? endif ?>

    <? /* Child pages */ ?>
    <? if ($page->childrens): ?>
        <ul class="children-pages">
            <? foreach ($page->childrens as $children): ?>
                <li class="children-pages__item">
                    <a class="children-pages__link" href="/p/<?= $children->id ?>/<?= $children->uri ?>">
                        <?= $children->title ?>
                    </a>
                </li>
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

    <?= View::factory('templates/components/share', array(
        'offer' => 'Если вам понравилась статья, поделитесь ссылкой на нее',
        'url'   => 'https://' . Arr::get($_SERVER, 'HTTP_HOST', Arr::get($_SERVER, 'SERVER_NAME', 'edu.ifmo.su')) . '/p/' . $page->id,
        'title' => html_entity_decode($page->title),
        'desc'  => ' ',
    )); ?>

</article>

<? /* Comments block */ ?>
<? if ($user->id): ?>

    <div class="comment-form__island island island--margined clearfix" id="comments">
        <?= View::factory('templates/comments/form', array('page_id' => $page->id, 'user' => $user)); ?>
    </div>

<? endif ?>

<?= View::factory('templates/comments/list', array(
    'page' => $page,
    'user' => $user,
    'emptyListMessage' => '<p>Станьте первым, кто оставит <br/> комментарий к данному материалу.</p>'
)); ?>
