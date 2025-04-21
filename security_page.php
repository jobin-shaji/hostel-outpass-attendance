<?php
session_start();
if (!isset($_SESSION["userdetails"])) {
    header("Location: login.php");
    exit();
}
$row = $_SESSION["userdetails"];
if ($row['usertype'] != 2) {
    header("Location: login.php");
    exit();
}

require("conn.php");

// Fetch active outpasses using MongoDB
$activeOutpasses = $db->outpasstable->find([
    'outpassstatus' => 4  // active status
]);

// Convert cursor to array for easier handling
$outpassList = iterator_to_array($activeOutpasses);
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
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

        .logosec {
            display: flex;
            align-items: center;
            gap: 20px;
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
            background-color: white;
            padding: 10px;
            border-radius: 4px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            width: 170px;
            z-index: 1031;
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
            padding: 8px 12px;
        }

        .dp-dropdown a {
            text-decoration: none;
            color: #333;
            display: block;
        }

        .dp-dropdown a:hover {
            color: #007bff;
        }

        .container-fluid {
            padding: 20px;
        }

        .table-responsive {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .table th {
            border-top: none;
            background-color: #f8f9fa;
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
            <div class="logo-text" style="color: black; font-size:24px;">Staff Portal</div>
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
            <h2 class="mb-4">Active Outpasses</h2>
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
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($outpassList as $outpass) {
                            $inmate = $db->hostelinmatestable->findOne([
                                '_id' => $outpass['inmateid']
                            ]);
                            $user = $db->usertable->findOne([
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
                            if ($outpass['outpassstatus'] == 4) {
                                echo "Active";
                            }
                            echo "</td>";
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