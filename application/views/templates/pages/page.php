<article class="article island island--padded">

    <? if (!empty($page->parent->id)): ?>
        <div class="article__parent js-emoji-included">
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
                <?= $page->commentsCount . PHP_EOL . $methods->num_decline($page->commentsCount, 'комментарий', 'комментария', 'комментариев'); ?>
            <? else: ?>
                Комментировать
            <? endif ?>
        </a>

        <? /* Manage page buttons */ ?>
        <? if ($page->canModify($user)): ?>

            <span class="island-settings js-page-settings" data-id="<?= $page->id ?>">
                    <? include(DOCROOT . 'public/app/svg/ellipsis.svg'); ?>
            </span>

        <? endif ?>

    </header>

    <? /* Page title */ ?>
    <h1 class="article__title js-emoji-included">
    	<?= $page->title ?>
    </h1>

    <? /* Page content */ ?>
    <? if (!empty($page->blocks)): ?>
        <div class="article__content js-emoji-included">
            <? foreach ($page->blocks as $block): ?>
                <?=
                    View::factory('templates/editor/plugins/' . $block['type'], array(
                        'block' => $block['data']
                    ))->render();
                ?>
            <? endforeach; ?>
        </div>
    <? endif ?>

    <? /* Child pages */ ?>
    <? if ($page->children): ?>
        <ul class="children-pages">
            <? foreach ($page->children as $child): ?>
                <li class="children-pages__item">
                    <a class="children-pages__link" href="/p/<?= $child->id ?>/<?= $child->uri ?>">
                        <?= $child->title ?>
                    </a>
                </li>
            <? endforeach ?>
        </ul>
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

    <script>
        codex.docReady(function() {
            codex.islandSettings.init({
                    selector: '.js-page-settings',
                    items : [{
                            title : 'Редактировать',
                            handler : codex.pages.openWriting
                        },
                        {
                            title : 'Вложенная страница',
                            handler : codex.pages.newChild
                        },
                        <? if ($user->isAdmin()): ?>
                        {
                            title : '<?= $page->isMenuItem() ? 'Убрать из меню' : 'Добавить в меню'; ?>',
                            handler : codex.pages.addToMenu
                        },
                        {
                            title : '<?= $page->isNewsPage() ? 'Убрать из новостей' : 'Добавить в новости'; ?>',
                            handler : codex.pages.addToNews
                        },
                        <? endif; ?>
                        {
                            title : 'Удалить',
                            handler : codex.pages.remove
                    }]
                })
        })
    </script>

<? endif ?>

<?= View::factory('templates/comments/list', array(
    'page' => $page,
    'user' => $user,
    'emptyListMessage' => '<p>Станьте первым, кто оставит <br/> комментарий к данному материалу.</p>'
)); ?>
