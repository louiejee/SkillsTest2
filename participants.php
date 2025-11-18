<?php
include 'config.php';

// Add Participant
if (isset($_POST['add_participant'])) {
    $fname = $_POST['partFName'];
    $lname = $_POST['partLName'];
    $drate = $_POST['partDRate'];
    $conn->query("INSERT INTO participants (partFName, partLName, partDRate) 
                  VALUES ('$fname', '$lname', '$drate')");
    header("Location: participants.php");
    exit;
}

// Update Participant
if (isset($_POST['update_participant'])) {
    $id = $_POST['partID'];
    $fname = $_POST['partFName'];
    $lname = $_POST['partLName'];
    $drate = $_POST['partDRate'];
    $conn->query("UPDATE participants SET partFName='$fname', partLName='$lname', partDRate='$drate' 
                  WHERE partID='$id'");
    header("Location: participants.php");
    exit;
}

// Delete Participant
if (isset($_GET['delete_participant'])) {
    $id = $_GET['delete_participant'];
    $conn->query("DELETE FROM participants WHERE partID='$id'");
    header("Location: participants.php");
    exit;
}

// Search Participant
$search_result = null;
if (isset($_POST['search_participant'])) {
    $keyword = $_POST['keyword'];
    $search_result = $conn->query("SELECT * FROM participants 
                                   WHERE partID='$keyword' OR partFName LIKE '%$keyword%' OR partLName LIKE '%$keyword%'");
}

// Get participant to edit
$edit_part = null;
if (isset($_GET['edit_participant'])) {
    $id = $_GET['edit_participant'];
    $res = $conn->query("SELECT * FROM participants WHERE partID='$id'");
    $edit_part = $res->fetch_assoc();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Participants Management</title>
    <style>
        body { font-family: Arial; background: #f9f9f9; padding: 20px; }
        .wrapper { max-width: 700px; margin: auto; }
        .box { background: #fff; padding: 15px; border: 1px solid #ccc; margin-bottom: 20px; }
        input { width: 100%; padding: 7px; margin-top: 3px; }
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
    <h1>Participants Management</h1>
    <div style="text-align:center;"><a href="index.php">Back to Dashboard</a></div>

    <!-- Add / Edit Participant -->
    <div class="box">
        <h3><?= $edit_part ? "Edit Participant" : "Add Participant" ?></h3>
        <form method="POST">
            <input type="hidden" name="<?= $edit_part ? 'update_participant' : 'add_participant' ?>" value="1">
            <?php if($edit_part): ?>
                <input type="hidden" name="partID" value="<?= $edit_part['partID'] ?>">
            <?php endif; ?>
            <label>First Name:</label>
            <input type="text" name="partFName" required value="<?= $edit_part['partFName'] ?? '' ?>">

            <label>Last Name:</label>
            <input type="text" name="partLName" required value="<?= $edit_part['partLName'] ?? '' ?>">

            <label>Discount Rate (%):</label>
            <input type="number" name="partDRate" required value="<?= $edit_part['partDRate'] ?? '' ?>">

            <button type="submit"><?= $edit_part ? "Update Participant" : "Add Participant" ?></button>
            <?php if($edit_part): ?>
                <a href="participants.php"><button type="button">Cancel</button></a>
            <?php endif; ?>
        </form>
    </div>

    <!-- Search Participant -->
    <div class="box">
        <h3>Search Participant</h3>
        <form method="POST">
            <label>Enter ID, First Name or Last Name:</label>
            <input type="text" name="keyword" required>
            <button type="submit" name="search_participant">Search</button>
        </form>

        <?php if ($search_result && $search_result->num_rows > 0): ?>
            <h4>Search Results:</h4>
            <table>
                <tr><th>ID</th><th>First Name</th><th>Last Name</th><th>Discount Rate</th><th>Actions</th></tr>
                <?php while ($row = $search_result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['partID'] ?></td>
                    <td><?= $row['partFName'] ?></td>
                    <td><?= $row['partLName'] ?></td>
                    <td><?= $row['partDRate'] ?></td>
                    <td>
                        <a href="?edit_participant=<?= $row['partID'] ?>"><button class="btn-edit">Edit</button></a>
                        <a href="?delete_participant=<?= $row['partID'] ?>" onclick="return confirm('Delete this participant?')">
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

    <!-- All Participants -->
    <div class="box">
        <h3>All Participants</h3>
        <table>
            <tr><th>ID</th><th>First Name</th><th>Last Name</th><th>Discount Rate</th><th>Actions</th></tr>
            <?php
            $all = $conn->query("SELECT * FROM participants");
            while($row = $all->fetch_assoc()): ?>
            <tr>
                <td><?= $row['partID'] ?></td>
                <td><?= $row['partFName'] ?></td>
                <td><?= $row['partLName'] ?></td>
                <td><?= $row['partDRate'] ?></td>
                <td>
                    <a href="?edit_participant=<?= $row['partID'] ?>"><button class="btn-edit">Edit</button></a>
                    <a href="?delete_participant=<?= $row['partID'] ?>" onclick="return confirm('Delete this participant?')">
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
