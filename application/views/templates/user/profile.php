<div class="user_page">
	<div class="ava">
		<img src="<?= $viewUser->photo_medium ?>" />
	</div>
	<h1 class="name">
		<?= $viewUser->name ?>
		<?
			switch ($viewUser->status){
				case Controller_User::USER_STATUS_ADMIN 	: echo "[администратор]"; break;   # надо будет убрать, чтобы не светить админские профили
				case Controller_User::USER_STATUS_TEACHER 	: echo "[преподаватель]"; break;
				case Controller_User::USER_STATUS_BANNED 	: echo "[заблокирован]"; break;
		   	}
		?>
	</h1>
	<? if ($viewUser->vk): ?>
		<a href="//vk.com/<?= $viewUser->vk_uri ?>" target="_blank"><?= $viewUser->vk_name ? $viewUser->vk_name : $viewUser->vk_uri ?></a>
	<? endif; ?>
	<? if ($viewUser->facebook): ?>
		<a href="//fb.com/<?= $viewUser->facebook ?>" target="_blank"><?= $viewUser->facebook_name ? $viewUser->facebook_name : $viewUser->name ?></a>
	<? endif ?>
	<? if ($viewUser->twitter): ?>
		<a href="//twitter.com/<?= $viewUser->twitter_username ?>" target="_blank"><?= $viewUser->twitter_name ? $viewUser->twitter_name : $viewUser->name ?></a>
	<? endif ?>
</div>
<? if ($success): ?>
	<div class="info_block align_c">
		Обновления сохранены
	</div>
<? endif; ?>
<div class="profile_panel clear">
	<? if ($user->status == Controller_User::USER_STATUS_ADMIN): ?>
		<? if ($viewUser->status < Controller_User::USER_STATUS_TEACHER ): ?>
			<a class="button" href="/user/<?= $viewUser->id ?>?act=rise">Активировать аккаунт преподавателя</a>
		<? else: ?>
			<a class="button" href="/user/<?= $viewUser->id ?>?act=degrade">Отключить аккаунт преподавателя</a>
		<? endif ?>
		<? if ($viewUser->status !=  Controller_User::USER_STATUS_BANNED ): ?>
			<a class="button fl_r" href="/user/<?= $viewUser->id ?>?act=ban">Заблокировать пользователя</a>
		<? else: ?>
			<a class="button fl_r" href="/user/<?= $viewUser->id ?>?act=unban">Разблокировать пользователя</a>
		<? endif ?>
	<? endif ?>

	<? if (!$viewUser->vk && $viewUser->email): ?>
		<a class="button" href="/auth/vk?state=attach">Прикрепить профиль ВК</a>
	<? else: ?>
		<a class="button" href="/auth/vk?state=remove">Открепить профиль ВК</a>
	<? endif; ?>
	<? if (!$viewUser->facebook && $viewUser->email): ?>
		<a class="button" href="/auth/fb?state=attach">Прикрепить профиль FB</a>
	<? else: ?>
		<a class="button" href="/auth/fb?state=remove">Открепить профиль FB</a>
	<? endif; ?>
	<? if (!$viewUser->twitter && $viewUser->email): ?>
		<a class="button" href="/auth/tw?state=attach">Прикрепить профиль TW</a>
	<? else: ?>
		<a class="button" href="/auth/tw?state=remove">Открепить профиль TW</a>
	<? endif; ?>


	<h2>Страницы пользователя</h2>
	<ul>
	<? if($user->id == $viewUser->id && $user->status > Controller_User::USER_STATUS_STUDENT): ?>
		<a class="button green" href="/page/add?type=<?= Controller_Pages::TYPE_PAGE ?>&parent=0">Создать страницу</a>
	<? endif?>
	<? if ($userPages): ?>
		<? foreach ($userPages as $page): ?>
			<li><h3><a href="/page/<?= $page['id'] ?>"><?= $page['title'] ?></a></h3></li>
		<? endforeach; ?>
	<? else: ?>
		<li>Пусто</li>
	<? endif ?>
	</ul>
</div>
<?/*
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

	*/ ?>