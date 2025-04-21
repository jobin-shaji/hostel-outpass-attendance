<?php
// Include database connection file
require("conn.php");
if (isset($_POST["in"])) {
    // Retrieve outpass ID from the form
    $inmateid = $_POST["inmateid"];

    // Update inmate status to out (approved)
    $sql = "UPDATE hostelinmatestable 
        SET inmatestatus = 0, checkindate = CURDATE()
        WHERE inmateid = $inmateid";

    if ($conn->query($sql) === TRUE) {
        // Outpass status updated successfully
        header("Location:security_page.php");
        exit();
    } else {
        // Error updating outpass status
        echo "<script>alert('Error: Unable to update outpass status.')</script>";
    }
} elseif (isset($_POST["out"])) {
    // Retrieve outpass ID from the form
    $inmateid = $_POST["inmateid"];

    // Update inmate status to out (approved)
    $sql = "UPDATE hostelinmatestable 
        SET inmatestatus = 1, checkoutdate = CURDATE()
        WHERE inmateid = $inmateid";
        
    if ($conn->query($sql) === TRUE) {
        // Outpass status updated successfully
        header("Location:security_page.php");
        exit();
    } else {
        // Error updating outpass status
        echo "<script>alert('Error: Unable to update outpass status.')</script>";
    }
} else {
    echo "<script>alert('Error: Unable to update outpass status.')</script>";
    // Redirect back to the listing page if the form was not submitted
    header("Location:security_page.php");
    exit();
}

// Close connection
$conn->close();
?>