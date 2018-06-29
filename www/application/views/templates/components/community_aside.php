<?
    if (empty($page->cover)) {
        $defaultCoverClass = "community-aside__cover--default";
    } else {
        $defaultCoverClass = "";
    }
?>
<aside class="island main-aside">
    <div class="community-aside">
        <? /* Manage page-community buttons */ ?>
        <? if ($page->canModify($user)): ?>
            <span class="island-settings community-aside__island-settings js-community-settings" data-id="<?= $page->id ?>" data-module="islandSettings">
                <module-settings hidden>
                    {
                        "selector" : ".js-community-settings",
                        "items" : [
                            {
                                "title" : "Редактировать",
                                "handler" : {
                                    "module" : "pages",
                                    "method" : "openWriting"
                                }
                            },
                            {
                                "title" : "Вложенная страница",
                                "handler" : {
                                    "module": "pages",
                                    "method": "newChild"
                                }

                            },
                    <? if ($user->isAdmin()): ?>
                        {
                            "title" : "<?= $page->isMenuItem() ? 'Убрать из меню' : 'Добавить в меню'; ?>",
                            "handler" : {
                                "module" : "pages",
                                "method" : "addToMenu"
                            }
                        },
                        {
                            "title" : "<?= $page->isPageOnMain() ? 'Убрать с главной' : 'На главную'; ?>",
                            "handler" : {
                                "module" : "pages",
                                "method" : "addToMain"
                            }
                        },
                    <? endif; ?>
                    {
                                "title" : "Удалить",
                                "handler" : {
                                    "module" : "pages",
                                    "method" : "remove"
                                }
                            }
                        ]
                    }
                </module-settings>
                <? include(DOCROOT . 'public/app/svg/ellipsis.svg'); ?>
            </span>
        <? endif ?>
        <a href="/p/<?= $page->id ?>/<?= $page->uri ?>" class="community-aside__cover <?= $defaultCoverClass ?>">
            <img src="/upload/pages/covers/b_<?= $page->cover ?>">
        </a>
        <a href="/p/<?= $page->id ?>/<?= $page->uri ?>" class="community-aside__title">
            <?= $page->title ?>
        </a>
    </div>
</aside>
