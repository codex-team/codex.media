<div class="island island--top-rounded writing-navigation__holder">

    <div class="writing-navigation">

        <a href="/user/<?= $user->id; ?>" rel="nofollow">
            <img class="writing-navigation__back-icon" src="<?= $user->photo ?>">
        </a>
        <a href="/" rel="nofollow">
            Главная
        </a>
        <? include(DOCROOT . "public/app/svg/arrow-right.svg") ?>
        <?
            $action = isset($page->id) && $page->id ? 'Редактирование' : 'Создание';
            $object = $page->isNewsPage() ? 'новости' : 'материала';

            echo $action . ' ' . $object;
        ?>

    </div>

</div>

<div class="writing--fullscreen">
    <?= View::factory('templates/pages/form', array(
        'page' => $page
    )); ?>
</div>
