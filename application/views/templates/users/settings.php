<div class="island island--padded">

    <a  class="profile-settings__profile-nav"  href="/user/<?= $user->id ?>">
        <? include(DOCROOT . "public/app/svg/arrow-left.svg") ?>
        Профиль
    </a>
    <a href = "/logout" data-title="Выйти" class="nav_chain profile-settings__logout">
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

        <button type="button" name="profile_ava" class="profile__settings_ava-button" onclick=" /** тут короче надо транспорт ебашить */">
            
            <? include(DOCROOT . "public/app/svg/camera.svg") ?>
            
        </button>

        <img class="profile-settings__ava" src="<?= $user->photo_medium ?>">

        
        <!--
        <input type="file" name="profile_ava" class="">
        -->

        <div class="profile-settings__block">
            <label>
                Фамилия и Имя
            </label>
            <input type="text" name="text" class="profile-settings__fio"></input>
            <label class="profile-settings__about">
                О себе
            </label>
            <textarea class="profile-settings__textarea"></textarea>
        </div>
            
        <div class="profile-settings__buttons">
            <button class="button master">Сохранить изменения</button>
        </div>

    </form>

    <div class="profile__social-buttons">
            
        <? if ( !$user->vk): ?>
            <a class="profile__social-button profile__social-button--vk:hover" href="//vk.com/<?= $user->vk_uri ?>" target="_blank">
                <i class="icon-vkontakte"></i>
                Привязать
            </a>    
        <? else: ?>
            <a class="profile__social-button profile__social-button--vk" href="//vk.com/<?= $user->vk_uri ?>" target="_blank">
                <i class="icon-vkontakte"></i>
                <?= $user->vk_uri ? $user->vk_uri : $user->vk_name ?>                  
            </a>                        
        <? endif; ?>

        <? if ( !$user->facebook ): ?>

            <a class="profile__social-button profile__social-button--facebook:hover" href="/auth/fb" target="_blank">
                <i class="icon-facebook"></i>
                Привязать                     
            </a>

        <? else: ?>
            <a class="profile__social-button profile__social-button--facebook" href="//fb.com/<?= $user->facebook_username ?>" target="_blank">
                <i class="icon-facebook"></i>
                <?= $user->facebook_name ? $user->facebook_name : $user->name ?>             
            </a>

        <? endif; ?>

        <? if ( !$user->twitter ): ?>

             <a class="profile__social-button profile__social-button--twitter:hover" href="/auth/fb?state=remove" target="_blank">
                <i class="icon-twitter"></i>
                Привязать                    
            </a>                

        <? else: ?>

            <a class="profile__social-button profile__social-button--twitter" href="<?= $user->twitter_username ?>" target="_blank">
                <i class="icon-twitter"></i>
                <?= $user->twitter_name ? $user->twitter_name : $user->name ?>               
            </a>

        <? endif; ?> 

    </div>

</div>


<?= View::factory('templates/components/email_confirm_island'); ?>

<script>
    codex.profileSettings.init();
</script>