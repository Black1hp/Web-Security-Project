<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
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
        }
        
        [data-theme="dark"] {
            --bg-color: #121212;
            --text-color: #e0e0e0;
            --sidebar-bg: #7149c6;
            --sidebar-text: #f0f0f0;
            --card-bg: #1e1e1e;
            --card-hover-shadow: rgba(255, 255, 255, 0.1);
            --border-color: #333;
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
        
        .dashboard-actions {
            display: flex;
            gap: 10px;
        }
        
        .action-button {
            padding: 8px 16px;
            background-color: var(--primary-color);
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.2s;
            font-size: 14px;
        }
        
        .action-button:hover {
            background-color: var(--primary-dark);
            transform: translateY(-2px);
        }
        
        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background-color: var(--card-bg);
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            transition: box-shadow 0.3s, transform 0.3s, background-color 0.3s;
        }
        
        .stat-card:hover {
            box-shadow: 0 5px 15px var(--card-hover-shadow);
            transform: translateY(-5px);
        }
        
        .stat-title {
            font-size: 16px;
            color: var(--text-color);
            opacity: 0.7;
            margin-bottom: 10px;
        }
        
        .stat-value {
            font-size: 28px;
            font-weight: 600;
            margin-bottom: 5px;
        }
        
        .stat-card.success .stat-value {
            color: var(--success-color);
        }
        
        .stat-card.warning .stat-value {
            color: var(--warning-color);
        }
        
        .stat-card.danger .stat-value {
            color: var(--danger-color);
        }
        
        .stat-card.info .stat-value {
            color: var(--info-color);
        }
        
        .stat-change {
            display: flex;
            align-items: center;
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
        
        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid var(--border-color);
        }
        
        .section-title {
            font-size: 18px;
            font-weight: 600;
        }
        
        .section-action {
            color: var(--primary-color);
            font-size: 14px;
            text-decoration: none;
        }
        
        .section-action:hover {
            text-decoration: underline;
        }
        
        /* Placeholder for the content layout */
        .placeholder-message {
            text-align: center;
            padding: 40px;
            color: var(--text-color);
            opacity: 0.7;
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
                <a href="{{ route('admin.dashboard') }}" class="menu-item active">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                        <polyline points="9 22 9 12 15 12 15 22"></polyline>
                    </svg>
                    Dashboard
                </a>
                <a href="{{ route('admin.users') }}" class="menu-item">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                        <circle cx="9" cy="7" r="4"></circle>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                    </svg>
                    Users
                </a>
                <a href="{{ route('admin.members') }}" class="menu-item">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                    Members
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
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                        <line x1="16" y1="2" x2="16" y2="6"></line>
                        <line x1="8" y1="2" x2="8" y2="6"></line>
                        <line x1="3" y1="10" x2="21" y2="10"></line>
                    </svg>
                    Events
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
                    <h2>Dashboard</h2>
                    <div class="dashboard-date">
                        {{ date('l, F j, Y') }}
                    </div>
                </div>
                
                <div class="dashboard-actions">
                    <button class="action-button">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 5px; vertical-align: text-bottom;">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="12" y1="8" x2="12" y2="16"></line>
                            <line x1="8" y1="12" x2="16" y2="12"></line>
                        </svg>
                        Add User
                    </button>
                </div>
            </div>
            
            <div class="stats-container">
                <div class="stat-card success">
                    <div class="stat-title">Total Users</div>
                    <div class="stat-value">{{ $totalUsers }}</div>
                    <div class="stat-change">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: var(--success-color); margin-right: 5px;">
                            <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline>
                            <polyline points="17 6 23 6 23 12"></polyline>
                        </svg>
                        Active users
                    </div>
                </div>
                
                <div class="stat-card warning">
                    <div class="stat-title">Active Courses</div>
                    <div class="stat-value">42</div>
                    <div class="stat-change">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: var(--warning-color); margin-right: 5px;">
                            <line x1="17" y1="7" x2="7" y2="17"></line>
                            <polyline points="17 17 7 17 7 7"></polyline>
                        </svg>
                        +3 new this week
                    </div>
                </div>
                
                <div class="stat-card danger">
                    <div class="stat-title">Support Tickets</div>
                    <div class="stat-value">8</div>
                    <div class="stat-change">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: var(--danger-color); margin-right: 5px;">
                            <polyline points="23 18 13.5 8.5 8.5 13.5 1 6"></polyline>
                            <polyline points="17 18 23 18 23 12"></polyline>
                        </svg>
                        2 unresolved
                    </div>
                </div>
                
                <div class="stat-card info">
                    <div class="stat-title">System Status</div>
                    <div class="stat-value">Online</div>
                    <div class="stat-change">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: var(--info-color); margin-right: 5px;">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                            <polyline points="22 4 12 14.01 9 11.01"></polyline>
                        </svg>
                        All systems operational
                    </div>
                </div>
            </div>
            
            <div class="content-section">
                <div class="section-header">
                    <h3 class="section-title">Recent Activity</h3>
                    <a href="#" class="section-action">View All</a>
                </div>
                
                <div class="placeholder-message">
                    This is a placeholder dashboard. The final design will be updated based on your requirements.
                </div>
            </div>
            
            <div class="content-section">
                <div class="section-header">
                    <h3 class="section-title">Latest Users</h3>
                    <a href="{{ route('admin.users') }}" class="section-action">View All Users</a>
                </div>
                
                @if($recentUsers->count() > 0)
                <table class="users-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Registered On</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentUsers as $user)
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->created_at->format('M d, Y') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <div class="placeholder-message">
                    No users found. Users will be displayed here once registered.
                </div>
                @endif
            </div>
        </main>
    </div>
</body>
</html> 