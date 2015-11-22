<h2 class="form_name">
<? if ( $page ):?>
	Редактирование
<? else:?>
	Создание
<? endif;?>	
<? switch ($page_type) {
	case 1: echo 'страницы'; break;
	case 2: echo 'новости'; break;
} ?>
</h2>

<form action="/admin/pages" method="post">

	<?= Form::hidden('csrf', Security::token()); ?>
	<?= Form::hidden('type', isset($page['type']) && $page['type'] ? $page['type'] : $page_type ); ?>
	<?= Form::hidden('id', isset($page['id']) ? $page['id'] : '' ); ?>

	<h4>Заголовок</h4>
	<div class="input_text mb30">
		<input type="text" name="title" value="<?= isset($page['title']) ? $page['title'] : Arr::get($_POST, 'title' , '') ?>" />
	</div>

	<div class="clear">
		<div class="w50 fl_l">
			<h4>URI</h4>
			<div class="input_text mb30">
				<input type="text" onkeydown="user.inputFilter($(this))" name="uri" value="<?= isset($page['uri']) ? $page['uri'] : Arr::get($_POST, 'uri' , '') ?>" />
			</div>
		</div>
		<div class="w50 fl_l">
			<h4 class="ml30">Родительская страница</h4>
			<div class="select ml30">
				<select name="id_parent">
					<option value="0" >Выберите страницу</option>
					<? if (isset($pages)): ?>
						<? foreach ($pages as $parent): ?>
							<option value="<?= $parent['id'] ?>" <?= isset($page['id_parent']) && $page['id_parent'] == $parent['id'] ? 'selected' : '' ?>><?= $parent['title'] ?></option>
						<? endforeach ?>
					<? endif ?>
				</select>
			</div>
		</div>
	</div>

	<h4>Содержание</h4>
	<textarea name="content" class="redactor" rows="7" >
		<?= isset($page['content']) ? $page['content'] : Arr::get($_POST, 'content' , '') ?>
	</textarea>

	<div class="extra_settings mb30 hide">
		<div class="checkbox dark">
			<i><input type="checkbox" id="is_menu_item" name="is_menu_item" value="1" <?= isset($page['is_menu_item']) && $page['is_menu_item'] == 1 ? 'checked="checked"' : Arr::get($_POST, 'is_menu_item' , '') ?>/></i>
			<label for="is_menu_item">Вынести в меню</label>
		</div>
		<h4 class="mt30">HTML content</h4>
		<textarea name="html_content" class="redactor extra" rows="7">
			<?= isset($page['html_content']) ? $page['html_content'] : Arr::get($_POST, 'html_content' , '') ?>
		</textarea>
	</div>

	<input class="mt20" type="submit" value="Опубликовать">

</form>

<? if ($page && $page['id'] ): ?>
	<div class="extra_settings mt30">
		<h4>Файлы</h4>
		<div class="form_error m20_0 hide" id="pageFileError">Превышен допустимый размер файла - 30 мб</div>
		<div class="form_error m20_0 hide" id="entityError">Файл слишком большой</div>
		<div class="add_file_form clear">
		    
		    <form onerror="alert('form');" class="ajaxfree" id="submitPageFile" method="post" enctype="multipart/form-data" target="transport" action="/ajax/file_transport" accept-charset="utf-8">

		    	<?= Form::hidden('csrf', Security::token()); ?>
				<?= Form::hidden('page_id', $page['id'] ); ?>

				<div id="submit_file_button" class="fl_r button main hide" onclick="callback.savePageFile($(this))" data-id="<?= $page['id'] ?>" data-loading-text="Загрузка">Сохранить файл</div>
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
<? endif; ?>
