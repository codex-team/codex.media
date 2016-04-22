<div>
    <? if (count($teachers) == 0): ?>
        <p>Учителей нет.</p>        
    <? else: ?>
    <h2 class="list_teacher_heading">Список учителей</h2>
        <table class="p_table">
            <? foreach ($teachers as $teacher): ?>                
                    <tr>
                        <td class="list_teacher_ava">                             
                                    
                        <? if (!empty($teacher->photo)): ?>
                            <img src="<?= $teacher->photo ?>">
                        <? endif ?>   
                                
                        </td>    
                        <td><?= $teacher->name ?></td>     
                        <? /*<td>Учитель начальных классов</td> */ ?>                                      
                    </tr>                
            <? endforeach; ?>
        </table>
    <? endif; ?>
</div>
