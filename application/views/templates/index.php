<? if($user->status == Model_User::USER_STATUS_ADMIN): ?>
    <?
        $page = new Model_Page();
        $page->type = Model_Page::TYPE_SITE_NEWS;
    ?>
    <?= View::factory('templates/pages/form', array(
        'page' => $page,
    )); ?>
<? endif ?>

<div id="list_of_news" class="news">

    <?= View::factory('templates/news_list', array(
        'pages'=> $pages
    )); ?>

</div>

<? if ($next_page): ?>

	<a class="load_more_button w_island" id="button_load_news" href="/<?= $page_number + 1 ?>">Показать больше новостей</a>

	<script>
		codex.documentIsReady(function() {
			codex.appender.init({
				button_id       : 'button_load_news',
				current_page    : '<?= $page_number ?>',
				url             : '/',
				target_block_id : 'list_of_news',
			});
		});
	</script>

<? endif ?>