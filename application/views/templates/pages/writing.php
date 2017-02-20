<div class="breadcrumb">

    <a class="nav_chain" href="/">Главная</a> »
    <span class="nav_chain">
        <?
    		$action = isset($page->id) && $page->id ? 'Редактирование' : 'Создание';
    		$object = $page->is_news_page ? 'новости' : 'страницы';

        	echo $action . ' ' . $object;
        ?>
</span>

</div>

<?= View::factory('templates/pages/form', array(
    'page'        => $page,
    'attachments' => $attachments,
)); ?>

<style>
    .grid-col--left  {display: none}
</style>