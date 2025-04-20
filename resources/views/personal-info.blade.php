<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Personal Info - Student Portal</title>
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
            --section-title: #333;
            --toggle-bg: #f0f0f0;
            --toggle-button: #925fe2;
            --form-bg: #f0f0f0;
            --switch-bg: #925fe2;
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
            --section-title: #e0e0e0;
            --toggle-bg: #2a2a2a;
            --toggle-button: #925fe2;
            --form-bg: #252525;
            --switch-bg: #7149c6;
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
            width: 176px;
            height: 100vh;
            top: 0;
            left: 0;
            background-color: var(--sidebar-bg);
            padding: 30px 0;
            display: flex;
            flex-direction: column;
            border-radius: 0;
            z-index: 10;
            transition: background-color 0.3s, transform 0.3s ease-out;
            animation: slideInLeft 0.5s ease-out forwards;
        }
        
        .logo-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 30px;
        }
        
        .logo {
            width: 90px;
            height: 90px;
            margin-bottom: 10px;
        }
        
        .logo-text {
            color: var(--sidebar-text);
            font-size: 16px;
            font-weight: 500;
            transition: color 0.3s;
        }
        
        .nav-items {
            display: flex;
            flex-direction: column;
            padding: 0 20px;
        }
        
        .nav-item {
            color: var(--sidebar-text);
            font-size: 16px;
            text-decoration: none;
            font-weight: normal;
            opacity: 0.9;
            padding: 10px 0;
            line-height: 1.2;
            transition: opacity 0.2s, color 0.3s, transform 0.2s;
        }
        
        .nav-item:hover {
            opacity: 1;
            transform: translateX(5px);
        }
        
        .nav-item.active {
            font-weight: bold;
        }
        
        .logout-link {
            margin-top: auto;
            color: var(--sidebar-text);
            font-size: 16px;
            text-decoration: none;
            padding: 10px 20px;
            margin-bottom: 20px;
            transition: color 0.3s;
        }
        
        .main-content {
            margin-left: 176px;
            padding: 20px;
            width: calc(100% - 176px);
            display: grid;
            grid-template-columns: repeat(12, 1fr);
            gap: 16px;
        }
        
        .top-bar {
            grid-column: span 12;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            animation: fadeIn 0.5s ease-out forwards;
        }
        
        .search-bar {
            width: 300px;
            height: 40px;
            background-color: var(--input-bg);
            border-radius: 20px;
            display: flex;
            align-items: center;
            padding: 0 15px;
            position: relative;
            transition: background-color 0.3s, box-shadow 0.3s;
        }
        
        .search-bar:focus-within {
            box-shadow: 0 0 0 2px var(--sidebar-bg);
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
        
        .search-input {
            flex: 1;
            border: none;
            background: transparent;
            height: 100%;
            color: var(--secondary-text);
            font-size: 14px;
            outline: none;
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
            width: 35px;
            height: 35px;
            border-radius: 50%;
            object-fit: cover;
            transition: transform 0.2s;
            cursor: pointer;
        }
        
        .user-avatar:hover {
            transform: scale(1.1);
        }
        
        .notification-icon {
            width: 24px;
            height: 24px;
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
            top: 0;
            right: 0;
            width: 8px;
            height: 8px;
            background-color: #ff0000;
            border-radius: 50%;
        }
        
        .settings-icon {
            width: 24px;
            height: 24px;
            cursor: pointer;
            transition: transform 0.2s;
        }
        
        .settings-icon:hover {
            transform: rotate(45deg);
        }
        
        /* Card styles */
        .card {
            background-color: #925fe2;
            border-radius: 16px;
            padding: 20px;
            position: relative;
        }
        
        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            position: relative;
        }
        
        .card-title {
            font-size: 18px;
            font-weight: 600;
            color: white;
        }
        
        .card-subtitle {
            font-size: 12px;
            color: rgba(255, 255, 255, 0.8);
            margin-bottom: 15px;
        }
        
        .card-icon {
            width: 24px;
            height: 24px;
            cursor: pointer;
        }
        
        /* Form styles */
        .form-group {
            margin-bottom: 15px;
        }
        
        .form-label {
            display: block;
            font-size: 12px;
            color: white;
            margin-bottom: 5px;
        }
        
        .form-input {
            width: 100%;
            height: 36px;
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 18px;
            border: none;
            padding: 0 15px;
            font-size: 14px;
            color: #333;
        }
        
        .form-row {
            display: flex;
            gap: 15px;
            margin-bottom: 15px;
        }
        
        .form-col {
            flex: 1;
        }
        
        /* Specific cards */
        .personal-info-card {
            grid-column: span 5;
        }
        
        .professional-info-card {
            grid-column: span 4;
        }
        
        .additional-info-card {
            grid-column: span 3;
        }
        
        .info-badge {
            background-color: #ff6b00;
            color: white;
            font-size: 12px;
            font-weight: bold;
            padding: 2px 6px;
            border-radius: 4px;
            margin-left: 5px;
        }
        
        .see-all {
            font-size: 14px;
            color: #925fe2;
            text-decoration: none;
            font-weight: 500;
        }
        
        .info-box {
            background-color: #2a2a2a;
            border-radius: 8px;
            padding: 15px;
            margin-top: 10px;
        }
        
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
        }
        
        .info-col {
            flex: 1;
        }
        
        .info-label {
            font-size: 14px;
            font-weight: 500;
            color: white;
            margin-bottom: 5px;
        }
        
        .info-value {
            font-size: 14px;
            color: rgba(255, 255, 255, 0.8);
        }
        
        .areas-card {
            grid-column: span 6;
        }
        
        .projects-card {
            grid-column: span 6;
        }
        
        .profile-section {
            grid-column: span 12;
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-top: 20px;
        }
        
        .profile-image {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 15px;
            position: relative;
        }
        
        .add-photo-button {
            position: absolute;
            bottom: 0;
            right: 0;
            width: 32px;
            height: 32px;
            background-color: #000;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 20px;
            cursor: pointer;
        }
        
        .profile-name {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 5px;
            text-align: center;
        }
        
        .profile-role {
            background-color: #f1f1f1;
            color: #333;
            padding: 5px 15px;
            border-radius: 15px;
            font-size: 14px;
            font-weight: 500;
        }
        
        .location-section {
            grid-column: span 12;
            margin-top: 20px;
        }
        
        .about-section {
            grid-column: span 12;
            margin-top: 20px;
        }
        
        .text-input {
            width: 100%;
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 8px;
            border: none;
            padding: 10px 15px;
            font-size: 14px;
            color: #333;
            resize: none;
            min-height: 100px;
        }
        
        .pill {
            display: inline-block;
            padding: 6px 12px;
            background-color: rgba(255, 255, 255, 0.2);
            border-radius: 15px;
            margin-right: 8px;
            margin-bottom: 8px;
            font-size: 14px;
            color: white;
        }
        
        .upload-link {
            width: 100%;
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 18px;
            border: none;
            padding: 8px 15px;
            font-size: 12px;
            color: #333;
            text-decoration: underline;
            margin-top: 10px;
            display: block;
        }
        
        .add-button, .edit-button {
            position: absolute;
            top: 20px;
            right: 20px;
            width: 24px;
            height: 24px;
            cursor: pointer;
            background: transparent;
            border: none;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .social-input {
            display: flex;
            gap: 5px;
            margin-top: 10px;
        }
        
        .social-select {
            flex: 2;
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 18px;
            border: none;
            padding: 5px 10px;
            font-size: 12px;
            color: #333;
        }
        
        .url-input {
            flex: 1;
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 18px;
            border: none;
            padding: 5px 10px;
            font-size: 12px;
            color: #333;
        }
        
        /* Create the grid layout for the profile information */
        .profile-info-grid {
            display: grid;
            grid-template-columns: 1fr 2fr 1fr;
            gap: 20px;
            grid-column: span 12;
        }
        
        .left-column {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }
        
        .center-column {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }
        
        .right-column {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }
        
        /* Create the grid for the profile cards */
        .profile-cards {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 20px;
            grid-column: span 12;
        }
        
        .profile-card {
            background-color: #2a2a2a;
            border-radius: 16px;
            padding: 20px;
            position: relative;
        }
        
        .profile-card-header {
            margin-bottom: 15px;
        }
        
        .profile-card-title {
            font-size: 18px;
            font-weight: 600;
            color: white;
        }
        
        .interest-pill {
            display: inline-flex;
            align-items: center;
            padding: 6px 12px;
            background-color: rgba(255, 255, 255, 0.2);
            border-radius: 15px;
            margin-right: 8px;
            margin-bottom: 8px;
            font-size: 14px;
            color: white;
        }
        
        .interest-pill:before {
            content: "•";
            margin-right: 5px;
        }
        
        .calendar-badge {
            display: inline-flex;
            align-items: center;
            background-color: #925fe2;
            color: white;
            font-size: 12px;
            border: 1px solid white;
            padding: 3px 8px;
            border-radius: 4px;
            margin-bottom: 10px;
            gap: 5px;
        }
        
        .calendar-icon {
            width: 14px;
            height: 14px;
        }
        
        .date-display {
            font-size: 14px;
            color: white;
            margin-bottom: 15px;
        }
        
        .course-badge {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background-color: #b281ff;
            border-radius: 6px;
            color: white;
            font-weight: bold;
            font-size: 14px;
            margin-left: 10px;
        }
        
        /* Animation delays for elements */
        .form-section:nth-child(1) { animation-delay: 0.1s; }
        .form-section:nth-child(2) { animation-delay: 0.2s; }
        .form-section:nth-child(3) { animation-delay: 0.3s; }
        .form-section:nth-child(4) { animation-delay: 0.4s; }
        
        /* Page load animations */
        .section-title, .form-card {
            animation: fadeInUp 0.6s ease-out forwards;
            opacity: 0;
        }
        
        .personal-info-card {
            animation: fadeIn 0.7s ease-out 0.2s forwards;
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
        
        /* Input animations */
        .form-input:focus {
            border-color: var(--sidebar-bg);
            box-shadow: 0 0 0 3px rgba(146, 95, 226, 0.2);
        }
        
        .form-submit {
            transition: background-color 0.3s, transform 0.2s;
        }
        
        .form-submit:hover {
            transform: translateY(-2px);
        }
        
        .form-submit:active {
            transform: translateY(1px);
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
        
        /* Form card animation */
        .form-card {
            transition: transform 0.3s, box-shadow 0.3s, background-color 0.3s;
        }
        
        .form-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px var(--card-hover-shadow);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="sidebar">
            <div class="logo-container">
                <img class="logo" alt="Logo" src="{{ asset('images/image 1.png') }}">
            </div>
            
            <div class="nav-items">
                <a href="{{ route('dashboard') }}" class="nav-item">Dashboard</a>
                <a href="{{ url('/personal-info') }}" class="nav-item active">Personal Info</a>
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
                    <div class="user-info">
                        <div class="username">Nischal Basavaraju</div>
                        <div class="user-details">2nd Year, Btech, CSE</div>
                    </div>
                    <img class="user-avatar" alt="User" src="{{ asset('images/image 2.png') }}">
                    <div class="notification-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                            <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                        </svg>
                        <div class="notification-dot"></div>
                    </div>
                    <div class="settings-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="3"></circle>
                            <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
                        </svg>
                    </div>
                </div>
            </div>
            
            <div class="profile-info-grid">
                <!-- Left column -->
                <div class="left-column">
                    <!-- Personal Info Card -->
                    <div class="card personal-info-card">
                        <div class="card-header">
                            <h2 class="card-title">Personal Info</h2>
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                            <div class="date-display">10th October, 2024</div>
                            <div class="calendar-badge">
                                <span>Calendar</span>
                                <svg class="calendar-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                    <line x1="16" y1="2" x2="16" y2="6"></line>
                                    <line x1="8" y1="2" x2="8" y2="6"></line>
                                    <line x1="3" y1="10" x2="21" y2="10"></line>
                                </svg>
                            </div>
                        </div>
                        <div class="card-subtitle">Enter your personal information here</div>
                        
                        <div class="form-group">
                            <label class="form-label">Full name</label>
                            <input type="text" class="form-input">
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-input">
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Contact number</label>
                            <input type="text" class="form-input">
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">DOB</label>
                            <input type="text" class="form-input">
                        </div>
                    </div>
                    
                    <!-- Projects Card -->
                    <div class="card projects-card">
                        <div class="card-header">
                            <h2 class="card-title">Projects</h2>
                            <a href="#" class="see-all">See all</a>
                            <button class="add-button" onclick="showNotification('Add project feature coming soon')">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <line x1="12" y1="8" x2="12" y2="16"></line>
                                    <line x1="8" y1="12" x2="16" y2="12"></line>
                                </svg>
                            </button>
                        </div>
                        
                        <div class="form-group">
                            <div style="display: flex; align-items: center;">
                                <label class="form-label" style="margin-bottom: 0; flex: 1;">Upload github link</label>
                                <div class="course-badge">GH</div>
                            </div>
                            <input type="text" class="form-input" placeholder="https://">
                        </div>
                        
                        <div class="form-group">
                            <textarea class="text-input" placeholder="Tell more about your project..."></textarea>
                        </div>
                        
                        <div style="margin-top: 20px; margin-bottom: 10px; border-top: 1px solid rgba(255,255,255,0.3);"></div>
                        
                        <div class="card-header">
                            <div style="display: flex; align-items: center;">
                                <h2 class="card-title">Internship</h2>
                                <div class="course-badge" style="background-color: #7d6ca8; margin-left: 10px;">INT</div>
                            </div>
                            <button class="add-button" onclick="showNotification('Add internship feature coming soon')">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <line x1="12" y1="8" x2="12" y2="16"></line>
                                    <line x1="8" y1="12" x2="16" y2="12"></line>
                                </svg>
                            </button>
                        </div>
                        
                        <div style="margin-top: 10px;">
                            <p>Nothing to show here</p>
                            <p>right now....</p>
                        </div>
                    </div>
                </div>
                
                <!-- Center column -->
                <div class="center-column">
                    <!-- Professional Info Card -->
                    <div class="card professional-info-card">
                        <div class="card-header">
                            <h2 class="card-title">Professional Info</h2>
                            <button class="edit-button" onclick="showNotification('Edit professional info feature coming soon')">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                </svg>
                            </button>
                        </div>
                        
                        <div class="info-box">
                            <div class="info-row">
                                <div class="info-col">
                                    <div class="info-label">Degree</div>
                                    <div class="info-value">Btech</div>
                                </div>
                                <div class="info-col">
                                    <div class="info-label">Branch</div>
                                    <div class="info-value">Computer Science and Engineering</div>
                                </div>
                            </div>
                            
                            <div class="info-row">
                                <div class="info-col">
                                    <div class="info-label">Semester</div>
                                    <div class="info-value">3</div>
                                </div>
                                <div class="info-col">
                                    <div class="info-label">Roll number</div>
                                    <div class="info-value">231CS139 <span class="info-badge">1194</span></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Areas of Interest Card -->
                    <div class="card areas-card">
                        <div class="card-header">
                            <div style="display: flex; align-items: center;">
                                <h2 class="card-title">Areas of Interest</h2>
                                <div class="course-badge" style="background-color: #8a55dd; margin-left: 10px;">AI</div>
                            </div>
                            <button class="add-button" onclick="showNotification('Add area of interest feature coming soon')">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <line x1="12" y1="8" x2="12" y2="16"></line>
                                    <line x1="8" y1="12" x2="16" y2="12"></line>
                                </svg>
                            </button>
                        </div>
                        
                        <div style="margin-top: 15px;">
                            <div class="interest-pill">Badminton</div>
                            <div class="interest-pill">Competitive Programming</div>
                            <div class="interest-pill">Web development</div>
                        </div>
                        
                        <div style="margin-top: 20px; margin-bottom: 20px; border-top: 1px solid rgba(255,255,255,0.3);"></div>
                        
                        <div class="card-header">
                            <h2 class="card-title">Achievements</h2>
                            <a href="#" class="see-all">See all</a>
                            <button class="add-button" onclick="showNotification('Add achievement feature coming soon')">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <line x1="12" y1="8" x2="12" y2="16"></line>
                                    <line x1="8" y1="12" x2="16" y2="12"></line>
                                </svg>
                            </button>
                        </div>
                        
                        <div style="margin-top: 15px;">
                            <p>Upload Certificate</p>
                            <input type="text" class="upload-link" value="https://udemy/datastrcutures_and_algorithms/certificate.com">
                        </div>
                    </div>
                </div>
                
                <!-- Right column -->
                <div class="right-column">
                    <!-- Additional Info Card -->
                    <div class="card additional-info-card">
                        <div class="card-header">
                            <h2 class="card-title">Additional Info</h2>
                            <span class="info-badge">75</span>
                            <button class="add-button" onclick="showNotification('Add additional info feature coming soon')">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="12" y1="5" x2="12" y2="19"></line>
                                    <line x1="5" y1="12" x2="19" y2="12"></line>
                                </svg>
                            </button>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Resume</label>
                            <input type="text" class="form-input" placeholder="Enter your resume link">
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Additional Links</label>
                            <div class="social-input">
                                <div class="social-select">LinkedIn</div>
                                <div class="url-input">https://</div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Profile Card -->
                    <div class="profile-card">
                        <div class="profile-section">
                            <div style="position: relative;">
                                <img class="profile-image" src="{{ asset('images/image 2.png') }}" alt="Profile">
                                <div class="add-photo-button" onclick="showNotification('Add photo feature coming soon')">+</div>
                            </div>
                            <h3 class="profile-name">Nischal Basavaraju</h3>
                            <div class="profile-role">Student</div>
                        </div>
                    </div>
                    
                    <!-- Location Card -->
                    <div class="profile-card">
                        <div class="profile-card-header">
                            <h2 class="profile-card-title">Location</h2>
                        </div>
                        <input type="text" class="form-input" value="National Institute of Technology, Surathkal">
                    </div>
                    
                    <!-- About Me Card -->
                    <div class="profile-card">
                        <div class="profile-card-header">
                            <h2 class="profile-card-title">About Me</h2>
                            <button class="edit-button" onclick="showNotification('Edit about me feature coming soon')">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                </svg>
                            </button>
                        </div>
                        <textarea class="text-input" style="margin-top: 15px;">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam ultricies mauris vel elit congue auctor. Curabitur maximus ipsum ac tortor semper, eu fringilla sem dictum. Morbi vel dui ullamcorper, molestie nisl sed, egestas metus.</textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
<script>
    function showNotification(message) {
        // Create notification element if it doesn't exist
        let notification = document.getElementById('notification');
        if (!notification) {
            notification = document.createElement('div');
            notification.id = 'notification';
            notification.style.position = 'fixed';
            notification.style.top = '20px';
            notification.style.right = '20px';
            notification.style.backgroundColor = '#925fe2';
            notification.style.color = 'white';
            notification.style.padding = '15px 25px';
            notification.style.borderRadius = '8px';
            notification.style.boxShadow = '0 4px 12px rgba(0, 0, 0, 0.1)';
            notification.style.zIndex = '1000';
            notification.style.opacity = '0';
            notification.style.transform = 'translateY(-20px)';
            notification.style.transition = 'opacity 0.3s, transform 0.3s';
            document.body.appendChild(notification);
        }
        
        // Set message and show notification
        notification.textContent = message;
        setTimeout(() => {
            notification.style.opacity = '1';
            notification.style.transform = 'translateY(0)';
        }, 10);
        
        // Hide notification after 3 seconds
        setTimeout(() => {
            notification.style.opacity = '0';
            notification.style.transform = 'translateY(-20px)';
        }, 3000);
    }

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
</html> 