test:
	php artisan test
install:
	composer install
	@test -f .env || cp .env.example .env
dockerUp:
	docker compose up -d
dockerDown:
	docker compose down -v