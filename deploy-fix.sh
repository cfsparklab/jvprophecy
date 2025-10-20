#!/bin/bash
# Simple deployment script to fix 404 issues

echo "🚀 Deploying PDF Download Fix..."
echo ""

# Navigate to project directory
cd /var/www/html || exit 1

echo "✅ In directory: $(pwd)"
echo ""

# Clear all caches
echo "🧹 Clearing caches..."
php artisan route:clear
php artisan view:clear
php artisan cache:clear
php artisan config:clear

echo ""
echo "📋 Registering new routes..."
php artisan route:cache

echo ""
echo "🔄 Restarting services..."
sudo systemctl restart php-fpm
sudo systemctl restart nginx

echo ""
echo "✅ Deployment complete!"
echo ""
echo "🧪 Test URLs:"
echo "   View: https://jvprophecy.vincentselvakumar.org/prophecies/20?language=ta"
echo "   Direct Download: https://jvprophecy.vincentselvakumar.org/prophecies/20/direct-download?language=ta"
echo ""
echo "📱 Try downloading on mobile - should work now!"

