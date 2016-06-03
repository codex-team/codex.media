<div>
    
    <ul>
        <li><?= !$is_teachers_list ? '<a href="/users/teachers">учителя</a>' : 'учителя' ?></li> 
        <li><?= $is_teachers_list ? '<a href="/users/">пользователи</a></li>' : 'пользователи' ?></li>
    </ul>

    <? if (count($users) == 0): ?>

        <p>Список пользователей пуст</p>        
    
    <? else: ?>

        <table class="p_table">
            <? foreach ($users as $user_table_row): ?>                
                    <tr>
                        <td>      
                            <a href="/user/<?= $user_table_row->id ?>">
                                <img  class="list_teacher_ava" src="<?= $user_table_row->photo ?>">
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
