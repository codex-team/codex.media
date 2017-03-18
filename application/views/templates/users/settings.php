<div class="island island--padded"> 
    <a  class="profile-settings__profile-nav"  href="/user/<?= $user->id ?>">
        <? include(DOCROOT . "public/app/svg/arrow-left.svg") ?>
        Профиль
    </a>
    <span class="profile-settings__logout">
        <a href = "/logout" data-title="Выйти" class="nav_chain">
        Выйти</a>
    </span>
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

    <form class="base_form" method="POST" action="user/settings" enctype="multipart/form-data">

        <input type="hidden" name="csrf" value="<?= Security::token(); ?>" />
        <img class="profile-settings__ava" src="<?= $user->photo_medium ?>">
        
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
                <a href="//vk.com/<?= $user->vk_uri ?>" target="_blank">
                    <span class="profile__social-button profile__social-button--vk:hover">
                        <i class="icon-vkontakte"></i>
                        Привязать
                    </span>
                </a>    
            
            <? else: ?>
                <a href="//vk.com/<?= $user->vk_uri ?>" target="_blank">
                    <span class="profile__social-button profile__social-button--vk">
                        <i class="icon-vkontakte"></i>
                        <?= $user->vk_uri ? $user->vk_uri : $user->vk_name ?>                  
                    </span>
                </a>                
            
            <? endif; ?>

            <? if ( !$user->facebook ): ?>

                <a href="/auth/fb" target="_blank">
                    <span class="profile__social-button profile__social-button--facebook:hover">
                        <i class="icon-facebook"></i>
                        Привязать                     
                    </span>
                </a>

            <? else: ?>
                <a href="//fb.com/<?= $user->facebook_username ?>" target="_blank">
                    <span class="profile__social-button profile__social-button--facebook">
                        <i class="icon-facebook"></i>
                        <?= $user->facebook_name ? $user->facebook_name : $user->name ?>             
                    </span>
                </a>

            <? endif; ?>

            <? if ( !$user->twitter ): ?>

                <a href="/auth/fb?state=remove" target="_blank">
                    <span class="profile__social-button profile__social-button--twitter:hover">
                        <i class="icon-twitter"></i>
                        Привязать                    
                    </span>
                </a>                

            <? else: ?>

                <a href="<?= $user->twitter_username ?>" target="_blank">
                    <span class="profile__social-button profile__social-button--twitter">
                        <i class="icon-instagram"></i>
                        <?= $user->twitter_name ? $user->twitter_name : $user->name ?>                  
                    </span>
                </a>

            <? endif; ?> 
    </div>

</div>


<?= View::factory('templates/components/email_confirm_island'); ?>

<script>
    codex.profileSettings.init();
</script>