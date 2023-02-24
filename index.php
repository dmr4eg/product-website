<?php
// Start session
session_start();

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
    setcookie('theme', $theme, time() + (86400 * 30), '/'); // expires in 30 days
}
?>

<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Home page</title>
    <?php
    // Output the theme CSS file based on the selected theme
    if ($theme === 'dark') {
        echo '<link href="css/ind.css" rel="stylesheet">';
    } else {
        echo '<link href="css/whiteind.css" rel="stylesheet">';
    }
    ?>
    <script src="js/fen.js" defer></script>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="icon" href="https://i.imgur.com/ZbhHFtb.png" type="image/x-icon">
    <link rel="stylesheet" type="text/css" href="/css/print.css" media="print">
</head>

<body>
    <header> <!-- Here is the navigation 'button' -->
        <img src="https://i.imgur.com/1w9H2ez.png" class="logo" width="160" height="90" alt="logo">
        <ul>
            <li><a href="#" class="active">Home</a></li>
            <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true): ?>
                <li><a href="logout.php">Logout</a></li>
            <?php else: ?>
                <li><a href="rpa.php">Sign in/up</a></li>
            <?php endif; ?>

            <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true): ?>
                <li><a href="pho.php">Gallery</a></li>
            <?php else: ?>
                <li><a href="treb.php">Gallery</a></li>
            <?php endif; ?>
        </ul>
        <form method="get" class="selector">
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
    <!-- Text and button for parallax and static guitar -->
    <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true): ?>
        <h1 id="text">Hello, user! </h1>
    <?php else: ?>
        <h1 id="text">Welcome.</h1>
    <?php endif; ?>
    <!-- <div id="btn"><a href=".bar">Explore guitars</a></div> -->
    <section class="filler"></section>
    <section class="infsec"> <!-- Block for text -->
        <div id="guitar">
            <?php
            if ($theme === 'dark') {
                echo '<img src="https://i.imgur.com/JUvtA02.png" alt="guitarTransitionBetweenSections">';
            } else {
                echo '<img src="https://i.imgur.com/XYFm8wR.png" alt="guitarTransitionBetweenSections">';
            }
            ?>
        </div>
        <h2>What is Fender?</h2>
        <p>The Fender Musical Instruments Corporation (FMIC, or simply Fender) is an American manufacturer of
            instruments and amplifiers. Fender produces acoustic guitars, bass amplifiers and public address equipment,
            however it is best known for its solid-body electric guitars and bass guitars, particularly the
            Stratocaster, Telecaster, Jaguar, Jazzmaster, Precision Bass, and the Jazz Bass. The company was founded in
            Fullerton, California by Clarence Leonidas "Leo" Fender in 1946. Its headquarters are in Los Angeles,
            California.</p>
        <div class="grey">
            <?php
            if ($theme === 'dark') {
                echo '<img src="https://i.imgur.com/7e6QNa0.png" alt="darkthemeimage">';
            } else {
                echo '<img src="https://i.imgur.com/klXQG4l.png" alt="lightthemeimage">';
            }
            ?>
        </div>
    </section>
    <section class="bar">
        <div class="cards" id="cd1">
            <img src="https://i.imgur.com/BxIy82B.png" id="photo" alt="FirstGuitarPicture" class="gpic">
            <p id="cardtext1" class="cardtexts">Telecaster</p>
        </div>
        <div class="cards" id="cd2">
            <img src="https://i.imgur.com/1eAj2iS.png" id="photo2" alt="SecondGuitarPicture" class="gpic">
            <p id="cardtext2" class="cardtexts">Superstrat</p>
        </div>
        <div class="cards" id="cd3">
            <img src="https://i.imgur.com/JEWMNts.png" id="photo3" alt="ThirdGuitarPicture" class="gpic">
            <p id="cardtext3" class="cardtexts">Jazzmaster</p>
        </div>
    </section>
    <section class="linking">
        <div class="butt">
            <a href="rpa.php"><span>Lets see!</span></a>
        </div>
        <div class="exampleg">
            <?php
            if ($theme === 'dark') {
                echo '<img src="https://i.imgur.com/ljTtWky.jpg" alt="ExamplePictureOnBackground">';
            } else {
                echo '<img src="https://i.imgur.com/Q1q9ngs.jpg" alt="ExamplePictureOnBackground">';
            }
            ?>
        </div>
        <div class="topacit">
            <p id="t1" class="tonp">Sign in/up</p>
            <p id="t2" class="tonp">There</p>
            <p id="t3" class="tonp">To</p>
            <p id="t4" class="tonp">Have</p>
            <p id="t5" class="tonp">A</p>
            <p id="t6" class="tonp">look</p>
            <p id="t7" class="tonp">at</p>
            <p id="t8" class="tonp">more</p>
            <p id="t9" class="tonp">Photos</p>
        </div>
    </section>
    <footer class="footer">
        <p>All rights reserved &copy; 2022-
            <?= date('Y'); ?>
        </p>
        <p>Created by: <a href="https://www.instagram.com/dmr4eg">@dmr4eg</a></p>
    </footer>
</body>

</html>