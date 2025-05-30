# SmartCart Development Guide

This guide provides technical information for developers who want to extend or customize the SmartCart application.

## Development Environment Setup

### Prerequisites

- PHP 8.2 or higher
- Composer
- Node.js and NPM
- Docker and Docker Compose
- Git

### Local Development Setup

1. Clone the repository:
   ```bash
   git clone https://github.com/your-organization/smart-cart.git
   cd smart-cart
   ```

2. Copy the example environment file:
   ```bash
   cp .env.example .env
   ```

3. Configure the `.env` file for local development:
   ```
   APP_ENV=local
   APP_DEBUG=true
   
   DB_CONNECTION=mysql
   DB_HOST=mysql
   DB_PORT=3306
   DB_DATABASE=smartcart
   DB_USERNAME=smartcart
   DB_PASSWORD=your_password
   ```

4. Start the Docker environment:
   ```bash
   docker-compose up -d
   ```

5. Install PHP dependencies:
   ```bash
   docker-compose exec app composer install
   ```

6. Generate application key:
   ```bash
   docker-compose exec app php artisan key:generate
   ```

7. Run database migrations and seeders:
   ```bash
   docker-compose exec app php artisan migrate --seed
   ```

8. Install frontend dependencies and compile assets:
   ```bash
   docker-compose exec app npm install
   docker-compose exec app npm run dev
   ```

## Using the Sneat Bootstrap Template

