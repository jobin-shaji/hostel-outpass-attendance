<?php
require("conn.php");

if (isset($_POST["approve"])) {
    // Retrieve outpass ID from the form
    $outpassid = $_POST["outpassid"];

    // Update outpass status directly to 4 (active) since there's no security approval needed
    $result = updateOne('outpasstable',
        ['_id' => new MongoDB\BSON\ObjectId($outpassid)],
        ['outpassstatus' => 4]
    );

    if ($result > 0) {
        header("Location: admin_page.php");
        exit();
    } else {
        echo "<script>alert('Error: Unable to update outpass status.')</script>";
    }
} elseif (isset($_POST["decline"])) {
    $outpassid = $_POST["outpassid"];
    $message = $_POST["message"];

    $result = updateOne('outpasstable',
        ['_id' => new MongoDB\BSON\ObjectId($outpassid)],
        [
            'outpassstatus' => 2,
            'message' => $message
        ]
    );

    if ($result > 0) {
        header("Location: admin_page.php");
        exit();
    } else {
        echo "<script>alert('Error: Unable to update outpass status.')</script>";
    }
} elseif (isset($_POST["update"])) {
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
    $outpassid = $_POST["outpassid"];

    $result = updateOne('outpasstable',
        ['_id' => new MongoDB\BSON\ObjectId($outpassid)],
        ['outpassstatus' => 3]
    );

    if ($result > 0) {
        header("Location: user_page.php");
        exit();
    } else {
        echo "<script>alert('Error: Unable to update outpass status.')</script>";
    }
} else {
    header("Location: user_page.php");
    exit();
}
?>
