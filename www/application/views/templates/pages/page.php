<article class="article <?= !empty($isWide) ? 'article--wide' : '' ?> island island--padded" itemscope itemtype="http://schema.org/Article">

    <?
        $domainAndProtocol = Model_Methods::getDomainAndProtocol();
        $siteLogo = !empty($site_info['logo']) ? $domainAndProtocol . "/upload/logo/m_" . $site_info['logo'] : 'https://capella.pics/6161256a-324d-40fd-9f37-4efd9db84adc';
        $pageId = $domainAndProtocol . "/p/" . $page->id . "/" . $page->uri;

        if (!empty($page->cover)) {
            $articleCover = $domainAndProtocol . "/upload/pages/covers/o_" . $page->cover;
        } else {
            $articleCover = $domainAndProtocol . "/public/app/img/meta-image.png";
        }
    ?>

    <script type="application/ld+json">
        {
            "@context": "http://schema.org",
            "@type": "Article",
            "mainEntityOfPage": {
                "@type": "WebPage",
                "@id": "<?= $pageId ?>"
            },
            "headline": "<?= HTML::chars($page->title); ?>",
            "datePublished": "<?= date(DATE_ISO8601, strtotime($page->date)) ?>",
            "image": {
                "@type": "ImageObject",
                "url": "<?= $articleCover ?>"
            },
            "author": {
                "@type": "Person",
                "name": "<?= HTML::chars($page->author->name); ?>",
                "image": "<?= HTML::chars($page->author->photo); ?>"
            },
            "publisher": {
                "@type": "Organization",
                "name": "<?= $site_info['title'] ?>",
                "logo": {
                    "@type": "ImageObject",
                    "url": "<?= $siteLogo ?>"
                }
            }
        }
    </script>

    <meta itemprop="datePublished" content="<?= date(DATE_ISO8601, strtotime($page->date)) ?>" />

    <meta itemscope itemtype="http://schema.org/ImageObject" itemprop="image" itemref="coverUrl">
    <meta itemprop="url" id="coverUrl" content="<?= $articleCover ?>">

    <div itemscope itemtype="http://schema.org/Organization" itemprop="publisher">
        <meta itemprop="name" content="<?= $site_info['title'] ?>"/>
        <meta itemscope itemtype="http://schema.org/ImageObject" itemprop="logo" itemref="organizationImgUrl">
        <meta itemprop="url" content="<?= $siteLogo ?>" id="organizationImgUrl" />
    </div>

    <? /* Page info */ ?>
    <header class="article__information">

        <? if (!empty($isWide)): ?>
            <a class="site-head clear" href="/">
                <span class="site-head__logo <?= empty($site_info['logo']) ? 'site-head__logo--empty' : ''?>" data-placeholder="<?= mb_substr($site_info['title'], 0, 1, "UTF-8"); ?>">
                    <? if (!empty($site_info['logo'])): ?>
                        <img id="js-site-logo" src="/upload/logo/m_<?=  $site_info['logo'] ?>">
                    <? endif ?>
                </span>
                <div class="r_col site-head__title">
                    <?= $site_info['title'] ?><br>
                    <?= $site_info['city'] ?>
                </div>
            </a>
        <? endif; ?>

        <div class="article__information-section">
            <a class="article__author" href="/user/<?= $page->author->id ?>" itemscope itemtype="http://schema.org/Person" itemprop="author">
                <img src="<?= $page->author->photo ?>" alt="<?= HTML::chars($page->author->name); ?>" itemprop="image">
                <span class="article__author-name" itemprop="name">
                    <?= HTML::chars($page->author->shortName); ?>
                </span>
            </a>
            <time class="article__time">
                <a href="<?= $page->url ?>">
                    <?= $methods->ftime(strtotime($page->date)) ?>
                </a>
            </time>
        </div>

        <? /* Manage page buttons */ ?>
        <? if ($page->canModify($user)): ?>
            <span class="island-settings js-page-settings" data-id="<?= $page->id ?>" data-module="islandSettings">
                <module-settings hidden>
                    {
                        "selector" : ".js-page-settings",
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
    </header>

    <? if (!empty($page->parent->id)): ?>
        <div class="article__parent js-emoji-included">
            <a href="/p/<?= $page->parent->id ?>/<?= $page->parent->uri?>">
                <? include(DOCROOT . "public/app/svg/arrow-left.svg") ?>
                <?= HTML::chars($page->parent->title) ?>
            </a>
        </div>
    <? endif ?>

    <? /* Page title */ ?>
    <div class="article__title-wrapper">
        <h1 class="article__title js-emoji-included" itemprop="headline">
            <?= HTML::chars($page->title); ?>
        </h1>
    </div>

    <? /* Page content */ ?>
    <? if (!empty($page->blocks)): ?>
        <div class="article__content js-emoji-included" itemprop="articleBody">
            <? foreach ($page->blocks as $block): ?>
                <?= View::factory('templates/pages/blocks/' . $block['type'], ['block' => $block['data']])->render(); ?>
            <? endforeach; ?>
        </div>
    <? endif ?>

    <? /* Child pages */ ?>
    <? if ($page->children): ?>
        <ul class="children-pages">
            <? foreach ($page->children as $child): ?>
                <li class="children-pages__item">
                    <a class="children-pages__link" href="/p/<?= $child->id ?>/<?= $child->uri ?>">
                        <?= HTML::chars($child->title); ?>
                    </a>
                </li>
            <? endforeach ?>
        </ul>
    <? endif ?>

    <?= View::factory('templates/components/join-button') ?>

    <?= View::factory('templates/components/share', [
        'offer' => 'Если вам понравилась статья, поделитесь ссылкой на нее',
        'url' => 'https://' . Arr::get($_SERVER, 'HTTP_HOST', Arr::get($_SERVER, 'SERVER_NAME', 'edu.ifmo.su')) . '/p/' . $page->id,
        'title' => html_entity_decode($page->title),
        'desc' => ' ',
    ]); ?>

</article>

<? /* Comments block */ ?>
<? if ($user->id): ?>

    <div class="comment-form__island island island--margined clearfix" id="comments">
        <?= View::factory('templates/comments/form', ['page_id' => $page->id, 'user' => $user]); ?>
    </div>

<? endif ?>

<?= View::factory('templates/comments/list', [
    'page' => $page,
    'user' => $user,
    'emptyListMessage' => '<p>Станьте первым, кто оставит <br/> комментарий к данному материалу.</p>'
]); ?>
