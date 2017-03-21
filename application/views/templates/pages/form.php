<form class="writing island" action="/p/writing" id="atlasForm" method="post" name="atlas">

    <?
        /** if there is no information about page */
        if (!isset($page)) {
            $page = new Model_Page();
        }

        /** Name of object's type in genitive declension */
        $object_name = $page->is_news_page ? 'новости' : 'страницы';
    ?>

    <?= Form::hidden('csrf', Security::token()); ?>
    <?= Form::hidden('id', $page->id); ?>
    <?= Form::hidden('id_parent', $page->id_parent); ?>

    <div class="writing__title-wrapper">
        <input class="writing__title" type="text" name="title" placeholder="Заголовок <?= $object_name ?>" value="<?= $page->title ?>">
    </div>

    <?= View::factory('templates/pages/editor', array(
        'page' => $page,
        'hideEditorToolbar' => !empty($hideEditorToolbar) ? $hideEditorToolbar : false
    )); ?>

    <div class="writing__actions clear">
        <div class="writing__actions-content">

            <span class="button master fl_r" onclick = "document.forms.atlasForm.submit()">Отправить</span>

            <? if (!empty($hideEditorToolbar) && $hideEditorToolbar): ?>
                <span class="button fl_r" onclick="codex.transport.openEditorFullscrean()">На весь экран</span>
            <? endif ?>

        </div>

    </div>

</form>
