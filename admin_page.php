<?php
session_start();
if (!isset($_SESSION["userdetails"])) {
    header("Location: login.php");
    exit();
}
$row = $_SESSION["userdetails"];
if ($row['usertype'] != 3) {
    header("Location: login.php");
    exit();
}

require("conn.php");

// Fetch pending outpass requests using MongoDB
$pendingOutpasses = find('outpasstable', [
    'outpassstatus' => 0 // pending status
]);

// Fetch all outpass requests that are not pending
$otherOutpasses = find('outpasstable', [
    'outpassstatus' => ['$ne' => 0]
]);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST["approve"])) {
        // Retrieve outpass ID from the form
        $outpassid = $_POST["outpassid"];

        // Update outpass status to 4 (active)
        $result = updateOne('outpasstable', 
            ['_id' => new MongoDB\BSON\ObjectId($outpassid)],
            ['outpassstatus' => 4]
        );

        if ($result > 0) {
            // Outpass status updated successfully
            header("Location: admin_page.php");
            exit();
        } else {
            // Error updating outpass status
            echo "<script>alert('Error: Unable to update outpass status.')</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
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
            z-index: 1030;
            box-shadow: var(--shadow);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo-text {
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
            z-index: 1031;
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

        .container-fluid {
            padding: 30px;
        }

        .table-responsive {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: var(--shadow);
            margin-bottom: 30px;
        }

        .table {
            margin-bottom: 0;
        }

        .table th {
            border-top: none;
            background-color: var(--light-bg);
            padding: 12px 16px;
            font-weight: 600;
            color: #444;
        }

        .table td {
            padding: 12px 16px;
            vertical-align: middle;
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

        .badge {
            padding: 0.5em 0.8em;
            font-weight: 500;
            border-radius: 6px;
        }

        h2 {
            margin-bottom: 1.5rem;
            color: #333;
            font-weight: 600;
        }

        @media (max-width: 768px) {
            .container-fluid {
                padding: 15px;
            }

            .table-responsive {
                padding: 15px;
            }

            .logo-text {
                display: none;
            }

            .table td, .table th {
                padding: 8px;
            }

            h2 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>

<body>
    <header>
        <div class="logosec">
            <div class="logo-text" style="color: black; font-size:24px;">Admin Portal</div>
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
    
    <div class="container-fluid">
        <div class="container">
            <h2>Pending Outpass Requests</h2>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Admission No</th>
                            <th>Name</th>
                            <th>Place</th>
                            <th>Purpose</th>
                            <th>Out Date</th>
                            <th>In Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($pendingOutpasses as $outpass) {
                            $inmate = findOne('hostelinmatestable', [
                                '_id' => $outpass['inmateid']
                            ]);
                            $user = findOne('usertable', [
                                '_id' => $inmate['userid']
                            ]);
                            echo "<tr>";
                            echo "<td>" . $inmate['admissionno'] . "</td>";
                            echo "<td>" . $user['name'] . "</td>";
                            echo "<td>" . $outpass['place'] . "</td>";
                            echo "<td>" . $outpass['purpose'] . "</td>";
                            echo "<td>" . date('Y-m-d', $outpass['outdate']->toDateTime()->getTimestamp()) . "</td>";
                            echo "<td>" . date('Y-m-d', $outpass['indate']->toDateTime()->getTimestamp()) . "</td>";
                            echo "<td>";
                            echo "<form method='post' action='admin_page.php' style='display:inline;'>";
                            echo "<input type='hidden' name='outpassid' value='" . $outpass['_id'] . "'>";
                            echo "<button type='submit' name='approve' class='btn btn-success btn-sm'>Approve</button>";
                            echo "</form>";
                            echo "<button type='button' class='btn btn-danger btn-sm' data-bs-toggle='modal' data-bs-target='#declineModal" . $outpass['_id'] . "'>Decline</button>";
                            echo "</td>";
                            echo "</tr>";
                            echo "<div class='modal fade' id='declineModal" . $outpass['_id'] . "' tabindex='-1' aria-labelledby='declineModalLabel' aria-hidden='true'>";
                            echo "</div>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <h2 class="mt-5">Outpass Request History</h2>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Admission No</th>
                            <th>Name</th>
                            <th>Place</th>
                            <th>Purpose</th>
                            <th>Out Date</th>
                            <th>In Date</th>
                            <th>Status</th>
                            <th>Message</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($otherOutpasses as $outpass) {
                            $inmate = findOne('hostelinmatestable', [
                                '_id' => $outpass['inmateid']
                            ]);
                            $user = findOne('usertable', [
                                '_id' => $inmate['userid']
                            ]);
                            $status = "";
                            $badgeClass = "";
                            switch ($outpass['outpassstatus']) {
                                case 4:
                                    $status = "Active";
                                    $badgeClass = "bg-primary";
                                    break;
                                case 2:
                                    $status = "Declined";
                                    $badgeClass = "bg-danger";
                                    break;
                                case 3:
                                    $status = "Canceled";
                                    $badgeClass = "bg-secondary";
                                    break;
                                default:
                                    $status = "Pending";
                                    $badgeClass = "bg-warning";
                            }
                            echo "<tr>";
                            echo "<td>" . $inmate['admissionno'] . "</td>";
                            echo "<td>" . $user['name'] . "</td>";
                            echo "<td>" . $outpass['place'] . "</td>";
                            echo "<td>" . $outpass['purpose'] . "</td>";
                            echo "<td>" . date('Y-m-d', $outpass['outdate']->toDateTime()->getTimestamp()) . "</td>";
                            echo "<td>" . date('Y-m-d', $outpass['indate']->toDateTime()->getTimestamp()) . "</td>";
                            echo "<td><span class='badge $badgeClass'>" . $status . "</span></td>";
                            echo "<td>" . ($outpass['message'] ?? '') . "</td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>

</html>