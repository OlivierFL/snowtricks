.PHONY: up
up:
	@docker-compose up -d
	symfony server:start -d

.PHONY: up-dev
up-dev:	up npm-dev

.PHONY: up-watch
up-watch:	up npm-watch

.PHONY: up-full
up-full:	up npm-watch mail

.PHONY: down
down:
	@symfony server:stop
	@docker-compose down

.PHONY: mail
mail:
	@maildev --hide-extensions STARTTLS

.PHONY: npm-install
npm-install:
	@npm install

.PHONY: npm-update
npm-update:
	@npm update

.PHONY: npm-watch
npm-watch:
	@npm run watch

.PHONY: npm-dev
npm-dev:
	@npm run dev

.PHONY: analyze
analyze:
	@codeclimate analyze assets src

.PHONY: composer-install
composer-install:
	@composer install

.PHONY: composer-update
composer-update:
	@composer update

.PHONY: js-dump-routes
js-dump-route:
	@bin/console fos:js-routing:dump --format=json --target=public/js/fos_js_routes.json

.PHONY: deps-install
deps-install: composer-install npm-install

.PHONY: deps-update
deps-update: composer-update npm-update
