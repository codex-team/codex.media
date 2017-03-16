<div class="island tabs">
        <span class="nav_chain" style="padding: 20px 15px; margin-right: 430px;">
        <!--
        <img src="C:\OpenServer\domains\codex.edu\public\app\svg\arrow-left.svg">
        -->
       <i class="icon-vkontakte"></i>
        <a class="nav_chain" href="/user/<?= $user->id ?>">Профиль</a></span>
        <span style="padding: 20px 15px;margin-left: -15px; "><a href = "/logout" data-title="Выйти" class="nav_chain">Выйти</a></span>
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
            
                
                <img style="float: right;height: 100px;border-radius: 50%;margin-left: 30px;" src="<?= $user->photo_medium ?>" />

               <!-- <span class="button fileinput iconic">
                    <i class="icon-picture"></i> Изменить фотографию
                    <input type="file" name="new_ava">
                </span>-->
                <div style="width: 400px;">
                    <label >Фамилия и Имя</label>
                    <input type="text" name="text" style="margin-top: 10px; margin-bottom: 15px;"></input>

                    <label style="margin-top: 30px; margin-right: 69%;height: 100px;">О себе</label>
                    <textarea style="margin-top: 10px; margin-bottom: 20px; height: 65px;"></textarea>
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
                        vengerov1                   
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
                        Привязать                  
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
                        Привязать                   
                    </span>
                    </a>
                
            <? endif; ?>
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