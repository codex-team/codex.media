<div id="list_of_comments">

    <?= View::factory('templates/comments/list', array(
        'user' => $user,
        'comments' => $userComments,
        'emptyListMessage' => '<p>Пользователь не оставил ни одного комментария.</p>'
    )); ?>

</div>
