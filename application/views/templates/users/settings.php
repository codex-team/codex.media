<div class="island island--padded">

     <div class="island__navigation">
        <a href="/user/<?= $user->id ?>" class="island__navigation-item">
            <? include(DOCROOT . "public/app/svg/arrow-left.svg") ?>
            Профиль
        </a>
        <a href="/logout" class="island__navigation-item island__navigation-item--right">
            Выйти
        </a>
    </div>

    <? if ($success): ?>
        <div class="info_block align_c">
            Обновления сохранены
        </div>
    <? endif; ?>

    <? if ($error): ?>
        <div class="info_block align_c" style="background-color:#EBA4B5; color:#F7053E;">
            <? foreach ($error as $info): ?>
                <?= $info; ?>
            <? endforeach; ?>
        </div>
    <? endif; ?>

    <form class="profile-settings" method="POST" action="user/settings" enctype="multipart/form-data">

        <input type="hidden" name="csrf" value="<?= Security::token(); ?>" />

        <div class="profile-settings__photo">
            <div class="profile-settings__photo-hover" onclick="codex.transport.selectFile( event, type );">
                <? include(DOCROOT . "public/app/svg/camera.svg") ?>
            </div>
            <img src="<?= $user->photo_medium ?>">
        </div>


        <label class="profile-settings__label">Фамилия и Имя</label>
        <input class="profile-settings__input" type="text" name="text" />

        <label class="profile-settings__label">О себе</label>
        <textarea class="profile-settings__input"></textarea>

        <div class="profile-settings__button--save">
            <button class="button master">Сохранить изменения</button>
        </div>
    </form>

    <div class="profile-settings__social-buttons">
        <? if ( !$user->vk): ?>
            <a class="profile-settings__social-button profile-settings__social-button--vk:hover" href="//vk.com/<?= $user->vk_uri ?>" target="_blank">
                <i class="icon-vkontakte"></i>
                Привязать
            </a>
        <? else: ?>
            <a class="profile-settings__social-button profile-settings__social-button--vk" href="//vk.com/<?= $user->vk_uri ?>" target="_blank">
                <i class="icon-vkontakte"></i>
                <?= $user->vk_uri ? $user->vk_uri : $user->vk_name ?>
            </a>
        <? endif; ?>

        <? if ( !$user->facebook ): ?>
            <a class="profile-settings__social-button profile-settings__social-button--facebook:hover" href="/auth/fb" target="_blank">
                <i class="icon-facebook"></i>
                Привязать
            </a>
        <? else: ?>
            <a class="profile-settings__social-button profile-settings__social-button--facebook" href="//fb.com/<?= $user->facebook_name ?>" target="_blank">
                <i class="icon-facebook"></i>
                <?= $user->facebook_name ? $user->facebook_name : $user->name ?>
            </a>
        <? endif; ?>
        <? if ( !$user->twitter ): ?>
             <a class="profile-settings__social-button profile-settings__social-button--twitter:hover" href="/auth/fb?state=remove" target="_blank">
                <i class="icon-twitter"></i>
                Привязать
            </a>
        <? else: ?>
            <a class="profile-settings__social-button profile-settings__social-button--twitter" href="<?= $user->twitter_username ?>" target="_blank">
                <i class="icon-twitter"></i>
                <?= $user->twitter_name ? $user->twitter_name : $user->name ?>
            </a>
        <? endif; ?>
    </div>
</div>

<?= View::factory('templates/components/email_confirm_island'); ?>

<script>

    var changePic = function () {

            codex.transport.init({
                url : 'upload/3'
            });

        };

</script>
