# Developer's guide

To run a local CodeX Media you'll need:

- Docker and docker-compose
- Node.js and npm

## Setting up the environment

Follow the [deployment guide](deployment.md) to run a local CodeX Media.

## Build scripts and styles

Use webpack to build scripts and styles for CodeX Media.
Firstly you heed to install Node.js dependencies.
 
```shell
npm i
```

Build bundles . Run this command in separate terminal. Webpack will watch project files and rebuild bundles on changes.

```shell
npm run build
```

## Fix PHP code style

Run this command before committing changes.

```shell
docker exec -i codexmedia_php_1 composer csfix
```