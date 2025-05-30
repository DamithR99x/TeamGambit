# SmartCart

SmartCart is a Laravel-based e-commerce platform designed to provide a comprehensive shopping experience. The application includes product management, cart functionality, order processing, and an admin dashboard.

## Features

- **Product Management**: Browse, search, and filter products by category, price, etc.
- **Shopping Cart**: Add products to cart, update quantities, and proceed to checkout
- **User Authentication**: Register, login, and manage your profile
- **Order Processing**: Place orders and track order status
- **Admin Dashboard**: Manage products, categories, orders, and customers
- **Reporting**: Generate sales, inventory, and customer reports

## Technical Details

- Built on Laravel 10.x
- Uses darryldecode/cart package for cart functionality
- Bootstrap 5 with Sneat template for responsive UI
- MySQL database for data storage

## Cart Functionality

SmartCart uses the `darryldecode/cart` package for shopping cart functionality. It provides:

### Guest Cart
- Guests can add products to cart without logging in
- Cart is stored in session
- Cart contents persist across page views

### User Cart
- Authenticated users have their own carts
- Cart contents persist across sessions
- Guest cart is automatically transferred to user cart upon login

### Cart Features
- Add products to cart
- Update product quantities
- Remove products from cart
- Clear the entire cart
- View cart subtotal, tax, and total
- Optional database persistence for cart data

## Installation

1. Clone the repository:
   ```bash
   git clone https://github.com/yourusername/smartcart.git
   cd smartcart
   ```

2. Install dependencies:
   ```bash
   composer install
   npm install
   ```

3. Copy the environment file:
   ```bash
   cp .env.example .env
   ```

4. Generate application key:
   ```bash
   php artisan key:generate
   ```

5. Configure your database in the `.env` file:
   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=smartcart
   DB_USERNAME=root
   DB_PASSWORD=
   ```

6. Run migrations and seeders:
   ```bash
   php artisan migrate --seed
   ```

7. Link storage:
   ```bash
   php artisan storage:link
   ```

8. Build assets:
   ```bash
   npm run dev
   ```

9. Start the development server:
   ```bash
   php artisan serve
   ```

## Docker Setup

You can also run SmartCart using Docker:

1. Start the Docker containers:
   ```bash
   docker-compose up -d
   ```

2. Access the application at http://localhost:8080

## Usage

### Customer Interface
- Browse products on the homepage
- Filter products by category or search for specific items
- Add products to cart
- View and manage cart contents
- Proceed to checkout and place orders
- View order history and status

### Admin Interface
- Access the admin dashboard at `/admin` (requires admin credentials)
- Manage products and categories
- Process and fulfill orders
- View customer information
- Generate reports

## Default Admin Credentials

- Email: admin@example.com
- Password: password

## License

This project is licensed under the MIT License - see the LICENSE file for details.