<div class="island tabs"> 
        <a  class="profile-settings__profile-button-link"  href="/user/<?= $user->id ?>">
        <span>
            <? include(DOCROOT . "public/app/svg/arrow-left.svg") ?>
        </span>Профиль</a>
        <span class="profile-settings__logout"><a href = "/logout" data-title="Выйти" class="nav_chain">Выйти</a></span>
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

    <div class="profile_panel clear">

        <form class="base_form" method="POST" action="user/settings" enctype="multipart/form-data">
            <input type="hidden" name="csrf" value="<?= Security::token(); ?>" />
            
                <img class="profile-settings__ava" src="<?= $user->photo_medium ?>">

                    <!--      
                    <input type="file" name="new_ava" >
                    --> 
               
                <div class="profile-settings__block">
                    <label >Фамилия и Имя</label>
                    <input type="text" name="text" class="profile-settings__fio"></input>
                    <label class="profile-settings__about">О себе</label>
                    <textarea class="profile-settings__textaria" 
                    
                     "></textarea>
                </div>

                <div class="profile-settings__buttons">
                    <button class="button master">Сохранить изменения</button>
                </div>

            
        </form>

        <div class="profile__social-buttons">
       

            <? if ( $user->vk == ( NULL || '' ) ): ?>
                
                    <a href="//vk.com/vengerov1" target="_blank">
                    <span class="profile__social-button profile__social-button--vk">
                        <i class="icon-vkontakte"></i>
                        Привязать                     
                    </span>
                    </a>
                
            <? else: ?>
               
                    <a href="//vk.com/vengerov1" target="_blank">
                    <span class="profile__social-button profile__social-button--vk">
                        <i class="icon-vkontakte"></i>
                        <?= $viewUser->vk_uri ? $viewUser->vk_uri : $viewUser->vk_name ?>                  
                    </span>
                    </a>
                
            <? endif; ?>

            <? if ( $user->facebook == ( NULL || '' ) ): ?>
                
                  
                    <a href="/auth/fb?state=attach" target="_blank">
                    <span class="profile__social-button profile__social-button--facebook">
                        <i class="icon-facebook"></i>
                        Привязать                     
                    </span>
                    </a>
                

            <? else: ?>

                
                    <a href="/auth/fb?state=remove" target="_blank">
                    <span class="profile__social-button profile__social-button--facebook">
                        <i class="icon-facebook"></i>
                        <?= $viewUser->facebook_name ? $viewUser->facebook_name : $viewUser->name ?>                  
                    </span>
                    </a>
                

            <? endif; ?>

            <? if ( $user->twitter == ( NULL || '' ) ): ?>

                    <a href="/auth/fb?state=remove" target="_blank">
                    <span class="profile__social-button profile__social-button--facebook">
                        <i class="icon-twitter"></i>
                        Привязать                    
                    </span>
                    </a>                

            <? else: ?>
                
                    <a href="/auth/fb?state=remove" target="_blank">
                    <span class="profile__social-button profile__social-button--facebook">
                        <i class="icon-instagram"></i>
 
                        <?= $viewUser->twitter_name ? $viewUser->twitter_name : $viewUser->name ?>                  
                    </span>
                    </a>
                
            <? endif; ?>
            <!-- Для инстаграмма --> 
            <? if ( $user->twitter == ( NULL || '' ) ): ?>

               
                    <a href="/auth/fb?state=remove" target="_blank">
                    <span class="profile__social-button profile__social-button--facebook">
                        <i class="icon-facebook"></i>
                        Привязать                      
                    </span>
                    </a>
               

            <? else: ?>
                    <a href="/auth/fb?state=remove" target="_blank">
                    <span class="profile__social-button profile__social-button--facebook">
                        <i class="icon-facebook"></i>
                        Привязать                   
                    </span>
                    </a>
              
            <? endif; ?>

        
            </div>
        </div>
</div>

<?= View::factory('templates/components/email_confirm_island'); ?>

<script>
    codex.profileSettings.init();
</script>