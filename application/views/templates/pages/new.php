<div class="breadcrumb">

    <a class="nav_chain" href="/">Главная</a> »
    <span class="nav_chain">
        <?= isset($page->id) && $page->id ? 'Редактирование' : 'Создание' ?>
        <?= $page->type == Model_Page::TYPE_SITE_NEWS ? 'новости' : 'страницы' ?>
    </span>

</div>

<?= View::factory('templates/pages/form', array(
    'page' => $page
)); ?>