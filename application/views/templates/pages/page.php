<article class="article island island--padded" itemscope itemtype="http://schema.org/Article">

    <script type="application/ld+json">
        {
            "@context": "http://schema.org",
            "@type": "Article",
            "mainEntityOfPage": {
                "@type": "WebPage",
                "@id": "<?= Model_Methods::getDomainAndProtocol(); ?><?= "/p/" . $page->id . "/" . $page->uri ?>"
            },
            "headline": "<?= $page->title; ?>",
            "datePublished": "<?= date(DATE_ISO8601, strtotime($page->date)) ?>",
            "image": {
                "@type": "ImageObject",
                <? if (isset($page->cover)): ?>
                "url": "<?= Model_Methods::getDomainAndProtocol();?>/<?= "/upload/pages/covers/o_" . $page->cover ?>"
                <? else: ?>
                "url": "<?= Model_Methods::getDomainAndProtocol();?>/public/app/img/meta-image.png"
                <? endif; ?>
            },
            "author": {
                "@type": "Person",
                "name": "<?= $page->author->name ?>",
                "image": "<?= $page->author->photo ?>"
            },
            "publisher": {
                "@type": "Organization",
                "name": "<?= $site_info['title'] ?>",
                "logo": {
                    "@type": "ImageObject",
                    "url": "<?= Model_Methods::getDomainAndProtocol();?><?= "/upload/logo/m_" . $site_info['logo'] ?>"
                }
            }
        }
    </script>

    <meta itemprop="datePublished" content="<?= date(DATE_ISO8601, strtotime($page->date)) ?>" />
    <?  
        $domainAndProtocol = Model_Methods::getDomainAndProtocol();
        $siteLogo = $domainAndProtocol . "/upload/logo/m_" . $site_info['logo'];

        if (!empty($page->cover)) {

            $articleCover = $domainAndProtocol . "/upload/pages/covers/o_" . $page->cover;

        } else {

            $articleCover = $domainAndProtocol . "/public/app/img/meta-image.png";

        }

    ?>
    <meta itemscope itemtype="http://schema.org/ImageObject" itemprop="image" itemref="coverUrl">
    <meta itemprop="url" id="coverUrl" content="<?= $articleCover ?>">

    <div itemscope itemtype="http://schema.org/Organization" itemprop="publisher">
        <meta itemprop="name" content="<?= $site_info['title'] ?>"/>
        <meta itemscope itemtype="http://schema.org/ImageObject" itemprop="logo" itemref="organizationImgUrl">
        <meta itemprop="url" content="<?= $siteLogo ?>" id="organizationImgUrl" />
    </div>

    <? if (!empty($page->parent->id)): ?>
        <div class="article__parent js-emoji-included">
            <a href="/p/<?= $page->parent->id ?>/<?= $page->parent->uri?>">
                <? include(DOCROOT . "public/app/svg/arrow-left.svg") ?>
                <?= $page->parent->title ?>
            </a>
        </div>
    <? endif ?>

    <? /* Page info */ ?>
    <header class="article__information">

        <time class="article__time">
            <a href="<?= $page->url ?>">
                <?= $methods->ftime(strtotime($page->date)) ?>
            </a>
        </time>

        <a class="article__author" href="/user/<?= $page->author->id ?>" itemscope itemtype="http://schema.org/Person" itemprop="author">
            <img src="<?= $page->author->photo ?>" alt="<?= $page->author->name ?>" itemprop="image">
            <span itemprop="name"><?= $page->author->name ?></span>
        </a>

        <div class="article__information-right">

            <span class="article__views-counter">
                <? include(DOCROOT . "public/app/svg/eye.svg") ?>
                <?= $page->views ?>
            </span>

            <a class="article__comments-counter" href="<?= $page->url ?>#comments">
                <? include(DOCROOT . "public/app/svg/comment-bubble.svg") ?>
                <? /* ?>
                <? if ($page->commentsCount > 0): ?>
                    <?= $page->commentsCount . PHP_EOL . $methods->num_decline($page->commentsCount, 'комментарий', 'комментария', 'комментариев'); ?>
                <? else: ?>
                    Комментировать
                <? endif ?>
                <? */ ?>
                <?= $page->commentsCount ?>
            </a>

            <? /* Manage page buttons */ ?>
            <? if ($page->canModify($user)): ?>

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
                            }]
                        }
                    </module-settings>
                    <? include(DOCROOT . 'public/app/svg/ellipsis.svg'); ?>
                </span>

            <? endif ?>

        </div>

    </header>

    <? /* Page title */ ?>
    <h1 class="article__title js-emoji-included" itemprop="headline">
        <?= $page->title ?>
    </h1>

    <? /* Page content */ ?>
    <? if (!empty($page->blocks)): ?>
        <div class="article__content js-emoji-included" itemprop="articleBody">
            <? foreach ($page->blocks as $block): ?>
                <?=
                    View::factory('templates/pages/blocks/' . $block['type'], array(
                        'block' => $block['data']
                    ))->render();
                ?>
            <? endforeach; ?>
        </div>
    <? endif ?>

    <? /* Child pages */ ?>
    <? if ($page->children): ?>
        <ul class="children-pages">
            <? foreach ($page->children as $child): ?>
                <li class="children-pages__item">
                    <a class="children-pages__link" href="/p/<?= $child->id ?>/<?= $child->uri ?>">
                        <?= $child->title ?>
                    </a>
                </li>
            <? endforeach ?>
        </ul>
    <? endif ?>

    <?= View::factory('templates/components/share', array(
        'offer' => 'Если вам понравилась статья, поделитесь ссылкой на нее',
        'url'   => 'https://' . Arr::get($_SERVER, 'HTTP_HOST', Arr::get($_SERVER, 'SERVER_NAME', 'edu.ifmo.su')) . '/p/' . $page->id,
        'title' => html_entity_decode($page->title),
        'desc'  => ' ',
    )); ?>

</article>

<? /* Comments block */ ?>
<? if ($user->id): ?>

    <div class="comment-form__island island island--margined clearfix" id="comments">
        <?= View::factory('templates/comments/form', array('page_id' => $page->id, 'user' => $user)); ?>
    </div>

<? endif ?>

<?= View::factory('templates/comments/list', array(
    'page' => $page,
    'user' => $user,
    'emptyListMessage' => '<p>Станьте первым, кто оставит <br/> комментарий к данному материалу.</p>'
)); ?>
