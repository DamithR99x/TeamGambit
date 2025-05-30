# Docker Setup for SmartCart

This document provides detailed information about the Docker configuration used in the SmartCart application.

## Docker Architecture

The SmartCart application uses a multi-container Docker setup with the following services:

- **app**: PHP Laravel application with Livewire
- **web**: Nginx web server
- **mysql**: MySQL database server
- **redis**: Redis for caching (optional)
- **mailhog**: For testing email functionality (optional)

## docker-compose.yml

Below is the `docker-compose.yml` file used for the SmartCart application:

```yaml
version: '3.8'

services:
  app:
    build:
      context: .
      dockerfile: Dockerfile
    container_name: smartcart-app
    restart: unless-stopped
    working_dir: /var/www/
    volumes:
      - ./:/var/www
    networks:
      - smartcart

  web:
    image: nginx:alpine
    container_name: smartcart-nginx
    restart: unless-stopped
    ports:
      - "8080:80"
    volumes:
      - ./:/var/www
      - ./docker/nginx/conf.d:/etc/nginx/conf.d
    networks:
      - smartcart
    depends_on:
      - app

  mysql:
    image: mysql:8.0
    container_name: smartcart-mysql
    restart: unless-stopped
    environment:
      MYSQL_DATABASE: ${DB_DATABASE}
      MYSQL_ROOT_PASSWORD: ${DB_PASSWORD}
      MYSQL_PASSWORD: ${DB_PASSWORD}
      MYSQL_USER: ${DB_USERNAME}
      SERVICE_TAGS: dev
      SERVICE_NAME: mysql
    volumes:
      - mysql-data:/var/lib/mysql
      - ./docker/mysql/my.cnf:/etc/mysql/my.cnf
    networks:
      - smartcart
    healthcheck:
      test: ["CMD", "mysqladmin", "ping", "-p${DB_PASSWORD}"]
      retries: 3
      timeout: 5s

  redis:
    image: redis:alpine
    container_name: smartcart-redis
    restart: unless-stopped
    networks:
      - smartcart

  mailhog:
    image: mailhog/mailhog:latest
    container_name: smartcart-mailhog
    ports:
      - "1025:1025"
      - "8025:8025"
    networks:
      - smartcart

networks:
  smartcart:
    driver: bridge

volumes:
  mysql-data:
    driver: local
```

## Dockerfile

Here's the `Dockerfile` used to build the PHP application container:

```dockerfile
FROM php:8.2-fpm

# Set working directory
WORKDIR /var/www

# Install dependencies
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    unzip \
    git \
    curl \
    libzip-dev \
    libonig-dev \
    libicu-dev

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install extensions
RUN docker-php-ext-install pdo_mysql mbstring zip exif pcntl
RUN docker-php-ext-install gd
RUN docker-php-ext-configure intl && docker-php-ext-install intl

# Install composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Add user for laravel application
RUN groupadd -g 1000 www
RUN useradd -u 1000 -ms /bin/bash -g www www

# Copy existing application directory contents
COPY . /var/www

# Copy existing application directory permissions
COPY --chown=www:www . /var/www

# Change current user to www
USER www

# Expose port 9000 and start php-fpm server
EXPOSE 9000
CMD ["php-fpm"]
```

## Nginx Configuration

Create a configuration file at `docker/nginx/conf.d/app.conf`:

```nginx
server {
    listen 80;
    index index.php index.html;
    error_log  /var/log/nginx/error.log;
    access_log /var/log/nginx/access.log;
    root /var/www/public;
    
    location ~ \.php$ {
        try_files $uri =404;
        fastcgi_split_path_info ^(.+\.php)(/.+)$;
        fastcgi_pass app:9000;
        fastcgi_index index.php;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        fastcgi_param PATH_INFO $fastcgi_path_info;
    }
    
    location / {
        try_files $uri $uri/ /index.php?$query_string;
        gzip_static on;
    }
}
```

## MySQL Configuration

Create a MySQL configuration file at `docker/mysql/my.cnf`:

```ini
[mysqld]
character-set-server = utf8mb4
collation-server = utf8mb4_unicode_ci
default_authentication_plugin = mysql_native_password

[client]
default-character-set = utf8mb4
```

## Environment Variables

The Docker setup uses the following environment variables from your `.env` file:

- `DB_DATABASE`: The name of the MySQL database
- `DB_USERNAME`: The MySQL user
- `DB_PASSWORD`: The MySQL password

## Docker Commands

### Building and Starting Containers

```bash
# Build and start containers in detached mode
docker-compose up -d

# Build containers without caching during build
docker-compose build --no-cache

# Start containers if they exist
docker-compose start
```

### Managing Containers

```bash
# Stop containers
docker-compose stop

# Stop and remove containers
docker-compose down

# Stop and remove containers along with volumes
docker-compose down -v

# View container logs
docker-compose logs

# View logs for a specific service
docker-compose logs app
```

### Executing Commands

```bash
# Run artisan commands
docker-compose exec app php artisan migrate

# Access MySQL CLI
docker-compose exec mysql mysql -u${DB_USERNAME} -p${DB_PASSWORD} ${DB_DATABASE}

# Run composer commands
docker-compose exec app composer install
```

## Container Details

### PHP Application (app)
- **Image**: Custom PHP 8.2-FPM
- **Ports**: 9000 (internal)
- **Volumes**: Application code mounted to `/var/www`

### Web Server (web)
- **Image**: nginx:alpine
- **Ports**: 8080 (host) -> 80 (container)
- **Volumes**: 
  - Application code mounted to `/var/www`
  - Nginx configuration mounted to `/etc/nginx/conf.d`

### Database (mysql)
- **Image**: mysql:8.0
- **Environment Variables**: Configured via `.env` file
- **Volumes**: 
  - Persistent data volume for `/var/lib/mysql`
  - Custom MySQL configuration

### Cache (redis)
- **Image**: redis:alpine
- **Ports**: Default Redis port (internal only)

### Email Testing (mailhog)
- **Image**: mailhog/mailhog:latest
- **Ports**: 
  - 1025 (SMTP)
  - 8025 (Web interface)

## Networking

All containers are connected to a custom bridge network named `smartcart`. This allows containers to communicate with each other using their service names as hostnames.

## Data Persistence

The MySQL data is persisted using a named volume (`mysql-data`). This ensures that your database data is not lost when containers are stopped or removed.

## Production Considerations

For production deployments, consider the following adjustments:

1. Remove development-only services like MailHog
2. Set appropriate resource limits for containers
3. Use environment-specific Docker Compose override files
4. Implement proper logging and monitoring solutions
5. Set up a reverse proxy for SSL termination
6. Configure database backups 