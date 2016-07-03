<div class="breadcrumb">

    <a class="nav_chain" href="/">Главная</a> »
    <span class="nav_chain">
        <?
    		$action = isset($page->id) && $page->id ? 'Редактирование' : 'Создание';
    		$object = $page->type == Model_Page::TYPE_SITE_NEWS ? 'новости' : 'страницы';

        	echo $action . ' ' . $object;
        ?>
    </span>

</div>

<?= View::factory('templates/pages/form', array(
    'page'   => $page,
    'action' => $action,
    'object' => $object,
)); ?>