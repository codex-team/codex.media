<?php

/**
 * Cache_Memcacheimp_Driver — усовершенствованный в части работы с тэгами драйвер для Memcached
 *
 * @author Kolger
 * Реализует метод работы с тэгами, описанный на странице http://www.smira.ru/2008/10/29/web-caching-memcached-5/
 */
class Cache_Memcacheimp
{
    protected $memcache;

    /**
     * Конструктор
     */
    public function __construct()
    {
        $this->memcache = Cache::instance('memcache');
    }

    /**
     * Метод find не поддеживается, но предусмотрен интерфейсом
     *
     * @param $tag
     *
     * @return exception
     */
    public function find($tag)
    {
        // Метод не поддерживается
        throw new BadMethodCallException();
    }

    /**
     * Возвращает значение ключа. В случае, если ключ не найден, или значения тэгов не совпадают (ключ сброшен) возвращает NULL.
     * Проверяет значения тэгов, хранящихся в ключах. В случае, если значения различаются ключ считается сброшенным.
     * Реализует метод работы с тэгами, описанный на странице http://www.smira.ru/2008/10/29/web-caching-memcached-5/
     *
     * @param $id
     *
     * @return NULL or data
     */
    public function get($id)
    {
        $value = $this->memcache->get($id);

        // Если ключ не найден - завершаемся и возвращает NULL
        if ($value === false) {
            return null;
        }

        // Если у значения есть тэги - обрабатываем им и проверяем, не изменилось ли их значение
        if (!empty($value['tags']) && count($value['tags']) > 0) {
            $expired = false;

            foreach ($value['tags'] as $tag => $tag_stored_value) {
                // Получаем значение текущее значение тэга
                $tag_current_value = $this->get_tag_value($tag);

                // И сравниваем это значение с тем, которое хранится в теле элемента кэша
                if ($tag_current_value != $tag_stored_value) {
                    // Если значение изменилось - считаем ключ не валидным
                    $expired = true;
                    break;
                }
            }

            // Если ключ не валидный - возвращаем NULL
            if ($expired) {
                return null;
            }
        }

        return isset($value['data']) ? $value['data'] : null;
    }

    /**
     * "Удаляет" тэг. А именно, увеличивает значение ключа tag_$tag на 1.
     * Используется для сброса всех ключей с тэгом $tag.
     * Реализует метод работы с тэгами, описанный на странице http://www.smira.ru/2008/10/29/web-caching-memcached-5/
     *
     * @param $tag
     *
     * @return
     */
    public function delete_tag($tag)
    {
        $key = "tag_" . $tag;
        $tag_value = $this->get_tag_value($tag);

        $this->set($key, microtime(true), null, 60 * 60 * 24 * 30);

        return true;
    }

    /**
     * Возвращает текущее значение тэга. В случае, если тэг не найден, создает его и возвращает значение 1.
     * Реализует метод работы с тэгами, описанный на странице http://www.smira.ru/2008/10/29/web-caching-memcached-5/
     *
     * @param $tag
     *
     * @return int
     */
    private function get_tag_value($tag)
    {
        $key = "tag_" . $tag;

        $tag_value = $this->get($key);

        if ($tag_value === null) {
            $tag_value = microtime(true);
            $this->set($key, $tag_value, null, 60 * 60 * 24 * 30);
        }

        return $tag_value;
    }

    /**
     * Добавляет ключ id со значением data, метками tags.
     * Реализует метод работы с тэгами, описанный на странице http://www.smira.ru/2008/10/29/web-caching-memcached-5/
     *
     * @param mixed $id
     * @param mixed $data
     * @param mixed $lifetime
     */
    public function set($id, $data, array $tags = null, $lifetime)
    {
        // Если заданы тэги — получаем их текущие значения в $key_tags
        if (!empty($tags)) {
            $key_tags = [];

            foreach ($tags as $tag) {
                $key_tags[$tag] = $this->get_tag_value($tag);
            }

            // Запоминаем $key_tags в элемент tags массива
            $key['tags'] = $key_tags;
        }

        $key['data'] = $data;

        return $this->memcache->set($id, $key, $lifetime);
    }

    /**
     * Удаляет ключ $id
     *
     * @param $id ID ключа
     * @param $tag Не используется, но предусмотрен интерфейсом
     * @param mixed $key
     *
     * @return bool
     */
    public function delete($key)
    {
        // Шлем запрос на удаление в драйвер memcached
        return $this->memcache->delete($key);
    }

    /**
     * Метод delete_expired не поддеживается, но предусмотрен интерфейсом
     *
     * @param $tag
     *
     * @return exception
     */
    public function delete_expired()
    {
        // Метод не поддерживается
        throw new BadMethodCallException();
    }
}
