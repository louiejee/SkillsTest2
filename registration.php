<?php 
include 'config.php';

// Handle registration
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['register'])) {
    $partID = $conn->real_escape_string($_POST['partID']);
    $evCode = $conn->real_escape_string($_POST['evCode']);
    $regPMode = $conn->real_escape_string($_POST['regPMode']);
    $regFeePaid = $conn->real_escape_string($_POST['regFeePaid']);
    $regDate = date('Y-m-d');
    
    $conn->query("INSERT INTO registration (partID, evCode, regDate, regFeePaid, regPMode) 
                 VALUES ('$partID', '$evCode', '$regDate', '$regFeePaid', '$regPMode')");
    header("Location: registration.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Participant Registration</title>
    <style>
        body { font-family: Arial; background: #f9f9f9; padding: 20px; }
        h1 { text-align: center; font-size: 20px; }
        .wrapper { max-width: 800px; margin: auto; }
        .box {
            background: #fff;
            padding: 15px;
            border: 1px solid #ccc;
            margin-bottom: 20px;
        }
        label { display: block; margin-top: 8px; font-size: 14px; }
        input, select { width: 100%; padding: 7px; border: 1px solid #ccc; margin-top: 3px; }
        button { background: #4CAF50; padding: 7px 12px; border: none; color: #fff; margin-top: 10px; cursor: pointer; }
        button:hover { background: #45a049; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #ccc; padding: 7px; font-size: 14px; }
        th { background: #eee; }
        .back-link { display: inline-block; margin-bottom: 20px; text-decoration: none; color: #3498db; }
    </style>
</head>
<body>

<div class="wrapper">
    <div style="text-align:center;">
        <a href="index.php">Back to Dashboard</a>
    </div>

    <div class="box">
        <h1>Participant Registration</h1>

        <!-- Registration Form -->
        <form method="POST">
            <input type="hidden" name="register" value="1">
            
            <label>Participant:</label>
            <select name="partID" id="partID" required onchange="calculateFee()">
                <option value="">Select Participant</option>
                <?php
                $parts = $conn->query("SELECT * FROM participants");
                while($p = $parts->fetch_assoc()): ?>
                <option value="<?= $p['partID'] ?>" data-discount="<?= $p['partDRate'] ?>">
                    <?= $p['partFName'] ?> <?= $p['partLName'] ?> (<?= $p['partDRate'] ?>% discount)
                </option>
                <?php endwhile; ?>
            </select>

            <label>Event:</label>
            <select name="evCode" id="evCode" required onchange="calculateFee()">
                <option value="">Select Event</option>
                <?php
                $events = $conn->query("SELECT * FROM events");
                while($e = $events->fetch_assoc()): ?>
                <option value="<?= $e['evCode'] ?>" data-fee="<?= $e['evFee'] ?>">
                    <?= $e['evName'] ?> (<?= $e['evFee'] ?>)
                </option>
                <?php endwhile; ?>
            </select>

            <label>Payment Mode:</label>
            <select name="regPMode" required>
                <option value="Cash">Cash</option>
                <option value="Card">Card</option>
            </select>

            <label>Fee to Pay:</label>
            <input type="number" step="0.01" name="regFeePaid" id="feeToPay" readonly>

            <button type="submit">Register Participant</button>
        </form>
    </div>

    <!-- Registration Records -->
    <div class="box">
        <h3>Registration Records (Latest to Oldest)</h3>
        <table>
            <tr>
                <th>Reg Code</th>
                <th>Participant</th>
                <th>Event</th>
                <th>Date</th>
                <th>Fee Paid</th>
                <th>Payment Mode</th>
            </tr>
            <?php
            $regs = $conn->query("SELECT r.*, p.partFName, p.partLName, e.evName 
                                FROM registration r
                                JOIN participants p ON r.partID = p.partID
                                JOIN events e ON r.evCode = e.evCode
                                ORDER BY r.regDate DESC, r.regCode DESC");
            while($row = $regs->fetch_assoc()): ?>
            <tr>
                <td><?= $row['regCode'] ?></td>
                <td><?= $row['partFName'] ?> <?= $row['partLName'] ?></td>
                <td><?= $row['evName'] ?></td>
                <td><?= $row['regDate'] ?></td>
                <td><?= $row['regFeePaid'] ?></td>
                <td><?= $row['regPMode'] ?></td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>

</div>

<script>
function calculateFee() {
    const participantSelect = document.getElementById('partID');
    const eventSelect = document.getElementById('evCode');
    const feeInput = document.getElementById('feeToPay');
    
    if (participantSelect.value && eventSelect.value) {
        const discount = parseFloat(participantSelect.options[participantSelect.selectedIndex].getAttribute('data-discount'));
        const eventFee = parseFloat(eventSelect.options[eventSelect.selectedIndex].getAttribute('data-fee'));
        feeInput.value = (eventFee - (eventFee * discount / 100)).toFixed(2);
    } else {
        feeInput.value = '';
    }
}
</script>

</body>
</html>
