<h1 class="site_category_title">
	<? if ( isset($page->id) ):?>
		Редактирование
	<? else:?>
		Создание
	<? endif;?>
	<? switch ($page->type) {
		case Controller_Pages::TYPE_SITE_PAGE : echo 'страницы'; break;
		case Controller_Pages::TYPE_SITE_NEWS : echo 'новости'; break;
	} ?>
</h1>

<div class="page_form">

	<form action="/page/add" method="post">

		<?= Form::hidden('csrf', Security::token()); ?>
		<?= Form::hidden('type', Arr::get($page, 'type', Arr::get($_POST, 'type', Controller_Pages::TYPE_USER_PAGE))); ?>
		<?= Form::hidden('id', Arr::get($page, 'id',  '')); ?>
		<?= Form::hidden('id_parent', Arr::get($page, 'id_parent', '0')); ?>

		<h4>Заголовок</h4>
		<div class="input_text mb30">
			<input type="text" name="title" value="<?= Arr::get($page, 'title', Arr::get($_POST, 'title' , '')) ?>" />
		</div>

		<div class="clear">
			<h4>URI</h4>
			<div class="input_text mb30">
				<input type="text" onkeydown="user.inputFilter($(this))" name="uri" value="<?= Arr::get($page, 'uri', Arr::get($_POST, 'uri' , '')) ?>" />
			</div>
		</div>

		<h4>Содержание</h4>
			<textarea name="content" class="redactor" rows="7" >
				<?= Arr::get($page, 'content', Arr::get($_POST, 'content' , '')) ?>
			</textarea>

		<? if ($user->status == Controller_User::USER_STATUS_ADMIN): ?>
			<div class="extra_settings mb30">
				<div class="checkbox dark">
					<i><input type="checkbox" id="is_menu_item" name="is_menu_item" value="1" <?= isset($page->is_menu_item) && $page->is_menu_item == 1 ? 'checked="checked"' : Arr::get($_POST, 'is_menu_item' , '') ?>/></i>
					<label for="is_menu_item">Вынести в меню</label>
				</div>
			</div>
		<? endif ?>

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
			<script>
				$(".editable").live("click", function(){
					if ( !$(this).data("toggled") ){
						var block = $(this),
							text  = block.html(),
							id 	  = block.data('id'),
							start_value = String.trim(text) || '',
							input = $('<input type="text" name="type" value="' + start_value + '"/>').bind('keypress', function(e){
								if( e.keyCode == 13 ){
									submitTitle(id, $(this).val(), block);
								}
							});
						input.blur(function(){
							submitTitle(id, $(this).val(), block);
						});
						block.html(input).data("toggled", true);
						input.focus();
					}
				});

				function submitTitle( fid, title , block ){

					simpleAjax.call({
						type: 'post',
						url: '/ajax/edit_file/title',
						data: { 'fid' : fid, 'title' : title },
						success: function(response){
							cLog(response.result , 'File updating');
							if (response.new_title) {
								block.find("input").remove();
								block.html(response.new_title).data("toggled", false);
							};
						}
					});
				}

				function editFile( action , fid, $this ){

					simpleAjax.call({
						type: 'post',
						url: '/ajax/edit_file/' + action,
						data: { 'fid' : fid },
						success: function(response){
							cLog(response.result , 'File removing');
							if (response.result == 'ok') {

								if (action == 'remove') {
									$this.parents('tr').addClass('removed');
									$this.addClass('hide');
									$this.next('.rollback').removeClass('hide');
								} else {
									$this.parents('tr').removeClass('removed');
									$this.addClass('hide');
									$this.prev('.remove').removeClass('hide');
								};

							};
						}
					});
				}
			</script>
		</table>

	</div>


</div>








<script src="/public/libs/redactor/9.2.2/redactor.min.js"></script>
<link rel="stylesheet" type="text/css" media="all" href="/public/libs/redactor/9.2.2/redactor.css">

<script>
	$(function(){
		user.renderRedactor();
	});
</script>