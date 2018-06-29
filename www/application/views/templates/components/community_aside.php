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

        <?
            $userCanEdit = $page->canModify($user);
            $coverClasses = array("community-aside__cover");

            if (empty($page->cover)) {
                $coverClasses[] = "community-aside__cover--default";
            }

            if ($userCanEdit){
                $coverClasses[] = 'community-aside__cover--editable';
            }

            $logoAttrs = array(
                'href' => sprintf('/p/%s/%s', $page->id, $page->uri),
                'class' => implode(' ', $coverClasses),
                'title' => $userCanEdit ? 'Загрузить логотип' : $page->title,
            );

            if ($userCanEdit){
                $logoAttrs['data-module'] = 'avatarUploader';
            }

        ?>
        <a <?= HTML::attributes($logoAttrs) ?>>
            <? if ($userCanEdit): ?>
                <module-settings hidden>
                    {
                        "pageId": <?= $page->id ?>
                    }
                </module-settings>
            <? endif; ?>
            <img src="/upload/pages/covers/b_<?= $page->cover ?>">
        </a>
        <a href="/p/<?= $page->id ?>/<?= $page->uri ?>" class="community-aside__title">
            <?= $page->title ?>
        </a>
    </div>
</aside>
