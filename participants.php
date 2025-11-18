<?php 
include 'config.php';

// Add Participant
if (isset($_POST['add_participant'])) {
    $partFName = $_POST['partFName'];
    $partLName = $_POST['partLName'];
    $partDRate = $_POST['partDRate'];

    $conn->query("INSERT INTO participants (partFName, partLName, partDRate) 
                  VALUES ('$partFName', '$partLName', '$partDRate')");
    header("Location: participants.php");
    exit;
}

// Update Participant
if (isset($_POST['update_participant'])) {
    $partID    = $_POST['partID'];
    $partFName = $_POST['partFName'];
    $partLName = $_POST['partLName'];
    $partDRate = $_POST['partDRate'];

    $conn->query("UPDATE participants SET
                    partFName='$partFName',
                    partLName='$partLName',
                    partDRate='$partDRate'
                  WHERE partID='$partID'");
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
    $search_result = $conn->query(
        "SELECT * FROM participants 
         WHERE partID='$keyword' OR partFName LIKE '%$keyword%' OR partLName LIKE '%$keyword%'"
    );
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Participants Management</title>
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
        input { width: 100%; padding: 7px; border: 1px solid #ccc; margin-top: 3px; }
        button { background: #4CAF50; padding: 7px 12px; border: none; color: #fff; margin-top: 10px; cursor: pointer; }
        button:hover { background: #45a049; }
        .btn-edit { background: #f39c12; }
        .btn-delete { background: #e74c3c; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #ccc; padding: 7px; font-size: 14px; }
        th { background: #eee; }
    </style>
</head>
<body>

<div class="wrapper">
    <h1>Participants Management</h1>

    <div style="text-align:center;">
        <a href="index.php">Back to Dashboard</a>
    </div>

    <!-- ADD PARTICIPANT -->
    <div class="box">
        <h3>Add Participant</h3>
        <form method="POST">
            <input type="hidden" name="add_participant" value="1">
            <label>First Name:</label>
            <input type="text" name="partFName" required>

            <label>Last Name:</label>
            <input type="text" name="partLName" required>

            <label>Discount Rate (%):</label>
            <input type="number" step="0.01" name="partDRate" value="0">

            <button type="submit">Add Participant</button>
        </form>
    </div>

    <!-- SEARCH PARTICIPANT -->
    <div class="box">
        <h3>Search Participant</h3>
        <form method="POST">
            <label>Enter Participant ID or Name:</label>
            <input type="text" name="keyword" required>
            <button type="submit" name="search_participant">Search</button>
        </form>

        <?php if ($search_result && $search_result->num_rows > 0): ?>
            <h4>Search Results:</h4>
            <table>
                <tr>
                    <th>ID</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Discount</th>
                    <th>Actions</th>
                </tr>
                <?php while ($row = $search_result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['partID'] ?></td>
                    <td><?= $row['partFName'] ?></td>
                    <td><?= $row['partLName'] ?></td>
                    <td><?= $row['partDRate'] ?>%</td>
                    <td>
                        <form method="POST" style="display:inline-block;">
                            <input type="hidden" name="partID" value="<?= $row['partID'] ?>">
                            <input type="hidden" name="partFName" value="<?= $row['partFName'] ?>">
                            <input type="hidden" name="partLName" value="<?= $row['partLName'] ?>">
                            <input type="hidden" name="partDRate" value="<?= $row['partDRate'] ?>">
                            <button class="btn-edit" name="update_participant">Update</button>
                        </form>
                        <a href="?delete_participant=<?= $row['partID'] ?>" 
                           onclick="return confirm('Delete this participant?')">
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

    <!-- VIEW ALL PARTICIPANTS -->
    <div class="box">
        <h3>All Participants</h3>
        <table>
            <tr>
                <th>ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Discount</th>
                <th>Actions</th>
            </tr>
            <?php 
            $all = $conn->query("SELECT * FROM participants");
            while ($row = $all->fetch_assoc()): ?>
            <tr>
                <td><?= $row['partID'] ?></td>
                <td><?= $row['partFName'] ?></td>
                <td><?= $row['partLName'] ?></td>
                <td><?= $row['partDRate'] ?>%</td>
                <td>
                    <a href="?delete_participant=<?= $row['partID'] ?>"
                       onclick="return confirm('Delete this participant?')">
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
