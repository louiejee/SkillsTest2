<?php 
include 'config.php';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_participant'])) {
        $partFName = $conn->real_escape_string($_POST['partFName']);
        $partLName = $conn->real_escape_string($_POST['partLName']);
        $partDRate = $conn->real_escape_string($_POST['partDRate']);
        
        $conn->query("INSERT INTO participants (partFName, partLName, partDRate) VALUES ('$partFName', '$partLName', '$partDRate')");
        header("Location: participants.php");
        exit;
    }
    
    if (isset($_POST['update_participant'])) {
        $partID = $conn->real_escape_string($_POST['partID']);
        $partFName = $conn->real_escape_string($_POST['partFName']);
        $partLName = $conn->real_escape_string($_POST['partLName']);
        $partDRate = $conn->real_escape_string($_POST['partDRate']);
        
        $conn->query("UPDATE participants SET partFName='$partFName', partLName='$partLName', partDRate='$partDRate' WHERE partID='$partID'");
        header("Location: participants.php");
        exit;
    }
}

// Handle deletions
if (isset($_GET['delete_participant'])) {
    $id = $conn->real_escape_string($_GET['delete_participant']);
    $conn->query("DELETE FROM participants WHERE partID = $id");
    header("Location: participants.php");
    exit;
}

// Check if editing
$edit_participant = null;
if (isset($_GET['edit_participant'])) {
    $id = $conn->real_escape_string($_GET['edit_participant']);
    $result = $conn->query("SELECT * FROM participants WHERE partID = $id");
    $edit_participant = $result->fetch_assoc();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Participants Management</title>
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
            <h1>Participants Management</h1>
        </header>
        
        <div class="content">
            <!-- Add/Edit Participant Form -->
            <form method="POST">
                <?php if($edit_participant): ?>
                    <input type="hidden" name="partID" value="<?= $edit_participant['partID'] ?>">
                    <input type="hidden" name="update_participant" value="1">
                    <h3>Edit Participant</h3>
                <?php else: ?>
                    <input type="hidden" name="add_participant" value="1">
                    <h3>Add New Participant</h3>
                <?php endif; ?>
                
                <div class="form-group">
                    <label>First Name:</label>
                    <input type="text" name="partFName" value="<?= $edit_participant ? $edit_participant['partFName'] : '' ?>" required>
                </div>
                <div class="form-group">
                    <label>Last Name:</label>
                    <input type="text" name="partLName" value="<?= $edit_participant ? $edit_participant['partLName'] : '' ?>" required>
                </div>
                <div class="form-group">
                    <label>Discount Rate (%):</label>
                    <input type="number" step="0.01" name="partDRate" value="<?= $edit_participant ? $edit_participant['partDRate'] : '0' ?>">
                </div>
                <button type="submit"><?= $edit_participant ? 'Update Participant' : 'Add Participant' ?></button>
                <?php if($edit_participant): ?>
                    <a href="participants.php"><button type="button">Cancel</button></a>
                <?php endif; ?>
            </form>

            <!-- Participants List -->
            <h3>All Participants</h3>
            <table>
                <tr>
                    <th>ID</th><th>First Name</th><th>Last Name</th><th>Discount</th><th>Actions</th>
                </tr>
                <?php
                $participants = $conn->query("SELECT * FROM participants");
                while($row = $participants->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['partID'] ?></td>
                    <td><?= $row['partFName'] ?></td>
                    <td><?= $row['partLName'] ?></td>
                    <td><?= $row['partDRate'] ?>%</td>
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
