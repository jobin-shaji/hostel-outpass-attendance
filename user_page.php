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
        case 2: return "Declined";
        case 3: return "Canceled";
        case 4: return "Active";
        default: return "Unknown";
    }
}

function getStatusBadgeClass($status) {
    switch ($status) {
        case 0: return "bg-warning";
        case 2: return "bg-danger";
        case 3: return "bg-secondary";
        case 4: return "bg-primary";
        default: return "bg-secondary";
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
        :root {
            --primary-color: #4a90e2;
            --danger-color: #dc3545;
            --success-color: #28a745;
            --warning-color: #ffc107;
            --secondary-color: #6c757d;
            --light-bg: #f8f9fa;
            --shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        body {
            margin: 0;
            padding: 0;
            min-height: 100vh;
            padding-top: 70px;
            background-color: var(--light-bg);
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
        }

        header {
            height: 70px;
            width: 100%;
            padding: 0 20px;
            background-color: white;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 100;
            box-shadow: var(--shadow);
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
            font-size: 1.4rem;
            font-weight: 600;
            color: var(--primary-color);
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
            background-color: white;
            padding: 8px 0;
            border-radius: 8px;
            box-shadow: var(--shadow);
            width: 180px;
            z-index: 101;
            animation: fadeIn 0.2s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
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
            padding: 8px 16px;
            transition: background-color 0.2s;
        }

        .dp-dropdown li:hover {
            background-color: var(--light-bg);
        }

        .dp-dropdown a {
            text-decoration: none;
            color: #333;
            display: block;
            font-size: 0.95rem;
        }

        .main-content {
            padding: 30px;
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
        }

        .card {
            background: white;
            border-radius: 10px;
            box-shadow: var(--shadow);
            border: none;
        }

        .card-header {
            background-color: white;
            border-bottom: 1px solid rgba(0,0,0,0.1);
            padding: 1.25rem;
        }

        .btn {
            padding: 0.5rem 1rem;
            font-weight: 500;
            border-radius: 6px;
            transition: all 0.2s;
        }

        .btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .btn-sm {
            padding: 0.25rem 0.8rem;
            font-size: 0.875rem;
        }

        .form-control {
            border-radius: 6px;
            border: 1px solid #dee2e6;
            padding: 0.5rem 0.75rem;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(74, 144, 226, 0.25);
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

        .badge {
            padding: 0.5em 0.8em;
            font-weight: 500;
            border-radius: 6px;
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

            .card-header {
                flex-direction: column;
                gap: 10px;
            }
        }
    </style>
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
                        <div>
                            <?php if ($currentOutpass['outpassstatus'] == 0): ?>
                                <button type="button" class="btn btn-primary btn-sm" onclick="showEditForm()">Edit</button>
                            <?php endif; ?>
                            <?php if (in_array($currentOutpass['outpassstatus'], [0, 4])): ?>
                                <form method="post" action="update_outpass_status.php" style="display:inline;">
                                    <input type="hidden" name="outpassid" value="<?php echo $currentOutpass['_id']; ?>">
                                    <button type="submit" name="close" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to cancel this outpass?')">Cancel</button>
                                </form>
                            <?php endif; ?>
                        </div>
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
                                <span class="badge <?php echo getStatusBadgeClass($currentOutpass['outpassstatus']); ?>">
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