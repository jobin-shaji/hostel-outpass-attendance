<?php
session_start();
if (isset($_SESSION["userdetails"])) {
  $row = $_SESSION["userdetails"];
  if ($row['usertype'] == 1) {
    header("Location: user_page.php");
  } elseif ($row['usertype'] == 2) {
    header("Location: security_page.php");
  } else {

?><a href="logout.php">Logout</a>
    
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


<?php
  }
  exit();
} else {

  header("Location: login.php");
}
?>

</html>