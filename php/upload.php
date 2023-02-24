<?php
session_start();

//directory where images will be uploaded
$target_dir = "uploads/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

//get the caption of the image
$caption = $_POST['caption'];

// Check if image file is a actual image or fake image
if (isset($_POST["submit"])) {
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if ($check !== false) {
        $_SESSION['message'] = "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        $_SESSION['message'] = "File is not an image.";
        $uploadOk = 0;
    }
}

// Check if file already exists
if (file_exists($target_file)) {
    $_SESSION['message'] = "Sorry, file already exists.";
    $uploadOk = 0;
}

// Check file size
if ($_FILES["fileToUpload"]["size"] > 500000) {
    $_SESSION['message'] = "Sorry, your file is too large.";
    $uploadOk = 0;
}

// Allow certain file formats
if (
    $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
    && $imageFileType != "gif"
) {
    $_SESSION['message'] = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
    $uploadOk = 0;
}

// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    $_SESSION['message'] = "Sorry, your file was not uploaded.";
    // if everything is ok, try to upload file
} else {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {

        // Get the original image dimensions
        list($originalWidth, $originalHeight) = getimagesize($target_file);

        // Set the new dimensions
        $resizedWidth = 100;
        $resizedHeight = 100;

        // Create a new image resource
        $resizedImage = imagecreatetruecolor($resizedWidth, $resizedHeight);

        // Get the original image resource
        $originalImage = imagecreatefromjpeg($target_file);

        // Copy and resize the original image onto the new image resource
        imagecopyresampled($resizedImage, $originalImage, 0, 0, 0, 0, $resizedWidth, $resizedHeight, $originalWidth, $originalHeight);

        // Save the resized image
        imagejpeg($resizedImage, $target_file);

        //create array of image information and caption
        $image = array(
            'caption' => $caption,
            'filepath' => $target_file
        );

        //read the existing JSON data from the file
        $jsonData = json_decode(file_get_contents("json/images.json"), true);

        //check if the jsonData is empty or not
        if (empty($jsonData)) {
            $jsonData = array();
        }

        //append new image information to the array
        $jsonData[] = $image;

        //encode the array back to a JSON string and write it to the file
        file_put_contents("json/images.json", json_encode($jsonData));

        $_SESSION['message'] = "The file " . basename($_FILES["fileToUpload"]["name"]) . " has been uploaded and resized.";
        header("Location: pho.php?message=success");
        exit;
    } else {
        $_SESSION['message'] = "Sorry, there was an error uploading your file.";
    }
}

// display error message
if (isset($_SESSION['message'])) {
    echo $_SESSION['message'];
    echo "<br> <a href='pho.php'>Back</a>";
    unset($_SESSION['message']);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <div class="message">

    </div>

</body>

</html>