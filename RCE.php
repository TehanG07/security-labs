
<?php
// -------------------------------
// Command Injection using shell_exec()
// -------------------------------
if (isset($_GET['cmd'])) {
    $cmd = $_GET['cmd']; // Directly using user input
    echo "<pre>";
    echo shell_exec($cmd); // RCE vulnerability
    echo "</pre>";
}

// -------------------------------
// File Upload Vulnerability allowing PHP script uploads
// -------------------------------
if (isset($_FILES['file'])) {
    $file = $_FILES['file']['tmp_name'];
    $destination = 'uploads/' . $_FILES['file']['name'];

    // Check if the uploads directory exists, if not create it
    if (!file_exists('uploads')) {
        mkdir('uploads', 0777, true); // Create directory if it doesn't exist
    }

    // Log the destination path for debugging
    echo "Destination Path: " . realpath($destination) . "<br>";

    // Move the uploaded file to the 'uploads' folder
    if (move_uploaded_file($file, $destination)) {
        echo "File uploaded successfully: <a href='uploads/{$_FILES['file']['name']}'>Click here</a>";
    } else {
        echo "Failed to upload the file. Check directory permissions.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>PHP RCE Lab</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #1e1e1e;
            color: #fff;
            text-align: center;
            padding: 20px;
        }
        h1 {
            font-size: 2rem;
        }
        .vulnerability {
            margin-top: 30px;
            padding: 20px;
            background-color: #333;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }
        input[type="text"], input[type="file"], input[type="submit"] {
            margin: 10px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            width: 100%;
            max-width: 400px;
            background-color: #2c2c2c;
            color: #fff;
        }
        input[type="submit"] {
            cursor: pointer;
            background-color: #28a745;
        }
        input[type="submit"]:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>

<h1>PHP RCE Lab - Vulnerabilities</h1>

<!-- Command Injection Vulnerability -->
<div class="vulnerability">
    <h2>Command Injection</h2>
    <form method="get" action="">
        <input type="text" name="cmd" placeholder="Enter command (e.g., ls)" required>
        <input type="submit" value="Execute Command">
    </form>
</div>

<!-- File Upload Vulnerability -->
<div class="vulnerability">
    <h2>File Upload (PHP Script Upload)</h2>
    <form method="post" enctype="multipart/form-data" action="">
        <input type="file" name="file" required>
        <input type="submit" value="Upload File">
    </form>
</div>

</body>
</html>
