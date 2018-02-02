<?php if ($pages): ?>

    <?php foreach ($pages as $index => $page): ?>

        <?php /**
            * if the elem is the first
            * $page === reset($pages)
            */ ?>
        <article id="js-page-<?= $page->id ?>" class="island <?= $index != 0 ? 'island--margined' : '' ?> post-list-item <?= $page->rich_view ? 'post-list-item--big' : '' ?> <?= $page->dt_pin ? 'post-list-item_pinned' : '' ?>">

            <div class="post-list-item__header">
                <?php if ($user->isAdmin || $user->id == $page->author->id): ?>
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
                                <?php if ($user->isAdmin): ?>
                                    {
                                        "title" : "Установить обложку",
                                        "handler" : {
                                            "module" : "pages",
                                            "method" : "cover",
                                            "submethod" : "toggleButton"
                                        }
                                    },
                                    <?php if ($active_tab == Model_Feed_Pages::MAIN): ?>
                                        {
                                            "title" : "Закрепить",
                                            "handler" : {
                                                "module" : "pages",
                                                "method" : "pin",
                                                "submethod" : "toggle"
                                            }
                                        },
                                    <?php endif; ?>
                                <?php endif; ?>
                                {
                                    "title" : "Удалить",
                                    "handler" : {
                                        "module" : "pages",
                                        "method" : "remove"
                                    }
                                }]
                            }
                        </module-settings>
                        <?php include(DOCROOT . 'public/app/svg/ellipsis.svg'); ?>
                    </span>
                <?php endif ?>
                <time class="post-list-item__date">
                    <a href="<?= $page->url ?>" class="js-article-time">
                        <?php if ($active_tab == Model_Feed_Pages::MAIN && $page->isPinned): ?>
                            Запись закреплена
                        <?php else: ?>
                            <?= date_format(date_create($page->date), 'd F Y, G:i') ?>
                        <?php endif; ?>
                    </a>
                </time>
                <a class="post-list-item__author" href="/user/<?= $page->author->id ?>">
                    <img src="<?= $page->author->photo ?>" alt="<?= $page->author->name ?>"><?= $page->author->name ?>
                </a>
            </div>

            <h3 class="post-list-item__title js-emoji-included">
                <a href="/p/<?= $page->id ?>/<?= $page->uri ?>">
                    <?= $page->title ?>
                </a>
            </h3>

            <?php if (!$page->rich_view && $page->description): ?>
                <div class="post-list-item__body js-emoji-included">
                    <?= $page->description ?>
                </div>
            <?php endif ?>

            <?php if (!empty($page->cover)): ?>
                <a class="posts-list-item__cover" style="background-image:  url('/upload/pages/covers/o_<?= $page->cover ?>');" href="/p/<?= $page->id ?>/<?= $page->uri ?>">
                    <?php include(DOCROOT . "public/app/svg/camera.svg") ?>
                </a>
            <?php elseif ($user->isAdmin) : ?>
                <div class="posts-list-item__cover posts-list-item__cover--empty" onclick="codex.pages.cover.set(<?= $page->id ?>)">
                    <?php include(DOCROOT . "public/app/svg/camera.svg") ?>
                </div>
            <?php endif ?>



            <div class="post-list-item__footer">
                <a class="post-list-item__comments" href="/p/<?= $page->id ?>/<?= $page->uri ?>" rel="nofollow">
                    <?php include(DOCROOT . "public/app/svg/comment-bubble.svg") ?>
                    <?php if ($page->commentsCount > 0): ?>
                        <?= $page->commentsCount . PHP_EOL . $methods->num_decline($page->commentsCount, 'комментарий', 'комментария', 'комментариев'); ?>
                    <?php else: ?>
                        Комментировать
                    <?php endif ?>
                </a>

                <span class="post-list-item__views">
                    <?php include(DOCROOT . "public/app/svg/eye.svg") ?>
                    <?= $page->views ?>
                </span>
            </div>
        </article>

    <?php endforeach; ?>

<?php else: ?>

    <div class="island island--padded island--centered">
        <div class="empty-motivator">
            <?php include(DOCROOT . "public/app/svg/article.svg") ?><br>
            <?= $emptyListMessage ?: 'Список статей пуст' ?>
        </div>
    </div>

<?php endif ?>
