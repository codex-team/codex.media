<div>
    <? if (count($users) == 0): ?>
        <p>Учителей нет.</p>        
    <? else: ?>
    <h2 class="list_teacher_heading">Список учителей</h2>
        <table class="p_table">
            <? foreach ($users as $user_table_row): ?>                
                    <tr>
                        <td class="list_teacher_ava">                             
                                    
                        <? if (!empty($user_table_row->photo)): ?>
                            <img src="<?= $user_table_row->photo ?>">
                        <? endif ?>   
                                
                        </td>    
                        <td><?= $user_table_row->name ?></td>     
                        <? /*<td>Учитель начальных классов</td> */ ?>                                      
                    </tr>                
            <? endforeach; ?>
        </table>
    <? endif; ?>
</div>
