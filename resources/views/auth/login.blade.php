<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Student Portal</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
            height: 100vh;
        }
        
        .login-container {
            max-width: 900px;
            margin: 0 auto;
        }
        
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        
        .card-img {
            background: linear-gradient(135deg, #925fe2, #6e45b9);
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 2rem;
            color: white;
        }
        
        .login-form {
            padding: 2.5rem;
        }
        
        .social-login-btn {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 10px;
            transition: all 0.2s;
        }
        
        .social-login-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        
        .btn-primary {
            background-color: #925fe2;
            border-color: #925fe2;
        }
        
        .btn-primary:hover {
            background-color: #7f49d3;
            border-color: #7f49d3;
        }
        
        .divider {
            display: flex;
            align-items: center;
            color: #6c757d;
            font-size: 0.9rem;
            margin: 1.5rem 0;
        }
        
        .divider::before, .divider::after {
            content: "";
            flex: 1;
            height: 1px;
            background: #dee2e6;
        }
        
        .divider span {
            padding: 0 1rem;
        }
        
        .form-floating label {
            color: #6c757d;
        }
        
        .btn-toggle-password {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            border: none;
            background: transparent;
            color: #6c757d;
        }
        
        .welcome-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        
        .welcome-subtitle {
            font-size: 1rem;
            font-weight: 400;
            margin-bottom: 2rem;
            opacity: 0.9;
        }
        
        .illustration {
            max-width: 100%;
            height: auto;
            margin-top: 2rem;
        }
        
        .form-control:focus {
            border-color: #925fe2;
            box-shadow: 0 0 0 0.25rem rgba(146, 95, 226, 0.25);
        }
        
        .alert {
            border-radius: 10px;
        }
    </style>
</head>
<body>
    <div class="container d-flex align-items-center justify-content-center" style="min-height: 100vh;">
        <div class="login-container">
            <div class="card">
                <div class="row g-0">
                    <div class="col-md-6">
                        <div class="login-form">
                            <h2 class="mb-4 fw-bold">Login</h2>
                            <p class="text-muted mb-4">Welcome back! Please enter your credentials</p>
                            
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            
                            @if (session('status'))
                                <div class="alert alert-success">
                                    {{ session('status') }}
                                </div>
                            @endif
                            
                            <form method="POST" action="{{ route('login.post') }}">
                                @csrf
                                
                                <div class="form-floating mb-3">
                                    <input type="email" class="form-control" id="email" name="email" placeholder="name@example.com" value="{{ old('email') }}" required>
                                    <label for="email">Email address</label>
                                </div>
                                
                                <div class="form-floating mb-3 position-relative">
                                    <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                                    <label for="password">Password</label>
                                    <button type="button" class="btn-toggle-password" id="togglePassword">
                                        <i class="fa-regular fa-eye-slash"></i>
                                    </button>
                                </div>
                                
                                <div class="mb-3 d-flex justify-content-end">
                                    <a href="{{ route('password.forgot') }}" class="text-decoration-none text-primary">Forgot Password?</a>
                                </div>
                                
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary py-2">Login</button>
                                </div>
                            </form>
                            
                            <div class="divider">
                                <span>or sign in with</span>
                            </div>
                            
                            <div class="d-flex justify-content-center">
                                <a href="{{ route('google.login') }}" class="social-login-btn bg-white border">
                                    <img src="{{ asset('images/Social/google.png') }}" alt="Google" width="24" height="24">
                                </a>
                                <a href="{{ route('github.login') }}" class="social-login-btn bg-dark">
                                    <img src="{{ asset('images/Social/github.png') }}" alt="GitHub" width="24" height="24">
                                </a>
                                <a href="#" class="social-login-btn bg-white border">
                                    <img src="{{ asset('images/Social/linkedin.png') }}" alt="LinkedIn" width="24" height="24">
                                </a>
                            </div>
                            
                            <div class="mt-4 text-center">
                                <p class="mb-0">Don't have an account? <a href="{{ route('register') }}" class="text-decoration-none text-primary">Sign up</a></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 d-none d-md-block">
                        <div class="card-img h-100">
                            <div>
                                <h1 class="welcome-title">Welcome to<br>Student Portal</h1>
                                <p class="welcome-subtitle">Login to access your account and student resources</p>
                            </div>
                            <div class="text-center">
                                <img src="{{ asset('images/boyss.png') }}" alt="Students illustration" class="illustration">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            
            // Toggle eye icon
            const eyeIcon = this.querySelector('i');
            if (type === 'text') {
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            } else {
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            }
        });
    </script>
</body>
</html> 