#!/usr/bin/env bash
set -euo pipefail

APP_DIR="/home/admin/domains/user.thaiprompt.online/laravel"
cd "$APP_DIR"

# --- Binaries (ปรับให้ตรงระบบคุณถ้าต่าง) ---
PHP="/usr/local/php83/bin/php"
COMPOSER="/usr/local/bin/composer"     # ใช้ `which composer` ตรวจ path หากไม่ตรง

echo "[PHP]"; $PHP -v

echo "[MAINTENANCE] app:down"
$PHP artisan down || true

echo "[GIT] pull origin main"
git pull origin main

# --- Composer: ติดตั้งแบบปลอดภัย (กัน post-scripts ทำให้ล้มกลางทาง) ---
echo "[COMPOSER] install (no-dev, no-scripts)"
$PHP $COMPOSER install --no-dev --no-interaction --prefer-dist --no-progress --no-scripts

echo "[COMPOSER] dump-autoload -o"
$PHP $COMPOSER dump-autoload -o

# --- Laravel: เคลียร์ก่อนแล้วค่อย discover (กันเคส provider พัง) ---
echo "[LARAVEL] optimize:clear"
$PHP artisan optimize:clear

echo "[LARAVEL] package:discover"
$PHP artisan package:discover --ansi || true

# --- Frontend (มี lockfile ใช้ npm ci; ไม่มีใช้ npm install; ไม่มี package.json ข้าม) ---
if [ -f package-lock.json ]; then
  echo "[NPM] ci"; npm ci
elif [ -f package.json ]; then
  echo "[NPM] install (no lockfile)"; npm install
else
  echo "[NPM] skip (no package.json)"
fi

if [ -f package.json ]; then
  echo "[VITE] build"; npm run build || true
fi

# --- สิทธิ์โฟลเดอร์ (กัน 500 เพราะเขียนไม่ได้) ---
echo "[PERM] fix storage & cache"
chown -R admin:admin storage bootstrap/cache || true
chmod -R 775 storage bootstrap/cache || true

# --- (ออปชัน) Migrate: เปิดใช้เมื่อมั่นใจ schema แล้วเท่านั้น ---
# echo "[DB] migrate --force"
# $PHP artisan migrate --force || true

# --- อุ่นแคชกลับ ---
echo "[CACHE] config/route/view cache"
$PHP artisan config:cache
$PHP artisan route:cache
$PHP artisan view:cache

echo "[MAINTENANCE] app:up"
$PHP artisan up

echo "✅ Deploy OK at $(date)"
