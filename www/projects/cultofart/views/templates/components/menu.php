<? if (!empty($site_menu)): ?>

    <ul class="menu js-emoji-included" id="js-site-menu">
        <? foreach ($site_menu as $item): ?>

            <?
                if (!empty($item->cover)) {
                    $cover = '/upload/pages/covers/o_' . $item->cover;
                } else {
                    $cover = '/public/app/svg/default-page-icon.svg';
                }

                if ($item->parent->id == 0) {
                    $isParent = true;
                } else {
                    $isParent = false;
                }
            ?>

            <? if ($isParent): ?>
                <li class="menu__community-parent">
                    <a href="/p/<?= HTML::chars($item->id) ?>/<?= HTML::chars($item->uri) ?>">
                        <?= HTML::chars($item->title) ?>
                    </a>
                    <? if ($item->children): ?>
                        <ul>
                            <? foreach ($item->children as $child): ?>
                                <?
                                    if (!$child->isMenuItem()) {
                                        continue;
                                    }
                                    if (!empty($child->cover)) {
                                        $cover = '/upload/pages/covers/o_' . $child->cover;
                                    } else {
                                        $cover = '/public/app/svg/default-page-icon.svg';
                                    }
                                ?>

                                <li>
                                    <a href="/p/<?= HTML::chars($child->id) ?>/<?= HTML::chars($child->uri) ?>">
                                        <img src="<?= HTML::chars($cover) ?>" alt="<?= HTML::chars($child->title) ?>">
                                        <?= HTML::chars($child->title) ?>
                                    </a>
                                </li>
                            <? endforeach; ?>
                        </ul>
                    <?endif;?>
                </li>
            <? elseif (!$isParent && !$item->parent->isMenuItem()): ?>
                <li>
                    <a href="/p/<?= HTML::chars($item->id) ?>/<?= HTML::chars($item->uri) ?>">
                        <img src="<?= HTML::chars($cover) ?>" alt="<?= HTML::chars($item->title) ?>">
                        <?= HTML::chars($item->title) ?>
                    </a>
                </li>
            <? endif; ?>
        <? endforeach ?>
    </ul>
<? else: ?>
    <ul class="menu" id="js-site-menu"></ul>
<? endif; ?>
