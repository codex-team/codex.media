<div class="sharing">

    <span class="sharing__button sharing__button--vk js-share"
          data-share-type="shareVk"
          data-url="<?= $url ?>"
          data-title="<?= $title ?>"
          data-desc="<?= $desc ?>"
          title="Forward in VK">
        <i class="icon-vkontakte"></i>
        Нравится
    </span>

    <span class="sharing__button sharing__button--facebook js-share"
          data-share-type="shareFacebook"
          data-url="<?= $url ?>"
          data-title="<?= $title ?>"
          data-desc="<?= $desc ?>"
          title="Forward in Facebook">
        <i class="icon-facebook"></i>
        Share
    </span>

    <span class="sharing__button sharing__button--twitter js-share"
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