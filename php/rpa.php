<?php
session_start();

// Include the validation library
require "lib/validate.php";

// Check if the form has been submitted
$formIsSent = isset($_POST['reg']);

// Initialize variables with default values
$name = '';
$email = '';
$dob = '';

// Sanitize the name input
$name = isset($_POST['name']) ? trim($_POST['name']) : '';
$name = strip_tags($name);
$name = preg_replace('/[^a-zA-Z0-9\s]/', '', $name);
$name = htmlspecialchars($name);

// Sanitize the email input
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$email = strip_tags($email);
$email = filter_var($email, FILTER_SANITIZE_EMAIL);
$email = htmlspecialchars($email);

// Sanitize the password input
$password = isset($_POST['password']) ? trim($_POST['password']) : '';
$password = strip_tags($password);
$password = htmlspecialchars($password);

// Sanitize the password confirmation input
$password_confirm = isset($_POST['password_confirm']) ? trim($_POST['password_confirm']) : '';
$password_confirm = strip_tags($password_confirm);
$password_confirm = htmlspecialchars($password_confirm);

// Sanitize the date of birth input
$dob = isset($_POST['dob']) ? trim($_POST['dob']) : '';
$dob = strip_tags($dob);
$dob = htmlspecialchars($dob);

if ($formIsSent) {
    // Check if CSRF token is present and valid
    if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die('CSRF token mismatch');
    }

    // Assign the form inputs to variables
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $password_confirm = $_POST['password_confirm'];

    // Validate the form inputs
    $validName = validateName($name);
    $validEmail = validateEmail($email);
    $validPass = validatePass($password);

    // Read the json data
    $json_data = file_get_contents('json/data.json');
    $data = json_decode($json_data, true);
    $nameExists = array_search($name, array_column($data, 'name'));

    // Check if all validation passed and name does not already exists
    if ($validName && $validEmail && $validPass && !$nameExists && $password === $password_confirm) {
        //create a salt and hashed password
        $salt = bin2hex(random_bytes(32));
        $hashed_password = password_hash($password . $salt, PASSWORD_DEFAULT);

        //create new user array
        $new_user = array(
            'id' => uniqid(),
            'name' => $name,
            'email' => $email,
            'password' => $hashed_password,
            'dob' => $dob,
            'salt' => $salt
        );

        //append new user to existing data
        $json_data = file_get_contents('json/data.json');
        $data = json_decode($json_data, true);
        $data[] = $new_user;
        $json_data = json_encode($data);
        file_put_contents('json/data.json', $json_data);

        //redirect to login page
        header('Location: login.php');
        exit;
    } else {
        // error occured, show error message
        if (!$validName) {
            $error = 'Invalid name';
        }
        if (!$validEmail) {
            $error = 'Invalid email';
        }
        if (!$validPass) {
            $error = 'Invalid password';
        }
        if ($nameExists) {
            $error = 'This name already exists';
        }
        if ($password !== $password_confirm) {
            $error = 'Passwords do not match';
        }
    }
}
// Generate a new CSRF token
$_SESSION['csrf_token'] = bin2hex(random_bytes(32));

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Registration</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/rpa.css">
    <!-- <script src="js/validace.js"></script> -->
    <script src="js/check.js"></script>
    <link rel="icon" href="https://i.imgur.com/ZbhHFtb.png" type="image/x-icon">
</head>

<body>
    <img src="https://i.imgur.com/wncRYql.png" id="bg" alt="backgroundimage">
    <aside>
        <div class="container">
            <p id="reg" class="wrap">Register</p>
            <p id="or" class="wrap">or</p>
            <p id="log" class="wrap">Login</p>
            <img src="https://i.imgur.com/v1NqGT0.png" alt="now" class="wrap" id="now">
        </div>
    </aside>
    <section>

        <form method="post" class="regform" autocomplete="off">
            <?php
            if (isset($error)) {
                echo "<p class=\"error\">$error</p>";
            }
            ?>
            <ul>
                <div class="inp1">
                    <li>
                        <label for="name">Username*</label>
                        <input type="text" name="name" maxlength="100" id="name"
                            value="<?= isset($name) ? $name : '' ?>" pattern="[A-Za-z0-9]{5,}" required>
                        <span>Create your username</span>
                    </li>
                    <span id="tooltip"
                        data-tooltip="This field is required. Password must contain 5 or more characters:">?</span>
                </div>
                <div class="inp2">
                    <li>
                        <label for="email">Email*</label>
                        <input type="email" name="email" id="email" maxlength="100"
                            value="<?= isset($email) ? $email : '' ?>" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$"
                            required>
                        <span>Enter a valid email address</span>
                    </li>
                    <span id="tooltip2"
                        data-tooltip="This field is required. Email must contain 5 or more characters and a @ sign">?</span>
                </div>
                <li>
                    <label for="dob">Date of Birth</label>
                    <input type="date" name="dob" id="dob" value="<?= isset($dob) ? $dob : '' ?>">
                    <span>Enter your date of birth</span>
                </li>
                <div class="inp3">
                    <li>
                        <label for="password">Password*</label>
                        <input type="password" id="password" name="password" maxlength="100" pattern=".{8,}" required
                            autocomplete="off">
                        <span>Create your password</span>
                    </li>
                    <span id="tooltip3"
                        data-tooltip="This field is required. Password must contain 8 or more characters:">?</span>
                </div>
                <li>
                    <label for="password_confirm">Password check</label>
                    <input type="password" id="password_confirm" name="password_confirm" maxlength="100" pattern=".{8,}"
                        required autocomplete="off">
                    <span>Re-enter your password</span>
                </li>
                <li>
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                    <input type="submit" name="reg" value="Register">
                </li>
            </ul>
        </form>
    </section>
    <div class="account" id="account">
        <h1 id="acc">Already have an account?</h1>
        <a href="login.php" id="si">Login!</a>
    </div>

    <div class="return" id="return">
        <a href="index.php"> &#8592; Return to homepage</a>
    </div>
</body>

</html>