<div class="grid-col grid-col--right">
    <? if ($user->id): ?>
        <div class="island">
            <?= View::factory('templates/components/user_panel')->render(); ?>
        </div>
    <? else: ?>
        <div class="island island--padded">
            <a class="button master" href="/auth">Войти на сайт</a>
        </div>
    <? endif ?>
</div>