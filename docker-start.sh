#!/bin/bash
set -e

echo "Writing .env..."
cat > /var/www/.env << ENVEOF
APP_NAME="${APP_NAME:-PawikanCare}"
APP_ENV="${APP_ENV:-production}"
APP_KEY="${APP_KEY}"
APP_DEBUG="${APP_DEBUG:-false}"
APP_URL="${APP_URL:-http://localhost}"

DB_CONNECTION="${DB_CONNECTION:-pgsql}"
DB_HOST="${DB_HOST}"
DB_PORT="${DB_PORT:-5432}"
DB_DATABASE="${DB_DATABASE}"
DB_USERNAME="${DB_USERNAME}"
DB_PASSWORD="${DB_PASSWORD}"
DB_SSLMODE="${DB_SSLMODE:-require}"

SESSION_DRIVER="${SESSION_DRIVER:-file}"
SESSION_LIFETIME=120
CACHE_STORE="${CACHE_STORE:-file}"
QUEUE_CONNECTION="${QUEUE_CONNECTION:-sync}"

LOG_CHANNEL="${LOG_CHANNEL:-stderr}"
LOG_LEVEL="${LOG_LEVEL:-error}"

SMS_GATEWAY_URL="${SMS_GATEWAY_URL}"
SMS_API_TOKEN="${SMS_API_TOKEN}"
SMS_SENDER_ID="${SMS_SENDER_ID}"
ENVEOF

echo ".env written. Clearing caches..."
php artisan config:clear
php artisan cache:clear

echo "Dropping all tables for clean migration..."
php artisan db:wipe --force || echo "db:wipe failed, continuing..."

echo "Running migrations..."
php artisan migrate --force

echo "Seeding database..."
php artisan db:seed --class=UserSeeder --force

echo "Caching config/routes/views..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "Starting server on port ${PORT:-10000}..."
php artisan serve --host=0.0.0.0 --port=${PORT:-10000}
