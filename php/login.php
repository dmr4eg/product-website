<?php
// Start session
session_start();

if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if (!isset($_SESSION['loggedin'])) {
    if (isset($_POST['login'])) {
        if (isset($_POST['csrf_token']) && $_POST['csrf_token'] === $_SESSION['csrf_token']) {
            // Read the contents of the JSON file into a string
            $json = file_get_contents('json/data.json');

            // Convert the JSON string into a PHP object
            $users = json_decode($json, true);

            // Retrieve the submitted username and password and sanitize them
            $name = htmlspecialchars(filter_var($_POST['name'], FILTER_SANITIZE_STRING));
            $password = htmlspecialchars(filter_var($_POST['password'], FILTER_SANITIZE_STRING));

            // Check if the submitted login credentials match any records in the file
            foreach ($users as $user) {
                if ($user['name'] == $name) {
                    $salt = $user['salt'];
                    if (password_verify($password . $salt, $user['password'])) {
                        // Login successful
                        header('Location: pho.php');
                        // Set session variable to indicate that the user is logged in
                        $_SESSION['loggedin'] = true;
                        // regenerate csrf token
                        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
                        break;
                    }
                }
            }
            // If we reach this point, it means that the login credentials were invalid
            $error = 'Invalid name or password';
        } else {
            die('Invalid CSRF token');
        }
    }
} else {
    //user is already logged in
    header('Location: pho.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/login.css">
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
        <form method="post" class="regform" action="login.php">
            <?php
            if (isset($error)) {
                echo "<p class=\"error\">$error</p>";
            }
            ?>
            <ul>
                <li>
                    <label for="name">Username</label>
                    <input type="text" name="name" id="name" maxlength="100" value="<?= isset($name) ? $name : '' ?>">
                    <span>Enter your username here</span>
                </li>
                <li>
                    <label for="password">Password</label>
                    <input type="password" name="password" maxlength="100" required autocomplete="off">
                    <span>Type your password</span>
                </li>
                <li>
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                    <input type="submit" name="login" value="Login">
                </li>
            </ul>

        </form>
    </section>

    <div class="account" id="account">
        <h1 id="acc">New here?</h1>
        <a href="rpa.php" id="si">Register!</a>
    </div>

    <div class="return" id="return">
        <a href="index.php"> &#8592; Return to homepage</a>
    </div>
</body>

</html>