<?php include 'config.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Events Registration System - Dashboard</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: Arial; }
        body { background: #f5f5f5; padding: 20px; }
        .container { max-width: 1200px; margin: 0 auto; }
        header { background: #2c3e50; color: white; padding: 15px; border-radius: 5px; margin-bottom: 20px; }
        .menu { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; }
        .menu-item { background: white; padding: 30px; text-align: center; border-radius: 5px; text-decoration: none; color: #333; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .menu-item:hover { background: #3498db; color: white; }
        .menu-icon { font-size: 40px; margin-bottom: 10px; }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>Events Registration System</h1>
            <p>Welcome, Local Admin</p>
        </header>
        
        <div class="menu">
            <a href="events.php" class="menu-item">
                <div class="menu-icon">📅</div>
                <h3>Events Management</h3>
                <p>Add, edit, delete events</p>
            </a>
            
            <a href="participants.php" class="menu-item">
                <div class="menu-icon">👥</div>
                <h3>Participants Management</h3>
                <p>Manage participants</p>
            </a>
            
            <a href="registration.php" class="menu-item">
                <div class="menu-icon">📝</div>
                <h3>Participant Registration</h3>
                <p>Register participants for events</p>
            </a>
            
            <a href="reports.php" class="menu-item">
                <div class="menu-icon">📊</div>
                <h3>Reports</h3>
                <p>View registration reports</p>
            </a>
        </div>
    </div>
</body>
</html>
