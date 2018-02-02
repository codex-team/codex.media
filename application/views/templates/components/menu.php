<?php if (!empty($site_menu)): ?>
    <ul class="menu js-emoji-included" id="js-site-menu">
        <?php foreach ($site_menu as $item): ?>
            <li><a href="/p/<?= $item->id ?>/<?= $item->uri ?>"><?= $item->title ?></a></li>
        <?php endforeach ?>
    </ul>
<?php else: ?>
    <ul class="menu" id="js-site-menu"></ul>
<?php endif; ?>
