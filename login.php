<?php
session_start();
if (isset($_SESSION["userdetails"])) {
    $row = $_SESSION["userdetails"];
    if ($row['usertype'] == 3) {
        header("Location: admin_page.php");
    } elseif ($row['usertype'] == 2) {
        header("Location: security_page.php");
    } else {
        header("Location: user_page.php");
    }
    exit();
} else {
?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    </head>
    <body class="bg-light">
        <section>
            <div class="container w-50 py-5">
                <div class="py-md-4">
                    <div class="bg-white shadow wrap my-md-5 d-md-flex justify-content-center border border-subtle-substitute rounded">
                        <div class="w-50 img-fluid" style="opacity: 95%; background-size: cover; background-image: url(images/loginsideimg3.jpg);">
                        </div>
                        <div class="w-50 p-5 ">
                            <div class="d-flex flex-column">
                                <h3 class="mb-3">Sign In</h3>
                                <div>
                                    <form method="post" onsubmit="return(val())">
                                        <div>
                                            <label for="name">Email</label>
                                            <input type="text" id="email" name="email" class="form-control" placeholder="Email" oninput="mailCheck()">
                                            <div class="text-danger" id="emailError" style="height: 30px;">
                                            </div>
                                        </div>
                                        <div>
                                            <label class="label" for="password">password</label>
                                            <input type="password" id="password" name="password" class="form-control" placeholder="password" oninput="passwordCheck()">
                                            <div class="text-danger" id="passwordError" style="height: 30px;">
                                                <?php
                                                require("conn.php");
                                                if (isset($_POST["submit"])) {
                                                    $email = $_POST['email'];
                                                    $password = $_POST['password'];
                                                    $sql = "SELECT * FROM usertable WHERE email = '$email' AND password = '$password';";
                                                    $result = $conn->query($sql);
                                                    if ($result->num_rows > 0) {
                                                        $row = $result->fetch_assoc();
                                                        $_SESSION["userdetails"] = $row;
                                                        if ($row['usertype'] == 3) {
                                                            header("Location: admin_page.php");
                                                        } elseif ($row['usertype'] == 2) {
                                                            header("Location: security_page.php");
                                                        } else {

                                                            $sql = "SELECT * FROM hostelinmatestable WHERE userid = " . $row["userid"] . ";";
                                                            $result = $conn->query($sql);
                                                            if ($result->num_rows > 0) {
                                                                $row = $result->fetch_assoc();
                                                                $_SESSION["inmatedetails"] = $row;
                                                            } else {
                                                                echo "<script type='text/javascript'>alert('This account is not linked to a hostelinmate')</script>";
                                                            }
                                                            header("Location: user_page.php");
                                                        }
                                                        exit();
                                                    } else {
                                                        echo "Invalid Email or Password";
                                                    }
                                                } ?>
                                            </div>
                                        </div>
                                        <div class=" mb-3">
                                            <button type="submit" id="submit" name="submit" class="form-control btn btn-primary rounded submit px-3">Sign
                                                In</button>
                                        </div>
                                    </form>
                                </div>
                                <p class="text-center">Not a member? <a href="signup.php">Sign Up</a></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </body>
    <script>
        var email = document.getElementById("email");
        var password = document.getElementById("password");
        var emailError = document.getElementById("emailError");
        var passwordError = document.getElementById("passwordError");
        const emailFormat = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9-]+\.[a-zA-Z]{2,4}$/;

        function val() {
            if (mailCheck()) {
                return (false);
            } else if (passwordCheck()) {
                return (false);
            } else {
                return (true);
            }
        }

        function passwordCheck() {
            if (password.value == "") {
                passwordError.innerHTML = "must enter password";
                document.getElementById("password").focus()
                return (true);
            } else if (password.value.length < 6) {
                passwordError.innerHTML = "password must be alteast 6 charactors long";
                document.getElementById("password").focus()
                return (true);
            } else {
                passwordError.innerHTML = "";
                return (false)
            }
        }

        function mailCheck() {
            passwordError.innerHTML = "";
            if (email.value == "") {
                document.getElementById("email").focus()
                return (true);
            } else if (!emailFormat.test(email.value)) {
                emailError.innerHTML = "must enter correct email fromat";
                document.getElementById("email").focus()
                return (true);
            } else {
                emailError.innerHTML = "";
                return (false)
            }
        }
    </script>
<?php
}
?>

    </html>