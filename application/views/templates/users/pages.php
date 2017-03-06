<div id="list_of_news" class="post-list">

    <?= View::factory('templates/posts_list', array(
        'pages'=> $userPages,
        'emptyListMessage' => 'Тут появятся статьи и заметки'
    )); ?>

</div>
