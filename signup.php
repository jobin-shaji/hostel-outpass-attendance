<?php
session_start();
use MongoDB\BSON\UTCDateTime;
use MongoDB\InsertOneResult;

if (isset($_SESSION["userdetails"])) {
    $row = $_SESSION["userdetails"];
    if ($row['usertype'] == 3) {
        header("Location: admin_page.php");
    } else {
        header("Location: user_page.php");
    }
    exit();
} else {
    require("conn.php");
    //val=0 if validation correct
    $val = 1;
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
            :root {
                --primary-color: #4a90e2;
                --danger-color: #dc3545;
                --success-color: #28a745;
                --warning-color: #ffc107;
                --secondary-color: #6c757d;
                --light-bg: #f8f9fa;
                --shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            }

            body {
                min-height: 100vh;
                font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
                background-color: var(--light-bg);
            }

            .wrap {
                background: white;
                border-radius: 10px;
                overflow: hidden;
                box-shadow: var(--shadow);
                border: none !important;
            }

            .img-fluid {
                min-height: 600px;
                background-position: center;
                background-size: cover;
                position: relative;
            }

            .img-fluid::after {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0,0,0,0.2);
            }

            h3 {
                color: #333;
                font-weight: 600;
                margin-bottom: 1.5rem;
            }

            .form-control {
                border-radius: 6px;
                border: 1px solid #dee2e6;
                padding: 0.6rem 0.75rem;
                transition: border-color 0.2s, box-shadow 0.2s;
                margin-bottom: 0.5rem;
            }

            .form-control:focus {
                border-color: var(--primary-color);
                box-shadow: 0 0 0 0.2rem rgba(74, 144, 226, 0.25);
            }

            /* input box number inner scrollbar disabled */
            input::-webkit-outer-spin-button,
            input::-webkit-inner-spin-button {
                -webkit-appearance: none;
                margin: 0;
            }

            label {
                color: #555;
                margin-bottom: 0.3rem;
                font-weight: 500;
            }

            .btn {
                padding: 0.6rem 1rem;
                font-weight: 500;
                border-radius: 6px;
                transition: all 0.2s;
            }

            .btn:hover {
                transform: translateY(-1px);
                box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            }

            .text-danger {
                font-size: 0.875rem;
                min-height: 20px;
                margin-top: 0.25rem;
            }

            a {
                color: var(--primary-color);
                text-decoration: none;
                transition: color 0.2s;
            }

            a:hover {
                color: darken(var(--primary-color), 10%);
            }

            @media (max-width: 768px) {
                .mx-auto {
                    width: 100% !important;
                    padding: 15px;
                }

                .img-fluid {
                    display: none;
                }

                .wrap > div:last-child {
                    width: 100% !important;
                }

                .row {
                    margin: 0;
                }

                .col-5, .col-6 {
                    width: 100%;
                    padding: 0 0 1rem 0;
                }
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
                                            
                                            $existingInmate = findOne('hostelinmatestable', [
                                                'admissionno' => $admissionNo
                                            ]);
                                            
                                            if ($existingInmate) {
                                                echo "Admission No already exists!";
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
                                            
                                            $existingUser = findOne('usertable', [
                                                'email' => $email
                                            ]);
                                            
                                            if ($existingUser) {
                                                echo "Email already used!";
                                            } else {
                                                if ($val == 0) {
                                                    $userDoc = [
                                                        'name' => $_POST['fullname'],
                                                        'email' => $email,
                                                        'password' => $_POST['password'],
                                                        'usertype' => 1 // Default user type
                                                    ];
                                                    
                                                    $result = insertOne('usertable', $userDoc);
                                                    if ($result instanceof MongoDB\InsertOneResult) {
                                                        $userId = $result->getInsertedId();
                                                        
                                                        $inmateDoc = [
                                                            'admissionno' => $_POST['admissionNo'],
                                                            'userid' => $userId,
                                                            'phone' => $_POST['phoneNo'],
                                                            'inmatestatus' => 0,
                                                            'attendancedate' => new \MongoDB\BSON\UTCDateTime(time() * 1000)
                                                        ];
                                                        
                                                        if (insertOne('hostelinmatestable', $inmateDoc) instanceof MongoDB\InsertOneResult) {
                                                            header("Location: login.php");
                                                            exit();
                                                        } else {
                                                            echo "<script>alert('Error creating inmate record')</script>";
                                                        }
                                                    } else {
                                                        echo "<script>alert('Error creating user')</script>";
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