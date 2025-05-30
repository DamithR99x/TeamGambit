# SmartCart Frequently Asked Questions (FAQ)

This document provides answers to common questions about the SmartCart application.

## General Questions

### What is SmartCart?

SmartCart is an online shopping platform designed to deliver a smooth, intuitive shopping experience for everyday users while also providing store managers with the tools needed to oversee products and customer orders.

### What technologies does SmartCart use?

SmartCart is built using:
- Laravel (PHP framework for backend development)
- Livewire (Full-stack framework for dynamic interfaces)
- Docker (Containerization for easy deployment)
- MySQL (Database for data storage)
- Bootstrap 5 (Front-end framework for responsive design)
- Sneat (Premium Bootstrap 5 admin template)

### Is SmartCart open-source?

SmartCart is an internal project at 99x. Please refer to the project's license for usage terms and restrictions.

## Installation & Setup

### What are the system requirements for SmartCart?

To run SmartCart, you need:
- Docker and Docker Compose
- Git (for cloning the repository)
- At least 2GB of RAM
- 1GB of available disk space

### How do I install SmartCart?

See the [Getting Started](./getting-started.md) guide for detailed installation instructions.

### Can I run SmartCart without Docker?

While Docker is the recommended way to run SmartCart, you can also set it up on a traditional LAMP/LEMP stack. You'll need:
- PHP 8.2 or higher with required extensions
- MySQL 8.0
- Nginx or Apache web server
- Composer
- Node.js and NPM

### How do I update SmartCart to the latest version?

To update SmartCart:

1. Pull the latest changes from the repository:
   ```bash
   git pull origin main
   ```

2. Rebuild the Docker containers:
   ```bash
   docker-compose build --no-cache
   ```

3. Run database migrations:
   ```bash
   docker-compose exec app php artisan migrate
   ```

4. Update assets:
   ```bash
   docker-compose exec app npm install
   docker-compose exec app npm run build
   ```

## User Interface

### What UI framework does SmartCart use?

