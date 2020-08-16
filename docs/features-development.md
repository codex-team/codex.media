# Features development

Install and run the project by [general installation](general-installation.md) guide.

## Build scripts and styles

Use webpack to build scripts and styles for CodeX Media.

Build scripts and styles in development mode. Webpack will watch project files and rebuild bundles on changes.

Run these commands in separate terminals.

```shell
docker exec -i codexmedia_php_1 yarn build_js:watch
```

```shell
docker exec -i codexmedia_php_1 yarn build_css:watch
```

## Composer packages

Use `composer` utility inside `php` container.

```bash
docker exec -i codexmedia_php_1 composer install
```

```bash
docker exec -i codexmedia_php_1 composer require package_name
```

## Fix PHP code style

Run this command before committing changes.

```bash
docker exec -i codexmedia_php_1 composer csfix
```

