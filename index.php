<?php include 'config.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Events Registration System</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: Arial; }
        body { background: #f5f5f5; padding: 20px; }
        .container { max-width: 1200px; margin: 0 auto; }
        header { background: #2c3e50; color: white; padding: 15px; border-radius: 5px; margin-bottom: 20px; }
        .tabs { display: flex; background: white; border-radius: 5px; margin-bottom: 20px; }
        .tab { padding: 15px 20px; cursor: pointer; border-right: 1px solid #eee; }
        .tab.active { background: #3498db; color: white; }
        .content { background: white; padding: 20px; border-radius: 5px; display: none; }
        .content.active { display: block; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input, select { width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; }
        button { background: #3498db; color: white; border: none; padding: 10px 15px; border-radius: 4px; cursor: pointer; margin: 5px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background: #f2f2f2; }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>Events Registration System</h1>
            <p>Welcome, Local Admin</p>
        </header>
        
        <div class="tabs">
            <div class="tab active" onclick="showTab('events')">Events Management</div>
            <div class="tab" onclick="showTab('participants')">Participants Management</div>
            <div class="tab" onclick="showTab('registration')">Registration</div>
            <div class="tab" onclick="showTab('reports')">Reports</div>
        </div>

        <!-- Events Management -->
        <div id="events" class="content active">
            <h2>Events Management</h2>
            
            <!-- Add Event Form -->
            <form method="POST">
                <input type="hidden" name="action" value="add_event">
                <div class="form-group">
                    <label>Event Name:</label>
                    <input type="text" name="evName" required>
                </div>
                <div class="form-group">
                    <label>Event Date:</label>
                    <input type="date" name="evDate" required>
                </div>
                <div class="form-group">
                    <label>Registration Fee:</label>
                    <input type="number" step="0.01" name="evFee" required>
                </div>
                <button type="submit">Add Event</button>
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
                        <a href="?edit_event=<?= $row['evCode'] ?>">Edit</a>
                        <a href="?delete_event=<?= $row['evCode'] ?>" onclick="return confirm('Delete this event?')">Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </table>
        </div>

        <!-- Participants Management -->
        <div id="participants" class="content">
            <h2>Participants Management</h2>
            
            <!-- Add Participant Form -->
            <form method="POST">
                <input type="hidden" name="action" value="add_participant">
                <div class="form-group">
                    <label>First Name:</label>
                    <input type="text" name="partFName" required>
                </div>
                <div class="form-group">
                    <label>Last Name:</label>
                    <input type="text" name="partLName" required>
                </div>
                <div class="form-group">
                    <label>Discount Rate (%):</label>
                    <input type="number" step="0.01" name="partDRate" value="0">
                </div>
                <button type="submit">Add Participant</button>
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
                        <a href="?edit_participant=<?= $row['partID'] ?>">Edit</a>
                        <a href="?delete_participant=<?= $row['partID'] ?>" onclick="return confirm('Delete this participant?')">Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </table>
        </div>

        <!-- Registration -->
        <div id="registration" class="content">
            <h2>Participant Registration</h2>
            
            <form method="POST">
                <input type="hidden" name="action" value="register">
                <div class="form-group">
                    <label>Participant:</label>
                    <select name="partID" required>
                        <option value="">Select Participant</option>
                        <?php
                        $parts = $conn->query("SELECT * FROM participants");
                        while($p = $parts->fetch_assoc()): ?>
                        <option value="<?= $p['partID'] ?>">
                            <?= $p['partFName'] ?> <?= $p['partLName'] ?>
                        </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Event:</label>
                    <select name="evCode" required onchange="calculateFee(this.value)">
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
                                    ORDER BY r.regDate DESC");
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

        <!-- Reports -->
        <div id="reports" class="content">
            <h2>Registration Reports</h2>
            
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

            <?php
            $filter = isset($_GET['event_filter']) ? "WHERE e.evCode = ".$_GET['event_filter'] : "";
            
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

            <div class="summary">
                <h3>Summary Statistics</h3>
                <p>Total Registrations: <?= $stat['totalReg'] ?></p>
                <p>Total Fees Paid: $<?= $stat['totalPaid'] ?></p>
                <p>Total Discounts: $<?= ($stat['totalOriginal'] - $stat['totalPaid']) ?></p>
            </div>
        </div>
    </div>

    <script>
        function showTab(tabName) {
            // Hide all tabs
            document.querySelectorAll('.tab').forEach(tab => {
                tab.classList.remove('active');
            });
            document.querySelectorAll('.content').forEach(content => {
                content.classList.remove('active');
            });
            
            // Show selected tab
            event.target.classList.add('active');
            document.getElementById(tabName).classList.add('active');
        }

        function calculateFee(evCode) {
            // This would need to be enhanced to calculate discount based on participant
            const selectedOption = document.querySelector('select[name="evCode"] option:checked');
            const eventFee = selectedOption.getAttribute('data-fee');
            document.getElementById('feeToPay').value = eventFee;
        }
    </script>

    <?php
    // Handle form submissions
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $action = $_POST['action'];
        
        if ($action == 'add_event') {
            $evName = $_POST['evName'];
            $evDate = $_POST['evDate'];
            $evFee = $_POST['evFee'];
            
            $conn->query("INSERT INTO events (evName, evDate, evFee) VALUES ('$evName', '$evDate', '$evFee')");
            echo "<script>alert('Event added successfully!');</script>";
        }
        
        if ($action == 'add_participant') {
            $partFName = $_POST['partFName'];
            $partLName = $_POST['partLName'];
            $partDRate = $_POST['partDRate'];
            
            $conn->query("INSERT INTO participants (partFName, partLName, partDRate) VALUES ('$partFName', '$partLName', '$partDRate')");
            echo "<script>alert('Participant added successfully!');</script>";
        }
        
        if ($action == 'register') {
            $partID = $_POST['partID'];
            $evCode = $_POST['evCode'];
            $regPMode = $_POST['regPMode'];
            $regFeePaid = $_POST['regFeePaid'];
            $regDate = date('Y-m-d');
            
            $conn->query("INSERT INTO registration (partID, evCode, regDate, regFeePaid, regPMode) 
                         VALUES ('$partID', '$evCode', '$regDate', '$regFeePaid', '$regPMode')");
            echo "<script>alert('Registration successful!');</script>";
        }
    }

    // Handle deletions
    if (isset($_GET['delete_event'])) {
        $id = $_GET['delete_event'];
        $conn->query("DELETE FROM events WHERE evCode = $id");
        echo "<script>alert('Event deleted!'); window.location.href='index.php';</script>";
    }
    
    if (isset($_GET['delete_participant'])) {
        $id = $_GET['delete_participant'];
        $conn->query("DELETE FROM participants WHERE partID = $id");
        echo "<script>alert('Participant deleted!'); window.location.href='index.php';</script>";
    }
    ?>
</body>
</html>
