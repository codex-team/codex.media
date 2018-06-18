<? if ($pages): ?>

    <? foreach ($pages as $index => $page): ?>

        <?/**
 * if the elem is the first
 * $page === reset($pages)
 */ ?>
        <article id="js-page-<?= $page->id ?>" class="island island--squared clearfix <?= $index != 0 ? 'island--margined' : '' ?> post-list-item post-list-item--cltofa <?= $page->rich_view ? 'post-list-item--big' : '' ?> <?= $page->dt_pin ? 'post-list-item_pinned' : '' ?><?= $page->cover ? 'posts-list-item--with-big-cover' : '' ?>">

            <div class="post-list-item__header">
                <? if ($user->isAdmin || $user->id == $page->author->id): ?>
                    <span class="island-settings js-page-settings" data-id="<?= $page->id ?>" data-module="islandSettings">
                        <module-settings hidden>
                            {
                                "selector" : ".js-page-settings",
                                "items" : [{
                                    "title" : "Редактировать",
                                    "handler" : {
                                        "module" : "pages",
                                        "method" : "openWriting"
                                    }
                                },
                                {
                                    "title" : "Вложенная страница",
                                    "handler" : {
                                        "module" : "pages",
                                        "method" : "newChild"
                                    }

                                },
                                <? if ($user->isAdmin): ?>
                                    {
                                        "title" : "Большая обложка",
                                        "handler" : {
                                            "module" : "pages",
                                            "method" : "cover",
                                            "submethod" : "toggleButton"
                                        },
                                        "arguments": "posts-list-item--with-big-cover"
                                    },
                                    {
                                        "title" : "Маленькая обложка",
                                         "handler" : {
                                            "module" : "pages",
                                            "method" : "cover",
                                            "submethod" : "toggleButton"
                                          },
                                         "arguments": "posts-list-item--with-small-cover"
                                    },
                                    <? if ($active_tab == Model_Feed_Pages::MAIN): ?>
                                        {
                                            "title" : "Закрепить",
                                            "handler" : {
                                                "module" : "pages",
                                                "method" : "pin",
                                                "submethod" : "toggle"
                                            }
                                        },
                                    <? endif; ?>
                                <? endif; ?>
                                {
                                    "title" : "Удалить",
                                    "handler" : {
                                        "module" : "pages",
                                        "method" : "remove"
                                    }
                                }]
                            }
                        </module-settings>
                        <? include(DOCROOT . 'public/app/svg/ellipsis.svg'); ?>
                    </span>
                <? endif ?>
                <a class="post-list-item__author" href="/user/<?= $page->author->id ?>">
                    <img src="<?= $page->author->photo ?>" alt="<?= $page->author->name ?>"><?= $page->author->shortName ?>
                </a>
                <time class="post-list-item__date">
                    <a href="<?= $page->url ?>" class="js-article-time">
                        <? if ($active_tab == Model_Feed_Pages::MAIN && $page->isPinned): ?>
                            Запись закреплена
                        <? else: ?>
                            <?= date_format(date_create($page->date), 'd F Y, G:i') ?>
                        <? endif; ?>
                    </a>
                </time>
            </div>

            <? if (!empty($page->cover)): ?>
                <a class="posts-list-item__cover" style="background-image:  url('/upload/pages/covers/o_<?= $page->cover ?>');" href="/p/<?= $page->id ?>/<?= $page->uri ?>">
                    <? include(DOCROOT . "public/app/svg/camera.svg") ?>
                </a>
            <? elseif ($user->isAdmin) : ?>
                <div class="posts-list-item__cover posts-list-item__cover--empty" onclick="codex.pages.cover.set(<?= $page->id ?>)">
                    <? include(DOCROOT . "public/app/svg/camera.svg") ?>
                </div>
            <? endif ?>


            <? if (!$page->rich_view && $page->description): ?>
                <div class="post-list-item__body js-emoji-included">
                    <?= $page->description ?>
                </div>
            <? endif ?>

            <div class="post-list-item__footer">
                <a class="post-list-item__comments" href="/p/<?= $page->id ?>/<?= $page->uri ?>" rel="nofollow">

                    <? if ($page->commentsCount > 0): ?>
                        <?= $page->commentsCount . PHP_EOL . $methods->num_decline($page->commentsCount, 'комментарий', 'комментария', 'комментариев'); ?>
                    <? else: ?>
                        Комментировать
                    <? endif ?>
                </a>
            </div>
        </article>

    <? endforeach; ?>

<? else: ?>

    <div class="island island--squared island--padded island--centered">
        <div class="empty-motivator">
            <? include(DOCROOT . "public/app/svg/article.svg") ?><br>
            <?= $emptyListMessage ?: 'Список статей пуст' ?>
        </div>
    </div>

<? endif ?>
