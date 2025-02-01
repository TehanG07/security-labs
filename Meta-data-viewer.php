
<?php
if (isset($_FILES['image'])) {
    $upload_dir = "assets/uploads/";
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }
    
    $file_name = basename($_FILES['image']['name']);
    $file_path = $upload_dir . $file_name;
    $file_type = strtolower(pathinfo($file_path, PATHINFO_EXTENSION));
    
    $allowed_types = ['jpg', 'jpeg', 'png', 'tiff'];
    
    if (in_array($file_type, $allowed_types)) {
        if (move_uploaded_file($_FILES['image']['tmp_name'], $file_path)) {
            $exif = exif_read_data($file_path);
        } else {
            $error = "File upload failed. Please try again.";
        }
    } else {
        $error = "Invalid file type. Only JPG, JPEG, PNG, and TIFF are allowed.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EXIF Metadata Viewer</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 40px;
            background-color: #121212;
            color: #e0e0e0;
            text-align: center;
        }
        h2 {
            color: #ff9800;
            font-size: 32px;
        }
        .container {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 100%;
        }
        form {
            background: #1e1e1e;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0px 6px 12px rgba(255, 152, 0, 0.3);
            display: flex;
            flex-direction: column;
            align-items: center;
            max-width: 600px;
            width: 100%;
        }
        input[type="file"] {
            margin: 15px 0;
            width: 100%;
            padding: 10px;
            border: 1px solid #ff9800;
            border-radius: 8px;
            background: #2c2c2c;
            color: #e0e0e0;
        }
        button {
            background: #ff9800;
            color: white;
            padding: 14px 24px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 18px;
            transition: background 0.3s;
        }
        button:hover {
            background: #e68900;
        }
        .metadata {
            background: #1e1e1e;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0px 6px 12px rgba(255, 152, 0, 0.3);
            text-align: left;
            max-width: 90%;
            width: 800px;
            margin-top: 25px;
            word-wrap: break-word;
            overflow: auto;
            color: #e0e0e0;
            font-size: 16px;
        }
        .error {
            color: #ff3d00;
            font-weight: bold;
            font-size: 18px;
        }
        .preview {
            margin-top: 20px;
            max-width: 500px;
            border-radius: 10px;
            box-shadow: 0px 6px 12px rgba(255, 152, 0, 0.3);
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>EXIF Metadata Viewer</h2>
        <form method="POST" enctype="multipart/form-data">
            <input type="file" name="image" accept="image/jpeg,image/png,image/tiff" required onchange="previewImage(event)">
            <button type="submit">Upload & View Metadata</button>
        </form>
        <img id="imagePreview" class="preview" style="display: none;" />
        
        <?php
        if (isset($error)) {
            echo "<p class='error'>$error</p>";
        }
        if (isset($exif)) {
            echo "<div class='metadata'><h3>Extracted Metadata:</h3><pre>" . print_r($exif, true) . "</pre></div>";
        }
        ?>
    </div>
    <script>
        function previewImage(event) {
            var reader = new FileReader();
            reader.onload = function() {
                var output = document.getElementById('imagePreview');
                output.src = reader.result;
                output.style.display = 'block';
            }
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>
</body>
</html>
