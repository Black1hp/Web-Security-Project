<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Student Portal</title>
    
    <!-- Bootstrap CSS (Local) -->
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Turnstile (if you're using Cloudflare's Turnstile for CAPTCHA) -->
    <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
    
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
            height: 100vh;
        }
        
        .register-container {
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
        
        .register-form {
            padding: 2.5rem;
        }
        
        .btn-primary {
            background-color: #925fe2;
            border-color: #925fe2;
        }
        
        .btn-primary:hover {
            background-color: #7f49d3;
            border-color: #7f49d3;
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
        
        .turnstile {
            margin: 1rem 0;
        }
    </style>
</head>
<body>
    <div class="container d-flex align-items-center justify-content-center" style="min-height: 100vh;">
        <div class="register-container">
            <div class="card">
                <div class="row g-0">
                    <div class="col-md-6">
                        <div class="register-form">
                            <h2 class="mb-4 fw-bold">Sign Up</h2>
                            <p class="text-muted mb-4">Create your student account</p>
                            
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
                            
                            <form method="POST" action="{{ route('register.post') }}">
                                @csrf
                                
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="name" name="name" placeholder="Full Name" value="{{ old('name') }}" required>
                                    <label for="name">Full Name</label>
                                </div>
                                
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
                                
                                <div class="form-floating mb-3 position-relative">
                                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Confirm Password" required>
                                    <label for="password_confirmation">Confirm Password</label>
                                    <button type="button" class="btn-toggle-password" id="toggleConfirmPassword">
                                        <i class="fa-regular fa-eye-slash"></i>
                                    </button>
                                </div>
                                
                                <!-- Cloudflare Turnstile CAPTCHA - If you're using it -->
                                <div class="turnstile d-flex justify-content-center">
                                    <div class="cf-turnstile" data-sitekey="{{ env('TURNSTILE_SITE_KEY') }}"></div>
                                </div>
                                
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" value="1" id="terms" name="terms" required>
                                    <label class="form-check-label" for="terms">
                                        I agree to the <a href="#" class="text-primary">Terms of Service</a> and <a href="#" class="text-primary">Privacy Policy</a>
                                    </label>
                                </div>
                                
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary py-2">Create Account</button>
                                </div>
                            </form>
                            
                            <div class="mt-4 text-center">
                                <p class="mb-0">Already have an account? <a href="{{ route('login') }}" class="text-decoration-none text-primary">Login</a></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 d-none d-md-block">
                        <div class="card-img h-100">
                            <div>
                                <h1 class="welcome-title">Join our<br>Student Portal</h1>
                                <p class="welcome-subtitle">Sign up to access course materials, grades, and more</p>
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
    
    <!-- Bootstrap JS Bundle with Popper (Local) -->
    <script src="{{ asset('js/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    
    <script>
        // Toggle password visibility
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
        
        // Toggle confirm password visibility
        document.getElementById('toggleConfirmPassword').addEventListener('click', function() {
            const passwordInput = document.getElementById('password_confirmation');
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