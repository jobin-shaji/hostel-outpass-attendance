<?php
session_start();
if (isset($_SESSION["userdetails"])) {
    $row = $_SESSION["userdetails"];
    if ($row['usertype'] == 3) {
        header("Location: admin_page.php");
    } elseif ($row['usertype'] == 2) {
        header("Location: security_page.php");
    } else {

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
                header {
                    height: 70px;
                    width: 100%;
                    padding: 0 100px;
                    background-color: whitesmoke;
                    position: fixed;
                    z-index: 100;
                    box-shadow: 1px 1px 15px rgba(161, 182, 253, 0.825);
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                }

                .message,
                .logosec {
                    display: flex;
                    align-items: center;
                    justify-content: center;
                }

                .message {
                    gap: 60px;
                    position: relative;
                    cursor: pointer;
                    /* margin-right: 120px; */
                }

                .dp-dropdown {
                    display: none;
                    position: absolute;
                    top: calc(100% + 0px);
                    right: -70px;
                    background-color: #ffffff;
                    padding: 10px;
                    border-radius: 5px;
                    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                    width: 170px;
                    /* Adjusted width */
                }


                ul {
                    list-style-type: none;
                    padding: 0;
                }

                li {
                    padding: 8px 0;
                }

                .dp-dropdown a {
                    text-decoration: none;
                    color: #333;
                }

                .dp:hover .dp-dropdown {
                    display: block;
                }

                .nav {
                    /* width: 250px; */
                    background-color: white;
                    position: relative;
                    box-shadow: 1px 1px 10px rgba(198, 189, 248, 0.825);
                    overflow: hidden;
                    padding: 30px 0 20px 10px;
                }

                .navcontainer {
                    /* height: calc(100% - 70px); */
                    width: 250px;
                    position: sticky;
                    top: 70px;
                    overflow-y: scroll;
                    overflow-x: hidden;
                    transition: all 0.6s ease-in-out;
                }

                /* invicible scrolbar */
                .navcontainer::-webkit-scrollbar {
                    display: none;
                }

                .navclose {
                    width: 80px;
                }

                .nav-option {
                    color: black;
                    /* width: 240px; */
                    height: 60px;
                    align-items: center;
                    padding: 0 30px 0 20px;
                    gap: 20px;
                    transition: all 0.1s ease-in-out;
                }

                .nav-option:hover {
                    border-left: 5px solid #a2a2a2;
                    background-color: gray;
                    cursor: pointer;
                }

                .nav-img {
                    height: 30px;
                }

                .option {
                    border-left: 5px solid #010058af;
                    /* background-color:white */
                    /* color: white; */
                    /* cursor: pointer; */
                }

                .option1:hover {
                    border-left: 5px solid #010058af;
                    background-color: white
                }
            </style>
        </head>

        <?php
        // Include database connection file
        require("conn.php");

        if (isset($_POST["submit"])) {
            // Retrieve user ID from session
            $inmateid = $_SESSION["inmatedetails"]["inmateid"];

            // Fetch rows with outpass status as 0 (pending)
            $sql = "SELECT o.*, u.name AS student_name 
            FROM Outpasstable o 
            INNER JOIN hostelinmatestable h ON o.inmateid = h.inmateid 
            INNER JOIN usertable u ON h.userid = u.userid 
            WHERE o.inmateid = $inmateid  AND   o.outpassstatus = '{0,4}' 
            ORDER BY o.outpassid DESC";

            $result = $conn->query($sql);

            if ($result->num_rows == 0) {

                // Retrieve form data
                $fromtime = $_POST['fromtime'];
                $totime = $_POST['totime'];
                $place = $_POST['place'];
                $reason = $_POST['reason'];

                // Insert into database
                $sql = "INSERT INTO Outpasstable (inmateid, outpassstatus, exitdate, returndate, outpassdescription, place) 
            VALUES ('$inmateid', '0', '$fromtime', '$totime', '$reason', '$place')";

                if ($conn->query($sql) === TRUE) {
                    // Outpass request successfully inserted
                    $message = "Outpass request submitted successfully.";
                } else {
                    // Error inserting data into database
                    $error_message = "Error: Unable to submit outpass request.";
                }
            } else {
                // $error_message = "outpass request pending.";
            }
            // Close connection
            // $conn->close();
        }
        ?>

        <body>
            <?php
            // Include database connection file
            // require("conn.php");

            // if (isset($_POST["submit"])) {
            // Retrieve user ID from session
            $inmateid = $_SESSION["inmatedetails"]["inmateid"];

            // Fetch rows with outpass status as 0 (pending)
            $sql =
            "
            UPDATE Outpasstable o 
            SET o.outpassstatus = 1 
            WHERE o.returndate < CURRENT_TIME AND o.outpassstatus = 4;
            ";

            $result = $conn->query($sql);

            $sql =
                "
            SELECT o.*, u.name AS student_name 
            FROM Outpasstable o 
            INNER JOIN hostelinmatestable h ON o.inmateid = h.inmateid 
            INNER JOIN usertable u ON h.userid = u.userid 
            WHERE o.inmateid = $inmateid AND o.returndate > CURRENT_TIME AND o.outpassstatus IN (0, 4)
            ORDER BY o.outpassid DESC;            
            ";

            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                echo "
                    <style>
                    #new {
                        display: none;
                    }
                
                    #pend{
                        display: block;
                    }
                    </style>
                    ";
            } else {
                echo "
                    <style>
                    #pend{
                        display: none;
                    }
                
                    #new {
                        display: block;
                    }
                    </style>
                    ";
            }
            ?>

            <!-- for header part -->
            <header>

                <div class="logosec" style="gap: 60px;">
                    <img src="https://media.geeksforgeeks.org/wp-content/uploads/20221210182541/Untitled-design-(30).png" style="  height: 30px; cursor: pointer;" id="menuicn" alt="menu-icon">
                    <div class="logo">Student</div>
                </div>
                <div class="message">
                    <div class="dp">
                        <img src="https://media.geeksforgeeks.org/wp-content/uploads/20221210180014/profile-removebg-preview.png" class="dpicn" style=" height: 42px;">
                        <div class="dp-dropdown">
                            <ul>
                                <li><a href="logout.php">Logout</a></li>
                                <!-- <li><a href="#">Option 2</a></li>
                                <li><a href="#">Option 3</a></li> -->
                            </ul>
                        </div>
                    </div>

                </div>
            </header>
            <div class="d-flex w-100 position-relative justify-content-start" style="top: 70px; z-index: 1;">
                <div class="navcontainer">
                    <nav class="nav w-100 h-100 d-flex flex-column gap-4">
                        <!-- <div class="nav-upper-options"> -->
                        <div class="nav-option d-flex option">
                            <img src="https://media.geeksforgeeks.org/wp-content/uploads/20221210182148/Untitled-design-(29).png" class="nav-img" alt="dashboard">
                            <h4> outpass</h4>
                        </div>

                        <div class="d-flex nav-option">
                            <img src="https://media.geeksforgeeks.org/wp-content/uploads/20221210183322/9.png" class="nav-img" alt="articles">
                            <h4> Attendance</h4>
                        </div>

                        <!-- <div class="nav-option d-flex option3">
                            <img src="https://media.geeksforgeeks.org/wp-content/uploads/20221210183320/5.png" class="nav-img" alt="report">
                            <h4> Laundry</h4>
                        </div> -->

                        <!-- <div class="nav-option d-flex option4">
                            <img src="https://media.geeksforgeeks.org/wp-content/uploads/20221210183321/6.png" class="nav-img" alt="institution">
                            <h4> Institution</h4>
                        </div> -->

                        <!-- <div class="nav-option d-flex option5">
                            <img src="https://media.geeksforgeeks.org/wp-content/uploads/20221210183323/10.png" class="nav-img" alt="blog">
                            <h4> Profile</h4>
                        </div> -->

                        <!-- <div class="nav-option d-flex option6">
                            <img src="https://media.geeksforgeeks.org/wp-content/uploads/20221210183320/4.png" class="nav-img" alt="settings">
                            <h4> Settings</h4>
                        </div> -->

                        <!-- <div class="nav-option d-flex logout">
                            <img src="https://media.geeksforgeeks.org/wp-content/uploads/20221210183321/7.png" class="nav-img" alt="logout">
                            <h4>Logout</h4>
                        </div> -->

                        <!-- </div> -->
                    </nav>
                </div>

                <?php

                ?>

                <section class="bg-light w-100">
                    <!-- Soft alert message -->
                    <?php if (isset($message)) { ?>
                        <div class="alert alert-success" role="alert">
                            <?php echo $message; ?>
                        </div>
                    <?php } ?>

                    <?php if (isset($error_message)) { ?>
                        <div class="alert alert-danger" role="alert">
                            <?php echo $error_message; ?>
                        </div>
                    <?php } ?>

                    <div class="py-4 row" id="a1">
                    </div>
                    <div class=" mx-auto" style="width: 1200px;" id="pend">
                        <div class="row">
                            <div class="col">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title mb-4 d-inline">Pending Outpasses</h5>
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <!-- <th scope="col">Name</th> -->
                                                    <th scope="col">Outdate</th>
                                                    <th scope="col">Indate</th>
                                                    <th scope="col">Place</th>
                                                    <th scope="col">Reason</th>
                                                    <th scope="col">Status</th>
                                                    <th scope="col">action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                // Include database connection file
                                                require("conn.php");
                                                $inmateid = $_SESSION["inmatedetails"]["inmateid"];

                                                // Fetch rows with outpass status as 0 (pending)
                                                $sql =
                                                    "
                                                SELECT o.*, u.name AS student_name 
                                                FROM Outpasstable o 
                                                INNER JOIN hostelinmatestable h ON o.inmateid = h.inmateid 
                                                INNER JOIN usertable u ON h.userid = u.userid 
                                                WHERE o.inmateid = $inmateid AND o.returndate > CURRENT_TIME 
                                                ORDER BY o.outpassid DESC
                                                ";

                                                $result = $conn->query($sql);

                                                if ($result->num_rows > 0) {
                                                    // Output data of each row
                                                    while ($row = $result->fetch_assoc()) {
                                                        if ($row["outpassstatus"] == 0 or $row["outpassstatus"] == 4) {
                                                            echo "<tr>";
                                                            // echo "<td>" . $row["student_name"] . "</td>";
                                                            echo "<td>" . $row["exitdate"] . "</td>";
                                                            echo "<td>" . $row["returndate"] . "</td>";
                                                            echo "<td>" . $row["place"] . "</td>";
                                                            echo "<td>" . $row["outpassdescription"] . "</td>";

                                                            echo "<td>";
                                                            if ($row["outpassstatus"] == 0) {
                                                                echo "<button class='btn btn-warning disabled'>pending</button>";
                                                            } else {
                                                                echo "<button class='btn btn-success disabled'>Active</button>";
                                                            }

                                                            echo "</td>";
                                                            echo "<td>";
                                                            echo "<form action='update_outpass_status.php' method='post'>";
                                                            echo "<input type='hidden' name='outpassid' value='" . $row["outpassid"] . "'>";
                                                            echo "<button type='submit' name='close' class='btn-close' aria-label='Close'></button>";
                                                            echo "</form>";
                                                            echo "</td>";
                                                            echo "</tr>";
                                                        }
                                                    }
                                                } else {
                                                    echo "<tr><td colspan='7'>No pending outpass requests.</td></tr>";
                                                }
                                                // Close connection
                                                // $conn->close();
                                                ?>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class=" mx-auto" style="width: 900px;" id="new">
                        <!-- <div class="py-md-4"> -->
                        <div class="bg-white shadow wrap my-1 d-flex justify-content-center border border-subtle-substitute rounded">
                            <div class="w-50 bg-info d-flex flex-column justify-content-center text-white" style="padding:0px 30px ; " id="l">
                                <!--  -->
                                <p class="h1 fw-bolder p-4">
                                    Apply
                                </p>
                                <p class="h1 fw-bolder p-4">
                                    for New
                                </p>
                                <p class="h1 fw-bolder p-4">
                                    Outpass
                                </p>
                            </div>
                            <div class="w-50 p-5 py-4 " id="r">
                                <div class="d-flex flex-column">
                                    <h4 class="mb-2">Outpass</h4>
                                    <form action="" method="post" onsubmit="return(validateForm())">
                                        <div class="row g-3">
                                            <span class="col-12 fw-semibold fs-5">Out</span>
                                            <div class="col-10">
                                                <input type="datetime-local" id="fromtime" name="fromtime" class="form-control" oninput="validateFrom()">
                                            </div>
                                            <div class="col-12 text-danger" id="fromError" style="height: 15px;">
                                            </div>
                                            <span class="col-12 fw-semibold fs-5">In</span>
                                            <div class="col-10">
                                                <input type="datetime-local" id="totime" name="totime" class="form-control" oninput="validateTo()">
                                            </div>
                                            <div class="col-12 text-danger" id="toError" style="height: 15px;">
                                            </div>
                                            <span class="col-12 fw-semibold fs-5">Place</span>

                                            <div class="col-12">
                                                <input type="text" id="place" name="place" class="form-control" placeholder="place" oninput="validatePlace()">
                                                <div class="text-danger" id="placeError" style="height: 20px;">
                                                </div>
                                            </div>
                                            <span class="col-12 fw-semibold fs-5">Reason</span>

                                            <div class="col-12">
                                                <input type="text" id="reason" name="reason" class="form-control" placeholder="reason" oninput="validateReason()">
                                                <div class="text-danger" id="reasonError" style="height: 20px;">
                                                </div>
                                            </div>
                                            <div class=" mb-3">
                                                <button type="submit" id="submit" name="submit" class="form-control btn btn-primary rounded submit px-3">Submit Request</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <!-- Right side of the application form -->
                        </div>
                    </div>
                    <div class=" mx-auto" style="margin-top:30px; width: 500px;">
                        </button><button class="w-75 h3 p-2" style="background-color: #FFA500; color: white; border: none; border-radius: 5px; cursor: pointer; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2); transition: background-color 0.3s ease-in-out;">
                            History
                        </button>
                    </div>

                    <div class=" mx-auto" style="width: 1100px;">
                        <div class="row">
                            <div class="col">
                                <div class="card">
                                    <div class="card-body">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <!-- <th scope="col">Name</th> -->
                                                    <th scope="col">Outdate</th>
                                                    <th scope="col">Indate</th>
                                                    <th scope="col">Place</th>
                                                    <th scope="col">Reason</th>
                                                    <th scope="col">Status</th>
                                                    <th scope="col">Message</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                // Include database connection file
                                                require("conn.php");
                                                $inmateid = $_SESSION["inmatedetails"]["inmateid"];

                                                // Fetch rows with outpass status as 0 (pending)
                                                $sql = "SELECT o.*, u.name AS student_name 
                                                FROM Outpasstable o 
                                                INNER JOIN hostelinmatestable h ON o.inmateid = h.inmateid 
                                                INNER JOIN usertable u ON h.userid = u.userid 
                                                WHERE o.inmateid = $inmateid 
                                                ORDER BY o.outpassid DESC";

                                                $result = $conn->query($sql);

                                                if ($result->num_rows > 0) {
                                                    // Output data of each row
                                                    while ($row = $result->fetch_assoc()) {
                                                        if ($row["outpassstatus"] != 0 and $row["outpassstatus"] != 4) {
                                                            echo "<tr>";
                                                            // echo "<td>" . $row["student_name"] . "</td>";
                                                            echo "<td>" . $row["exitdate"] . "</td>";
                                                            echo "<td>" . $row["returndate"] . "</td>";
                                                            echo "<td>" . $row["place"] . "</td>";
                                                            echo "<td>" . $row["outpassdescription"] . "</td>";
                                                            echo "<td>";
                                                            // echo "<button class='btn btn-warning disabled'>pending</button>";
                                                            if ($row["outpassstatus"] == 1) {
                                                                echo "<button class='btn btn-success disabled'>Approved</button>";
                                                            } elseif ($row["outpassstatus"] == 2) {
                                                                echo "<button class='btn btn-danger disabled'>Declined </button>";
                                                            } else {
                                                                echo "<button class='btn btn-secondary disabled'>Canceled </button>";
                                                            }
                                                            echo "</td>";
                                                            echo "<td>" . $row["message"] . "</td>";
                                                            // echo "</form>";
                                                            echo "</tr>";
                                                        }
                                                    }
                                                } else {
                                                    echo "<tr><td colspan='7'>No pending outpass requests.</td></tr>";
                                                }
                                                // Close connection
                                                // $conn->close();
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>


            <script>
                let menuicn = document.querySelector("#menuicn");
                let nav = document.querySelector(".navcontainer");

                menuicn.addEventListener("click", () => {
                    nav.classList.toggle("navclose");
                })
            </script>
            <script>
                // Function to validate 'From' datetime
                function validateFrom() {
                    var fromtime = document.getElementById("fromtime").value;
                    var fromError = document.getElementById("fromError");
                    fromError.innerHTML = "";

                    var currentDate = new Date();
                    var selectedDate = new Date(fromtime);

                    if (selectedDate < currentDate) {
                        fromError.innerHTML = "Enter a date after today";
                        return false;
                    }

                    if (fromtime === "") {
                        fromError.innerHTML = "Select OUT datetime";
                        return false;
                    }
                    return true;
                }

                // Function to validate 'To' datetime
                // Function to validate 'To' datetime after 'From' datetime
                function validateTo() {
                    var fromtime = document.getElementById("fromtime").value;
                    var totime = document.getElementById("totime").value;
                    var toError = document.getElementById("toError");
                    toError.innerHTML = "";
                    if (totime === "") {
                        toError.innerHTML = "Select IN datetime";
                        return false;
                    } else if (totime <= fromtime) {
                        toError.innerHTML = "Return should be after out datetime";
                        return false;
                    }
                    return true;
                }

                // Function to validate 'Place'
                function validatePlace() {
                    var place = document.getElementById("place").value;
                    var placeError = document.getElementById("placeError");
                    placeError.innerHTML = "";
                    if (place.trim() === "") {
                        placeError.innerHTML = "Please enter the place";
                        return false;
                    }
                    return true;
                }

                // Function to validate 'Reason'
                function validateReason() {
                    var reason = document.getElementById("reason").value;
                    var reasonError = document.getElementById("reasonError");
                    reasonError.innerHTML = "";
                    if (reason.trim() === "") {
                        reasonError.innerHTML = "Please enter the reason";
                        return false;
                    }
                    return true;
                }

                // Function to validate the entire form
                function validateForm() {
                    return validateFrom() && validateTo() && validateToAfterFrom() && validatePlace() && validateReason();
                }
            </script>
        </body>
<?php
    }
    exit();
} else {
    header("Location: login.php");
}
$conn->close();
?>

        </html>