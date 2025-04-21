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
    require("conn.php");
    //val=0 if validation currect
    $val = 1
?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>SignUp</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
        <style>
            /* input box number inner scrollbar disabled */
            input::-webkit-outer-spin-button,
            input::-webkit-inner-spin-button {
                -webkit-appearance: none;
                margin: 0;
            }
        </style>
    </head>

    <body class="bg-light">
        <section>
            <div class="mx-auto w-75  py-5">
                <div class="d-flex  bg-white shadow wrap my-4  border border-subtle-substitute rounded">
                    <div class="img-fluid" style="width:35%; opacity: 95%; background-size: cover; background-image: url(images/loginsideimg2.jpg);">
                    </div>
                    <div class="p-5 w-100">
                        <h3 class="mb-3">Sign Up</h3>
                        <form method="post" onsubmit="return(val())">

                            <div class="row g-4">
                                <div class="col-5">
                                    <label for="fullname">Full Name</label>
                                    <input type="text" id="fullname" name="fullname" class="form-control" oninput="validateFullName()">
                                    <div class="text-danger" id="fullnameError" style="height: 50px; width:200px;">
                                    </div>
                                </div>

                                <div class="col-4">
                                    <label for="admissionNo">Admission No</label>
                                    <input type="text" id="admissionNo" name="admissionNo" class="form-control" oninput="validateadminNo()">
                                    <div class="text-danger" id="admissionNoError" style="height: 50px;">
                                        <?php
                                        if (isset($_POST["submit"])) {
                                            $admissionNo = $_POST['admissionNo'];
                                            $sql = "SELECT * FROM hostelinmatestable WHERE admissionNo = '$admissionNo'";
                                            $result = $conn->query($sql);
                                            if ($result->num_rows > 0) {
                                                echo "Admision No already exists!";
                                                $val = 1;
                                            } else {
                                                $val = 0;
                                            }
                                        }
                                        ?>

                                    </div>
                                </div>
                            </div>

                            <div class="g-3 row">
                                <div class="col-5">
                                    <label for="phoneNo">Phone</label>
                                    <input type="number" id="phoneNo" name="phoneNo" class="form-control" oninput="validatephoneNo()">
                                    <div class="text-danger" id="phoneNoError" style="height: 50px;">
                                    </div>
                                </div>

                                <div class="col-6">
                                    <label for="email">Email</label>
                                    <input type="text" id="email" name="email" class="form-control" oninput="validateEmail()">
                                    <div class="text-danger" id="emailError" style="height: 50px;">
                                        <?php
                                        if (isset($_POST["submit"])) {
                                            $email = $_POST['email'];
                                            $sql = "SELECT * FROM usertable WHERE email = '$email'";
                                            $result = $conn->query($sql);
                                            if ($result->num_rows > 0) {
                                                echo "Email already exists!";
                                            } else {
                                                if ($val  == 0) {
                                                    $email = $_POST['email'];
                                                    $admissionNo = $_POST['admissionNo'];
                                                    $fullname = $_POST['fullname'];
                                                    $phone = $_POST['phoneNo'];
                                                    $password = $_POST['password'];

                                                    // Inserting user data into the database
                                                    $sql = "INSERT INTO usertable (name, email, password) VALUES ('$fullname', '$email', '$password')";
                                                    if ($conn->query($sql)) {
                                                        // Retrieving user ID
                                                        $sql = "SELECT userid FROM usertable WHERE email = '$email'";
                                                        $result = $conn->query($sql);
                                                        if ($result->num_rows > 0) {
                                                            $row = $result->fetch_assoc();
                                                            $userid = $row["userid"];

                                                            // Inserting user data into hostelinmatestable
                                                            $sql = "INSERT INTO hostelinmatestable (admissionno, userid, phone) VALUES ('$admissionNo', '$userid', '$phone')";
                                                            if ($conn->query($sql)) {
                                                                header("Location: login.php");
                                                                exit();
                                                            } else {
                                                                echo "<script>alert('Error')</script>";
                                                            }
                                                        }
                                                    } else {
                                                        echo "<script>alert('Error')</script>";
                                                    }
                                                }
                                            }
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>

                            <div class="g-3 row">
                                <div class="col-6">
                                    <label class="label" for="password">password</label>
                                    <input type="password" id="password" name="password" class="form-control" oninput="valpass()">
                                    <div class="text-danger" id="passwordError" style="height: 50px;">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <label class="label" for="confirmPassword">confirmPassword</label>
                                    <input type="Password" id="confirmPassword" name="confirmPassword" class="form-control" oninput="valcpass()">
                                    <div class="text-danger" id="confirmPasswordError" style="height: 50px;">
                                    </div>
                                </div>
                            </div>

                            <div>
                                <div class="w-50 mx-auto mb-3">
                                    <button type="submit" id="submit" name="submit" class="form-control btn btn-primary rounded submit px-3">
                                        Sign Up
                                    </button>
                                </div>
                                <p class="text-center">Already a member? <a href="login.php">Sign In</a></p>
                            </div>
                            
                        </form>
                    </div>
                </div>
            </div>
        </section>
        <script>
            var fullname = document.getElementById("fullname");
            var fullnameError = document.getElementById("fullnameError");
            var email = document.getElementById("email");
            var emailError = document.getElementById("emailError");
            var admissionNo = document.getElementById("admissionNo");
            var admissionNoError = document.getElementById("admissionNoError");
            var phoneNo = document.getElementById("phoneNo");
            var phoneNoError = document.getElementById("phoneNoError");
            var password = document.getElementById("password");
            var passwordError = document.getElementById("passwordError");
            var confirmPassword = document.getElementById("confirmPassword");
            var confirmPasswordError = document.getElementById("confirmPasswordError");

            const numberFormat = /^[0-9]+$/;
            const fullnameFormat = /^[a-zA-Z ]+$/;
            const emailFormat = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9-]+\.[a-zA-Z]{2,4}$/;

            function valpass() {
                if (password.value == "") {
                    passwordError.innerHTML = "must enter password";
                    document.getElementById("password").focus()
                    return (true);
                } else if (password.value.length < 6) {
                    passwordError.innerHTML = "password must be atleast 6 characters long";
                    document.getElementById("password").focus()
                    return (true);
                } else {
                    passwordError.innerHTML = "";
                    return (false);
                }
            }

            function valcpass() {
                if (confirmPassword.value == "") {
                    confirmPasswordError.innerHTML = "must enter confirm Password";
                    document.getElementById("confirmPassword").focus()
                    return (true);
                } else if (confirmPassword.value.length < 6) {
                    confirmPasswordError.innerHTML = "password must be atleast 6 characters long";
                    document.getElementById("confirmPassword").focus()
                    return (true);
                } else if (confirmPassword.value !== password.value) {
                    confirmPasswordError.innerHTML = "Passwords do not match"
                    document.getElementById("confirmPassword").focus()
                } else {
                    confirmPasswordError.innerHTML = "";
                    return (false);
                }
            }

            function validatephoneNo() {
                if (phoneNo.value == "") {
                    phoneNoError.innerHTML = "Enter your phone no";
                    document.getElementById("phoneNo").focus();
                    return (true);
                } else {
                    roomNoError.innerHTML = "";
                    return (false);
                }
            }

            function validateadminNo() {
                if (admissionNo.value == "") {
                    admissionNoError.innerHTML = "Enter your admission no";
                    document.getElementById("admissionNo").focus();
                    return (true);
                } else if (!numberFormat.test(admissionNo.value)) {
                    admissionNoError.innerHTML = "Only numbers are allowed";
                    document.getElementById("admissionNo").focus();
                    return (true);
                } else {
                    admissionNoError.innerHTML = "";
                    return (false);
                }
            }

            function validateFullName() {
                if (fullname.value == "") {
                    fullnameError.innerHTML = "Enter your full name";
                    document.getElementById("fullname").focus();
                    return (true);
                } else if (!fullnameFormat.test(fullname.value)) {
                    fullnameError.innerHTML = "Special characters and numbers are not allowed";
                    document.getElementById("fullname").focus();
                    return (true);
                } else {
                    fullnameError.innerHTML = "";
                    return (false);
                }
            }

            function validateEmail() {
                if (email.value == "") {
                    emailError.innerHTML = "Enter your email";
                    document.getElementById("email").focus();
                    return (true);
                } else if (!emailFormat.test(email.value)) {
                    emailError.innerHTML = "Email format is not correct";
                    document.getElementById("email").focus();
                    return (true);
                } else {
                    emailError.innerHTML = "";
                    return (false);
                }
            }

            function val() {
                if (validateFullName()) {
                    return (false);
                } else if (validateEmail()) {
                    return (false);
                } else if (validateadminNo()) {
                    return (false);
                } else if (valpass()) {
                    return (false);
                } else if (valcpass()) {
                    return (false);
                } else {
                    return (true);
                }
            }
        </script>
        <!-- disable input number scrollwheel -->
        <script>
            function GFG_Fun() {
                let input = document.getElementById("input");
                input.addEventListener("mousewheel",
                    function(event) {
                        this.blur()
                    });
            }
        </script>
    </body>

    </html>
<?php
}
?>