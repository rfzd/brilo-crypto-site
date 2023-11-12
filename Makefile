#!/usr/bin/make -f
ifneq (,$(wildcard ./.env))
	include .env
	export
endif

ifneq (,$(wildcard ./.env.local))
	include .env.local
	export
endif

@all:
	cat Makefile

build:
	[[ -f .env.local ]] || cp .env.local.example .env.local
	make build-app
	make up
	make init-app

build-app:
	docker compose build --no-cache

init-app:
	docker compose exec php composer install
	docker compose exec php npm install --frozen-lockfile
	docker compose exec php npm run dev

up:
	docker compose up -d

down:
	docker compose -f docker-compose.yml down --remove-orphans

shell: up
	docker compose exec php /bin/bash

open: up
	open "${APP_URL}" > /dev/null 2>&1

hadolint:
	@echo "Lint nginx Dockerfile"
	docker run --rm -i hadolint/hadolint:2.12.0-alpine < docker/nginx/Dockerfile
	@echo "Lint PHP Dockerfile"
	docker run --rm -i hadolint/hadolint:2.12.0-alpine < docker/php-fpm/Dockerfile
