<? if (!empty($_SERVER['ENABLE_GOV_FOOTER_BANNERS']) && $_SERVER['ENABLE_GOSUSLUGI_FEEDBACENABLE_GOV_FOOTER_BANNERSK_WIDGET']): ?>
  <style>
    .gov-footer-banners {
      display: flex;
      justify-content: center;
      gap: 20px;
      padding: 20px 0;
      max-width: 980px;
      margin: 0 auto;
      width: 100%;
    }

    .gov-footer-banners__item {
      display: block;
      width: 100%;
    }

    .gov-footer-banners__item img {
      width: 100%;
      height: 100%;
      object-fit: contain;
    }
  </style>

  <div class="gov-footer-banners">
    <a href="https://spbdeti.org/" class="gov-footer-banners__item">
      <img src="/public/app/img/banners/Лого УПР 1760х440.jpg" alt="Уполномоченный по правам ребенка">
    </a>
  </div>
<? endif; ?>
