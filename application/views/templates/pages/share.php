<div class="sharing">

    <span class="article-sharing__button article-sharing__button--vk"
          data-share-type="shareVk"
          data-url="<?= $url ?>"
          data-title="<?= $title ?>"
          data-desc="<?= $desc ?>"
          title="Forward in VK">
        <i class="icon-vkontakte"></i>
        Нравится
    </span>

    <span class="article-sharing__button article-sharing__button--facebook"
          data-share-type="shareFacebook"
          data-url="<?= $url ?>"
          data-title="<?= $title ?>"
          data-desc="<?= $desc ?>"
          title="Forward in Facebook">
        <i class="icon-facebook"></i>
        Share
    </span>

    <span class="article-sharing__button article-sharing__button--twitter"
          data-share-type="shareTwitter"
          data-url="<?= $url ?>"
          data-title="<?= $title ?>"
          data-desc="<?= $desc ?>"
          title="Forward in Twitter">
        <i class="icon-twitter"></i>
        Tweet
    </span>

</div>


<script>
    codex.sharer.init();
</script>