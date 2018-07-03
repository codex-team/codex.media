<ul class="transparent-menu js-emoji-included" id="js-site-menu">
    <? if (!empty($site_menu)): ?>
        <? foreach ($site_menu as $item): ?>
                <li class="transparent-menu__section">
                    <a class="transparent-menu__section-label" href="/p/<?= HTML::chars($item['id']) ?>/<?= $methods->getUriByTitle($item['title']) ?>">
                        <?= HTML::chars($item['title']) ?>
                    </a>
                    <? if ($item['children']): ?>
                        <ul class="transparent-menu__subsection">
                            <? foreach ($item['children'] as $child): ?>
                                <li>
                                    <a class="transparent-menu__item" href="/p/<?= HTML::chars($child['id']) ?>/<?= $methods->getUriByTitle($child['title']) ?>">
                                        <img src="<?= !empty($child['cover ']) ? '/upload/pages/covers/b_' . $child['cover ']: '/public/app/svg/default-page-icon.svg' ?>" alt="<?= HTML::chars($child['title']) ?>">
                                        <?= HTML::chars($child['title']) ?>
                                    </a>
                                </li>
                            <? endforeach; ?>
                        </ul>
                    <?endif;?>
                </li>
        <? endforeach ?>
    <? endif; ?>
</ul>

