<? if($user->status == Model_User::USER_STATUS_ADMIN): ?>
    <div class="breadcrumb">
        <a class="button green" href="/p/add-news">Добавить новость</a>
    </div>
<? endif ?>
<div id="list_of_news" class="news">
    
    <?= View::factory('templates/news_list', array(
        'pages'=> $pages
    )); ?>

</div>

<? if ($next_page): ?>

	<a class="load_more_button button" id="button_load_news" href="/<?= $page_number + 1 ?>">Показать больше новостей</a>

	<script>
		$(function(){
			news_loader.init({
				button_id		: 'button_load_news',
				current_page	: '<?= $page_number ?>',
				url				: '/',
				target_block_id : 'list_of_news',
			});
		});
	</script>
	
<? endif ?>