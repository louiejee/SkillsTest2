<?php include 'config.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Events Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f9f9f9;
            padding: 20px;
            color: #000;
        }
        h1 {
            text-align: center;
            font-size: 20px;
        }
        .menu {
            max-width: 500px;
            margin: 20px auto;
            background: #fff;
            padding: 15px;
            border: 1px solid #ccc;
        }
        .menu a {
            display: block;
            padding: 8px 12px;
            margin: 5px 0;
            background: #3498db;
            color: #fff;
            text-decoration: none;
            border-radius: 4px;
            text-align: center;
        }
        .menu a:hover {
            background: #2e86c1;
        }
    </style>
</head>
<body>

<h1>Events Registration System</h1>

<div class="menu">
    <a href="events.php">Manage Events</a>
    <a href="participants.php">Manage Participants</a>
    <a href="registration.php">Register Participants</a>
    <a href="reports.php">View Reports</a>
</div>

</body>
</html>
