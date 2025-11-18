<?php 
include 'config.php';

$filter = isset($_GET['event_filter']) && $_GET['event_filter'] != '' ? "WHERE e.evCode = ".$conn->real_escape_string($_GET['event_filter']) : "";

$report = $conn->query("SELECT e.evName, p.partFName, p.partLName, r.regDate, r.regFeePaid, e.evFee
                      FROM registration r
                      JOIN participants p ON r.partID = p.partID
                      JOIN events e ON r.evCode = e.evCode
                      $filter
                      ORDER BY r.regDate DESC");

$stats = $conn->query("SELECT COUNT(*) as totalReg, SUM(r.regFeePaid) as totalPaid, SUM(e.evFee) as totalOriginal
                     FROM registration r
                     JOIN events e ON r.evCode = e.evCode
                     $filter");
$stat = $stats->fetch_assoc();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Registration Reports</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: Arial; }
        body { background: #f5f5f5; padding: 20px; }
        .container { max-width: 1200px; margin: 0 auto; }
        header { background: #2c3e50; color: white; padding: 15px; border-radius: 5px; margin-bottom: 20px; }
        .content { background: white; padding: 20px; border-radius: 5px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input, select { width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background: #f2f2f2; }
        .summary { background: #ecf0f1; padding: 15px; border-radius: 5px; margin-top: 20px; }
        .back-link { display: inline-block; margin-bottom: 20px; text-decoration: none; color: #3498db; }
    </style>
</head>
<body>
    <div class="container">
        <a href="index.php" class="back-link">← Back to Dashboard</a>
        
        <header>
            <h1>Registration Reports</h1>
        </header>
        
        <div class="content">
            <!-- Filter Form -->
            <form method="GET">
                <div class="form-group">
                    <label>Filter by Event:</label>
                    <select name="event_filter" onchange="this.form.submit()">
                        <option value="">All Events</option>
                        <?php
                        $events = $conn->query("SELECT * FROM events");
                        while($e = $events->fetch_assoc()): ?>
                        <option value="<?= $e['evCode'] ?>" <?= isset($_GET['event_filter']) && $_GET['event_filter']==$e['evCode']?'selected':'' ?>>
                            <?= $e['evName'] ?>
                        </option>
                        <?php endwhile; ?>
                    </select>
                </div>
            </form>

            <!-- Report Table -->
            <table>
                <tr>
                    <th>Event Name</th><th>Participant Name</th><th>Registration Date</th><th>Fee Paid</th>
                </tr>
                <?php while($row = $report->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['evName'] ?></td>
                    <td><?= $row['partFName'] ?> <?= $row['partLName'] ?></td>
                    <td><?= $row['regDate'] ?></td>
                    <td>$<?= $row['regFeePaid'] ?></td>
                </tr>
                <?php endwhile; ?>
            </table>

            <!-- Summary Statistics -->
            <div class="summary">
                <h3>Summary Statistics</h3>
                <p>Total Registrations: <?= $stat['totalReg'] ?></p>
                <p>Total Fees Paid: $<?= number_format($stat['totalPaid'], 2) ?></p>
                <p>Total Discounts Given: $<?= number_format($stat['totalOriginal'] - $stat['totalPaid'], 2) ?></p>
            </div>
        </div>
    </div>
</body>
</html>
