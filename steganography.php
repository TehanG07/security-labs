<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Steganography Tool</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #1e3c72, #2a5298);
            color: #f1f1f1;
            margin: 0;
            padding: 0;
        }
        header {
            background-color: #243b55;
            color: #fff;
            padding: 20px 30px;
            text-align: center;
            font-size: 26px;
            font-weight: bold;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        main {
            max-width: 900px;
            margin: 30px auto;
            padding: 30px;
            background: #1c1c1c;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.4);
        }
        h3 {
            margin-top: 20px;
            font-size: 22px;
            color: #00bcd4;
        }
        label {
            display: block;
            margin: 10px 0 5px;
            font-size: 16px;
            font-weight: 500;
            color: #ddd;
        }
        input[type="file"],
        textarea,
        input[type="submit"] {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border: 1px solid #444;
            border-radius: 8px;
            background-color: #2c2c2c;
            color: #f1f1f1;
            font-size: 16px;
        }
        input[type="submit"] {
            background-color: #00796b;
            color: #fff;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.2s;
        }
        input[type="submit"]:hover {
            background-color: #009688;
            transform: translateY(-2px);
        }
        .result {
            background: #37474f;
            padding: 20px;
            margin-top: 30px;
            border-radius: 8px;
            border-left: 5px solid #00796b;
            font-size: 16px;
            word-wrap: break-word;
        }
        .btn {
            display: inline-block;
            padding: 12px 20px;
            background-color: #009688;
            color: #fff;
            text-decoration: none;
            border-radius: 6px;
            transition: background-color 0.3s, transform 0.2s;
        }
        .btn:hover {
            background-color: #00796b;
            transform: translateY(-2px);
        }
        footer {
            text-align: center;
            margin-top: 40px;
            font-size: 14px;
            color: #888;
        }
    </style>
</head>
<body>
    <header>Steganography Tool</header>
    <main>
        <!-- Encode Data Form -->
        <form action="" method="post" enctype="multipart/form-data">
            <h3>Encode Data into Image</h3>
            <label for="image">Select an Image:</label>
            <input type="file" name="image" id="image" accept="image/*" required>
            <label for="data">Enter Data to Encode:</label>
            <textarea name="data" id="data" rows="5" placeholder="Enter data to hide" required></textarea>
            <input type="submit" name="encode" value="Encode Data">
        </form>

        <!-- Decode Data Form -->
        <form action="" method="post" enctype="multipart/form-data">
            <h3>Decode Data from Image</h3>
            <label for="image">Select an Image:</label>
            <input type="file" name="image" id="image" accept="image/*" required>
            <input type="submit" name="decode" value="Decode Data">
        </form>

        <!-- Result Section -->
        <?php if (!empty($message)): ?>
            <div class="result"><?php echo $message; ?></div>
        <?php endif; ?>
    </main>
    <footer>
        &copy; <?php echo date("Y"); ?> Steganography Tool. All Rights Reserved.
    </footer>
</body>
</html>
