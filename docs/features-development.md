# Features development

Install and run the project by [general installation](general-installation.md) guide.

For building frontend public files you need Node.js and Yarn.

## Build scripts and styles

Use webpack to build scripts and styles for CodeX Media.

Go to project's `www` directoy and install Node.js dependencies.
 
```bash
cd www
yarn
```

Build scripts and styles in development mode. Webpack will watch project files and rebuild bundles on changes.

Run these commands in separate terminal.

```shell
yarn build_js:watch
```

```shell
yarn build_css:watch
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