SmartCart uses the [Sneat Bootstrap Laravel Livewire Starter Kit](https://github.com/themeselection/sneat-bootstrap-laravel-livewire-starter-kit) for its UI. This provides a robust foundation for both the admin dashboard and customer-facing frontend.

### Template Structure

The Sneat template is organized as follows:

```
resources/
├── scss/                     # SCSS stylesheets
│   ├── _bootstrap-extended/  # Bootstrap overrides
│   ├── _components/          # Custom components
│   ├── _theme/               # Theme variables and mixins
│   ├── _variables.scss       # Core variables
│   └── app.scss              # Main stylesheet
├── js/
│   ├── components/           # JS components
│   ├── menu/                 # Menu functionality
│   ├── app.js                # Main JavaScript file
│   └── bootstrap.js          # Bootstrap initialization
└── views/
    ├── layouts/
    │   ├── admin/            # Admin layout templates
    │   └── customer/         # Customer layout templates
    ├── components/           # Reusable UI components
    ├── admin/                # Admin views
    └── customer/             # Customer-facing views
```

### Using Template Components

Sneat provides many pre-built components that can be used in your views:

```blade
<!-- Example card component -->
<div class="card">
  <div class="card-header">
    <h5 class="card-title">Product Information</h5>
  </div>
  <div class="card-body">
    <p class="card-text">Product details go here.</p>
  </div>
  <div class="card-footer">
    <button class="btn btn-primary">Save</button>
  </div>
</div>
```

### Customizing the Theme

To customize the theme colors and appearance:

1. Edit the `resources/scss/_variables.scss` file to modify:
   - Color schemes
   - Typography
   - Spacing
   - Breakpoints

2. Rebuild the assets:
   ```bash
   docker-compose exec app npm run dev
   ```

### Dark Mode Support

The template includes built-in dark mode support. To implement dark mode toggle:

```php
// Example Livewire component for theme switching
namespace App\Http\Livewire;

use Livewire\Component;

class ThemeSwitch extends Component
{
    public $darkMode = false;

    public function mount()
    {
        $this->darkMode = session('darkMode', false);
    }

    public function toggleTheme()
    {
        $this->darkMode = !$this->darkMode;
        session(['darkMode' => $this->darkMode]);
    }

    public function render()
    {
        return view('livewire.theme-switch');
    }
}
```

## Project Structure

The SmartCart application follows Laravel's directory structure with some additional organization:

```
smart-cart/
├── app/                      # Application code
│   ├── Console/              # Console commands
│   ├── Exceptions/           # Exception handlers
│   ├── Http/
│   │   ├── Controllers/      # Controllers for web and API
│   │   ├── Livewire/         # Livewire components
│   │   ├── Middleware/       # HTTP middleware
│   │   └── Requests/         # Form request validation
│   ├── Models/               # Eloquent models
│   ├── Providers/            # Service providers
│   ├── Services/             # Business logic services
│   └── Repositories/         # Data access layer
├── bootstrap/                # Application bootstrap files
├── config/                   # Configuration files
├── database/
│   ├── factories/            # Model factories for testing
│   ├── migrations/           # Database migrations
│   └── seeders/              # Database seeders
├── docker/                   # Docker configuration files
├── public/                   # Publicly accessible files
├── resources/
│   ├── css/                  # CSS files
│   ├── js/                   # JavaScript files
│   └── views/                # Blade templates
│       ├── admin/            # Admin panel views
│       ├── auth/             # Authentication views
│       ├── components/       # Reusable view components
│       ├── layouts/          # Layout templates
│       └── livewire/         # Livewire component views
├── routes/                   # Route definitions
│   ├── web.php               # Web routes
│   ├── api.php               # API routes
│   └── admin.php             # Admin panel routes
├── storage/                  # Application storage
├── tests/                    # Test files
├── .env.example              # Example environment configuration
├── artisan                   # Laravel Artisan CLI
├── composer.json             # PHP dependencies
├── docker-compose.yml        # Docker Compose configuration
├── package.json              # JS dependencies
└── README.md                 # Project README
```

## Architecture

### MVC Pattern

The application follows the Model-View-Controller (MVC) pattern:

- **Models**: Located in `app/Models/`, represent database entities and relationships
- **Views**: Located in `resources/views/`, implemented using Blade templates
- **Controllers**: Located in `app/Http/Controllers/`, handle HTTP requests

### Livewire Components

Livewire components combine frontend and backend code to create dynamic interfaces without writing JavaScript:

- Component classes: `app/Http/Livewire/`
- Component views: `resources/views/livewire/`

Each Livewire component consists of a PHP class and a Blade template. The class handles the logic, while the template handles the presentation.

Example Livewire component class:

```php
// app/Http/Livewire/ProductList.php
namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Product;
use Livewire\WithPagination;

class ProductList extends Component
{
    use WithPagination;

    public $search = '';
    public $category = '';
    public $sortBy = 'name';
    public $sortDirection = 'asc';

    protected $queryString = [
        'search' => ['except' => ''],
        'category' => ['except' => ''],
        'sortBy' => ['except' => 'name'],
        'sortDirection' => ['except' => 'asc'],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }
        
        $this->sortBy = $field;
    }

    public function render()
    {
        $products = Product::query()
            ->when($this->search, function ($query) {
                return $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->when($this->category, function ($query) {
                return $query->whereHas('categories', function ($q) {
                    $q->where('slug', $this->category);
                });
            })
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate(12);

        return view('livewire.product-list', [
            'products' => $products,
        ]);
    }
}
```

Example Livewire component view:

```blade
<!-- resources/views/livewire/product-list.blade.php -->
<div>
    <div class="mb-4">
        <input wire:model.debounce.300ms="search" type="text" placeholder="Search products..." class="form-control">
        
        <select wire:model="category" class="form-select mt-2">
            <option value="">All Categories</option>
            @foreach($categories as $category)
                <option value="{{ $category->slug }}">{{ $category->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="row g-4">
        @foreach($products as $product)
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card h-100">
                    <img src="{{ $product->primaryImage->image_path }}" class="card-img-top" alt="{{ $product->name }}">
                    <div class="card-body">
                        <h5 class="card-title">{{ $product->name }}</h5>
                        <p class="card-text">${{ number_format($product->price, 2) }}</p>
                        <button wire:click="addToCart({{ $product->id }})" class="btn btn-primary">
                            Add to Cart
                        </button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-4">
        {{ $products->links() }}
    </div>
</div>
```

### Service Layer

The service layer contains business logic, separated from controllers and models:

```php
// app/Services/CartService.php
namespace App\Services;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CartService
{
    public function getCart()
    {
        $sessionId = session()->getId();
        $userId = Auth::id();

        $cart = Cart::query()
            ->when($userId, function ($query) use ($userId) {
                return $query->where('user_id', $userId);
            })
            ->when(!$userId, function ($query) use ($sessionId) {
                return $query->where('session_id', $sessionId);
            })
            ->first();

        if (!$cart) {
            $cart = Cart::create([
                'user_id' => $userId,
                'session_id' => $sessionId,
            ]);
        }

        return $cart;
    }

    public function addItem($productId, $quantity = 1)
    {
        $cart = $this->getCart();
        $product = Product::findOrFail($productId);

        $cartItem = $cart->items()->where('product_id', $productId)->first();

        if ($cartItem) {
            $cartItem->update([
                'quantity' => $cartItem->quantity + $quantity,
                'price' => $product->price,
            ]);
        } else {
            $cart->items()->create([
                'product_id' => $productId,
                'quantity' => $quantity,
                'price' => $product->price,
            ]);
        }

        return $cart->fresh();
    }

    public function updateItemQuantity($cartItemId, $quantity)
    {
        $cart = $this->getCart();
        $cartItem = $cart->items()->findOrFail($cartItemId);

        if ($quantity <= 0) {
            $cartItem->delete();
        } else {
            $cartItem->update(['quantity' => $quantity]);
        }

        return $cart->fresh();
    }

    public function removeItem($cartItemId)
    {
        $cart = $this->getCart();
        $cart->items()->findOrFail($cartItemId)->delete();

        return $cart->fresh();
    }

    public function clearCart()
    {
        $cart = $this->getCart();
        $cart->items()->delete();

        return $cart->fresh();
    }
}
```

## Working with Models

### Eloquent Relationships

The SmartCart application uses Eloquent relationships to define relationships between models:

```php
// app/Models/Product.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'discount_price',
        'stock_quantity',
        'sku',
        'featured',
        'status',
    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function primaryImage()
    {
        return $this->hasOne(ProductImage::class)
            ->where('is_primary', true)
            ->withDefault(function () {
                return new ProductImage([
                    'image_path' => 'images/no-image.jpg',
                    'is_primary' => true,
                ]);
            });
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }
}
```

## Authentication and Authorization

### Authentication

SmartCart uses Laravel's built-in authentication system with customizations for multi-role support:

- Role-based guards in `config/auth.php`
- Custom middleware for admin access in `app/Http/Middleware/AdminMiddleware.php`

### Authorization

Permissions are handled using Laravel's Gate and Policy features:

```php
// app/Providers/AuthServiceProvider.php
namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;
use App\Models\Order;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        // ...
    ];

    public function boot()
    {
        $this->registerPolicies();

        Gate::define('access-admin', function (User $user) {
            return $user->role === 'admin';
        });

        Gate::define('manage-products', function (User $user) {
            return $user->role === 'admin';
        });

        Gate::define('manage-orders', function (User $user) {
            return $user->role === 'admin';
        });

        Gate::define('view-order', function (User $user, Order $order) {
            return $user->id === $order->user_id || $user->role === 'admin';
        });
    }
}
```

## Frontend Assets

### CSS with Bootstrap

SmartCart uses Bootstrap 5 via the Sneat template for styling:

- Core files: `resources/scss/_bootstrap.scss`
- Custom overrides: `resources/scss/_bootstrap-extended/`
- Theme variables: `resources/scss/_variables.scss`

### JavaScript

The application uses several JavaScript libraries:

- **Bootstrap JS**: Core Bootstrap functionality
- **Alpine.js**: Lightweight reactivity for enhanced interactivity
- **Apex Charts**: For data visualization in the admin dashboard
- **Perfect Scrollbar**: Enhanced scrollbars for sidebar menus

The main JavaScript file is located at `resources/js/app.js`.

## Testing

### Unit Tests

Unit tests focus on testing individual components:

```php
// tests/Unit/Services/CartServiceTest.php
namespace Tests\Unit\Services;

use App\Models\Cart;
use App\Models\Product;
use App\Models\User;
use App\Services\CartService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $cartService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->cartService = new CartService();
    }

    public function test_can_add_item_to_cart()
    {
        $product = Product::factory()->create([
            'price' => 10.99,
            'stock_quantity' => 5,
        ]);

        $cart = $this->cartService->addItem($product->id, 2);

        $this->assertCount(1, $cart->items);
        $this->assertEquals($product->id, $cart->items[0]->product_id);
        $this->assertEquals(2, $cart->items[0]->quantity);
        $this->assertEquals(10.99, $cart->items[0]->price);
    }

    public function test_can_update_item_quantity()
    {
        $product = Product::factory()->create();
        $cart = $this->cartService->addItem($product->id, 1);
        $cartItemId = $cart->items[0]->id;

        $cart = $this->cartService->updateItemQuantity($cartItemId, 3);

        $this->assertEquals(3, $cart->items[0]->quantity);
    }

    public function test_can_remove_item()
    {
        $product = Product::factory()->create();
        $cart = $this->cartService->addItem($product->id, 1);
        $cartItemId = $cart->items[0]->id;

        $cart = $this->cartService->removeItem($cartItemId);

        $this->assertCount(0, $cart->items);
    }
}
```

### Feature Tests

Feature tests focus on testing complete features:

```php
// tests/Feature/CheckoutTest.php
namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class CheckoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_complete_checkout()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create([
            'price' => 29.99,
            'stock_quantity' => 10,
        ]);

        $this->actingAs($user);

        // Add item to cart
        Livewire::test('product-detail', ['product' => $product])
            ->call('addToCart', 2);

        // Go to checkout
        Livewire::test('checkout')
            ->set('shippingAddress.name', 'John Doe')
            ->set('shippingAddress.email', 'john@example.com')
            ->set('shippingAddress.phone', '1234567890')
            ->set('shippingAddress.address_line_1', '123 Main St')
            ->set('shippingAddress.city', 'Anytown')
            ->set('shippingAddress.state', 'State')
            ->set('shippingAddress.postal_code', '12345')
            ->set('shippingAddress.country', 'Country')
            ->call('placeOrder')
            ->assertRedirect(route('checkout.success'));

        // Check that order was created
        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
            'total' => 59.98, // 2 * 29.99
        ]);

        // Check that stock was reduced
        $this->assertEquals(8, $product->fresh()->stock_quantity);
    }
}
```

## Extending the Application

### Adding New Features

To add a new feature to SmartCart:

1. **Create necessary models**:
   ```bash
   docker-compose exec app php artisan make:model NewFeature -m
   ```

2. **Create migration**:
   ```php
   // database/migrations/xxxx_xx_xx_create_new_features_table.php
   public function up()
   {
       Schema::create('new_features', function (Blueprint $table) {
           $table->id();
           $table->string('name');
           $table->text('description');
           $table->timestamps();
       });
   }
   ```

3. **Run migration**:
   ```bash
   docker-compose exec app php artisan migrate
   ```

4. **Create controller**:
   ```bash
   docker-compose exec app php artisan make:controller NewFeatureController
   ```

5. **Create Livewire component**:
   ```bash
   docker-compose exec app php artisan make:livewire NewFeatureComponent
   ```

6. **Add routes**:
   ```php
   // routes/web.php
   Route::middleware(['auth'])->group(function () {
       Route::get('/new-feature', [NewFeatureController::class, 'index'])->name('new-feature.index');
   });
   ```

7. **Create views**:
   - Create Blade templates in `resources/views/new-feature/`
   - Create Livewire component views in `resources/views/livewire/`

8. **Add to navigation**:
   - Update `resources/views/layouts/app.blade.php` to include the new feature in the navigation

### Custom Admin Dashboard Widgets

To add a custom widget to the admin dashboard:

1. Create a new Livewire component:
   ```bash
   docker-compose exec app php artisan make:livewire Admin/Widgets/CustomWidget
   ```

2. Implement the component class:
   ```php
   namespace App\Http\Livewire\Admin\Widgets;
   
   use Livewire\Component;
   use App\Models\YourModel;
   
   class CustomWidget extends Component
   {
       public function render()
       {
           $data = YourModel::query()
               ->latest()
               ->take(5)
               ->get();
   
           return view('livewire.admin.widgets.custom-widget', [
               'data' => $data,
           ]);
       }
   }
   ```

3. Create the component view:
   ```blade
   <!-- resources/views/livewire/admin/widgets/custom-widget.blade.php -->
   <div class="card">
       <div class="card-header">
           <h3 class="card-title">Custom Widget</h3>
       </div>
       <div class="card-body">
           <!-- Widget content -->
       </div>
   </div>
   ```

4. Add the widget to the dashboard:
   ```blade
   <!-- resources/views/admin/dashboard.blade.php -->
   <div class="row">
       <div class="col-md-6 col-lg-4">
           @livewire('admin.widgets.stats-widget')
       </div>
       <div class="col-md-6 col-lg-4">
           @livewire('admin.widgets.recent-orders-widget')
       </div>
       <div class="col-md-6 col-lg-4">
           @livewire('admin.widgets.custom-widget')
       </div>
   </div>
   ```

## Deployment

### Production Deployment

For production deployment:

1. Configure `.env` for production:
   ```
   APP_ENV=production
   APP_DEBUG=false
   APP_URL=https://your-production-domain.com
   
   # Database settings
   DB_CONNECTION=mysql
   DB_HOST=production-db-host
   DB_PORT=3306
   DB_DATABASE=smartcart_prod
   DB_USERNAME=production_user
   DB_PASSWORD=secure_password
   ```

2. Optimize Laravel for production:
   ```bash
   docker-compose exec app php artisan config:cache
   docker-compose exec app php artisan route:cache
   docker-compose exec app php artisan view:cache
   ```

3. Compile assets for production:
   ```bash
   docker-compose exec app npm run build
   ```

4. Set proper permissions:
   ```bash
   docker-compose exec app chmod -R 775 storage bootstrap/cache
   ```

5. Configure a reverse proxy like Nginx or Apache to handle SSL termination.

## Troubleshooting

### Common Issues

1. **Database Connection Issues**:
   - Check environment variables
   - Ensure MySQL container is running
   - Try restarting the containers:
     ```bash
     docker-compose restart
     ```

2. **Permission Issues**:
   - Set proper ownership:
     ```bash
     docker-compose exec app chown -R www-data:www-data storage bootstrap/cache
     ```
   - Set proper permissions:
     ```bash
     docker-compose exec app chmod -R 775 storage bootstrap/cache
     ```

3. **Livewire Component Not Updating**:
   - Clear view cache:
     ```bash
     docker-compose exec app php artisan view:clear
     ```
   - Ensure the component is registered in `AppServiceProvider`

4. **Migrations Failing**:
   - Check if database exists and credentials are correct
   - Try rolling back and migrating again:
     ```bash
     docker-compose exec app php artisan migrate:rollback
     docker-compose exec app php artisan migrate
     ```

### Debugging

For debugging issues:

1. Enable debug mode in `.env`:
   ```
   APP_DEBUG=true
   ```

2. Check Laravel logs:
   ```bash
   docker-compose exec app tail -f storage/logs/laravel.log
   ```

3. Use Laravel Telescope for detailed debugging (if installed):
   ```bash
   docker-compose exec app composer require laravel/telescope --dev
   docker-compose exec app php artisan telescope:install
   docker-compose exec app php artisan migrate
   ```

4. Access Telescope at `/telescope`

## Resources

- [Laravel Documentation](https://laravel.com/docs)
- [Livewire Documentation](https://laravel-livewire.com/docs)
- [Bootstrap Documentation](https://getbootstrap.com/docs)
- [Sneat Template Documentation](https://demos.themeselection.com/sneat-bootstrap-html-admin-template/documentation/)
- [Alpine.js Documentation](https://alpinejs.dev/start-here)
- [MySQL Documentation](https://dev.mysql.com/doc/) 