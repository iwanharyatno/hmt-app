#!/bin/bash
set -e

APP_CONTAINER="hmt_app"
VOLUME_NAME="test-hmt_app_code"

echo "🚀 Starting Laravel Docker redeploy process..."

echo "📦 Step 1: Stopping containers..."
docker compose down

echo "🧹 Step 2: Removing old app_code volume..."
APP_CODE_VOL=$(docker volume ls -q | grep app_code || true)
if [ -n "$APP_CODE_VOL" ]; then
  docker volume rm -f $APP_CODE_VOL
  echo "✅ Removed volume: $APP_CODE_VOL"
else
  echo "⚠️ No existing app_code volume found, skipping..."
fi

echo "🔨 Step 3: Rebuilding Laravel image (no cache)..."
docker compose build --no-cache hmt_app

echo "🔄 Step 4: Recreating containers..."
docker compose up -d

echo "🕓 Waiting for app container to stabilize..."
sleep 10

echo "⚙️ Step 5: Running artisan optimizations..."
docker exec -it $APP_CONTAINER composer install --no-dev --optimize-autoloader || true
docker exec -it $APP_CONTAINER php artisan migrate --force || true
docker exec -it $APP_CONTAINER php artisan config:cache
docker exec -it $APP_CONTAINER php artisan route:cache
docker exec -it $APP_CONTAINER php artisan view:cache

echo "✅ Deployment complete!"
docker ps --filter "name=$APP_CONTAINER"
