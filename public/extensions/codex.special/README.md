# Модуль для активации контрастной версии сайта

CodeX.Special сделает информацию на вашем сайте более доступной для людей с ограниченными возможностями. Модуль прост в подключении и использовании.

## Инструкция

Сохраните файлы модуля и подключите js-скрипт.

```html
<script src="/public/extensions/codex.special/codex-special.v.1.0.2.min.js"></script>
```

Для того, чтобы запустить модуль, достаточно вызвать метод `init()`. Файл стилей будут подгружен автоматически.

```js
<script type="text/javascript">

	codexSpecial.init({});

</script>
```

Результат

<img width="515" alt="screen shot 2016-10-20 at 02 38 43" src="https://cloud.githubusercontent.com/assets/15259299/19541365/5f59894e-966e-11e6-937b-216984380db3.png">

## Дополнительные параметры

### Положение панели на странице

По умолчанию скрипт создает панель, которая привязана к верхней правой границе экрана.

Угол, в котором будет располагаться панель управления модулем, можно указать в параметре `position`.

Возможные значения: `top-left`, `bottom-right`, `bottom-left`, `top-right` (устанавливается по-умолчанию).
```js
codexSpecial.init({
    position : 'bottom-right',
});
```


Вы можете определить положение панели, указав в параметре `blockId` идентификатор обертки.

Например, чтобы встроить панель в блок с идентификатором `#panelHolder`:
```js
codexSpecial.init({
    blockId : 'panelHolder',
});
```

<img width="581" alt="screen shot 2016-12-13 at 18 51 13" src="https://cloud.githubusercontent.com/assets/15259299/21147396/e1ed1548-c165-11e6-8707-341676ee11c6.png">

### Мультиязычность

При необходимости можно инициализировать скрипт с параметром `lang`, определяющим язык для надписей на блоке.
Доступны `ru` и `en` для русского и английского языка.

 ```js
codexSpecial.init({
    lang : 'en',
});
```
<img width="501" alt="screen shot 2016-10-20 at 02 56 17" src="https://cloud.githubusercontent.com/assets/15259299/19541744/4e5dea7e-9671-11e6-854d-93d99ee240ec.png">


## Разработка
Репозиторий — https://github.com/codex-team/codex.special
Codex Team — https://ifmo.su
