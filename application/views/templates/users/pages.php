<div id="list_of_news" class="post-list">

    <?= View::factory('templates/pages/list', array(
        'pages'=> $user_pages,
        'emptyListMessage' => 'Тут появятся статьи и заметки'
    )); ?>

</div>

<? if (isset($next_page) && $next_page): ?>
    <a class="button button--load-more island island--padded island--centered island--stretched" id="buttonLoadNews" href="/<?= $page_number + 1 ?>">
        Показать больше новостей
    </a>
    <script>
        codex.docReady(function() {
            codex.appender.init({
                buttonId      : 'buttonLoadNews',
                currentPage   : '<?= $page_number ?>',
                url           : '<?= '/user/' . $user_id . '/pages/' ?>',
                targetBlockId : 'list_of_news',
                autoLoading   : true
            });
        });
    </script>
<? endif ?>