SmartCart uses the [Sneat Bootstrap Laravel Livewire Starter Kit](https://github.com/themeselection/sneat-bootstrap-laravel-livewire-starter-kit) which provides a modern, responsive UI based on Bootstrap 5. This template is used for both the admin dashboard and the customer-facing frontend.

### Does SmartCart support dark mode?

Yes, the Sneat template includes built-in dark mode support. Users can toggle between light and dark modes by clicking on their profile icon and switching the theme mode.

### Can I customize the look and feel of SmartCart?

Yes, you can customize the appearance by:

1. Modifying the Bootstrap variables in `resources/scss/_variables.scss`
2. Creating custom styles in `resources/scss/custom.scss`
3. Overriding Bootstrap components in `resources/scss/_bootstrap-extended/`

### Is the interface mobile-friendly?

Yes, SmartCart is fully responsive and works well on mobile devices, tablets, and desktops thanks to Bootstrap's responsive grid system and the Sneat template's mobile-optimized components.

## User Management

### How do I create an admin user?

You can create an admin user in several ways:

1. Using the database seeder (development only):
   ```bash
   docker-compose exec app php artisan db:seed --class=AdminUserSeeder
   ```

2. Using Artisan command:
   ```bash
   docker-compose exec app php artisan make:admin
   ```

3. By changing a user's role in the database:
   ```sql
   UPDATE users SET role = 'admin' WHERE email = 'user@example.com';
   ```

### Can customers register themselves?

Yes, customers can register by clicking the "Register" button on the website. They will need to provide their name, email address, and password.

### How do I reset a user's password?

There are two ways to reset a user's password:

1. Users can reset their own password using the "Forgot Password" link on the login page.

2. Administrators can reset a user's password from the admin dashboard:
   - Go to Customers > All Customers
   - Find the user and click "Edit"
   - Enter a new password
   - Click "Save Changes"

## Products Management

### How do I add a new product?

To add a new product:

1. Log in as an administrator
2. Go to Products > All Products
3. Click "Add New Product"
4. Fill in the product details
5. Click "Save"

See the [Admin Guide](./admin-guide.md) for detailed instructions.

### How do I organize products into categories?

First, create your categories:

1. Go to Products > Categories
2. Click "Add New Category"
3. Fill in the category details
4. Click "Save"

Then, assign products to categories:

1. Edit a product
2. In the "Categories" section, select one or more categories
3. Click "Save"

### Can I import products in bulk?

Yes, you can import products using a CSV file:

1. Go to Products > Import
2. Download the CSV template
3. Fill in your product data following the template format
4. Upload the completed CSV file
5. Review and confirm the import

### How do I manage product images?

Each product can have multiple images, with one set as the primary image:

1. Edit a product
2. In the "Images" section, click "Add Image"
3. Upload your image files
4. To set a primary image, hover over the image and click "Set as Primary"
5. To delete an image, hover over it and click the "Delete" icon
6. To reorder images, drag and drop them into the desired order

## Orders and Checkout

### How do customers place orders?

Customers place orders by:

1. Adding products to their cart
2. Clicking "Proceed to Checkout"
3. Entering shipping information
4. Selecting a shipping method
5. Reviewing their order
6. Clicking "Place Order"

### How do I process orders?

To process an order:

1. Go to Orders > All Orders
2. Click on the order number to view details
3. Update the order status as needed:
   - Pending: Order received but not processed
   - Processing: Order is being prepared
   - Completed: Order has been shipped/delivered
   - Cancelled: Order has been cancelled
   - Refunded: Payment has been refunded
4. Add a comment (optional)
5. Click "Update Status"

### Can customers track their orders?

Yes, customers can track their orders by:

1. Logging into their account
2. Going to "My Orders"
3. Clicking on an order number to view details, including status

### How do I generate an invoice for an order?

To generate an invoice:

1. Go to Orders > All Orders
2. Click on the order number to view details
3. Click the "Generate Invoice" button
4. The invoice will be generated as a PDF
5. You can download, print, or email the invoice

## Customization

### Can I customize the store appearance?

Yes, you can customize the appearance by:

1. Modifying the Blade templates in `resources/views/`
2. Editing the SCSS files in `resources/scss/`
3. Updating the Bootstrap variables in `resources/scss/_variables.scss`

### How do I add a custom feature?

To add a custom feature, see the [Development Guide](./development-guide.md) for detailed instructions on extending the application.

### Can I change the store currency?

Yes, you can change the store currency:

1. Go to Settings > General
2. In the "Currency" section, select your preferred currency
3. Click "Save Changes"

### How do I add custom payment methods?

SmartCart supports adding custom payment methods through its modular payment gateway system:

1. Create a new payment gateway class in `app/PaymentGateways/`
2. Register the gateway in `app/Providers/PaymentServiceProvider.php`
3. Create a view for the payment method in `resources/views/checkout/payment-methods/`
4. The new payment method will appear in the checkout process

## Sneat Template

### What is the Sneat template?

Sneat is a premium Bootstrap 5 admin template integrated with Laravel and Livewire. It provides a clean, modern interface with numerous pre-built components and layouts that are used throughout SmartCart.

### How do I use Sneat components?

Sneat components can be used by following the template documentation and examples:

1. Check the template examples in `resources/views/components/`
2. Refer to the [Sneat documentation](https://demos.themeselection.com/sneat-bootstrap-html-admin-template/documentation/)
3. Use the provided classes and structure in your Blade templates

### Can I update the Sneat template separately?

Yes, you can update the Sneat template independently:

1. Check for updates in the [Sneat GitHub repository](https://github.com/themeselection/sneat-bootstrap-laravel-livewire-starter-kit)
2. Follow their update instructions
3. Make sure to back up any customizations you've made

### Where can I find more examples of Sneat components?

You can find more examples of Sneat components in:

1. The template demo pages
2. The documentation
3. Example views in `resources/views/examples/` (if available)

## Troubleshooting

### The website is slow. How can I improve performance?

To improve performance:

1. Enable caching:
   ```bash
   docker-compose exec app php artisan config:cache
   docker-compose exec app php artisan route:cache
   docker-compose exec app php artisan view:cache
   ```

2. Optimize the database:
   ```bash
   docker-compose exec app php artisan db:optimize
   ```

3. Enable Redis for caching (update `.env`):
   ```
   CACHE_DRIVER=redis
   SESSION_DRIVER=redis
   ```

4. Optimize images by enabling the image optimization middleware.

### I'm seeing database connection errors. What should I do?

If you're experiencing database connection errors:

1. Check that your MySQL container is running:
   ```bash
   docker-compose ps
   ```

2. Verify your database credentials in `.env`

3. Try restarting the containers:
   ```bash
   docker-compose restart
   ```

4. Check the logs for more details:
   ```bash
   docker-compose logs mysql
   docker-compose logs app
   ```

### How do I fix permission issues with file uploads?

If you're having permission issues with file uploads:

1. Set the correct ownership:
   ```bash
   docker-compose exec app chown -R www-data:www-data storage
   ```

2. Set the correct permissions:
   ```bash
   docker-compose exec app chmod -R 775 storage
   ```

### The admin dashboard shows no data. What's wrong?

If the admin dashboard shows no data:

1. Check if you're logged in as an admin user
2. Verify that there is data in the database
3. Clear the application cache:
   ```bash
   docker-compose exec app php artisan cache:clear
   ```
4. Check the browser console for JavaScript errors
5. Check the Laravel logs:
   ```bash
   docker-compose exec app tail -f storage/logs/laravel.log
   ```

### Some Bootstrap components don't look right. What's wrong?

If Bootstrap components don't appear correctly:

1. Make sure you're using the correct Bootstrap classes
2. Check for JavaScript errors in the console
3. Verify that Bootstrap JS is properly loaded
4. Check for CSS conflicts with custom styles
5. Ensure you're using components as documented in the Sneat template

## Security

### How secure is SmartCart?

SmartCart implements several security measures:

- CSRF protection for all forms
- Input validation for all user inputs
- Password hashing using Bcrypt
- Role-based access control
- XSS protection through proper output escaping
- SQL injection prevention through parameterized queries
- Rate limiting for sensitive endpoints

### How do I update SmartCart to fix security vulnerabilities?

To ensure your SmartCart installation is secure:

1. Regularly check for updates and apply them
2. Keep all dependencies up-to-date:
   ```bash
   docker-compose exec app composer update
   docker-compose exec app npm update
   ```
3. Monitor security advisories for Laravel and other dependencies
4. Implement additional security measures as needed (firewall, WAF, etc.)

### Is customer data encrypted?

Sensitive customer data such as passwords are hashed (not encrypted). Payment information is not stored in the database but processed through secure payment gateways.

To add encryption for other sensitive data, you can use Laravel's encryption features:

```php
// Encrypt
$encryptedValue = encrypt($sensitiveData);

// Decrypt
$decryptedValue = decrypt($encryptedValue);
```

## Development

### How do I set up a development environment?

See the [Development Guide](./development-guide.md) for detailed instructions on setting up a development environment.

### How do I run tests?

To run tests:

```bash
docker-compose exec app php artisan test
```

To run specific test classes:

```bash
docker-compose exec app php artisan test --filter=CartTest
```

### How do I contribute to SmartCart?

To contribute to SmartCart:

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Write tests for your changes
5. Submit a pull request

Please follow the coding standards and write tests for all new features.

### Where can I find more documentation?

Additional documentation can be found in the `docs/` directory:

- [Introduction](./introduction.md)
- [Getting Started](./getting-started.md)
- [Architecture](./architecture.md)
- [Features](./features.md)
- [User Guide](./user-guide.md)
- [Admin Guide](./admin-guide.md)
- [Development Guide](./development-guide.md)
- [API Reference](./api-reference.md)
- [Docker Setup](./docker-setup.md)
- [Database Schema](./database-schema.md)

## Support

### How do I get support for SmartCart?

For support:

1. Check the documentation in the `docs/` directory
2. Look for answers in this FAQ
3. Contact the development team through the internal ticketing system
4. For critical issues, contact the project maintainer directly

### Can I request new features?

Yes, feature requests can be submitted through the internal ticketing system. Please include:

1. A clear description of the feature
2. The business value it provides
3. Any specific requirements or constraints
4. Mockups or examples (if applicable)

### How do I report bugs?

To report a bug:

1. Check if the bug has already been reported
2. Submit a detailed bug report through the internal ticketing system, including:
   - Steps to reproduce the bug
   - Expected behavior
   - Actual behavior
   - Screenshots (if applicable)
   - Browser/environment information
   - Logs or error messages 