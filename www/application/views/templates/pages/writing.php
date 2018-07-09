<?= View::factory('templates/pages/form_type_selector', [
    'page' => $page
]); ?>

<div class="island island--bottom-rounded writing-navigation__holder">

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

<?
/**
 * Hide ESIR-navigator
 */
?>
<style>
    .esir { display: none; }
</style>
