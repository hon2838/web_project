<?php
session_start();
include_once 'includes/config.php';

// Check if user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: index.php");
    exit;
}

// Handle file upload
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $target_dir = "files/";
    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
    $uploadOk = 1;
    $fileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

    // Check if file already exists
    if (file_exists($target_file)) {
        echo "Sorry, file already exists.";
        $uploadOk = 0;
    }

    // Check file size
    if ($_FILES["fileToUpload"]["size"] > 500000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Allow only certain file formats
    if($fileType != "jpg" && $fileType != "png" && $fileType != "jpeg"
    && $fileType != "gif" ) {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
    // if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            echo "The file ". htmlspecialchars( basename( $_FILES["fileToUpload"]["name"])). " has been uploaded.";
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File Manager</title>
    <link rel="stylesheet" href="css/bootstrap.css">
</head>
<body>
    <div class="container">
        <h2>File Manager</h2>
        <form action="dashboard.php" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="fileToUpload">Select file to upload:</label>
                <input type="file" name="fileToUpload" id="fileToUpload" class="form-control">
            </div>
            <button type="submit" class="btn btn-primary" name="upload">Upload</button>
        </form>

        <h3>Uploaded Files:</h3>
        <ul>
            <?php
            $files = glob('files/*');
            if ($files) {
                foreach ($files as $file) {
                    echo "<li><a href='$file' download>" . basename($file) . "</a></li>";
                }
            } else {
                echo "<li>No files uploaded yet.</li>";
            }
            ?>
        </ul>

        <a href="logout.php" class="btn btn-danger">Logout</a>
    </div>
</body>
</html>
