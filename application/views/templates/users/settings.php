<div class="profile-settings__navigation">
    <a class="profile-settings__link--profile"  href="/user/<?= $user->id ?>">
        <? include(DOCROOT . "public/app/svg/arrow-left.svg") ?>
        Профиль
    </a>
    <a class="profile-settings__link--logout" href = "/logout" >
        Выйти
    </a>
</div>
<div class="island island--padded">
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
    <form method="POST" action="user/settings" enctype="multipart/form-data">
        <input type="hidden" name="csrf" value="<?= Security::token(); ?>" />
        <div class="profile-settings__user-photo">
            <div  class="profile-settings__camera" onclick="codex.upload.init();">
                <? include(DOCROOT . "public/app/svg/camera.svg") ?>
            </div>
            <img class="profile-settings__img" src="<?= $user->photo_medium ?>">    
        </div>  
        <div class="profile-settings__about">
            <label class="profile-settings__label--name ">
                Фамилия и Имя
            </label>
            <input class="profile-settings__input--name" type="text" name="text" ></input>
            <label class="profile-settings__label--user-info">
                О себе
            </label>
            <textarea class="profile-settings__textarea--user-info"></textarea>
        </div>
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
            <a class="profile-settings__social-button profile-settings__social-button--facebook" href="//fb.com/<?= $user->facebook_username ?>" target="_blank">
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

</script>