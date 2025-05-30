<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;
use App\Http\Controllers\UserCartController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\SettingController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Cart routes for guests
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::get('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
Route::get('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

// User-specific cart routes (requires authentication)
Route::middleware(['auth'])->group(function () {
    Route::get('/user/cart', [UserCartController::class, 'index'])->name('user.cart.index');
    Route::post('/user/cart/add', [UserCartController::class, 'add'])->name('user.cart.add');
    Route::post('/user/cart/update', [UserCartController::class, 'update'])->name('user.cart.update');
    Route::get('/user/cart/remove/{id}', [UserCartController::class, 'remove'])->name('user.cart.remove');
    Route::get('/user/cart/clear', [UserCartController::class, 'clear'])->name('user.cart.clear');
});

// Admin routes (requires authentication and admin role)
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    
    // Products
    Route::resource('products', ProductController::class);
    Route::post('products/{product}/images/{image}/primary', [ProductController::class, 'setPrimaryImage'])->name('products.images.primary');
    Route::delete('products/{product}/images/{image}', [ProductController::class, 'deleteImage'])->name('products.images.delete');
    
    // Categories
    Route::resource('categories', CategoryController::class);
    
    // Orders
    Route::resource('orders', OrderController::class)->only(['index', 'show']);
    Route::post('orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.status.update');
    Route::get('orders/{order}/invoice', [OrderController::class, 'generateInvoice'])->name('orders.invoice.generate');
    Route::post('orders/{order}/invoice/email', [OrderController::class, 'emailInvoice'])->name('orders.invoice.email');
    
    // Customers
    Route::resource('customers', CustomerController::class)->only(['index', 'show', 'edit', 'update']);
    
    // Reports
    Route::get('reports/sales', [ReportController::class, 'sales'])->name('reports.sales');
    Route::get('reports/inventory', [ReportController::class, 'inventory'])->name('reports.inventory');
    Route::get('reports/customers', [ReportController::class, 'customers'])->name('reports.customers');
    
    // Settings
    Route::get('settings/general', [SettingController::class, 'general'])->name('settings.general');
    Route::post('settings/general', [SettingController::class, 'updateGeneral'])->name('settings.general.update');
    Route::get('settings/shipping', [SettingController::class, 'shipping'])->name('settings.shipping');
    Route::post('settings/shipping', [SettingController::class, 'updateShipping'])->name('settings.shipping.update');
    Route::get('settings/email-templates/{template?}', [SettingController::class, 'emailTemplates'])->name('settings.email-templates');
    Route::post('settings/email-templates/{template}', [SettingController::class, 'updateEmailTemplate'])->name('settings.email-templates.update');
});