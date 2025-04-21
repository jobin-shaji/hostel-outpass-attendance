<?php
// Include database connection file
require("conn.php");

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
} elseif (isset($_POST["decline"])) {
    // Retrieve outpass ID and message from the form
    $outpassid = $_POST["outpassid"];
    $message = $_POST["message"];

    // Update outpass status to 2 (declined) and add message
    $result = updateOne('outpasstable',
        ['_id' => new MongoDB\BSON\ObjectId($outpassid)],
        [
            'outpassstatus' => 2,
            'message' => $message
        ]
    );

    if ($result > 0) {
        // Outpass status updated successfully
        header("Location: admin_page.php");
        exit();
    } else {
        // Error updating outpass status
        echo "<script>alert('Error: Unable to update outpass status.')</script>";
    }
} elseif (isset($_POST["update"])) {
    // Handle outpass update
    $outpassid = $_POST["outpassid"];
    $place = $_POST["place"];
    $purpose = $_POST["purpose"];
    $outdate = new MongoDB\BSON\UTCDateTime(strtotime($_POST['outdate']) * 1000);
    $indate = new MongoDB\BSON\UTCDateTime(strtotime($_POST['indate']) * 1000);

    $result = updateOne('outpasstable',
        ['_id' => new MongoDB\BSON\ObjectId($outpassid)],
        [
            'place' => $place,
            'purpose' => $purpose,
            'outdate' => $outdate,
            'indate' => $indate
        ]
    );

    if ($result > 0) {
        header("Location: user_page.php");
        exit();
    } else {
        echo "<script>alert('Error: Unable to update outpass request.'); window.location.href='user_page.php';</script>";
        exit();
    }
} elseif (isset($_POST["close"])) {
    // Retrieve outpass ID from the form
    $outpassid = $_POST["outpassid"];

    // Update outpass status to 3 (canceled)
    $result = updateOne('outpasstable',
        ['_id' => new MongoDB\BSON\ObjectId($outpassid)],
        ['outpassstatus' => 3]
    );

    if ($result > 0) {
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
?>
