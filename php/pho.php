<?php
// Start session
session_start();

// Generate a unique token for the user's session
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Set default theme
$theme = 'dark';

// Check if theme cookie is set
if (isset($_COOKIE['theme'])) {
    $theme = $_COOKIE['theme'];
}

// Check if theme parameter is set in the query string
if (isset($_GET['theme'])) {
    $theme = $_GET['theme'];

    // Set theme cookie
    setcookie('theme', $theme, time() + (86400 * 30), '/');
}

// Verify the token on form submission
if (isset($_POST['csrf_token']) && $_POST['csrf_token'] === $_SESSION['csrf_token']) {
    // Form submission is valid
} else {
    // Form submission is invalid
}

$jsonData = json_decode(file_get_contents("json/images.json"), true);
$total_pages = ceil(count($jsonData) / 8);
if (isset($_GET['page']) && is_numeric($_GET['page'])) {
    $current_page = $_GET['page'];
} else {
    $current_page = 1;
}
$images = array_slice($jsonData, ($current_page - 1) * 8, 8);

// Regenerate the token after form submission
$_SESSION['csrf_token'] = bin2hex(random_bytes(32));
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <?php
    // Output the theme CSS file based on the selected theme
    if ($theme === 'dark') {
        echo '<link href="css/pho.css" rel="stylesheet">';
    } else {
        echo '<link href="css/whitepho.css" rel="stylesheet">';
    }
    ?>
    <title>Gallery</title>
    <link rel="icon" href="https://i.imgur.com/ZbhHFtb.png" type="image/x-icon">
</head>

<body>
    <header> <!-- Here is the navigation 'button' -->
        <img src="https://i.imgur.com/1w9H2ez.png" class="logo" width="160px" height="90px" alt="logo">
        <ul>
            <li><a href="index.php">Home</a></li>
            <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true): ?>
                <li><a href="logout.php">Logout</a></li>
            <?php else: ?>
                <li><a href="rpa.php">Sign in/up</a></li>
            <?php endif; ?>
            <li><a href="#" class="active">Gallery</a></li>
        </ul>
        <form action="" method="get" class="selector">
            <select name="theme" id="theme-select">
                <option value="dark" <?php if ($theme === 'dark')
                    echo ' selected'; ?>>Dark</option>
                <option value="light" <?php if ($theme === 'light')
                    echo ' selected'; ?>>Light</option>
            </select>
            <button data-text="Awesome" type="submit" class="button">
                <span class="actual-text">&nbsp; Change &nbsp;</span>
                <span class="hover-text" aria-hidden="true">&nbsp; Change&nbsp;</span>
            </button>
        </form>
    </header>

    <section class="section">
        <div class="grid">
            <?php
            foreach ($images as $image) {
                echo '<div class="item item--medium">
                    <img src="' . $image['filepath'] . '" alt="Uploaded Image" width="100%">
                    <div class="item__details">' . $image['caption'] . '</div>
            </div>';
            }
            ?>
        </div>
        </div>
    </section>
    <section class="pages">
        <nav aria-label="Page navigation example">
            <ul class="pagination">
                <li class="page-item <?php echo $current_page <= 1 ? 'disabled' : '' ?>">
                    <a class="page-link" href="?page=<?php echo $current_page - 1 ?>">Previous</a>
                </li>
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <li class="page-item <?php echo $i == $current_page ? 'active' : '' ?>">
                        <a class="page-link" href="?page=<?php echo $i ?>">
                            <?php echo $i ?>
                        </a>
                    </li>
                <?php endfor; ?>
                <li class="page-item <?php echo $current_page >= $total_pages ? 'disabled' : '' ?>">
                    <a class="page-link" href="?page=<?php echo $current_page + 1 ?>">Next</a>
                </li>
            </ul>
        </nav>
    </section>
    <section class="inpb">
        <form action="upload.php" method="post" enctype="multipart/form-data">
            Select image to upload:
            <input type="file" name="fileToUpload" id="fileToUpload">
            <br>
            Image caption: <input type="text" name="caption">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            <input type="submit" value="Upload Image" name="submit">
        </form>
    </section>

    <footer class="footer">
        <p>All rights reserved &copy; 2022-
            <?= date('Y'); ?>
        </p>
        <p>Created by: <a href="https://www.instagram.com/dmr4eg">@dmr4eg</a></p>
    </footer>
</body>

</html>