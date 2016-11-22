# Модуль для активации контрастной версии сайта

CodeX.Special сделает информацию на вашем сайте более доступной для людей с ограниченными возможностями. Модуль прост в подключении и использовании.

## Инструкция

Сохраните и подключите скрипт

```html
<script src="/public/extensions/codex.special/codex-special.v.1.0.js"></script>
```

Для того, чтобы запустить модуль, достаточно вызвать метод init

```js
<script type="text/javascript">

	codexSpecial.init({});

</script>
```

Результат

<img width="515" alt="screen shot 2016-10-20 at 02 38 43" src="https://cloud.githubusercontent.com/assets/15259299/19541365/5f59894e-966e-11e6-937b-216984380db3.png">

## Дополнительные параметры запуска

### Положение панели на странице

По умолчанию скрипт создает панель инструментов в верхней части экрана. Вы можете задать собственное положение этой панели. Для этого передайте при инициализации в параметре `blockId` идентификатор обертки, в которую нужно вставить панель 

Например, чтобы встроить панель в блок с идентификатором `#panelHolder`:
```js
codexSpecial.init({
    blockId : 'panelHolder',
});
```

<img width="578" alt="screen shot 2016-10-20 at 03 15 52" src="https://cloud.githubusercontent.com/assets/15259299/19542000/908185b2-9673-11e6-8347-4714af3c8d17.png">

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
