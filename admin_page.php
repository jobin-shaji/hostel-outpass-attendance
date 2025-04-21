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
        body {
            margin: 0;
            padding: 0;
            min-height: 100vh;
            padding-top: 70px;
            background-color: #f5f5f5;
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
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .container-fluid {
            padding: 20px;
        }

        .table-responsive {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            margin-bottom: 30px;
        }

        .table th {
            border-top: none;
            background-color: #f8f9fa;
        }

        .btn-sm {
            padding: 0.25rem 0.8rem;
            margin: 0 0.2rem;
        }

        @media (max-width: 768px) {
            header {
                padding: 0 10px;
            }

            .logo-text {
                display: none;
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
                            echo "<form method='post' action='update_outpass_status.php' style='display:inline;'>";
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
            <h2 class="mt-5">Other Outpass Requests</h2>
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
                            switch ($outpass['outpassstatus']) {
                                case 1:
                                    $status = "Approved";
                                    break;
                                case 2:
                                    $status = "Declined";
                                    break;
                                case 3:
                                    $status = "Canceled";
                                    break;
                                case 4:
                                    $status = "Active";
                                    break;
                            }
                            echo "<tr>";
                            echo "<td>" . $inmate['admissionno'] . "</td>";
                            echo "<td>" . $user['name'] . "</td>";
                            echo "<td>" . $outpass['place'] . "</td>";
                            echo "<td>" . $outpass['purpose'] . "</td>";
                            echo "<td>" . date('Y-m-d', $outpass['outdate']->toDateTime()->getTimestamp()) . "</td>";
                            echo "<td>" . date('Y-m-d', $outpass['indate']->toDateTime()->getTimestamp()) . "</td>";
                            echo "<td>" . $status . "</td>";
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