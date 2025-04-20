<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Edit User</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-color: #f6f6f6;
            --text-color: #333;
            --sidebar-bg: #925fe2;
            --sidebar-text: white;
            --card-bg: white;
            --card-hover-shadow: rgba(0, 0, 0, 0.1);
            --border-color: #e0e0e0;
            --primary-color: #925fe2;
            --primary-dark: #7f49d3;
            --success-color: #4CAF50;
            --warning-color: #FF9800;
            --danger-color: #F44336;
            --info-color: #2196F3;
            --input-bg: #f0f0f0;
        }
        
        [data-theme="dark"] {
            --bg-color: #121212;
            --text-color: #e0e0e0;
            --sidebar-bg: #7149c6;
            --sidebar-text: #f0f0f0;
            --card-bg: #1e1e1e;
            --card-hover-shadow: rgba(255, 255, 255, 0.1);
            --border-color: #333;
            --input-bg: #2a2a2a;
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
            transition: background-color 0.3s, color 0.3s;
        }
        
        .dashboard-container {
            display: flex;
        }
        
        .sidebar {
            width: 250px;
            background-color: var(--sidebar-bg);
            height: 100vh;
            position: fixed;
            padding: 20px 0;
            transition: background-color 0.3s;
            z-index: 10;
        }
        
        .sidebar-header {
            padding: 0 20px 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            text-align: center;
        }
        
        .sidebar-header h1 {
            color: var(--sidebar-text);
            font-size: 24px;
            font-weight: 600;
        }
        
        .sidebar-menu {
            padding: 20px 0;
        }
        
        .menu-item {
            padding: 12px 20px;
            color: var(--sidebar-text);
            text-decoration: none;
            display: flex;
            align-items: center;
            transition: background-color 0.3s;
            margin-bottom: 5px;
        }
        
        .menu-item:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }
        
        .menu-item.active {
            background-color: rgba(255, 255, 255, 0.2);
            border-left: 4px solid white;
        }
        
        .menu-item svg {
            margin-right: 10px;
        }
        
        .logout-item {
            margin-top: auto;
            padding: 12px 20px;
            color: var(--sidebar-text);
            text-decoration: none;
            display: flex;
            align-items: center;
            opacity: 0.8;
            transition: opacity 0.3s;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .logout-item:hover {
            opacity: 1;
        }
        
        .main-content {
            flex: 1;
            margin-left: 250px;
            padding: 20px;
        }
        
        .dashboard-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 20px;
            border-bottom: 1px solid var(--border-color);
        }
        
        .dashboard-title h2 {
            font-size: 24px;
            font-weight: 600;
        }
        
        .dashboard-date {
            color: var(--text-color);
            opacity: 0.7;
            font-size: 14px;
        }
        
        .content-section {
            background-color: var(--card-bg);
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            transition: background-color 0.3s;
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
        
        .error-message {
            color: var(--danger-color);
            font-size: 14px;
            margin-top: 5px;
        }
        
        .btn {
            display: inline-block;
            padding: 12px 20px;
            border: none;
            border-radius: 5px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.2s;
            text-decoration: none;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            color: white;
        }
        
        .btn-primary:hover {
            background-color: var(--primary-dark);
            transform: translateY(-2px);
        }
        
        .btn-secondary {
            background-color: rgba(0, 0, 0, 0.1);
            color: var(--text-color);
        }
        
        .btn-secondary:hover {
            background-color: rgba(0, 0, 0, 0.2);
            transform: translateY(-2px);
        }
        
        .form-actions {
            display: flex;
            gap: 10px;
            margin-top: 30px;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <aside class="sidebar">
            <div class="sidebar-header">
                <h1>Admin Portal</h1>
            </div>
            
            <nav class="sidebar-menu">
                <a href="{{ route('admin.dashboard') }}" class="menu-item">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                        <polyline points="9 22 9 12 15 12 15 22"></polyline>
                    </svg>
                    Dashboard
                </a>
                <a href="{{ route('admin.users') }}" class="menu-item active">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                        <circle cx="9" cy="7" r="4"></circle>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                    </svg>
                    Users
                </a>
                <a href="#" class="menu-item">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                        <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                    </svg>
                    Courses
                </a>
                <a href="#" class="menu-item">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="8" y1="6" x2="21" y2="6"></line>
                        <line x1="8" y1="12" x2="21" y2="12"></line>
                        <line x1="8" y1="18" x2="21" y2="18"></line>
                        <line x1="3" y1="6" x2="3.01" y2="6"></line>
                        <line x1="3" y1="12" x2="3.01" y2="12"></line>
                        <line x1="3" y1="18" x2="3.01" y2="18"></line>
                    </svg>
                    Reports
                </a>
                <a href="#" class="menu-item">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="3"></circle>
                        <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
                    </svg>
                    Settings
                </a>
            </nav>
            
            <a href="{{ route('admin.logout') }}" class="logout-item">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                    <polyline points="16 17 21 12 16 7"></polyline>
                    <line x1="21" y1="12" x2="9" y2="12"></line>
                </svg>
                Logout
            </a>
        </aside>
        
        <main class="main-content">
            <div class="dashboard-header">
                <div class="dashboard-title">
                    <h2>Edit User</h2>
                    <div class="dashboard-date">
                        {{ date('l, F j, Y') }}
                    </div>
                </div>
            </div>
            
            <div class="content-section">
                <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="form-group">
                        <label class="form-label" for="name">Name</label>
                        <input 
                            type="text" 
                            id="name" 
                            name="name" 
                            class="form-input" 
                            value="{{ old('name', $user->name) }}" 
                            required
                        >
                        @error('name')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label" for="email">Email Address</label>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            class="form-input" 
                            value="{{ old('email', $user->email) }}" 
                            required
                        >
                        @error('email')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">Update User</button>
                        <a href="{{ route('admin.users') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </main>
    </div>
</body>
</html> 