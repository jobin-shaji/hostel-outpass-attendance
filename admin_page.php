<?php
session_start();
if (isset($_SESSION["userdetails"])) {
    $row = $_SESSION["userdetails"];
    if ($row['usertype'] == 1) {
        header("Location: user_page.php");
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

        <body>

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
                                <li><a href="#">Option 2</a></li>
                                <li><a href="#">Option 3</a></li>
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

                        <div class="nav-option d-flex option3">
                            <img src="https://media.geeksforgeeks.org/wp-content/uploads/20221210183320/5.png" class="nav-img" alt="report">
                            <h4> Laundry</h4>
                        </div>

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

                        <div class="nav-option d-flex logout">
                            <img src="https://media.geeksforgeeks.org/wp-content/uploads/20221210183321/7.png" class="nav-img" alt="logout">
                            <h4>Logout</h4>
                        </div>

                        <!-- </div> -->
                    </nav>
                </div>
                <section class="bg-light w-100">
                    <div class=" mx-auto" style="width: 900px;">
                        <div class="py-md-4">
                        <div class="row">
                            <div class="col">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title mb-4 d-inline">Pending Outpasses</h5>
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th scope="col">#</th>
                                                    <th scope="col">Name</th>
                                                    <th scope="col">Phone</th>
                                                    <th scope="col">Outdate</th>
                                                    <th scope="col">Place</th>
                                                    <th scope="col">Reason</th>
                                                    <th scope="col">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                // Include database connection file
                                                require("conn.php");

                                                // Fetch rows with outpass status as 0 (pending)
                                                $sql = "SELECT o.*, u.name AS student_name, h.phone
                                FROM Outpasstable o
                                INNER JOIN hostelinmatestable h ON o.inmateid = h.inmateid
                                INNER JOIN usertable u ON h.userid = u.userid
                                WHERE o.outpassstatus = 0";
                                                $result = $conn->query($sql);

                                                if ($result->num_rows > 0) {
                                                    // Output data of each row
                                                    while ($row = $result->fetch_assoc()) {
                                                        echo "<tr>";
                                                        echo "<th scope='row'>" . $row["outpassid"] . "</th>";
                                                        echo "<td>" . $row["student_name"] . "</td>";
                                                        echo "<td>" . $row["phone"] . "</td>";
                                                        echo "<td>" . $row["exitdate"] . "</td>";
                                                        echo "<td>" . $row["place"] . "</td>";
                                                        echo "<td>" . $row["outpassdescription"] . "</td>";
                                                        echo "<td>";
                                                        echo "<form method='post' action='update_outpass_status.php'>";
                                                        echo "<input type='hidden' name='outpassid' value='" . $row["outpassid"] . "'>";
                                                        echo "<button type='submit' name='approve' class='btn btn-success'>Approve</button>";
                                                        echo "&nbsp;";
                                                        echo "<button type='submit' name='decline' class='btn btn-danger'>Decline</button>";
                                                        echo "</form>";
                                                        echo "</td>";
                                                        echo "</tr>";
                                                    }
                                                } else {
                                                    echo "<tr><td colspan='7'>No pending outpass requests.</td></tr>";
                                                }
                                                // Close connection
                                                $conn->close();
                                                ?>
                                            </tbody>
                                        </table>
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

        </body>

        </html>
<?php
    }
    exit();
} else {

    header("Location: login.php");
}
?>