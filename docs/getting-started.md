# Getting Started with SmartCart

This guide will help you set up and run the SmartCart application using Docker.

## Prerequisites

Before you begin, ensure you have the following installed on your system:
- [Docker](https://www.docker.com/get-started) (version 20.10.0 or higher)
- [Docker Compose](https://docs.docker.com/compose/install/) (version 1.29.0 or higher)
- [Git](https://git-scm.com/downloads) (optional, for cloning the repository)

## Installation

### 1. Clone the Repository

```bash
git clone https://github.com/your-organization/smart-cart.git
cd smart-cart
```

### 2. Configure Environment Variables

Copy the example environment file and adjust the settings as needed:

```bash
cp .env.example .env
```

Open the `.env` file and update the database connection variables:

```
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=smartcart
DB_USERNAME=smartcart
DB_PASSWORD=your_secure_password
```

### 3. Build and Start Docker Containers

```bash
docker-compose up -d
```

This command will build and start the following containers:
- Web server (Nginx)
- PHP application
- MySQL database

### 4. Install PHP Dependencies

```bash
docker-compose exec app composer install
```

### 5. Generate Application Key

```bash
docker-compose exec app php artisan key:generate
```

### 6. Run Database Migrations and Seeders

```bash
docker-compose exec app php artisan migrate --seed
```

### 7. Install and Build Frontend Assets

The application uses the Sneat Bootstrap Laravel Livewire Starter Kit for its UI. To install and build the frontend assets:

```bash
docker-compose exec app npm install
docker-compose exec app npm run dev
```

This will compile all the Bootstrap and Sneat template assets required for both the customer-facing interface and admin dashboard.

## Accessing the Application

Once the setup is complete, you can access the application at:

- **Customer Frontend**: http://localhost:8080
- **Admin Dashboard**: http://localhost:8080/admin

### Default Admin Credentials

Use these credentials to log in to the admin dashboard:

- **Email**: admin@smartcart.com
- **Password**: adminpassword

## Sneat Theme Customization

The Sneat Bootstrap template comes with multiple customization options:

### Switching Between Light and Dark Mode

The application supports both light and dark modes. To toggle between them:

1. Log in to the application
2. Click on your profile icon in the top-right corner
3. Toggle the "Dark Mode" switch

### Customizing Colors and Layout

To customize the theme colors and layout:

1. Edit the theme variables in `resources/scss/_variables.scss`
2. Rebuild the assets:
   ```bash
   docker-compose exec app npm run dev
   ```

## Development Workflow

### Running Artisan Commands

```bash
docker-compose exec app php artisan <command>
```

### Watching for Asset Changes

During development, you may want to watch for changes to frontend assets:

```bash
docker-compose exec app npm run watch
```

### Running Tests

```bash
docker-compose exec app php artisan test
```

### Stopping the Application

```bash
docker-compose down
```

To stop the application and remove volumes (this will delete all data):

```bash
docker-compose down -v
```

## Troubleshooting

### Permission Issues

If you encounter permission issues, try:

```bash
docker-compose exec app chmod -R 777 storage bootstrap/cache
```

### Database Connection Issues

If the application cannot connect to the database, ensure:
1. MySQL container is running: `docker-compose ps`
2. Environment variables are correctly set in the `.env` file
3. Try restarting the containers: `docker-compose restart`

For more detailed troubleshooting, check the logs:

```bash
docker-compose logs
``` 