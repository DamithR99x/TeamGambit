# SmartCart Architecture

This document outlines the architecture of the SmartCart application, explaining the overall system design, components, and their interactions.

## System Overview

SmartCart is built using a modern web application architecture with the following key components:

1. **Frontend Layer**: Laravel Blade templates enhanced with Livewire for dynamic interactions
2. **Backend Layer**: Laravel PHP framework with controllers, models, and services
3. **Database Layer**: MySQL database for data persistence
4. **Infrastructure Layer**: Docker containers for deployment

## Technology Stack

### Frontend Technologies
- **Laravel Blade**: Template engine for rendering views
- **Livewire**: Full-stack framework for dynamic interfaces without writing JavaScript
- **Bootstrap 5**: Front-end framework for responsive design
- **Sneat Admin Template**: Premium Bootstrap 5 admin interface
- **Alpine.js**: Minimal JavaScript framework for enhancing interactivity
- **HTMX**: For AJAX requests and DOM updates (optional)

### Backend Technologies
- **Laravel**: PHP framework providing the application foundation
- **PHP 8.2+**: Programming language
- **Laravel Sanctum**: Authentication system
- **Laravel Validator**: For data validation
- **Laravel Eloquent**: ORM for database interactions

### Database
- **MySQL 8.0**: Relational database management system

### Development & Deployment
- **Docker**: Containerization platform
- **Docker Compose**: Multi-container orchestration

## Application Structure

The application follows Laravel's MVC (Model-View-Controller) architecture with additional layers for services and repositories.

### Directory Structure

```
smart-cart/
├── app/
│   ├── Console/              # Console commands
│   ├── Exceptions/           # Exception handlers
│   ├── Http/
│   │   ├── Controllers/      # Request handlers
│   │   ├── Livewire/         # Livewire components
│   │   ├── Middleware/       # Request middleware
│   │   └── Requests/         # Form requests
│   ├── Models/               # Eloquent models
│   ├── Providers/            # Service providers
│   ├── Services/             # Business logic services
│   └── Repositories/         # Data access layer
├── bootstrap/                # Application bootstrap
├── config/                   # Configuration files
├── database/
│   ├── factories/            # Model factories
│   ├── migrations/           # Database migrations
│   └── seeders/              # Database seeders
├── docker/                   # Docker configuration
├── public/                   # Publicly accessible files
├── resources/
│   ├── css/                  # CSS files
│   ├── js/                   # JavaScript files
│   └── views/                # Blade templates
├── routes/                   # Route definitions
├── storage/                  # Application storage
└── tests/                    # Test files
```

## Component Interactions

### Request Flow

1. **Client Request**: User interacts with the application UI
2. **Web Server**: Nginx receives the request and forwards it to PHP-FPM
3. **Laravel Router**: Routes the request to the appropriate controller or Livewire component
4. **Middleware**: Applies authentication, validation, and other middleware
5. **Controller/Livewire**: Processes the request
6. **Service Layer**: Implements business logic
7. **Repository Layer**: Handles data access operations
8. **Model Layer**: Interacts with the database through Eloquent ORM
9. **Response**: Data is returned to the view
10. **View Layer**: Blade templates render the HTML
11. **Client**: Updated UI is displayed to the user

### Authentication Flow

1. User submits login credentials
2. Laravel Sanctum validates credentials
3. Authentication tokens are generated
4. User session is created
5. Role-based access control determines available features

## UI Architecture

SmartCart implements a dual-interface architecture using the Sneat Bootstrap template:

### Admin Interface
- Dashboard-centric layout with sidebar navigation
- Data tables for managing products, orders, and customers
- Charts and statistics widgets for business analytics
- Forms for data entry and configuration

### Customer Interface
- Product-focused layout with top navigation
- Responsive product grids and lists
- Shopping cart and checkout process
- User account management

Both interfaces share the same Bootstrap 5 foundation while maintaining distinct user experiences appropriate for their respective audiences.

## Database Schema

### Key Entities

- **Users**: Store user account information
- **Products**: Store product details
- **Categories**: Organize products into categories
- **Orders**: Track customer orders
- **Order Items**: Individual items within orders
- **Carts**: Temporary storage for items before checkout
- **Cart Items**: Individual items within carts

### Relationships

- A User can have many Orders
- A User can have one Cart
- A Cart can have many Cart Items
- A Cart Item belongs to one Product
- A Product belongs to one or many Categories
- An Order belongs to one User
- An Order can have many Order Items
- An Order Item belongs to one Product

## Livewire Components

Livewire components provide dynamic functionality without requiring custom JavaScript:

- **ProductList**: Displays products with filtering and sorting
- **ProductSearch**: Handles product search functionality
- **ShoppingCart**: Manages the user's shopping cart
- **CheckoutForm**: Handles the checkout process
- **AdminProductTable**: CRUD operations for products in admin dashboard
- **AdminOrderTable**: Order management in admin dashboard

## Service Layer

The service layer contains business logic, separated from controllers and models:

- **ProductService**: Product-related operations
- **CartService**: Shopping cart operations
- **OrderService**: Order processing and management
- **UserService**: User account management
- **PaymentService**: Payment processing (if implemented)
- **RecommendationService**: Product recommendations (if implemented)

## Security Considerations

- Authentication via Laravel Sanctum
- CSRF protection for all forms
- Input validation for all user inputs
- Role-based access control
- Database query parameter binding to prevent SQL injection
- XSS protection through proper output escaping
- Rate limiting for sensitive endpoints

## Caching Strategy

- Product catalog caching
- Category list caching
- Homepage caching
- Redis for session storage
- Database query caching for frequently accessed data

## Performance Optimizations

- Eager loading of relationships to prevent N+1 query issues
- Pagination for large result sets
- Deferred loading of non-critical components
- Image optimization and lazy loading
- Database indexing for frequently queried columns

## Scalability Considerations

- Stateless application design
- Horizontal scaling capabilities through Docker
- Database connection pooling
- Potential for microservices architecture in future iterations
- Cache distribution for high-traffic scenarios

## Monitoring and Logging

- Application logs using Laravel's logging system
- Error tracking and reporting
- Performance metrics collection
- User activity monitoring for security purposes

## Testing Strategy

- Unit tests for business logic
- Feature tests for user workflows
- Browser tests for UI functionality
- API tests for endpoints
- Database tests for model interactions 