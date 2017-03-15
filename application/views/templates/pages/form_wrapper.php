<div class="writing-wrapper island">

    <div class="writing-wrapper__placeholder" onclick="codex.writing.openEditor(this, 'writingForm', 'writing-wrapper__placeholder--opened');">
        <img class="writing-wrapper__photo" src="<?= $user->photo ?>" alt="<?= $user->name ?>">
        <span class="writing-wrapper__placeholder-text">Написать заметку в блог</span>
        <span class="writing-wrapper__placeholder-name"><?= $user->name ?></span>
    </div>

    <span class="hide" id="writingForm">
        <?= View::factory('templates/pages/form', array(
            'hideEditorToolbar' => true
        )); ?>
    </span>

</div>
