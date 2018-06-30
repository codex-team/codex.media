<? if (!empty($site_menu)): ?>
    <ul class="aside-menu js-emoji-included" id="js-site-menu">
        <? foreach ($site_menu as $item): ?>
            <li><a href="/p/<?= $item->id ?>/<?= $item->uri ?>"><?= $item->title ?></a></li>
        <? endforeach ?>
    </ul>
<? else: ?>
    <ul class="aside-menu" id="js-site-menu"></ul>
<? endif; ?>
