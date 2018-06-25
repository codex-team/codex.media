<?= View::factory('templates/components/about',[
        'title' => 'Our mission',
        'description'=>'Вообще "миссия" это текст 1000 знаков. Он важен для понимания нашей деятельности и наших ценностей. 
                        Поэтому он нужен в первую очередь... ',
        'link_text' => 'Read more about Culture of Art'
])->render(); ?>

<?/** add form for new page */ ?>
<? if ($user->id): ?>

    <?= View::factory('templates/pages/form_wrapper', [
        'hideEditorToolbar' => true
    ]); ?>

<? endif ?>
<? /***/ ?>

<div class="island tabs">
    <? foreach ($tabs as $tab): ?>
        <a class="tabs__tab <?= $active_tab == $tab['uri'] ? 'tabs__tab--current' : '' ?>" href="/<?= $tab['uri'] ?>">
            <?= $tab['label'] ?>
        </a>
    <? endforeach; ?>
</div>

<?/** pages list */ ?>
<div id="list_of_news" class="post-list">
    <? $emptyListMessage = $user->id ? 'Добавьте новую запись с помощью формы вверху страницы' : 'Здесь появятся новости и интересные публикации' ?>
    <?= View::factory('templates/pages/list', [
        'pages' => $pages,
        'emptyListMessage' => $emptyListMessage,
        'active_tab' => $active_tab,
        'events' => $events,
        'events_uri' => $events_uri,
        'total_events' => $total_events
    ]); ?>
</div>

<? if ($next_page): ?>
    <a class="button button--load-more island island--padded island--centered island--stretched" href="/<?= $page_number + 1 ?>" data-module="appender">
        Показать больше записей
        <module-settings hidden>
            {
                "currentPage" : "<?= $page_number ?>",
                "url" : "<?= $active_tab ? "/" . $active_tab . "/" : "/" ?>",
                "targetBlockId" : "list_of_news",
                "autoLoading" : "true",
                "dontWaitFirstClick" : "true"
            }
        </module-settings>
    </a>
<? endif ?>
