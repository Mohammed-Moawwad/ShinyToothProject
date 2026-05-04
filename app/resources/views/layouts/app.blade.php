<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ShinyTooth - Dental Clinic Management')</title>
    
    <!-- CSRF Token for AJAX requests -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Google Fonts - Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS from CDN -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.0/font/bootstrap-icons.min.css" rel="stylesheet">
    
    <!-- Custom CSS (compiled via Vite) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Livewire CSS -->
    @livewireStyles
    
    <style>
        /* Additional custom styles */
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background-color: #f8f9fa;
        }
        
        main {
            flex: 1;
        }
        
        .page-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px 0;
            margin-bottom: 30px;
        }
        
        .page-header h1 {
            margin: 0;
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Navigation Bar (hidden on auth pages) -->
    @if (!in_array(Route::currentRouteName(), ['login', 'register']))
    <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom">
        <div class="container-lg">
            <a class="navbar-brand fw-bold" href="/">
                <i class="bi bi-tooth"></i> ShinyTooth
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/services">Services</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/doctors">Our Dentists</a>
                    </li>
                    
                    @auth
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                                {{ Auth::user()->name ?? 'User' }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="{{ route('patient.dashboard') }}">Dashboard</a></li>
                                <li><a class="dropdown-item" href="{{ route('subscriptions.my') }}">My Subscription</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="/logout" method="POST" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="dropdown-item">Logout</button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="/login">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/register">Register</a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>
    @endif
    
    <!-- Main Content -->
    <main>
        @if (in_array(Route::currentRouteName(), ['login', 'register']))
            @yield('content')
        @else
        <div class="container-lg py-4">
            <!-- Flash Messages -->
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Oops! Something went wrong:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            <!-- Page Content -->
            @yield('content')
        </div>
        @endif
    </main>
    
    <!-- Footer (hidden on auth pages) -->
    @if(!in_array(Route::currentRouteName(), ['login', 'register']))
    <footer class="mt-auto" style="background: linear-gradient(135deg, #003263 0%, #047a6e 100%); color: #fff;">
        <div class="container-lg py-5">
            <div class="row">
                <div class="col-md-6 mb-4">
                    <h5 class="mb-3" style="font-weight: 700; font-size: 1.1rem;">Quick Links</h5>
                    <ul class="list-unstyled">
                        <li style="margin-bottom: 0.8rem;"><a href="/" class="text-decoration-none" style="color: rgba(255,255,255,.85); transition: color .2s;">Home</a></li>
                        <li style="margin-bottom: 0.8rem;"><a href="/services" class="text-decoration-none" style="color: rgba(255,255,255,.85); transition: color .2s;">Services</a></li>
                        <li><a href="/doctors" class="text-decoration-none" style="color: rgba(255,255,255,.85); transition: color .2s;">Dentists</a></li>
                    </ul>
                </div>
                
                <div class="col-md-6 mb-4">
                    <h5 class="mb-3" style="font-weight: 700; font-size: 1.1rem;">Contact Us</h5>
                    <ul class="list-unstyled">
                        <li style="margin-bottom: 0.8rem; color: rgba(255,255,255,.85);">📞 +966 11 XXXX XXXX</li>
                        <li style="margin-bottom: 0.8rem; color: rgba(255,255,255,.85);">📧 info@shinytooth.com</li>
                        <li style="color: rgba(255,255,255,.85);">📍 Riyadh, Saudi Arabia</li>
                    </ul>
                </div>
            </div>
            
            <hr style="border-color: rgba(255,255,255,.2); margin: 2rem 0;">
        </div>
    </footer>
    @endif
    
    <!-- Livewire Scripts -->
    @livewireScripts
    
    <!-- Popper.js and Bootstrap JS from CDN -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.11.8/umd/popper.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.min.js"></script>
    
    <!-- Application Scripts (Alpine.js, etc. loaded via Vite) -->
    @stack('scripts')
</body>
</html>
