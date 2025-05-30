<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - SmartCart</title>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <style>
        body {
            font-family: 'Nunito', sans-serif;
            padding-top: 56px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        .main-content {
            flex: 1;
        }
        
        .navbar-brand {
            font-weight: bold;
            font-size: 1.5rem;
        }
        
        .card {
            transition: transform 0.3s;
            margin-bottom: 20px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            border: none;
            border-radius: 0.5rem;
        }
        
        .card:hover {
            transform: translateY(-5px);
        }
        
        .product-card .card-img-top {
            height: 200px;
            object-fit: cover;
        }
        
        .category-badge {
            background-color: #f8f9fa;
            color: #212529;
            border-radius: 30px;
            padding: 5px 15px;
            margin-right: 5px;
            margin-bottom: 5px;
            display: inline-block;
            transition: all 0.3s;
        }
        
        .category-badge:hover {
            background-color: #007bff;
            color: #fff;
        }
        
        .btn-cart {
            background-color: #007bff;
            border-color: #007bff;
            color: white;
        }
        
        .btn-cart:hover {
            background-color: #0069d9;
            border-color: #0062cc;
        }
        
        .btn-favorite {
            background-color: transparent;
            border-color: #dc3545;
            color: #dc3545;
        }
        
        .btn-favorite:hover, .btn-favorite.active {
            background-color: #dc3545;
            color: white;
        }
        
        .footer {
            background-color: #343a40;
            color: white;
            padding: 30px 0;
            margin-top: auto;
        }
        
        .footer a {
            color: rgba(255,255,255,0.8);
        }
        
        .footer a:hover {
            color: white;
        }

        .cart-count {
            position: absolute;
            top: -8px;
            right: -8px;
            background-color: #dc3545;
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            font-size: 0.7rem;
            display: flex;
            justify-content: center;
            align-items: center;
        }
    </style>
    
    @yield('styles')
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">SmartCart</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="{{ url('/') }}">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('shop') ? 'active' : '' }}" href="{{ route('shop.index') }}">Shop</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Categories
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            @foreach(\App\Models\Category::whereNull('parent_id')->get() as $category)
                                <li><a class="dropdown-item" href="{{ route('shop.category', $category->slug) }}">{{ $category->name }}</a></li>
                            @endforeach
                        </ul>
                    </li>
                </ul>
                
                <form class="d-flex me-3" action="{{ route('shop.search') }}" method="GET">
                    <input class="form-control me-2" type="search" name="query" placeholder="Search products..." aria-label="Search">
                    <button class="btn btn-outline-light" type="submit">Search</button>
                </form>
                
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link position-relative" href="{{ route('cart.index') }}">
                            <i class="fas fa-shopping-cart"></i>
                            @if(Auth::check() && Auth::user()->cart && Auth::user()->cart->getTotalItems() > 0)
                                <span class="cart-count">{{ Auth::user()->cart->getTotalItems() }}</span>
                            @endif
                        </a>
                    </li>
                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">Register</a>
                        </li>
                    @else
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                {{ Auth::user()->name }}
                            </a>

                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                    Profile
                                </a>
                                <a class="dropdown-item" href="{{ route('orders.index') }}">
                                    My Orders
                                </a>
                                <a class="dropdown-item" href="{{ route('favorites.index') }}">
                                    Favorites
                                </a>
                                @if(Auth::user()->isAdmin())
                                    <a class="dropdown-item" href="{{ route('admin.dashboard') }}">
                                        Admin Panel
                                    </a>
                                @endif
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                   onclick="event.preventDefault();
                                                 document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="main-content py-4">
        <div class="container">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            
            @yield('content')
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4 mb-md-0">
                    <h5>SmartCart</h5>
                    <p>Your one-stop shop for all your shopping needs.</p>
                </div>
                <div class="col-md-2 mb-4 mb-md-0">
                    <h5>Shop</h5>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('shop.index') }}">All Products</a></li>
                        <li><a href="#">Featured Items</a></li>
                        <li><a href="#">New Arrivals</a></li>
                        <li><a href="#">Sales</a></li>
                    </ul>
                </div>
                <div class="col-md-2 mb-4 mb-md-0">
                    <h5>About</h5>
                    <ul class="list-unstyled">
                        <li><a href="#">About Us</a></li>
                        <li><a href="#">Contact</a></li>
                        <li><a href="#">Blog</a></li>
                        <li><a href="#">Careers</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5>Stay Connected</h5>
                    <div class="d-flex gap-3 mb-3">
                        <a href="#" class="text-decoration-none"><i class="fab fa-facebook-f fa-lg"></i></a>
                        <a href="#" class="text-decoration-none"><i class="fab fa-twitter fa-lg"></i></a>
                        <a href="#" class="text-decoration-none"><i class="fab fa-instagram fa-lg"></i></a>
                        <a href="#" class="text-decoration-none"><i class="fab fa-linkedin-in fa-lg"></i></a>
                    </div>
                    <p class="small">&copy; {{ date('Y') }} SmartCart. All rights reserved.</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    @yield('scripts')
</body>
</html> 