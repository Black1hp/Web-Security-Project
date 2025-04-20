<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results - Student Portal</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            /* Light mode colors */
            --bg-color: #f6f6f6;
            --text-color: #333;
            --secondary-text: #777;
            --card-bg: white;
            --card-hover-shadow: rgba(0, 0, 0, 0.1);
            --sidebar-bg: #925fe2;
            --sidebar-text: white;
            --input-bg: #f0f0f0;
            --card-border: #eee;
            --result-card-bg: #f8f8f8;
            --notification-bg: #333;
            --notification-text: white;
            --description-color: #666;
            --toggle-bg: #f0f0f0;
            --toggle-button: #925fe2;
        }
        
        [data-theme="dark"] {
            /* Dark mode colors */
            --bg-color: #121212;
            --text-color: #e0e0e0;
            --secondary-text: #aaa;
            --card-bg: #1e1e1e;
            --card-hover-shadow: rgba(255, 255, 255, 0.1);
            --sidebar-bg: #7149c6;
            --sidebar-text: #f0f0f0;
            --input-bg: #2a2a2a;
            --card-border: #333;
            --result-card-bg: #252525;
            --notification-bg: #e0e0e0;
            --notification-text: #121212;
            --description-color: #b0b0b0;
            --toggle-bg: #2a2a2a;
            --toggle-button: #925fe2;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
            outline: none;
        }
        
        body {
            margin: 0;
            padding: 0;
            overflow-x: hidden;
            background-color: var(--bg-color);
            color: var(--text-color);
            transition: background-color 0.3s, color 0.3s;
        }
        
        .container {
            display: flex;
            width: 100%;
            min-height: 100vh;
        }
        
        .sidebar {
            position: fixed;
            width: 180px;
            height: 100vh;
            top: 0;
            left: 0;
            background-color: var(--sidebar-bg);
            padding: 30px 0;
            display: flex;
            flex-direction: column;
            transition: background-color 0.3s, transform 0.3s ease-out;
            z-index: 100;
        }
        
        .logo-container {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 40px;
            position: relative;
        }
        
        .badge {
            position: absolute;
            top: -5px;
            right: 15px;
            background-color: #ff6b00;
            color: white;
            font-size: 12px;
            font-weight: bold;
            padding: 2px 6px;
            border-radius: 4px;
        }
        
        .logo {
            width: 90px;
            height: 90px;
        }
        
        .nav-items {
            display: flex;
            flex-direction: column;
            gap: 22px;
            padding-left: 25px;
        }
        
        .nav-item {
            color: var(--sidebar-text);
            font-size: 15px;
            text-decoration: none;
            font-weight: normal;
            opacity: 0.9;
            transition: opacity 0.2s, color 0.3s, transform 0.2s;
            display: block;
        }
        
        .nav-item:hover {
            opacity: 1;
            transform: translateX(5px);
        }
        
        .nav-item.active {
            font-weight: 600;
            opacity: 1;
        }
        
        .logout-link {
            margin-top: auto;
            color: var(--sidebar-text);
            font-size: 15px;
            text-decoration: none;
            padding: 40px 0 20px 25px;
            opacity: 0.9;
            transition: color 0.3s;
        }
        
        .main-content {
            margin-left: 180px;
            padding: 20px 25px;
            width: calc(100% - 180px);
        }
        
        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            animation: fadeIn 0.5s ease-out forwards;
        }
        
        .search-bar {
            width: 400px;
            height: 40px;
            background-color: var(--input-bg);
            border-radius: 20px;
            display: flex;
            align-items: center;
            padding: 0 20px;
            position: relative;
            transition: background-color 0.3s, box-shadow 0.3s;
        }
        
        .search-bar:focus-within {
            box-shadow: 0 0 0 2px var(--sidebar-bg);
        }
        
        .search-input {
            flex: 1;
            border: none;
            background: transparent;
            height: 100%;
            color: var(--text-color);
            font-size: 14px;
            outline: none;
            transition: color 0.3s;
        }
        
        .search-input::placeholder {
            color: var(--secondary-text);
            font-size: 14px;
            transition: color 0.3s;
        }
        
        .search-button {
            background: none;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .search-button svg {
            stroke: var(--secondary-text);
            transition: stroke 0.3s;
        }
        
        .user-section {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .theme-toggle {
            width: 50px;
            height: 24px;
            background-color: var(--toggle-bg);
            border-radius: 12px;
            position: relative;
            cursor: pointer;
            margin-right: 10px;
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
        
        .user-info {
            text-align: right;
        }
        
        .username {
            font-weight: 600;
            color: var(--text-color);
            font-size: 15px;
            transition: color 0.3s;
        }
        
        .user-details {
            color: var(--secondary-text);
            font-size: 13px;
            transition: color 0.3s;
        }
        
        .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            object-fit: cover;
            transition: transform 0.2s;
            cursor: pointer;
        }
        
        .user-avatar:hover {
            transform: scale(1.1);
        }
        
        .notification-icon {
            width: 20px;
            height: 20px;
            position: relative;
            cursor: pointer;
            transition: transform 0.2s;
        }
        
        .notification-icon:hover {
            transform: scale(1.15);
        }
        
        .notification-icon svg {
            stroke: var(--text-color);
            transition: stroke 0.3s;
        }
        
        .notification-dot {
            position: absolute;
            top: -5px;
            right: -5px;
            width: 8px;
            height: 8px;
            background-color: #ff0000;
            border-radius: 50%;
        }
        
        .search-results-container {
            background-color: var(--card-bg);
            border-radius: 16px;
            padding: 25px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            transition: background-color 0.3s, box-shadow 0.3s;
            animation: fadeIn 0.7s ease-out forwards;
        }
        
        .search-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            align-items: center;
        }
        
        .search-title {
            font-size: 18px;
            font-weight: 600;
            color: var(--text-color);
            transition: color 0.3s;
        }
        
        .search-query {
            font-weight: 600;
            color: #925fe2;
        }
        
        .search-count {
            font-size: 14px;
            color: var(--secondary-text);
            transition: color 0.3s;
        }
        
        .search-results {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
        }
        
        .result-card {
            background-color: var(--result-card-bg);
            border-radius: 12px;
            padding: 20px;
            transition: transform 0.3s, box-shadow 0.3s, background-color 0.3s;
            position: relative;
            border: 1px solid var(--card-border);
            animation: fadeInUp 0.5s ease-out forwards;
            opacity: 0;
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
        
        .result-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px var(--card-hover-shadow);
        }
        
        .result-type {
            position: absolute;
            top: 12px;
            right: 12px;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 500;
            color: white;
        }
        
        .result-type.course {
            background-color: #3498db;
        }
        
        .result-type.instructor {
            background-color: #2ecc71;
        }
        
        .result-type.student {
            background-color: #e74c3c;
        }
        
        .result-type.notice {
            background-color: #f39c12;
        }
        
        .result-type.project {
            background-color: #9b59b6;
        }
        
        .result-title {
            font-size: 16px;
            margin-bottom: 10px;
            padding-right: 70px;
            color: var(--text-color);
            transition: color 0.3s;
        }
        
        .result-description {
            font-size: 14px;
            color: var(--description-color);
            margin-bottom: 15px;
            line-height: 1.4;
            transition: color 0.3s;
        }
        
        .result-link {
            display: inline-block;
            background-color: #925fe2;
            color: white;
            padding: 8px 15px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: background-color 0.2s, transform 0.2s;
        }
        
        .result-link:hover {
            background-color: #7f49d3;
            transform: translateY(-2px);
        }
        
        .result-link:active {
            transform: translateY(1px);
        }
        
        .no-results {
            text-align: center;
            padding: 40px 0;
        }
        
        .no-results h2 {
            font-size: 20px;
            margin-bottom: 10px;
            color: var(--text-color);
            transition: color 0.3s;
        }
        
        .no-results p {
            color: var(--secondary-text);
            margin-bottom: 20px;
            transition: color 0.3s;
        }
        
        .notification {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: var(--notification-bg);
            color: var(--notification-text);
            padding: 10px 20px;
            border-radius: 4px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
            transform: translateY(100px);
            opacity: 0;
            transition: transform 0.3s, opacity 0.3s, background-color 0.3s, color 0.3s;
            z-index: 1000;
        }
        
        .notification.show {
            transform: translateY(0);
            opacity: 1;
            animation: pulse 1.5s ease-in-out;
        }
        
        @keyframes pulse {
            0% { transform: translateY(0); }
            10% { transform: translateY(-5px); }
            20% { transform: translateY(0); }
            30% { transform: translateY(-3px); }
            40% { transform: translateY(0); }
            100% { transform: translateY(0); }
        }
        
        @media (max-width: 768px) {
            .sidebar {
                width: 60px;
                padding: 20px 0;
            }
            
            .nav-items {
                padding-left: 0;
                align-items: center;
            }
            
            .nav-item span, .logo-text, .user-info, .logout-link span {
                display: none;
            }
            
            .main-content {
                margin-left: 60px;
                width: calc(100% - 60px);
            }
            
            .search-bar {
                width: 200px;
            }
            
            .search-results {
                grid-template-columns: 1fr;
            }
            
            .theme-toggle {
                width: 40px;
            }
            
            [data-theme="dark"] .theme-toggle-button {
                transform: translateX(16px);
            }
        }
        
        /* Animation delays for cards */
        .search-results .result-card:nth-child(1) { animation-delay: 0.1s; }
        .search-results .result-card:nth-child(2) { animation-delay: 0.2s; }
        .search-results .result-card:nth-child(3) { animation-delay: 0.3s; }
        .search-results .result-card:nth-child(4) { animation-delay: 0.4s; }
        .search-results .result-card:nth-child(5) { animation-delay: 0.5s; }
        .search-results .result-card:nth-child(6) { animation-delay: 0.6s; }
        .search-results .result-card:nth-child(7) { animation-delay: 0.7s; }
        .search-results .result-card:nth-child(8) { animation-delay: 0.8s; }
        
        /* Page load animations */
        .sidebar {
            animation: slideInLeft 0.5s ease-out forwards;
        }
        
        .top-bar {
            animation: fadeIn 0.5s ease-out forwards;
        }
        
        .search-results-container {
            animation: fadeIn 0.7s ease-out forwards;
        }
        
        @keyframes slideInLeft {
            from {
                transform: translateX(-20px);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
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
        
        /* Theme transition */
        html {
            transition: background-color 0.5s ease;
        }
        
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: var(--bg-color);
        }
        
        ::-webkit-scrollbar-thumb {
            background: var(--sidebar-bg);
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: #7f49d3;
        }
        
        /* Responsive design animation adjustments */
    </style>
</head>
<body>
    <div class="container">
        <div class="sidebar">
            <div class="logo-container">
                <span class="badge">V1</span>
                <img src="/img/logo.png" alt="Logo" class="logo">
            </div>
            
            <div class="nav-items">
                <a href="{{ route('dashboard') }}" class="nav-item">Dashboard</a>
                <a href="#" class="nav-item">Personal Info</a>
                <a href="#" class="nav-item">Academics</a>
                <a href="#" class="nav-item">Clubs</a>
                <a href="#" class="nav-item">Achievements</a>
                <a href="#" class="nav-item">Skills</a>
                <a href="#" class="nav-item">Projects</a>
                <a href="#" class="nav-item">Notices</a>
            </div>
            
            <a href="{{ route('logout') }}" class="logout-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
            @if(Auth::check())
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
            @else
            <form id="logout-form" action="{{ route('login') }}" method="GET" style="display: none;"></form>
            @endif
        </div>
        
        <div class="main-content">
            <div class="top-bar">
                <form action="{{ route('search') }}" method="GET" class="search-bar">
                    <input type="text" name="query" class="search-input" placeholder="Search..." value="{{ $query }}">
                    <button type="submit" class="search-button">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                    </button>
                </form>
                
                <div class="user-section">
                    <div class="theme-toggle" id="theme-toggle">
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
                    
                    <div class="notification-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                            <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                        </svg>
                        <div class="notification-dot"></div>
                    </div>
                    
                    <div class="user-info">
                        <div class="username">{{ Auth::check() ? Auth::user()->name : 'Guest User' }}</div>
                        <div class="user-details">{{ Auth::check() ? Auth::user()->email : 'Not logged in' }}</div>
                    </div>
                    
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::check() ? Auth::user()->name : 'Guest') }}&background=925fe2&color=fff" alt="User Avatar" class="user-avatar">
                </div>
            </div>
            
            <div class="search-results-container">
                <div class="search-header">
                    <div class="search-title">
                        Search results for: <span class="search-query">{{ $query }}</span>
                    </div>
                    <div class="search-count">
                        {{ count($results) }} results found
                    </div>
                </div>
            
                <div class="search-results">
                    @if(count($results) > 0)
                        @foreach($results as $result)
                            <div class="result-card">
                                <span class="result-type {{ $result['type'] }}">{{ ucfirst($result['type']) }}</span>
                                <h2 class="result-title">{{ $result['title'] }}</h2>
                                <p class="result-description">{{ $result['description'] }}</p>
                                <a href="{{ $result['url'] }}" class="result-link">View Details</a>
                            </div>
                        @endforeach
                    @else
                        <div class="no-results">
                            <h2>No results found</h2>
                            <p>Try different keywords or check your spelling</p>
                            <a href="{{ route('dashboard') }}" class="result-link">Return to Dashboard</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <div class="notification" id="notification"></div>
    
    <script>
        // Function to show notification
        function showNotification(message) {
            const notification = document.getElementById('notification');
            notification.textContent = message;
            notification.classList.add('show');
            
            setTimeout(() => {
                notification.classList.remove('show');
            }, 3000);
        }
        
        // Get links and add click event listener
        document.querySelectorAll('.result-link').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const url = this.getAttribute('href');
                
                showNotification('This section is under development');
                
                // Redirect after showing notification
                setTimeout(() => {
                    window.location.href = "{{ route('dashboard') }}";
                }, 1500);
            });
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
                
                showNotification(newTheme.charAt(0).toUpperCase() + newTheme.slice(1) + ' mode enabled');
            });
        });
    </script>
</body>
</html> 