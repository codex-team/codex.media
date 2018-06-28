<?= View::factory('templates/components/about', [
    'title' => $page->title,
    'description' => $page->description,
    'link_text' => 'Read more',
    'page_uri' => '/p/' . $page->id
])->render(); ?>

<div class="island tabs island--margined">
    <a class="tabs__tab tabs__tab--current" href="<?= $page->url ?>">
        General
    </a>
</div>

<?= View::factory('templates/pages/list', [
    'pages' => $page->children,
    'emptyListMessage' => 'Тут появятся статьи и заметки',
    'active_tab' => '',
    'events' => $events,
    'events_uri' => $events_uri,
    'total_events' => $total_events
]); ?>
