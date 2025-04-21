<?php
session_start();
if (!isset($_SESSION["userdetails"])) {
    header("Location: login.php");
    exit();
}

require("conn.php");

if (isset($_POST['update'])) {
    $inmateid = $_POST['inmateid'];
    $phone = $_POST['phone'];

    $result = $db->hostelinmatestable->updateOne(
        ['_id' => new MongoDB\BSON\ObjectId($inmateid)],
        [
            '$set' => [
                'phone' => $phone
            ]
        ]
    );

    if ($result->getModifiedCount() > 0) {
        $_SESSION["inmatedetails"]["phone"] = $phone;
        header("Location: user_page.php");
        exit();
    } else {
        echo "<script>alert('Error updating phone number.')</script>";
    }
}

header("Location: user_page.php");
exit();
?>