<?php 
include 'config.php';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_event'])) {
        $evName = $conn->real_escape_string($_POST['evName']);
        $evDate = $conn->real_escape_string($_POST['evDate']);
        $evFee = $conn->real_escape_string($_POST['evFee']);
        
        $conn->query("INSERT INTO events (evName, evDate, evFee) VALUES ('$evName', '$evDate', '$evFee')");
        header("Location: events.php");
        exit;
    }
    
    if (isset($_POST['update_event'])) {
        $evCode = $conn->real_escape_string($_POST['evCode']);
        $evName = $conn->real_escape_string($_POST['evName']);
        $evDate = $conn->real_escape_string($_POST['evDate']);
        $evFee = $conn->real_escape_string($_POST['evFee']);
        
        $conn->query("UPDATE events SET evName='$evName', evDate='$evDate', evFee='$evFee' WHERE evCode='$evCode'");
        header("Location: events.php");
        exit;
    }
}

// Handle deletions
if (isset($_GET['delete_event'])) {
    $id = $conn->real_escape_string($_GET['delete_event']);
    $conn->query("DELETE FROM events WHERE evCode = $id");
    header("Location: events.php");
    exit;
}

// Check if editing
$edit_event = null;
if (isset($_GET['edit_event'])) {
    $id = $conn->real_escape_string($_GET['edit_event']);
    $result = $conn->query("SELECT * FROM events WHERE evCode = $id");
    $edit_event = $result->fetch_assoc();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Events Management</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: Arial; }
        body { background: #f5f5f5; padding: 20px; }
        .container { max-width: 1200px; margin: 0 auto; }
        header { background: #2c3e50; color: white; padding: 15px; border-radius: 5px; margin-bottom: 20px; }
        .content { background: white; padding: 20px; border-radius: 5px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input, select { width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; }
        button { background: #3498db; color: white; border: none; padding: 10px 15px; border-radius: 4px; cursor: pointer; margin: 5px; }
        .btn-edit { background: #f39c12; }
        .btn-delete { background: #e74c3c; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background: #f2f2f2; }
        .back-link { display: inline-block; margin-bottom: 20px; text-decoration: none; color: #3498db; }
    </style>
</head>
<body>
    <div class="container">
        <a href="index.php" class="back-link">← Back to Dashboard</a>
        
        <header>
            <h1>Events Management</h1>
        </header>
        
        <div class="content">
            <!-- Add/Edit Event Form -->
            <form method="POST">
                <?php if($edit_event): ?>
                    <input type="hidden" name="evCode" value="<?= $edit_event['evCode'] ?>">
                    <input type="hidden" name="update_event" value="1">
                    <h3>Edit Event</h3>
                <?php else: ?>
                    <input type="hidden" name="add_event" value="1">
                    <h3>Add New Event</h3>
                <?php endif; ?>
                
                <div class="form-group">
                    <label>Event Name:</label>
                    <input type="text" name="evName" value="<?= $edit_event ? $edit_event['evName'] : '' ?>" required>
                </div>
                <div class="form-group">
                    <label>Event Date:</label>
                    <input type="date" name="evDate" value="<?= $edit_event ? $edit_event['evDate'] : '' ?>" required>
                </div>
                <div class="form-group">
                    <label>Registration Fee:</label>
                    <input type="number" step="0.01" name="evFee" value="<?= $edit_event ? $edit_event['evFee'] : '' ?>" required>
                </div>
                <button type="submit"><?= $edit_event ? 'Update Event' : 'Add Event' ?></button>
                <?php if($edit_event): ?>
                    <a href="events.php"><button type="button">Cancel</button></a>
                <?php endif; ?>
            </form>

            <!-- Events List -->
            <h3>All Events</h3>
            <table>
                <tr>
                    <th>Code</th><th>Name</th><th>Date</th><th>Fee</th><th>Actions</th>
                </tr>
                <?php
                $events = $conn->query("SELECT * FROM events");
                while($row = $events->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['evCode'] ?></td>
                    <td><?= $row['evName'] ?></td>
                    <td><?= $row['evDate'] ?></td>
                    <td>$<?= $row['evFee'] ?></td>
                    <td>
                        <a href="?edit_event=<?= $row['evCode'] ?>"><button class="btn-edit">Edit</button></a>
                        <a href="?delete_event=<?= $row['evCode'] ?>" onclick="return confirm('Delete this event?')">
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
