<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Student Portal</title>
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
            --circle-bg: #e0e0e0;
            --section-title: #333;
            --welcome-card: #925fe2;
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
            --circle-bg: #333;
            --section-title: #e0e0e0;
            --welcome-card: #7149c6;
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
            animation: slideInLeft 0.5s ease-out forwards;
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
            color: white;
            font-size: 15px;
            text-decoration: none;
            font-weight: normal;
            opacity: 0.9;
            transition: opacity 0.2s, transform 0.2s, color 0.3s;
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
            color: white;
            font-size: 15px;
            text-decoration: none;
            padding: 40px 0 20px 25px;
            opacity: 0.9;
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
        
        .search-badge {
            position: absolute;
            top: -5px;
            left: -5px;
            background-color: #ff6b00;
            color: white;
            font-size: 12px;
            font-weight: bold;
            padding: 2px 6px;
            border-radius: 4px;
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
        
        .settings-icon {
            width: 20px;
            height: 20px;
            cursor: pointer;
            transition: transform 0.2s;
        }
        
        .settings-icon:hover {
            transform: rotate(45deg);
        }
        
        .welcome-card {
            background-color: var(--welcome-card);
            border-radius: 16px;
            padding: 25px;
            margin-bottom: 25px;
            position: relative;
            overflow: hidden;
            height: 180px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            transition: background-color 0.3s, transform 0.3s;
            animation: fadeIn 0.5s ease-out forwards;
        }
        
        .welcome-card:hover {
            transform: translateY(-5px);
        }
        
        .welcome-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            background-color: #ff6b00;
            color: white;
            font-size: 12px;
            font-weight: bold;
            padding: 2px 6px;
            border-radius: 4px;
        }
        
        .welcome-date {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 15px;
            font-size: 14px;
            color: white;
        }
        
        .calendar-badge {
            position: absolute;
            top: 15px;
            left: 130px;
            background-color: #925fe2;
            color: white;
            font-size: 12px;
            border: 1px solid white;
            padding: 3px 8px;
            border-radius: 4px;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .calendar-icon {
            width: 14px;
            height: 14px;
        }
        
        .welcome-title {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 8px;
            color: white;
            position: relative;
            opacity: 0;
            animation: slideInFromLeft 0.5s ease-out forwards;
        }
        
        .welcome-subtitle {
            font-size: 15px;
            opacity: 0.9;
            color: white;
            position: relative;
            opacity: 0;
            animation: slideInFromLeft 0.5s ease-out forwards;
            animation-delay: 0.1s;
        }
        
        .welcome-image {
            position: absolute;
            right: 0;
            top: 0;
            height: 180px;
            width: auto;
            opacity: 0;
            animation: fadeIn 0.5s ease-out 0.3s forwards;
        }
        
        .content-grid {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 20px;
            animation: fadeIn 0.5s ease-out 0.2s forwards;
            opacity: 0;
        }
        
        .info-section {
            margin-bottom: 25px;
            opacity: 0;
            animation: fadeIn 0.5s ease-out forwards;
        }
        
        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }
        
        .section-title {
            font-size: 18px;
            font-weight: 600;
            color: var(--section-title);
            transition: color 0.3s;
        }
        
        .see-all {
            font-size: 14px;
            color: #925fe2;
            text-decoration: none;
        }
        
        .info-card {
            background-color: var(--card-bg);
            border-radius: 16px;
            padding: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            transition: background-color 0.3s, transform 0.3s, box-shadow 0.3s;
            animation: fadeInUp 0.6s ease-out forwards;
            opacity: 0;
        }
        
        .info-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 25px;
            font-size: 16px;
            color: var(--text-color);
            transition: color 0.3s;
        }
        
        .info-row:last-child {
            margin-bottom: 0;
        }
        
        .info-badge {
            background-color: #ff6b00;
            color: white;
            font-size: 12px;
            font-weight: bold;
            padding: 2px 6px;
            border-radius: 4px;
            margin-left: 10px;
        }
        
        .attendance-circle {
            width: 180px;
            height: 180px;
            margin: 0 auto;
            position: relative;
        }
        
        .circle-bg {
            fill: none;
            stroke: var(--circle-bg);
            stroke-width: 25;
            transition: stroke 0.3s;
        }
        
        .circle-progress {
            fill: none;
            stroke: #925fe2;
            stroke-width: 25;
            stroke-linecap: round;
            stroke-dasharray: 502;
            stroke-dashoffset: 48; /* 100% - 90.5% of 502 */
            transform: rotate(-90deg);
            transform-origin: center;
        }
        
        .attendance-percent {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 24px;
            font-weight: 600;
            color: var(--text-color);
            transition: color 0.3s;
        }
        
        .instructors-grid {
            display: flex;
            gap: 15px;
        }
        
        .instructor {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            object-fit: cover;
            background-color: #c291ff;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 16px;
        }
        
        .notice-card {
            background-color: white;
            border-radius: 16px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            margin-bottom: 15px;
            transition: transform 0.3s, box-shadow 0.3s, background-color 0.3s;
            animation: fadeInUp 0.8s ease-out forwards;
            opacity: 0;
        }
        
        .notice-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .notice-title {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 10px;
            color: #333;
        }
        
        .notice-text {
            font-size: 14px;
            color: #777;
            margin-bottom: 10px;
        }
        
        .notice-link {
            font-size: 14px;
            color: #925fe2;
            text-decoration: none;
            transition: color 0.2s, transform 0.2s;
            display: inline-block;
        }
        
        .notice-link:hover {
            color: #7f49d3;
            transform: translateX(5px);
        }
        
        .course-cards {
            display: flex;
            gap: 20px;
        }
        
        .course-card {
            background-color: #f0e6ff;
            border-radius: 16px;
            padding: 20px;
            width: 220px;
            position: relative;
            min-height: 120px;
            transition: transform 0.3s, box-shadow 0.3s;
            animation: fadeInUp 0.7s ease-out forwards;
            opacity: 0;
        }
        
        .course-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(146, 95, 226, 0.2);
        }
        
        .course-title {
            font-size: 16px;
            font-weight: 500;
            color: #925fe2;
            margin-bottom: 15px;
            width: 80%;
        }
        
        .view-btn {
            background-color: #925fe2;
            color: white;
            border: none;
            border-radius: 20px;
            padding: 6px 20px;
            font-size: 14px;
            cursor: pointer;
            transition: background-color 0.2s, transform 0.2s;
        }
        
        .view-btn:hover {
            background-color: #7f49d3;
            transform: translateY(-2px);
        }
        
        .view-btn:active {
            transform: translateY(1px);
        }
        
        .course-icon {
            position: absolute;
            top: 15px;
            right: 15px;
            width: 50px;
            height: 50px;
            background-color: #925fe2;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 20px;
        }
        
        .flash-message {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 15px 25px;
            background-color: #925fe2;
            color: white;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            z-index: 1000;
            opacity: 0;
            transform: translateY(-20px);
            animation: slideIn 0.3s forwards, fadeOut 0.5s 3s forwards, pulse 1.5s ease-in-out;
        }
        
        @keyframes slideIn {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes fadeOut {
            to {
                opacity: 0;
                transform: translateY(-20px);
            }
        }
        
        @keyframes pulse {
            0% { transform: translateY(0); }
            10% { transform: translateY(-5px); }
            20% { transform: translateY(0); }
            30% { transform: translateY(-3px); }
            40% { transform: translateY(0); }
            100% { transform: translateY(0); }
        }
        
        /* Animation delays for elements */
        .info-section:nth-child(1) .info-card { animation-delay: 0.1s; }
        .info-section:nth-child(2) .info-card { animation-delay: 0.2s; }
        .info-section:nth-child(3) .info-card { animation-delay: 0.3s; }
        
        .notice-card:nth-child(1) { animation-delay: 0.4s; }
        .notice-card:nth-child(2) { animation-delay: 0.5s; }
        
        .course-card:nth-child(1) { animation-delay: 0.3s; }
        .course-card:nth-child(2) { animation-delay: 0.4s; }
        
        /* Page load animations */
        .top-bar {
            animation: fadeIn 0.5s ease-out forwards;
        }
        
        .content-grid {
            animation: fadeIn 0.5s ease-out 0.2s forwards;
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
        
        /* Welcome elements animation */
        .welcome-title, .welcome-subtitle {
            position: relative;
            opacity: 0;
            animation: slideInFromLeft 0.5s ease-out forwards;
        }
        
        .welcome-subtitle {
            animation-delay: 0.1s;
        }
        
        @keyframes slideInFromLeft {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        .welcome-image {
            position: absolute;
            right: 0;
            top: 0;
            height: 180px;
            width: auto;
            opacity: 0;
            animation: fadeIn 0.5s ease-out 0.3s forwards;
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
        
        /* Sections appear one by one */
        .info-section {
            opacity: 0;
            animation: fadeIn 0.5s ease-out forwards;
        }
        
        .info-section:nth-child(1) { animation-delay: 0.1s; }
        .info-section:nth-child(2) { animation-delay: 0.2s; }
        .info-section:nth-child(3) { animation-delay: 0.3s; }
    </style>
</head>
<body>
    @if(session('info'))
    <div class="flash-message">
        {{ session('info') }}
    </div>
    @endif
    
    <div class="container">
        <div class="sidebar">
            <div class="logo-container">
                <img class="logo" alt="Logo" src="{{ asset('images/image 1.png') }}">
                <span class="badge">576</span>
            </div>
            
            <div class="nav-items">
                <a href="{{ route('dashboard') }}" class="nav-item active">Dashboard</a>
                <a href="{{ url('/personal-info') }}" class="nav-item">Personal Info</a>
                <a href="{{ url('/mess-info') }}" class="nav-item">Mess info</a>
                <a href="{{ url('/academics') }}" class="nav-item">Academics</a>
                <a href="{{ url('/clubs') }}" class="nav-item">Clubs</a>
                <a href="{{ url('/achievements') }}" class="nav-item">Achievements</a>
                <a href="{{ url('/research') }}" class="nav-item">Research work</a>
                <a href="{{ url('/internships') }}" class="nav-item">Internships</a>
                <a href="{{ url('/skills') }}" class="nav-item">Skills</a>
                <a href="{{ url('/projects') }}" class="nav-item">Projects</a>
            </div>
            
            <a href="{{ route('logout') }}" class="logout-link">Logout</a>
        </div>
        
        <div class="main-content">
            <div class="top-bar">
                <form action="{{ url('/search') }}" method="GET" class="search-bar">
                    <span class="search-badge">156</span>
                    <input type="text" name="query" placeholder="Search" class="search-input">
                    <button type="submit" class="search-button">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M21 21L16.65 16.65M19 11C19 15.4183 15.4183 19 11 19C6.58172 19 3 15.4183 3 11C3 6.58172 6.58172 3 11 3C15.4183 3 19 6.58172 19 11Z" stroke="#787878" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
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
                    
                    <p class="user-info">
                        <span class="username">
                            {{ Auth::user()->name ?? 'Nischal Basavaraju' }}
                        </span>
                        <br>
                        <span class="user-details">
                            2nd Year, Btech, CSE
                        </span>
                    </p>
                    
                    <img class="user-avatar" alt="User" src="{{ asset('images/image 2.png') }}">
                    
                    <div class="notification-icon">
                        <img src="{{ asset('images/notifications.svg') }}" alt="Notifications">
                        <div class="notification-dot"></div>
                    </div>
                    
                    <img class="settings-icon" alt="Settings" src="{{ asset('images/settings.svg') }}">
                </div>
            </div>
            
            <div class="welcome-card">
                <span class="welcome-badge">840</span>
                <div class="welcome-date">
                    <span>10th October, 2024</span>
                </div>
                <div class="calendar-badge">
                    <span>Calendar</span>
                    <img class="calendar-icon" alt="Calendar" src="{{ asset('images/Calendar copy.svg') }}">
                </div>
                
                <h1 class="welcome-title">Welcome back, Nischal Basavaraju!!</h1>
                <p class="welcome-subtitle">Always stay updated in your student portal</p>
                
                <img class="welcome-image" alt="Welcome" src="{{ asset('images/image 3.png') }}">
            </div>
            
            <div class="content-grid">
                <div class="info-section">
                    <div class="section-header">
                        <h2 class="section-title">Basic Info</h2>
                    </div>
                    <div class="info-card">
                        <div class="info-row">
                            <span>Credits</span>
                            <span>40</span>
                        </div>
                        <div class="info-row">
                            <span>CGPA</span>
                            <span>8.3 <span class="info-badge">846</span></span>
                        </div>
                        <div class="info-row">
                            <span>Semester</span>
                            <span>3rd</span>
                        </div>
                    </div>
                </div>
                
                <div class="info-section">
                    <div class="section-header">
                        <h2 class="section-title">Attendance</h2>
                    </div>
                    <div class="info-card">
                        <div class="attendance-circle">
                            <svg width="180" height="180" viewBox="0 0 180 180">
                                <circle class="circle-bg" cx="90" cy="90" r="80"></circle>
                                <circle class="circle-progress" cx="90" cy="90" r="80"></circle>
                            </svg>
                            <div class="attendance-percent">90.5%</div>
                        </div>
                    </div>
                </div>
                
                <div class="info-section">
                    <div class="section-header">
                        <h2 class="section-title">Course Intructors</h2>
                        <a href="{{ url('/instructors') }}" class="see-all">See all</a>
                    </div>
                    <div class="instructors-grid">
                        <div class="instructor">JS</div>
                        <div class="instructor">AB</div>
                        <div class="instructor">RM</div>
                    </div>
                    
                    <div class="section-header" style="margin-top: 25px;">
                        <h2 class="section-title">Daily notice</h2>
                        <a href="{{ url('/notices') }}" class="see-all">See all</a>
                    </div>
                    
                    <div class="notice-card">
                        <h3 class="notice-title">Prelim payment due</h3>
                        <p class="notice-text">Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                        <a href="{{ url('/notices/payment') }}" class="notice-link">See more</a>
                    </div>
                    
                    <div class="notice-card">
                        <h3 class="notice-title">Exam schedule</h3>
                        <p class="notice-text">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc vulputate libero et velit interdum, ac aliquet odio mattis.</p>
                        <a href="{{ url('/notices/exams') }}" class="notice-link">See more</a>
                    </div>
                </div>
            </div>
            
            <div class="info-section">
                <div class="section-header">
                    <h2 class="section-title">Enrolled Courses</h2>
                    <a href="{{ url('/courses') }}" class="see-all">See all</a>
                </div>
                
                <div class="course-cards">
                    <div class="course-card">
                        <h3 class="course-title">Object oriented programming</h3>
                        <a href="{{ url('/courses/oop') }}"><button class="view-btn">View</button></a>
                        <div class="course-icon">OOP</div>
                    </div>
                    
                    <div class="course-card">
                        <h3 class="course-title">Fundamentals of database systems</h3>
                        <a href="{{ url('/courses/database') }}"><button class="view-btn">View</button></a>
                        <div class="course-icon">DB</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
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
                
                // Show notification (if you want to add this)
                // showNotification(newTheme.charAt(0).toUpperCase() + newTheme.slice(1) + ' mode enabled');
            });
        });
    </script>
</body>
</html> 