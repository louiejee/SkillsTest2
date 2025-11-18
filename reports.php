<?php
include 'config.php';

// Filter by event
$filter = "";
if (isset($_GET['event_filter']) && $_GET['event_filter'] != '') {
    $filter = "WHERE e.evCode = ".$conn->real_escape_string($_GET['event_filter']);
}

// Get registrations
$report = $conn->query("SELECT e.evName, p.partFName, p.partLName, r.regDate, r.regFeePaid, e.evFee
                        FROM registration r
                        JOIN participants p ON r.partID = p.partID
                        JOIN events e ON r.evCode = e.evCode
                        $filter
                        ORDER BY r.regDate DESC");

// Get summary
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
</head>
<body>
    <div style="text-align:center;">
        <a href="index.php">Back to Dashboard</a>
    </div>

    <h1>Registration Reports</h1>

    <!-- Filter -->
    <form method="GET">
        Filter by Event:
        <select name="event_filter" onchange="this.form.submit()">
            <option value="">All Events</option>
            <?php
            $events = $conn->query("SELECT * FROM events");
            while ($e = $events->fetch_assoc()):
            ?>
                <option value="<?= $e['evCode'] ?>" <?= isset($_GET['event_filter']) && $_GET['event_filter']==$e['evCode'] ? 'selected' : '' ?>>
                    <?= $e['evName'] ?>
                </option>
            <?php endwhile; ?>
        </select>
    </form>

    <!-- Table -->
    <table border="1" cellpadding="5" cellspacing="0" style="margin-top:10px; width:100%;">
        <tr>
            <th>Event Name</th>
            <th>Participant Name</th>
            <th>Date</th>
            <th>Fee Paid</th>
        </tr>
        <?php while ($row = $report->fetch_assoc()): ?>
        <tr>
            <td><?= $row['evName'] ?></td>
            <td><?= $row['partFName'] ?> <?= $row['partLName'] ?></td>
            <td><?= $row['regDate'] ?></td>
            <td><?= $row['regFeePaid'] ?></td>
        </tr>
        <?php endwhile; ?>
    </table>

    <!-- Summary -->
    <h3>Summary</h3>
    Total Registrations: <?= $stat['totalReg'] ?><br>
    Total Fees Paid: <?= $stat['totalPaid'] ?><br>
    Total Discounts: <?= $stat['totalOriginal'] - $stat['totalPaid'] ?>
</body>
</html>
