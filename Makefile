.PHONY: build install start stop restart shell artisan migrate seed npm fresh logs deploy

# ──────────────────────────────────────────
# Setup
# ──────────────────────────────────────────

build:
	docker compose build

## Bootstrap completo: installa Laravel, configura .env, migra, seed
install: build
	@echo "→ Installing Laravel via Composer..."
	docker compose run --rm -e COMPOSER_ALLOW_SUPERUSER=1 app bash -c "\
		composer create-project laravel/laravel /tmp/laravel --prefer-dist --no-interaction && \
		cp -rn /tmp/laravel/. /var/www/html/ && \
		rm -rf /tmp/laravel"
	@echo "→ Configuring .env..."
	@if [ ! -f .env ]; then cp .env.example .env; fi
	@echo "→ Generating app key..."
	docker compose run --rm app php artisan key:generate
	@echo "→ Starting services..."
	docker compose up -d
	@echo "→ Installing npm dependencies (+ sass, typescript)..."
	npm install
	npm install --save-dev sass typescript
	@echo "→ Building assets..."
	npm run build
	@echo "→ Running migrations and seed..."
	docker compose exec app php artisan migrate --seed
	@echo ""
	@echo "✓ Done! Site:  http://localhost:8080"
	@echo "✓ Admin: http://localhost:8080/admin/login"
	@echo "  (credenziali nel file .env: ADMIN_EMAIL / ADMIN_PASSWORD)"

# ──────────────────────────────────────────
# Runtime
# ──────────────────────────────────────────

start:
	docker compose up -d

stop:
	docker compose down

restart:
	docker compose restart app

logs:
	docker compose logs -f app

# ──────────────────────────────────────────
# Development helpers
# ──────────────────────────────────────────

shell:
	docker compose exec app bash

## Es: make artisan cmd="route:list"
artisan:
	docker compose exec app php artisan $(cmd)

migrate:
	docker compose exec app php artisan migrate

## Wipe database e ri-migra con seed
fresh:
	docker compose exec app php artisan migrate:fresh --seed

seed:
	docker compose exec app php artisan db:seed

## Comandi da eseguire sull'hosting remoto dopo ogni deploy (migrate + storage link)
deploy:
	php artisan migrate --force
	php artisan storage:link
	php artisan config:cache
	php artisan route:cache
	php artisan view:cache

## Es: make npm cmd="run dev"
npm:
	npm $(cmd)
