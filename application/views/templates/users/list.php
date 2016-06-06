<div class="list_users_heading">
    <ul class="page_menu">
        <li><?= $status == Model_User::USER_STATUS_TEACHER ? '<a href="/users/">Пользователи</a></li>' : 'Пользователи' ?></li>
        <li><?= $status != Model_User::USER_STATUS_TEACHER ? '<a href="/users/teachers">Учителя</a>' : 'Учителя' ?></li>
    </ul>
</div>

<div>    

    <? if (count($users) == 0): ?>

        <p>Список пользователей пуст</p>        
    
    <? else: ?>

        <table class="p_table">
            <? foreach ($users as $user_table_row): ?>                
                    <tr>
                        <td class="ava">      
                            <a href="/user/<?= $user_table_row->id ?>">
                                <img class="list_teacher_ava" src="<?= $user_table_row->photo ?>">
                            </a>     
                        </td>    
                        <td>
                            <a href="/user/<?= $user_table_row->id ?>">
                                <?= $user_table_row->name ?>
                            </a>    
                        </td>                                        
                    </tr>                
            <? endforeach; ?>
        </table>

    <? endif ?>

</div>
