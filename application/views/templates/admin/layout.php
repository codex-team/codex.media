<div class="w_island w_island_centercol">
	<h1 class="page_title">
		<?= $title ?>
	</h1>
	<div class="action-line action-line__onpage">
	    <a class="button" href="/admin/news/new">Добавить новость</a>
		<a class="button" href="/admin/pages/new">Создать страницу</a>
		<a class="button" href="/admin/files/new">Загрузить файл</a>
	</div>
	<? if ($form_saved): ?>
		<div class="form_saved">Изменения сохранены</div>
	<? endif ?>
	<? if (isset($error) && $error): ?>
		<div class="form_error"><?= $error ?></div>
	<? endif ?>
	<? if ( in_array($category, Controller_Admin::$categories) ): ?>

		<? if ( isset($pageId) && $pageId ) $category = 'page'; ?>
		<? if ( $category == 'news' ) $category = 'pages'; ?>
		<? include(APPPATH . '/views/templates/admin/' . $category . '.php'); ?>

	<? endif; ?>
</div>





<script src="/public/libs/redactor/9.2.2/redactor.min.js"></script>
<link rel="stylesheet" type="text/css" media="all" href="/public/libs/redactor/9.2.2/redactor.css">

<script>
	$(function(){
		user.renderRedactor();
	});
</script>