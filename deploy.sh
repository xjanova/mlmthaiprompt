#!/usr/bin/env bash
set -e
cd /home/admin/domains/user.thaiprompt.online/laravel
echo "[1] git pull"
git pull origin main
echo "[2] composer install"
composer install --no-dev --optimize-autoloader
echo "[3] migrate"
php artisan migrate --force || true
echo "[4] build assets"
npm ci && npm run build || true
echo "[5] optimize cache"
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
echo "Deploy OK at $(date)"
