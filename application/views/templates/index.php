<div class="index_header clear">
    <div class="fl_r status clear">
        <i class="icon fl_l"></i>
        <div class="r_col">
            <div class="date">29 апреля, 15:41</div>
            <a href="/lessons">идет 2 урок</a>
        </div>
    </div>
    <?= $site_info->description ?>
</div>

<div class="recommend_block">
    Рекомендуем
    <a href="">Прием в ОУ</a>
    <a href="">Расписание уроков</a>
    <a href="/page/files">Файлообменник</a>
</div>

<div class="clear">

    <div class="fl_r page_right_col">
        <a class="banner" href="">Banner 200x240</a>


    </div>
    <div class="page_left_col">

        <div class="official_links">
            <a href="/page/official-docs">Официальные документы</a>
            <a href="">Приемные часы администрации</a>
        </div>


        <div class="news">

            <a class="button green" href="/page/add?type=<?= Controller_Pages::TYPE_NEWS ?>">Добавить новость</a>

            <? foreach ($pages as $page): ?>

                <article class="post">
                    <h2><a href="/page/<?= $page['id'] ?>"><?= $page['title'] ?></a></h2>
                    <div class="body">
                        <?= $page['content'] ?>
                    </div>
                    <time><?= date_format(date_create($page['date']), 'd F Y, G:i') ?></time>
                </article>

            <? endforeach; ?>

        </div>

    </div>

</div>