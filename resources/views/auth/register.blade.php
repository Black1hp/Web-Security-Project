<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Student Portal</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        :root {
            /* Light mode colors */
            --bg-color: #f6f6f6;
            --text-color: #333;
            --card-bg: white;
            --form-bg: white;
            --input-bg: #f0f0f0;
            --primary-color: #925fe2;
            --primary-dark: #7f49d3;
            --toggle-bg: #f0f0f0;
            --toggle-button: #925fe2;
        }
        
        [data-theme="dark"] {
            /* Dark mode colors */
            --bg-color: #1c1d21;
            --text-color: white;
            --card-bg: #1c1d21;
            --form-bg: #1c1d21;
            --input-bg: #2a2a2a;
            --primary-color: #925fe2;
            --primary-dark: #7f49d3;
            --toggle-bg: #2a2a2a;
            --toggle-button: #925fe2;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
            outline: none;
        }
        
        /* Remove any focus outlines that might show blue */
        *:focus {
            outline: none;
        }
        
        /* Improve the appearance of form elements */
        input, button {
            border: none;
            outline: none;
        }
        
        body {
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }
        
        .container {
            background-color: #1c1d21;
            display: flex;
            flex-direction: row;
            justify-content: center;
            width: 100%;
            min-height: 100vh;
            padding: 20px;
        }
        
        .register-wrapper {
            background-color: var(--card-bg);
            width: 1440px;
            height: 1024px;
            position: relative;
            overflow: hidden;
            border-radius: 24px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: background-color 0.3s, transform 0.5s ease-out;
            animation: fadeIn 0.5s ease-out forwards;
        }
        
        .content-container {
            position: relative;
            height: 1024px;
            border-radius: 24px;
            overflow: hidden;
        }
        
        .vector-2 {
            position: absolute;
            width: 402px;
            height: 342px;
            top: 0;
            left: 1038px;
            animation: floatAnimation 4s ease-in-out infinite;
        }
        
        .vector-3 {
            position: absolute;
            width: 763px;
            height: 616px;
            top: 408px;
            left: 677px;
            animation: floatAnimation 4s ease-in-out infinite;
            animation-delay: 0.5s;
        }
        
        .vector-4 {
            position: absolute;
            width: 421px;
            height: 389px;
            top: 245px;
            left: 459px;
            animation: floatAnimation 4s ease-in-out infinite;
            animation-delay: 1s;
        }
        
        .vector-5 {
            position: absolute;
            width: 177px;
            height: 200px;
            top: 175px;
            left: 1263px;
            animation: floatAnimation 4s ease-in-out infinite;
            animation-delay: 1.5s;
        }
        
        .vector {
            position: absolute;
            width: 297px;
            height: 221px;
            top: 0;
            left: 868px;
            animation: floatAnimation 4s ease-in-out infinite;
            animation-delay: 2s;
        }
        
        .image-decoration {
            position: absolute;
            width: 297px;
            height: 273px;
            top: 671px;
            left: 664px;
            animation: floatAnimation 4s ease-in-out infinite;
            animation-delay: 1s;
        }
        
        .element-copy {
            position: absolute;
            width: 770px;
            height: 100%;
            top: 0;
            right: 0;
            object-fit: cover;
            z-index: 0;
            background-color: var(--primary-color);
            border-radius: 0 24px 24px 0;
            transition: background-color 0.3s;
        }
        
        .boys-illustration {
            position: absolute;
            width: 600px;
            height: auto;
            top: 55%;
            right: 120px;
            transform: translateY(-40%);
            z-index: 1;
            margin-top: 0;
            animation: slideInRight 0.8s ease-out forwards;
        }
        
        .boys-illustration::after {
            display: none;
        }
        
        .boys-illustration img {
            position: relative;
            z-index: 1;
            display: block;
            width: 100%;
            height: auto;
        }
        
        .left-panel {
            position: absolute;
            width: 670px;
            height: 1024px;
            top: 0;
            left: 0;
            background-color: var(--card-bg);
            border-radius: 24px 0 0 24px;
            transition: background-color 0.3s;
        }
        
        .register-form-container {
            display: inline-flex;
            flex-direction: column;
            align-items: flex-start;
            gap: 40px;
            position: absolute;
            top: 250px;
            left: 128px;
            animation: fadeInUp 0.8s ease-out forwards;
        }
        
        .form-content {
            display: inline-flex;
            flex-direction: column;
            align-items: flex-start;
            gap: 24px;
            position: relative;
        }
        
        .form-top {
            display: inline-flex;
            flex-direction: column;
            align-items: flex-start;
            gap: 40px;
            position: relative;
        }
        
        .form-title {
            display: inline-flex;
            flex-direction: column;
            align-items: flex-start;
            gap: 12px;
            position: relative;
        }
        
        .title-text {
            font-weight: 700;
            font-size: 48px;
            position: relative;
            width: fit-content;
            margin-top: -1px;
            color: var(--text-color);
            letter-spacing: 0;
            transition: color 0.3s;
        }
        
        .subtitle-text {
            position: relative;
            width: fit-content;
            font-weight: 500;
            color: var(--text-color);
            opacity: 0.6;
            font-size: 16px;
            letter-spacing: 0;
            transition: color 0.3s;
        }
        
        .form-fields {
            display: inline-flex;
            flex-direction: column;
            align-items: flex-start;
            gap: 24px;
            position: relative;
        }
        
        .field-container {
            display: flex;
            flex-direction: column;
            width: 381px;
            align-items: flex-start;
            gap: 12px;
            position: relative;
        }
        
        .field-label {
            position: relative;
            width: fit-content;
            margin-top: -1px;
            font-weight: 400;
            color: rgba(255, 255, 255, 0.5);
            font-size: 16px;
            letter-spacing: 0;
        }
        
        .field-input {
            width: 381px;
            background-color: transparent;
            border: none;
            border-bottom: 1px solid rgba(255, 255, 255, 0.5);
            padding-bottom: 8px;
            color: white;
            font-size: 16px;
            outline: none;
        }
        
        .password-field-header {
            display: inline-flex;
            align-items: flex-start;
            justify-content: space-between;
            width: 100%;
            position: relative;
        }
        
        .toggle-password {
            background: none;
            border: none;
            cursor: pointer;
        }
        
        .eye-icon {
            width: 24px;
            height: 24px;
        }
        
        .register-button {
            display: flex;
            width: 393px;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 12px 0;
            position: relative;
            background-color: #9c6fe4;
            border-radius: 12px;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.2s, box-shadow 0.3s;
            margin-top: 20px;
        }
        
        .register-button:hover {
            background-color: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }
        
        .register-button:active {
            transform: translateY(1px);
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }
        
        .button-text {
            font-weight: 400;
            font-size: 16px;
            position: relative;
            width: fit-content;
            margin-top: -1px;
            color: white;
            letter-spacing: 0;
        }
        
        .welcome-container {
            display: inline-flex;
            flex-direction: column;
            align-items: flex-start;
            gap: 12px;
            position: absolute;
            top: 155px;
            left: 730px;
        }
        
        .welcome-title {
            position: relative;
            width: fit-content;
            margin-top: -1px;
            font-weight: 400;
            color: #eeeeee;
            font-size: 80px;
            letter-spacing: 0;
            line-height: 70px;
        }
        
        .welcome-bold {
            font-weight: 700;
        }
        
        .welcome-regular {
            font-weight: 400;
        }
        
        .welcome-subtitle {
            position: relative;
            width: fit-content;
            font-weight: 500;
            color: #eeeeee;
            font-size: 16px;
            letter-spacing: 0;
        }
        
        .login-container {
            display: inline-flex;
            align-items: center;
            gap: 67px;
            position: absolute;
            top: 912px;
            left: 128px;
        }
        
        .login-text {
            position: relative;
            width: fit-content;
            font-weight: 400;
            color: rgba(255, 255, 255, 0.5);
            font-size: 16px;
            letter-spacing: 0;
        }
        
        .login-button {
            display: flex;
            width: 100px;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 12px 24px;
            position: relative;
            background-color: #333437;
            border-radius: 8px;
            text-decoration: none;
            transition: background-color 0.3s;
        }
        
        .login-button:hover {
            background-color: #444548;
        }
        
        .login-button-text {
            position: relative;
            width: fit-content;
            margin-top: -1px;
            margin-left: -4px;
            margin-right: -4px;
            font-weight: 400;
            color: white;
            font-size: 16px;
            letter-spacing: 0;
        }
        
        .copy-image {
            position: absolute;
            width: 767px;
            height: 628px;
            top: 368px;
            left: 673px;
            object-fit: cover;
        }
        
        .error-message {
            color: #ef4444;
            font-size: 14px;
            margin-top: 4px;
        }
        
        .theme-toggle {
            position: absolute;
            top: 30px;
            right: 790px;
            width: 50px;
            height: 24px;
            background-color: var(--toggle-bg);
            border-radius: 12px;
            cursor: pointer;
            z-index: 10;
            transition: background-color 0.3s, transform 0.2s;
        }
        
        .theme-toggle:hover {
            transform: scale(1.05);
        }
        
        .theme-toggle-button {
            position: absolute;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            background-color: var(--toggle-button);
            top: 3px;
            left: 3px;
            transition: transform 0.3s, background-color 0.3s;
        }
        
        [data-theme="dark"] .theme-toggle-button {
            transform: translateX(26px);
        }
        
        .theme-toggle-icon {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 14px;
            height: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .sun-icon {
            right: 4px;
            color: #ffc107;
            opacity: 1;
        }
        
        .moon-icon {
            left: 4px;
            color: #384364;
            opacity: 0.5;
        }
        
        [data-theme="dark"] .sun-icon {
            opacity: 0.5;
        }
        
        [data-theme="dark"] .moon-icon {
            opacity: 1;
            color: #afc8ff;
        }
        
        /* Animation for decorative vectors */
        .vector-2, .vector-3, .vector-4, .vector-5, .vector, .image-decoration {
            animation: floatAnimation 4s ease-in-out infinite;
        }
        
        .vector-3 { animation-delay: 0.5s; }
        .vector-4 { animation-delay: 1s; }
        .vector-5 { animation-delay: 1.5s; }
        .vector { animation-delay: 2s; }
        .image-decoration { animation-delay: 1s; }
        
        /* Animations for form elements */
        .form-input-container {
            animation: fadeInUp 0.6s ease-out forwards;
            animation-delay: 0.2s;
            opacity: 0;
        }
        
        .register-button-container {
            animation: fadeInUp 0.6s ease-out forwards;
            animation-delay: 0.4s;
            opacity: 0;
        }
        
        .signin-link {
            animation: fadeInUp 0.6s ease-out forwards;
            animation-delay: 0.5s;
            opacity: 0;
        }
        
        .girls-illustration {
            animation: slideInRight 0.8s ease-out forwards;
        }
        
        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }
        
        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translate(40px, -50%);
            }
            to {
                opacity: 1;
                transform: translate(0, -50%);
            }
        }
        
        @keyframes floatAnimation {
            0% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-10px);
            }
            100% {
                transform: translateY(0);
            }
        }
        
        /* Button hover animations */
        .register-button {
            transition: background-color 0.3s, transform 0.2s, box-shadow 0.3s;
        }
        
        .register-button:hover {
            background-color: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }
        
        .register-button:active {
            transform: translateY(1px);
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }
        
        /* Form input animations */
        .form-input {
            transition: border-color 0.3s, box-shadow 0.3s, background-color 0.3s;
            background-color: var(--input-bg) !important;
            color: var(--text-color) !important;
        }
        
        .form-input:focus {
            border-color: var(--primary-color) !important;
            box-shadow: 0 0 0 3px rgba(146, 95, 226, 0.2);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="register-wrapper">
            <div class="content-container">
                <!-- Theme toggle -->
                <div class="theme-toggle" id="theme-toggle" aria-label="Toggle theme">
                    <div class="theme-toggle-button"></div>
                    <div class="theme-toggle-icon moon-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path>
                        </svg>
                    </div>
                    <div class="theme-toggle-icon sun-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="5"></circle>
                            <line x1="12" y1="1" x2="12" y2="3"></line>
                            <line x1="12" y1="21" x2="12" y2="23"></line>
                            <line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line>
                            <line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line>
                            <line x1="1" y1="12" x2="3" y2="12"></line>
                            <line x1="21" y1="12" x2="23" y2="12"></line>
                            <line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line>
                            <line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line>
                        </svg>
                    </div>
                </div>

                <div class="boys-illustration">
                    <img src="{{ asset('images/boyss.png') }}" alt="Boys illustration">
                </div>

                <div class="register-form-container">
                    <form method="POST" action="{{ route('register.post') }}">
                        @csrf
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
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
                        <div class="form-content">
                            <div class="form-top">
                                <div class="form-title">
                                    <div class="title-text">Register</div>
                                    <div class="subtitle-text">Create your account</div>
                                </div>

                                <div class="form-fields">
                                    <div class="field-container">
                                        <label for="name" class="field-label">Full Name</label>
                                        <input type="text" id="name" name="name" class="field-input" value="{{ old('name') }}" required>
                                        @error('name')
                                            <span class="error-message">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="field-container">
                                        <label for="email" class="field-label">Email</label>
                                        <input type="email" id="email" name="email" class="field-input" value="{{ old('email') }}" required>
                                        @error('email')
                                            <span class="error-message">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="field-container">
                                        <div class="password-field-header">
                                            <label for="password" class="field-label">Password</label>
                                            <button type="button" id="togglePassword" class="toggle-password" aria-label="Toggle password visibility">
                                                <img class="eye-icon" alt="Toggle password visibility" src="{{ asset('images/eye-slash-fill.svg') }}">
                                            </button>
                                        </div>
                                        <input type="password" id="password" name="password" class="field-input" required>
                                        @error('password')
                                            <span class="error-message">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="field-container">
                                        <div class="password-field-header">
                                            <label for="password_confirmation" class="field-label">Confirm Password</label>
                                            <button type="button" id="toggleConfirmPassword" class="toggle-password" aria-label="Toggle confirm password visibility">
                                                <img class="eye-icon" alt="Toggle confirm password visibility" src="{{ asset('images/eye-slash-fill.svg') }}">
                                            </button>
                                        </div>
                                        <input type="password" id="password_confirmation" name="password_confirmation" class="field-input" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="register-button">
                            <div class="button-text">Register</div>
                        </button>
                    </form>
                </div>

                <div class="welcome-container">
                    <p class="welcome-title">
                        <span class="welcome-bold">Welcome to <br></span>
                        <span class="welcome-regular">Student Portal</span>
                    </p>
                    <p class="welcome-subtitle">Create an account to get started</p>
                </div>

                <div class="login-container">
                    <div class="login-text">Already have an account?</div>
                    <a href="{{ route('login') }}" class="login-button">
                        <div class="login-button-text">Login</div>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            
            // Change the eye icon based on password visibility
            const eyeIcon = this.querySelector('img');
            if (type === 'text') {
                eyeIcon.src = "{{ asset('images/eye-fill.svg') }}";
            } else {
                eyeIcon.src = "{{ asset('images/eye-slash-fill.svg') }}";
            }
        });

        document.getElementById('toggleConfirmPassword').addEventListener('click', function() {
            const passwordInput = document.getElementById('password_confirmation');
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            
            // Change the eye icon based on password visibility
            const eyeIcon = this.querySelector('img');
            if (type === 'text') {
                eyeIcon.src = "{{ asset('images/eye-fill.svg') }}";
            } else {
                eyeIcon.src = "{{ asset('images/eye-slash-fill.svg') }}";
            }
        });

        // Theme toggle functionality
        document.addEventListener('DOMContentLoaded', function() {
            const themeToggle = document.getElementById('theme-toggle');
            const htmlElement = document.documentElement;
            
            // Check if user has a saved preference
            const savedTheme = localStorage.getItem('theme');
            if (savedTheme) {
                htmlElement.setAttribute('data-theme', savedTheme);
            }
            
            themeToggle.addEventListener('click', function() {
                const currentTheme = htmlElement.getAttribute('data-theme');
                const newTheme = currentTheme === 'light' ? 'dark' : 'light';
                
                htmlElement.setAttribute('data-theme', newTheme);
                localStorage.setItem('theme', newTheme);
            });
        });
    </script>
</body>
</html> 