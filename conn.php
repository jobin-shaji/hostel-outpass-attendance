<!-- 
    usertype 
    3 -> admin
    2 -> security
    1 -> user default
    
    userstatus
    2 -> approved
    1 -> pending

    outpassstatus
    2 -> declined
    1 -> approved
    0 -> pending
-->
<?php
$conn = new mysqli("localhost", "root", "", "hostel");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// echo "Connected successfully";
