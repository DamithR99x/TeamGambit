#!/bin/bash

# Create .env file from example values
cat > .env << 'EOF'
APP_NAME=SmartCart
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost:8080

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=smartcart
DB_USERNAME=smartcart
DB_PASSWORD=your_secure_password

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

MAIL_MAILER=smtp
MAIL_HOST=mailhog
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"
EOF

echo "Environment file created."
echo "Next steps:"
echo "1. Build and start Docker containers: docker-compose up -d"
echo "2. Install dependencies: docker-compose exec app composer install"
echo "3. Generate app key: docker-compose exec app php artisan key:generate"
echo "4. Run migrations: docker-compose exec app php artisan migrate --seed"
echo "5. Install frontend assets: docker-compose exec app npm install && npm run dev" 