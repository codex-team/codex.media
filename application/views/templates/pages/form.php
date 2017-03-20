<form class="writing island" action="/p/writing" id="atlasForm" method="post" name="atlas">

    <?
        /** if there is no information about page */
        if (!isset($page)) {
            $page = new Model_Page();
        }

        /** Name of object's type in genitive declension */
        // $object_name = $page->is_news_page ? 'новости' : 'страницы';
    ?>

    <?= Form::hidden('csrf', Security::token()); ?>
    <?= Form::hidden('id', $page->id); ?>
    <?= Form::hidden('id_parent', $page->id_parent); ?>

    <div class="writing__title-wrapper">
        <input class="writing__title" type="text" name="title" placeholder="Заголовок" value="<?= $page->title ?>" id="editorWritingTitle">
    </div>

    <div class="editor-wrapper" id="placeForEditor"></div>

    <div class="writing__actions clear">
        <div class="writing__actions-content">

            <span class="button master fl_r" onclick="codex.writing.submit()">Отправить</span>

            <? if (!empty($hideEditorToolbar) && $hideEditorToolbar): ?>
                <span class="button fl_r" onclick="codex.writing.openEditorFullscreen()">На весь экран</span>
            <? endif ?>

            <? /*
            <? if ($user->isAdmin): ?>

                <div class="toggler fl_l js-custom-checkbox <?= $page->dt_pin ? 'checked' : '' ?>" data-title="Закрепить новость">
                    <input type="checkbox" name="dt_pin" value="<?= $page->dt_pin ? $page->dt_pin : date('Y-m-d H:i:s') ?>" <?= isset($page->dt_pin) ? 'checked="checked"' : '' ?>/>
                    <i class="icon-pin"></i>
                </div>

                <div class="toggler fl_l js-custom-checkbox <?= $page->rich_view ? 'checked' : '' ?>" data-title="Важная новость">
                    <input type="checkbox" name="rich_view" value="1" <?= isset($page->rich_view) && $page->rich_view == 1 ? 'checked="checked"' : Arr::get($_POST, 'rich_view' , '') ?>/>
                    <i class="icon-megaphone"></i>
                </div>

                <div class="hidden toggler fl_l js-custom-checkbox <?= $page->is_menu_item ? 'checked' : '' ?>" data-title="Пункт меню">
                    <input type="checkbox" name="is_menu_item" value="1" <?= isset($page->is_menu_item) && $page->is_menu_item == 1 ? 'checked="checked"' : Arr::get($_POST, 'is_menu_item' , '') ?>/>
                    <i class="icon-star"></i>
                </div>

            <? endif ?>
            */ ?>

        </div>

    </div>

</form>

<script>

    /** Document is ready */
    codex.docReady(function(){

        <?
            $hideEditorToolbar = !empty($hideEditorToolbar) && $hideEditorToolbar;
        ?>

        var editorReady = codex.writing.prepare({
                hideEditorToolbar : <?= $hideEditorToolbar ? 'true' : 'false' ?>,
                items : <?= json_encode($page->blocks) ?: '[]' ?>,

                holderId : 'placeForEditor',
                pageId   : <?= $page->id ?>,
                parentId : <?= $page->id_parent ?>,
            });

        <? if (!$hideEditorToolbar): ?>;
            editorReady.then(
                codex.writing.init
            );
        <? endif ?>
    });

</script>
