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
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Document</title>
        </head>

        <body>
            <h1>security/staff page</h1>
        </body>
<?php
    }
    exit();
} else {

    header("Location: login.php");
}
?>

        </html>