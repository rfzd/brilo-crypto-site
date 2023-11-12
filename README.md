# Brilo Crypto Site

Shows price of BTC in EUR and USD currencies. Data is refreshed every 5 seconds.

## Docker

Running project in Docker is preferred way for local development.

### Prerequisites

- Installed [docker](https://www.docker.com/products/docker-desktop/)
- Installed make (for `Makefile`)

#### Install all necessary services, files etc.

Simply run:

```shell
make build
```

What this do?

- copy `.env.local.example` to `.env.local` (if not exists)
- build images (for nginx, php)
- start up all services
- install PHP dependencies - composer vendors
- install FE dependencies- npm vendors

#### Open application in browser

Simply run:

```shell
make open
```

### Make file

Show all available commands:

```shell
make
```
## Testing

```shell
composer test:unit # Unit tests
```

Run PHPStan tests:

```shell
composer phpstan
```

Run Linter tests:

```shell
composer phpcbf
```
