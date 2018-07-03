<ul class="aside-menu js-emoji-included" id="js-site-menu">
    <? if (!empty($site_menu)): ?>
        <? foreach ($site_menu as $item): ?>
            <li><a href="/p/<?= $item['id'] ?>/<?= $methods->getUriByTitle($item['title']) ?>"><?= $item['title'] ?></a></li>
            <? if (!empty($item['children'])): ?>
                <? foreach ($item['children'] as $child): ?>
                    <li><a href="/p/<?= $child['id'] ?>/<?= $methods->getUriByTitle($child['title']) ?>"><?= $child['title'] ?></a></li>
                <? endforeach; ?>
            <? endif; ?>
        <? endforeach ?>
    <? endif; ?>
</ul>
