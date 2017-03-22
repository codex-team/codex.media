<div class="island writing-navigation__holder">

    <div class="writing-navigation">

        <a href="/" rel="nofollow">
            <img class="writing-navigation__back-icon" src="<?= $user->photo ?>">
            Главная
        </a>
        <? include(DOCROOT . "public/app/svg/arrow-right.svg") ?>
        <?
    		$action = isset($page->id) && $page->id ? 'Редактирование' : 'Создание';
    		$object = $page->is_news_page ? 'новости' : 'материала';

        	echo $action . ' ' . $object;
        ?>
    </div>

</div>

<div class="writing--fullscreen">
    <?= View::factory('templates/pages/form', array(
        'page' => $page
    )); ?>
</div>

<style>
    .grid-col--left  {
        display: none
    }
</style>
