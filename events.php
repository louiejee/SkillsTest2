<?php 
include 'config.php';

// Add Event
if (isset($_POST['add_event'])) {
    $evName = $_POST['evName'];
    $evDate = $_POST['evDate'];
    $evFee  = $_POST['evFee'];
    $conn->query("INSERT INTO events (evName, evDate, evFee) VALUES ('$evName', '$evDate', '$evFee')");
    header("Location: events.php");
    exit;
}

// Update Event
if (isset($_POST['update_event'])) {
    $evCode = $_POST['evCode'];
    $evName = $_POST['evName'];
    $evDate = $_POST['evDate'];
    $evFee  = $_POST['evFee'];

    $conn->query("UPDATE events SET evName='$evName', evDate='$evDate', evFee='$evFee' WHERE evCode='$evCode'");
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

// Get event to edit
$edit_event = null;
if (isset($_GET['edit_event'])) {
    $id = $_GET['edit_event'];
    $res = $conn->query("SELECT * FROM events WHERE evCode='$id'");
    $edit_event = $res->fetch_assoc();
}

// Search Event
$search_result = null;
if (isset($_POST['search_event'])) {
    $keyword = $_POST['keyword'];
    $search_result = $conn->query("SELECT * FROM events WHERE evCode='$keyword' OR evName LIKE '%$keyword%'");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Events Management</title>
    <style>
        body { font-family: Arial; background: #f9f9f9; padding: 20px; }
        .wrapper { max-width: 700px; margin: auto; }
        .box { background: #fff; padding: 15px; border: 1px solid #ccc; margin-bottom: 20px; }
        input, select { width: 100%; padding: 7px; margin-top: 3px; }
        button { padding: 7px 12px; margin-top: 10px; cursor: pointer; }
        .btn-delete { background: #e74c3c; color: white; border: none; }
        .btn-edit { background: #f39c12; color: white; border: none; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #ccc; padding: 7px; text-align: left; }
        th { background: #eee; }
    </style>
</head>
<body>

<div class="wrapper">
    <h1>Events Management</h1>
    <div style="text-align:center;"><a href="index.php">Back to Dashboard</a></div>

    <!-- Add / Edit Event -->
    <div class="box">
        <h3><?= $edit_event ? "Edit Event" : "Add Event" ?></h3>
        <form method="POST">
            <input type="hidden" name="<?= $edit_event ? 'update_event' : 'add_event' ?>" value="1">
            <?php if($edit_event): ?>
                <input type="hidden" name="evCode" value="<?= $edit_event['evCode'] ?>">
            <?php endif; ?>
            <label>Event Name:</label>
            <input type="text" name="evName" required value="<?= $edit_event['evName'] ?? '' ?>">

            <label>Event Date:</label>
            <input type="date" name="evDate" required value="<?= $edit_event['evDate'] ?? '' ?>">

            <label>Event Fee:</label>
            <input type="number" name="evFee" required value="<?= $edit_event['evFee'] ?? '' ?>">

            <button type="submit"><?= $edit_event ? "Update Event" : "Add Event" ?></button>
            <?php if($edit_event): ?>
                <a href="events.php"><button type="button">Cancel</button></a>
            <?php endif; ?>
        </form>
    </div>

    <!-- Search Event -->
    <div class="box">
        <h3>Search Event</h3>
        <form method="POST">
            <input type="text" name="keyword" required placeholder="Enter Code or Name">
            <button type="submit" name="search_event">Search</button>
        </form>

        <?php if ($search_result && $search_result->num_rows > 0): ?>
            <table>
                <tr><th>Code</th><th>Name</th><th>Date</th><th>Fee</th><th>Actions</th></tr>
                <?php while($row = $search_result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['evCode'] ?></td>
                        <td><?= $row['evName'] ?></td>
                        <td><?= $row['evDate'] ?></td>
                        <td><?= $row['evFee'] ?></td>
                        <td>
                            <a href="?edit_event=<?= $row['evCode'] ?>"><button class="btn-edit">Edit</button></a>
                            <a href="?delete_event=<?= $row['evCode'] ?>" onclick="return confirm('Delete this event?')">
                                <button class="btn-delete">Delete</button>
                            </a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </table>
        <?php elseif ($search_result): ?>
            <p>No results found.</p>
        <?php endif; ?>
    </div>

    <!-- All Events -->
    <div class="box">
        <h3>All Events</h3>
        <table>
            <tr><th>Code</th><th>Name</th><th>Date</th><th>Fee</th><th>Actions</th></tr>
            <?php 
            $all = $conn->query("SELECT * FROM events");
            while($row = $all->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['evCode'] ?></td>
                    <td><?= $row['evName'] ?></td>
                    <td><?= $row['evDate'] ?></td>
                    <td><?= $row['evFee'] ?></td>
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
