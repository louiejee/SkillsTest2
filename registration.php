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
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: Arial; }
        body { background: #f5f5f5; padding: 20px; }
        .container { max-width: 1200px; margin: 0 auto; }
        header { background: #2c3e50; color: white; padding: 15px; border-radius: 5px; margin-bottom: 20px; }
        .content { background: white; padding: 20px; border-radius: 5px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input, select { width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; }
        button { background: #3498db; color: white; border: none; padding: 10px 15px; border-radius: 4px; cursor: pointer; margin: 5px; }
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
            <h1>Participant Registration</h1>
        </header>
        
        <div class="content">
            <!-- Registration Form -->
            <form method="POST">
                <input type="hidden" name="register" value="1">
                <div class="form-group">
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
                </div>
                <div class="form-group">
                    <label>Event:</label>
                    <select name="evCode" id="evCode" required onchange="calculateFee()">
                        <option value="">Select Event</option>
                        <?php
                        $events = $conn->query("SELECT * FROM events");
                        while($e = $events->fetch_assoc()): ?>
                        <option value="<?= $e['evCode'] ?>" data-fee="<?= $e['evFee'] ?>">
                            <?= $e['evName'] ?> ($<?= $e['evFee'] ?>)
                        </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Payment Mode:</label>
                    <select name="regPMode" required>
                        <option value="Cash">Cash</option>
                        <option value="Card">Card</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Fee to Pay:</label>
                    <input type="number" step="0.01" name="regFeePaid" id="feeToPay" readonly>
                </div>
                <button type="submit">Register Participant</button>
            </form>

            <!-- Registration Records -->
            <h3>Registration Records (Latest to Oldest)</h3>
            <table>
                <tr>
                    <th>Reg Code</th><th>Participant</th><th>Event</th><th>Date</th><th>Fee Paid</th><th>Payment</th>
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
                    <td>$<?= $row['regFeePaid'] ?></td>
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
                
                const discountedFee = eventFee - (eventFee * (discount / 100));
                feeInput.value = discountedFee.toFixed(2);
            } else {
                feeInput.value = '';
            }
        }
    </script>
</body>
</html>
