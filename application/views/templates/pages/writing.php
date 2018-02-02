<div class="island island--top-rounded writing-navigation__holder">

    <div class="writing-navigation">

        <a href="/user/<?= $user->id; ?>" rel="nofollow">
            <img class="writing-navigation__back-icon" src="<?= $user->photo ?>">
        </a>
        <a href="/" rel="nofollow">
            Главная
        </a>
        <?php include(DOCROOT . "public/app/svg/arrow-right.svg") ?>
        <?php
            $action = isset($page->id) && $page->id ? 'Редактирование' : 'Создание';
            $object = $page->isPageOnMain() ? 'новости' : 'материала';

            echo $action . ' ' . $object;
        ?>

    </div>

</div>

<div class="writing--fullscreen">
    <?= View::factory('templates/pages/form', [
        'page' => $page
    ]); ?>
</div>

<?php
/**
 * Hide ESIR-navigator
 */
?>
<style>
    .esir { display: none; }
</style>
