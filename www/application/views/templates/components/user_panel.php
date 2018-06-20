<div class="user-panel clearfix">
    <a class="fl_r user-panel__logout" href="/logout" data-title="Выйти">
        <i class="icon-logout"></i>
    </a>
    <a href="/user/<?= $user->id ?>">
        <img src="<?= $user->photo ?>" name="js-img-updatable" />
        <span class="user-panel__name">
            <?= $user->shortName ?>
        </span>
    </a>
</div>