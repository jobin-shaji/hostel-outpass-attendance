<?php
session_start();
if (!isset($_SESSION["userdetails"]) || !isset($_SESSION["inmatedetails"])) {
    header("Location: login.php");
    exit();
}

require("conn.php");

$userDetails = $_SESSION["userdetails"];
$inmateDetails = $_SESSION["inmatedetails"];

if ($userDetails['usertype'] != 1) {
    header("Location: login.php");
    exit();
}

// Handle outpass request submission
if (isset($_POST["submit"])) {
    $outpassDoc = [
        'inmateid' => $inmateDetails['_id'],
        'place' => $_POST['place'],
        'purpose' => $_POST['purpose'],
        'outdate' => new MongoDB\BSON\UTCDateTime(strtotime($_POST['outdate']) * 1000),
        'indate' => new MongoDB\BSON\UTCDateTime(strtotime($_POST['indate']) * 1000),
        'outpassstatus' => 0 // pending
    ];
    
    if (insertOne('outpasstable', $outpassDoc)) {
        echo "<script>alert('Outpass request submitted successfully!')</script>";
    } else {
        echo "<script>alert('Error submitting outpass request')</script>";
    }
}

// Fetch user's most recent outpass request
$outpasses = find('outpasstable', [
    'inmateid' => $inmateDetails['_id']
], [
    'sort' => ['_id' => -1],
    'limit' => 1
]);

// Convert cursor to array for easier handling
$outpassList = iterator_to_array($outpasses);
$currentOutpass = !empty($outpassList) ? reset($outpassList) : null;

