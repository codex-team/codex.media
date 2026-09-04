var banner = (function () {
    var bannerElement;
    var storageKey = "cookie-banner-accepted";
    var styleId = "cookie-banner-styles";

    function init() {
        if (window.localStorage.getItem(storageKey)) {
            return;
        }

        addStyles();
        renderBanner();
        showBanner();
    }

    function showBanner() {
        if (bannerElement) {
            bannerElement.style.display = "block";
        }
    }

    function hideBanner() {
        if (bannerElement) {
            bannerElement.remove();
            bannerElement = null;
        }

        window.localStorage.setItem(storageKey, "true");
    }

    function renderBanner() {
        bannerElement = document.createElement("div");

        bannerElement.className = "cookie-banner";

        bannerElement.innerHTML = `
      <div class="cookie-banner__content">
        <div class="cookie-banner__text">
          <p>
            Сайт использует сервис веб-аналитики Яндекс.Метрика посредством сбора файлов cookie в целях анализа взаимодействия посетителей,что позволяет нам улучшить работу сайта, повысить его эффективность и удобство, а также для авторизации на сайте.
          </p>
          <p>
            Продолжая пользоваться сайтом, вы соглашаетесь на использование файлов cookie и их обработку сервисом Яндекс.Метрика.
          </p>
          <p>
            Вы можете запретить использование файлов cookie в настройках вашего браузера. Однако в таком случае некоторые функции сайта могут быть недоступны.
          </p>
        </div>

        <button
          class="cookie-banner__button"
          type="button"
        >
          Понятно
        </button>
      </div>
    `;

        document.body.appendChild(bannerElement);

        var button = bannerElement.querySelector(".cookie-banner__button");

        button.addEventListener("click", hideBanner);
    }

    function addStyles() {
        if (document.getElementById(styleId)) {
            return;
        }

        var styleElement = document.createElement("style");

        styleElement.id = styleId;

        styleElement.textContent = `
      .cookie-banner {
        position: fixed;
        right: 20px;
        bottom: 20px;
        left: 20px;
        z-index: 9999;
      }

      .cookie-banner__content {
        max-width: 900px;
        margin: 0 auto;
        padding: 20px;
        box-sizing: border-box;

        background: #ffffff;
        border: 1px solid #dddddd;
        border-radius: 8px;

        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
      }

      .cookie-banner__text {
        margin: 0 0 15px;

        font-family: Arial, sans-serif;
        font-size: 14px;
        line-height: 1.5;
        color: #333333;
      }

      .cookie-banner__text p:first-child {
        margin-top: 0;
      }

      .cookie-banner__button {
        padding: 10px 16px;

        font-family: Arial, sans-serif;
        font-size: 14px;
        color: #ffffff;

        background: #000000;
        border: none;
        border-radius: 4px;

        cursor: pointer;
      }

      .cookie-banner__button:hover {
        opacity: 0.8;
      }

      @media (max-width: 600px) {
        .cookie-banner {
          right: 10px;
          bottom: 10px;
          left: 10px;
        }

        .cookie-banner__content {
          padding: 15px;
        }
      }
    `;

        document.head.appendChild(styleElement);
    }

    return {
        init: init
    };
})({});
