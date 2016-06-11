<form class="atlas_form" action="/p/save" id="atlasForm" method="post" name="atlas">

    <?= Form::hidden('csrf', Security::token()); ?>
    <?= Form::hidden('type', $page->type); ?>
    <?= Form::hidden('id', $page->id); ?>
    <?= Form::hidden('id_parent', $page->id_parent); ?>

    <span class="import">
        <i class="icon-link"></i> Импортировать
    </span>

    <input class="title_input" type="text" name="title" placeholder="Заголовок новости">
    <textarea name="content" rows="5" placeholder="Содержание новости"></textarea>

    <div class="attaches" id="formAttaches"></div>

    <div class="actions clear">
        <span class="button main fl_r" onclick="codex.transport.submitAtlasForm()">Отправить</span>
        <div class="toggler fl_r"><i class="icon-megaphone"></i></div>
        <div class="toggler fl_r"><i class="icon-pin"></i></div>
        <span class="attach" onclick="codex.transport.selectFile(event, '<?= Controller_Transport::PAGE_FILE ?>')"><i class="icon-attach"></i>Прикрепить файл</span>
    </div>

</form>
<? /*
    <div class="mb30">
        <h4>Импортивать страницу</h4>
        <input type="text" name="url" id="parser_input_url" />
    </div>

    <? if (isset($errors['title']) &&  $errors['title']): ?>
        <div class="form_error align_c">
            <?= $errors['title'] ?>
        </div>
        <br>
    <? endif; ?>

    <form action="<? if (isset($page->id) && $page->id): ?>
                        /p/<?= $page->id ?>/<? if ($page->uri != ''): echo $page->uri; else: echo 'no-title'; endif ?>/edit
                  <? else: ?>
                    <? if (isset($page->parent->id) && $page->parent->id != 0) : ?>
                        /p/<?= $page->parent->id ?>/<?= $page->parent->uri ?>/add-page
                    <? else: ?>
                        /p/add-page
                    <? endif; ?>
                  <? endif; ?>" method="post">

        <?= Form::hidden('csrf', Security::token()); ?>
        <?= Form::hidden('type', $page->type); ?>
        <?= Form::hidden('id', $page->id); ?>
        <?= Form::hidden('id_parent', $page->id_parent); ?>

        <h4>Заголовок</h4>
        <div class="input_text mb30">
            <input type="text" name="title" id="page_form_title" value="<?= $page->title ?>" />
        </div>

        <h4>Содержание</h4>
            <textarea name="content" class="redactor" id="page_form_content" rows="7" >
                <?= $page->content ?>
            </textarea>

        <? if ($user->status == Model_User::USER_STATUS_ADMIN): ?>
            <div class="extra_settings mb30">
                <div class="checkbox dark <?= $page->is_menu_item ? 'checked' : '' ?>">
                    <i><input type="checkbox" id="is_menu_item" name="is_menu_item" value="1" <?= isset($page->is_menu_item) && $page->is_menu_item == 1 ? 'checked="checked"' : Arr::get($_POST, 'is_menu_item' , '') ?>/></i>
                    <label for="is_menu_item">Вынести в меню</label>
                </div>
                <? if ($page->type == Model_Page::TYPE_SITE_NEWS): ?>
                    <div class="checkbox dark <?= $page->rich_view ? 'checked' : '' ?>">
                        <i><input type="checkbox" id="rich_view" name="rich_view" value="1" <?= isset($page->rich_view) && $page->rich_view == 1 ? 'checked="checked"' : Arr::get($_POST, 'rich_view' , '') ?>/></i>
                        <label for="rich_view">Важная новость</label>
                    </div>
                    <div class="checkbox dark <?= $page->dt_pin ? 'checked' : '' ?>">
                        <i><input type="checkbox" id="dt_pin" name="dt_pin" value="<?= $page->dt_pin ? $page->dt_pin : date('Y-m-d H:i:s') ?>" <?= isset($page->dt_pin) ? 'checked="checked"' : '' ?>/></i>
                        <label for="dt_pin">Закрепить новость</label>
                    </div>
                <? endif ?>
            </div>
        <? endif ?>

        <input type="hidden" name="source_link" id="source_link" value="">

        <input class="mt20" type="submit" value="Опубликовать">

    </form>

    <div class="extra_settings mt30">
        <h4>Файлы</h4>
        <div class="form_error m20_0 hide" id="pageFileError">Превышен допустимый размер файла - 30 мб</div>
        <div class="form_error m20_0 hide" id="entityError">Файл слишком большой</div>
        <div class="add_file_form clear">

            <form onerror="alert('form');" class="ajaxfree" id="submitPageFile" method="post" enctype="multipart/form-data" target="transport" action="/ajax/file_transport" accept-charset="utf-8">

                <?= Form::hidden('csrf', Security::token()); ?>
                <?= Form::hidden('page_id', isset($page->id) ? $page->id : '0' ); # TODO ?>

                <div id="submit_file_button" class="fl_r button main hide" onclick="callback.savePageFile($(this))" data-id="<?= isset($page->id) ? $page->id : '0' ?>" data-loading-text="Загрузка">Сохранить файл</div>
                <div class="input_text fl_l"><input id="pageFileTitle" name="title" type="text" autocomplete="0" placeholder="Название" / ></div>

                <div class="r_col">
                    <div class="button green fileinput overflow_long">
                        <input type="file" name="file" id="pageFileUpload" />
                        <span class="button_text" data-default-text="Выбрать файл">Выбрать файл</span>
                    </div>
                </div>

            </form>
        </div>

        <table class="page_files">
            <? if (isset($files) && $files): ?>
                <? foreach ($files as $file): ?>
                    <?= View::factory('templates/admin/file_row' , array('file' => $file) );?>
                <? endforeach ?>
            <? endif ?>
        </table>
    </div>

*/ ?>