function getStatusText($status) {
    switch ($status) {
        case 0: return "Pending";
        case 1: return "Approved";
        case 2: return "Declined";
        case 3: return "Canceled";
        case 4: return "Active";
        default: return "Unknown";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <style>
        body {
            margin: 0;
            padding: 0;
            min-height: 100vh;
            padding-top: 70px; /* Account for fixed header */
        }

        header {
            height: 70px;
            width: 100%;
            padding: 0 20px;
            background-color: whitesmoke;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 100;
            box-shadow: 1px 1px 15px rgba(161, 182, 253, 0.825);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logosec {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .logo {
            font-size: 1.2rem;
            font-weight: bold;
        }

        .message {
            position: relative;
        }

        .dp {
            position: relative;
            cursor: pointer;
        }

        .dp-dropdown {
            display: none;
            position: absolute;
            top: 100%;
            right: 0;
            background-color: #ffffff;
            padding: 10px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 170px;
            z-index: 101;
        }

        .dp:hover .dp-dropdown {
            display: block;
        }

        .dp-dropdown ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }

        .dp-dropdown li {
            padding: 8px 0;
        }

        .dp-dropdown a {
            text-decoration: none;
            color: #333;
            display: block;
        }

        .dp-dropdown a:hover {
            color: #007bff;
        }

        .main-content {
            padding: 20px;
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
        }

        .date-row {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }

        .date-field {
            flex: 1;
            min-width: 250px;
        }

        @media (max-width: 768px) {
            .logo {
                display: none;
            }
            
            .main-content {
                padding: 15px;
            }

            .date-field {
                min-width: 100%;
            }
        }
    </style>

    <script>
        function validateDates() {
            const outdate = new Date(document.getElementById('outdate').value);
            const indate = new Date(document.getElementById('indate').value);
            
            if (outdate >= indate) {
                alert('Out Date must be before In Date');
                return false;
            }
            
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            
            if (outdate < today) {
                alert('Out Date cannot be in the past');
                return false;
            }
            
            return true;
        }

        function updateMinInDate() {
            const outdate = document.getElementById('outdate').value;
            document.getElementById('indate').min = outdate;
        }
    </script>
</head>

<body>
    <header>
        <div class="logosec">
            <div class="logo">Student Portal</div>
        </div>
        <div class="message">
            <div class="dp">
                <img src="https://media.geeksforgeeks.org/wp-content/uploads/20221210180014/profile-removebg-preview.png" class="dpicn" style="height: 42px;">
                <div class="dp-dropdown">
                    <ul>
                        <li><a href="logout.php">Logout</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </header>
    <main class="main-content">
        <div class="container">
            <?php if (!$currentOutpass || in_array($currentOutpass['outpassstatus'], [2, 3])): ?>
                <h2>Request Outpass</h2>
                <form method="post" onsubmit="return validateDates()">
                    <div class="mb-3">
                        <label for="place" class="form-label">Place</label>
                        <input type="text" class="form-control" id="place" name="place" required>
                    </div>
                    <div class="mb-3">
                        <label for="purpose" class="form-label">Purpose</label>
                        <input type="text" class="form-control" id="purpose" name="purpose" required>
                    </div>
                    <div class="date-row mb-3">
                        <div class="date-field">
                            <label for="outdate" class="form-label">Out Date</label>
                            <input type="date" class="form-control" id="outdate" name="outdate" 
                                   required min="<?php echo date('Y-m-d'); ?>" 
                                   onchange="updateMinInDate()">
                        </div>
                        <div class="date-field">
                            <label for="indate" class="form-label">In Date</label>
                            <input type="date" class="form-control" id="indate" name="indate" required>
                        </div>
                    </div>
                    <button type="submit" name="submit" class="btn btn-primary">Submit Request</button>
                </form>
            <?php else: ?>
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="mb-0">Current Outpass Request</h3>
                        <?php if ($currentOutpass['outpassstatus'] == 0): ?>
                            <div>
                                <button type="button" class="btn btn-primary btn-sm" onclick="showEditForm()">Edit</button>
                                <form method="post" action="update_outpass_status.php" style="display:inline;">
                                    <input type="hidden" name="outpassid" value="<?php echo $currentOutpass['_id']; ?>">
                                    <button type="submit" name="close" class="btn btn-danger btn-sm">Cancel</button>
                                </form>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <strong>Place:</strong> <?php echo $currentOutpass['place']; ?>
                            </div>
                            <div class="col-md-6 mb-2">
                                <strong>Purpose:</strong> <?php echo $currentOutpass['purpose']; ?>
                            </div>
                            <div class="col-md-6 mb-2">
                                <strong>Out Date:</strong> <?php echo date('Y-m-d', $currentOutpass['outdate']->toDateTime()->getTimestamp()); ?>
                            </div>
                            <div class="col-md-6 mb-2">
                                <strong>In Date:</strong> <?php echo date('Y-m-d', $currentOutpass['indate']->toDateTime()->getTimestamp()); ?>
                            </div>
                            <div class="col-md-6 mb-2">
                                <strong>Status:</strong> 
                                <span class="badge <?php echo $currentOutpass['outpassstatus'] == 0 ? 'bg-warning' : 
                                    ($currentOutpass['outpassstatus'] == 1 ? 'bg-success' : 
                                    ($currentOutpass['outpassstatus'] == 4 ? 'bg-primary' : 'bg-danger')); ?>">
                                    <?php echo getStatusText($currentOutpass['outpassstatus']); ?>
                                </span>
                            </div>
                            <?php if (!empty($currentOutpass['message'])): ?>
                            <div class="col-12 mb-2">
                                <strong>Message:</strong> <?php echo $currentOutpass['message']; ?>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Edit Form (Hidden by default) -->
                <div id="editForm" style="display: none;">
                    <h3>Edit Outpass Request</h3>
                    <form method="post" action="update_outpass_status.php" onsubmit="return validateDates()">
                        <div class="mb-3">
                            <label for="place" class="form-label">Place</label>
                            <input type="text" class="form-control" id="place" name="place" 
                                   value="<?php echo $currentOutpass['place']; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="purpose" class="form-label">Purpose</label>
                            <input type="text" class="form-control" id="purpose" name="purpose" 
                                   value="<?php echo $currentOutpass['purpose']; ?>" required>
                        </div>
                        <div class="date-row mb-3">
                            <div class="date-field">
                                <label for="outdate" class="form-label">Out Date</label>
                                <input type="date" class="form-control" id="outdate" name="outdate" 
                                       value="<?php echo date('Y-m-d', $currentOutpass['outdate']->toDateTime()->getTimestamp()); ?>"
                                       required min="<?php echo date('Y-m-d'); ?>" 
                                       onchange="updateMinInDate()">
                            </div>
                            <div class="date-field">
                                <label for="indate" class="form-label">In Date</label>
                                <input type="date" class="form-control" id="indate" name="indate" 
                                       value="<?php echo date('Y-m-d', $currentOutpass['indate']->toDateTime()->getTimestamp()); ?>"
                                       required>
                            </div>
                        </div>
                        <input type="hidden" name="outpassid" value="<?php echo $currentOutpass['_id']; ?>">
                        <button type="submit" name="update" class="btn btn-primary">Update Request</button>
                        <button type="button" class="btn btn-secondary" onclick="hideEditForm()">Cancel</button>
                    </form>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <script>
        function showEditForm() {
            document.getElementById('editForm').style.display = 'block';
        }

        function hideEditForm() {
            document.getElementById('editForm').style.display = 'none';
        }

        // ...existing validation code...
    </script>
</body>

</html>