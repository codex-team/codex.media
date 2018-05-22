<?php

class Model_Feed_VkPosts extends Model_Feed_Abstract
{
    const TYPE = 'vkPosts';

    public function __construct($type = self::TYPE, $prefix = '')
    {
        $key_prefix = Arr::get($_SERVER, 'REDIS_PREFIX', 'codex.org:');

        $this->timeline_key = $key_prefix . $type;

        parent::__construct($prefix);
    }

    /**
     * Добавляем элемент в фид, передав в score page_id со стены из паблика
     *
     * @param int $article_id
     * @param int $post_id
     *
     * @return bool|int
     */
    public function add($article_id, $post_id = null)
    {
        return parent::add($article_id, $post_id);
    }
}
