<?php
// Include database connection file
require("conn.php");

if (isset($_POST["approve"])) {
    // Retrieve outpass ID from the form
    $outpassid = $_POST["outpassid"];

    // Update outpass status to 1 (approved)
    $sql = "UPDATE Outpasstable SET outpassstatus = 4  WHERE outpassid = $outpassid";

    if ($conn->query($sql) === TRUE) {
        // Outpass status updated successfully
        header("Location: admin_page.php");
        exit();
    } else {
        // Error updating outpass status
        echo "<script>alert('Error: Unable to update outpass status.')</script>";
    }
} elseif (isset($_POST["decline"])) {
    // Retrieve outpass ID from the form
    $outpassid = $_POST["outpassid"];
    $message = $_POST["message"];

    // Update outpass status to 2 (declined)
    $sql = "UPDATE Outpasstable SET outpassstatus = 2 , message = '$message' WHERE outpassid = $outpassid";

    if ($conn->query($sql) === TRUE) {
        // Outpass status updated successfully
        header("Location: admin_page.php");
        exit();
    } else {
        // Error updating outpass status
        echo "<script>alert('Error: Unable to update outpass status.')</script>";
    }
} elseif (isset($_POST["close"])) {
    // Retrieve outpass ID from the form
    $outpassid = $_POST["outpassid"];
    // $message = $_POST["message"];

    // Update outpass status to 3 (declined)
    $sql = "UPDATE Outpasstable SET outpassstatus = 3  WHERE outpassid = $outpassid";

    if ($conn->query($sql) === TRUE) {
        // Outpass status updated successfully
        header("Location: user_page.php");
        exit();
    } else {
        // Error updating outpass status
        echo "<script>alert('Error: Unable to update outpass status.')</script>";
    }
} else {
    // Redirect back to the listing page if the form was not submitted
    header("Location: list_outpasses.php");
    exit();
}

// Close connection
$conn->close();
