<?php
session_start();
if (isset($_SESSION["userdetails"])) {
    $row = $_SESSION["userdetails"];
    if ($row['usertype'] == 3) {
        header("Location: admin_page.php");
    } elseif ($row['usertype'] == 1) {
        header("Location: user_page.php");
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
            <!-- Load icon library -->
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
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

                * {
                    box-sizing: border-box;
                }

                /* Style the search field */
                form.example input[type=text] {
                    padding: 10px;
                    font-size: 17px;
                    border: 1px solid grey;
                    float: left;
                    width: 80%;
                    background: #f1f1f1;
                }

                /* Style the submit button */
                form.example button {
                    float: left;
                    width: 20%;
                    padding: 10px;
                    background: #2196F3;
                    color: white;
                    font-size: 17px;
                    border: 1px solid grey;
                    border-left: none;
                    /* Prevent double borders */
                    cursor: pointer;
                }

                form.example button:hover {
                    background: #0b7dda;
                }

                /* Clear floats */
                form.example::after {
                    content: "";
                    clear: both;
                    display: table;
                }

                a {
                    text-decoration: none !important;
                }
            </style>
        </head>

        <body>

            <!-- for header part -->
            <header>

                <div class="logosec" style="gap: 60px;">
                    <img src="https://media.geeksforgeeks.org/wp-content/uploads/20221210182541/Untitled-design-(30).png" style="  height: 30px; cursor: pointer;" id="menuicn" alt="menu-icon">
                    <div style="color: black; font-size:30px;">Staff</div>
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

                        <a href="security_attendance.php">
                            <div class="d-flex nav-option">
                                <img src="https://media.geeksforgeeks.org/wp-content/uploads/20221210183322/9.png" class="nav-img" alt="articles">
                                <h4> Attendance</h4>
                            </div>
                        </a>
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
                <section class="bg-light w-100">

                    <div class="my-5"></div>
                    <div class=" mx-auto p-3" style="width: 900px;" id="new">
                        <div class="h3">Admission No</div>

                        <!-- The form -->
                        <form class="example" method="post" action="">
                            <input type="text" placeholder="Search.." name="admission">
                            <button name="search" type="submit"><i class="fa fa-search"></i></button>
                        </form>
                    </div>

                    <div class=" mx-auto" style="width: 1200px;">
                        <div class="py-4 row" id="a1">

                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="card">
                                    <div class="card-body">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th scope="col">Name</th>
                                                    <th scope="col">Outdate</th>
                                                    <th scope="col">Indate</th>
                                                    <th scope="col">Place</th>
                                                    <th scope="col">Reason</th>
                                                    <th scope="col">Status</th>
                                                    <th scope="col">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                if (isset($_POST['search'])) {
                                                    // Include database connection file
                                                    require("conn.php");
                                                    // $inmateid = $_SESSION["inmatedetails"]["inmat

                                                    $admission = $_POST["admission"];
                                                    $sql = "
                                                SELECT o.*, u.name AS student_name ,h.inmatestatus
FROM Outpasstable o 
INNER JOIN hostelinmatestable h ON o.inmateid = h.inmateid 
INNER JOIN usertable u ON h.userid = u.userid 
WHERE h.admissionno = $admission AND o.outpassstatus = 4 AND o.returndate >= CURDATE()
ORDER BY o.returndate ASC;
                                                ";
                                                    $result = $conn->query($sql);
                                                    if ($result->num_rows > 0) {
                                                        // Output data of each row
                                                        while ($row = $result->fetch_assoc()) {
                                                            echo "<tr>";
                                                            echo "<td>" . $row["student_name"] . "</td>";
                                                            echo "<td>" . $row["exitdate"] . "</td>";
                                                            echo "<td>" . $row["returndate"] . "</td>";
                                                            echo "<td>" . $row["place"] . "</td>";
                                                            echo "<td>" . $row["outpassdescription"] . "</td>";
                                                            echo "<td>";
                                                            if ($row["inmatestatus"] == '0') {
                                                                echo "<button name='in' class='btn btn-success disabled'>IN</button>";
                                                            } else {
                                                                echo "<button name='out' class='btn btn-danger disabled'>OUT</button>";
                                                            }
                                                            echo "</td>";
                                                            echo "<td>";
                                                            echo "<form action='inmate_update.php' method='post'>";
                                                            echo "<input type='hidden' name='inmateid' value='" . $row["inmateid"] . "'>";
                                                            if ($row["inmatestatus"] == '0') {
                                                                echo "<button name='out' class='btn btn-danger '>OUT</button>";
                                                            } else {
                                                                echo "<button name='in' class='btn btn-success '>IN</button>";
                                                            }
                                                            echo "</td>";
                                                            echo "</form>";
                                                            echo "</tr>";
                                                        }
                                                    } else {
                                                        echo "<tr><td colspan='7'>No outpass.</td></tr>";
                                                    }
                                                    // Close connection
                                                    // $conn->close();
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <form action="update_inmate_status" method="post">
                    </form>


                    <!-- <div class=" mx-auto" style="width: 1200px;">
                        <div class="py-4 row" id="a1">

                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title mb-4 d-inline">Pending Outpasses</h5>
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th scope="col">Name</th>
                                                    <th scope="col">Outdate</th>
                                                    <th scope="col">Indate</th>
                                                    <th scope="col">Place</th>
                                                    <th scope="col">Reason</th>
                                                    <th scope="col">Action</th>
                                                    <th scope="col"></th>
                                                    <th scope="col">Message</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                
                                                <?php
                                                // // Include database connection file
                                                // require("conn.php");

                                                // // Fetch rows with outpass status as 0 (pending)
                                                // $sql = "SELECT o.*, u.name AS student_name FROM Outpasstable o INNER JOIN hostelinmatestable h ON o.inmateid = h.inmateid INNER JOIN usertable u ON h.userid = u.userid WHERE o.outpassstatus = 0";
                                                // $result = $conn->query($sql);

                                                // if ($result->num_rows > 0) {
                                                //     // Output data of each row
                                                //     while ($row = $result->fetch_assoc()) {
                                                //         echo "<tr>";
                                                //         echo "<td>" . $row["student_name"] . "</td>";
                                                //         echo "<td>" . $row["exitdate"] . "</td>";
                                                //         echo "<td>" . $row["returndate"] . "</td>";
                                                //         echo "<td>" . $row["place"] . "</td>";
                                                //         echo "<td>" . $row["outpassdescription"] . "</td>";
                                                //         echo "<td>";
                                                //         echo "<form method='post' action='update_outpass_status.php'>";
                                                //         echo "<input type='hidden' name='outpassid' value='" . $row["outpassid"] . "'>";
                                                //         echo "<button type='submit' name='approve' class='btn btn-success'>Approve</button>";
                                                //         echo "&nbsp;";
                                                //         echo "</td>";
                                                //         echo "<td>";
                                                //         echo "<button type='submit' name='decline' class='btn btn-danger'>Decline</button>";
                                                //         echo "</td>";
                                                //         echo "<td>" . "<input type='text' name='message'>" . "</td>";
                                                //         echo "</form>";
                                                //         echo "</tr>";
                                                //     }
                                                // } else {
                                                //     echo "<tr><td colspan='7'>No pending outpass requests.</td></tr>";
                                                // }
                                                // // Close connection
                                                // $conn->close();
                                                ?>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> -->
                </section>
            </div>
            <script>
                let menuicn = document.querySelector("#menuicn");
                let nav = document.querySelector(".navcontainer");

                menuicn.addEventListener("click", () => {
                    nav.classList.toggle("navclose");
                })
            </script>

        </body>

        </html>
<?php
    }
    exit();
} else {

    header("Location: login.php");
}
?>

</html>