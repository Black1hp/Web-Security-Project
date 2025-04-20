<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-color: #f6f6f6;
            --text-color: #333;
            --card-bg: white;
            --form-bg: white;
            --input-bg: #f0f0f0;
            --primary-color: #925fe2;
            --primary-dark: #7f49d3;
            --error-color: #e74c3c;
        }
        
        [data-theme="dark"] {
            --bg-color: #121212;
            --text-color: #e0e0e0;
            --card-bg: #1e1e1e;
            --form-bg: #1e1e1e;
            --input-bg: #2a2a2a;
            --primary-color: #925fe2;
            --primary-dark: #7f49d3;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }
        
        body {
            background-color: var(--bg-color);
            color: var(--text-color);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            transition: background-color 0.3s, color 0.3s;
        }
        
        .login-container {
            background-color: var(--card-bg);
            width: 420px;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            animation: fadeInUp 0.5s ease-out forwards;
            transition: background-color 0.3s;
        }
        
        .login-title {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .login-title h1 {
            font-size: 28px;
            color: var(--primary-color);
            margin-bottom: 10px;
        }
        
        .login-title p {
            font-size: 14px;
            color: var(--text-color);
            opacity: 0.7;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-label {
            display: block;
            font-size: 14px;
            margin-bottom: 8px;
            color: var(--text-color);
            font-weight: 500;
        }
        
        .form-input {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid rgba(0, 0, 0, 0.1);
            border-radius: 5px;
            font-size: 14px;
            transition: border-color 0.3s, box-shadow 0.3s;
            background-color: var(--input-bg);
            color: var(--text-color);
        }
        
        .form-input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(146, 95, 226, 0.2);
        }
        
        .btn {
            display: block;
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 5px;
            background-color: var(--primary-color);
            color: white;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.2s;
        }
        
        .btn:hover {
            background-color: var(--primary-dark);
            transform: translateY(-2px);
        }
        
        .btn:active {
            transform: translateY(1px);
        }
        
        .error-message {
            color: var(--error-color);
            font-size: 14px;
            margin-top: 5px;
        }
        
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
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-title">
            <h1>Admin Portal</h1>
            <p>Enter your credentials to access the admin dashboard</p>
        </div>
        
        <form action="{{ route('admin.login.post') }}" method="POST">
            @csrf
            
            <div class="form-group">
                <label class="form-label" for="email">Email Address</label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    class="form-input" 
                    placeholder="Enter your email" 
                    value="{{ old('email') }}" 
                    required
                >
                @error('email')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="form-group">
                <label class="form-label" for="password">Password</label>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    class="form-input" 
                    placeholder="Enter your password" 
                    required
                >
            </div>
            
            <button type="submit" class="btn">Sign In</button>
        </form>
    </div>
</body>
</html> 