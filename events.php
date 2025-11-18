<?php 
include 'config.php';

// Add Event
if (isset($_POST['add_event'])) {
    $evName = $_POST['evName'];
    $evDate = $_POST['evDate'];
    $evFee  = $_POST['evFee'];
    $conn->query("INSERT INTO events (evName, evDate, evFee) 
                  VALUES ('$evName', '$evDate', '$evFee')");
    header("Location: events.php");
    exit;
}

// Update Event
if (isset($_POST['update_event'])) {
    $evCode = $_POST['evCode'];
    $evName = $_POST['evName'];
    $evDate = $_POST['evDate'];
    $evFee  = $_POST['evFee'];

    $conn->query("UPDATE events SET
                    evName='$evName',
                    evDate='$evDate',
                    evFee='$evFee'
                  WHERE evCode='$evCode'");
    header("Location: events.php");
    exit;
}

// Delete Event
if (isset($_GET['delete_event'])) {
    $id = $_GET['delete_event'];
    $conn->query("DELETE FROM events WHERE evCode='$id'");
    header("Location: events.php");
    exit;
}

// Search Event
$search_result = null;
if (isset($_POST['search_event'])) {
    $keyword = $_POST['keyword'];
    $search_result = $conn->query(
        "SELECT * FROM events 
         WHERE evCode='$keyword' OR evName LIKE '%$keyword%'"
    );
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Events Management</title>
    <style>
        body { font-family: Arial; background: #f9f9f9; padding: 20px; }
        h1 { text-align: center; font-size: 20px; }
        .wrapper { max-width: 700px; margin: auto; }
        .box {
            background: #fff;
            padding: 15px;
            border: 1px solid #ccc;
            margin-bottom: 20px;
        }
        label { display: block; margin-top: 8px; font-size: 14px; }
        input {
            width: 100%;
            padding: 7px;
            border: 1px solid #ccc;
            margin-top: 3px;
        }
        button {
            background: #4CAF50;
            padding: 7px 12px;
            border: none;
            color: #fff;
            margin-top: 10px;
            cursor: pointer;
        }
        button:hover { background: #45a049; }
        .btn-edit { background: #f39c12; }
        .btn-delete { background: #e74c3c; }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 7px;
            font-size: 14px;
        }
        th { background: #eee; }
    </style>
</head>
<body>

<div class="wrapper">
    <h1>Events Management</h1>

    <div style="text-align:center;">
    <a href="index.php">Back to Dashboard</a>
</div>

    <!-- ADD EVENT -->
    <div class="box">
        <h3>Add Event</h3>
        <form method="POST">
            <input type="hidden" name="add_event" value="1">
            <label>Event Name:</label>
            <input type="text" name="evName" required>

            <label>Event Date:</label>
            <input type="date" name="evDate" required>

            <label>Event Fee:</label>
            <input type="number" name="evFee" required>

            <button type="submit">Add Event</button>
        </form>
    </div>

    <!-- SEARCH EVENT -->
    <div class="box">
        <h3>Search Event</h3>
        <form method="POST">
            <label>Enter Event Code or Event Name:</label>
            <input type="text" name="keyword" required>
            <button type="submit" name="search_event">Search</button>
        </form>

        <?php if ($search_result && $search_result->num_rows > 0): ?>
            <h4>Search Results:</h4>
            <table>
                <tr>
                    <th>Code</th>
                    <th>Name</th>
                    <th>Date</th>
                    <th>Fee</th>
                    <th>Actions</th>
                </tr>
                <?php while ($row = $search_result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['evCode'] ?></td>
                    <td><?= $row['evName'] ?></td>
                    <td><?= $row['evDate'] ?></td>
                    <td>$<?= $row['evFee'] ?></td>
                    <td>
                        <!-- Update form -->
                        <form method="POST" style="display:inline-block;">
                            <input type="hidden" name="evCode" value="<?= $row['evCode'] ?>">
                            <input type="hidden" name="evName" value="<?= $row['evName'] ?>">
                            <input type="hidden" name="evDate" value="<?= $row['evDate'] ?>">
                            <input type="hidden" name="evFee" value="<?= $row['evFee'] ?>">
                            <button class="btn-edit" name="update_event">Update</button>
                        </form>
                        
                        <!-- Delete link -->
                        <a href="?delete_event=<?= $row['evCode'] ?>" 
                           onclick="return confirm('Delete this event?')">
                            <button class="btn-delete">Delete</button>
                        </a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </table>
        <?php elseif ($search_result): ?>
            <p>No matching records found.</p>
        <?php endif; ?>
    </div>

    <!-- VIEW ALL EVENTS -->
    <div class="box">
        <h3>All Events</h3>
        <table>
            <tr>
                <th>Code</th>
                <th>Name</th>
                <th>Date</th>
                <th>Fee</th>
                <th>Actions</th>
            </tr>
            <?php 
            $all = $conn->query("SELECT * FROM events");
            while ($row = $all->fetch_assoc()): ?>
            <tr>
                <td><?= $row['evCode'] ?></td>
                <td><?= $row['evName'] ?></td>
                <td><?= $row['evDate'] ?></td>
                <td>$<?= $row['evFee'] ?></td>
                <td>
                    <a href="?delete_event=<?= $row['evCode'] ?>"
                       onclick="return confirm('Delete this event?')">
                        <button class="btn-delete">Delete</button>
                    </a>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>

</div>

</body>
</html>
