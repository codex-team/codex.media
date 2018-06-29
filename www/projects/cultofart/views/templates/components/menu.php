<? if (!empty($site_menu)): ?>

    <ul class="menu js-emoji-included" id="js-site-menu">
        <? foreach ($site_menu as $item): ?>

            <?
                if (!empty($item->cover)) {
                    $cover = '/upload/pages/covers/o_' . $item->cover;
                } else {
                    $cover = '/public/app/svg/default-page-icon.svg';
                }
            ?>

            <li>
                <a href="/p/<?= HTML::chars($item->id) ?>/<?= HTML::chars($item->uri) ?>">
                    <img src="<?= HTML::chars($cover) ?>" alt="<?= HTML::chars($item->title) ?>">
                </a>
                <a class="menu__community-item" href="/p/<?= HTML::chars($item->id) ?>/<?= HTML::chars($item->uri) ?>">
                    <?= HTML::chars($item->title) ?>
                </a>
            </li>
        <? endforeach ?>
    </ul>
<? else: ?>
    <ul class="menu" id="js-site-menu"></ul>
<? endif; ?>
