
<?php
// Base16 (Hexadecimal) Encoding/Decoding
function base16_encode($data) {
    return bin2hex($data);
}

function base16_decode($data) {
    return hex2bin($data);
}

// Base64url Encoding/Decoding
function base64url_encode($data) {
    return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
}

function base64url_decode($data) {
    return base64_decode(strtr($data, '-_', '+/'));
}

// Base58 Encoding/Decoding
function base58_encode($data) {
    $alphabet = "123456789ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz";
    $base58 = '';
    $data = bin2hex($data); // Convert the binary data to hexadecimal
    $num = base_convert($data, 16, 10); // Convert hexadecimal to decimal
    while ($num > 0) {
        $rem = $num % 58;
        $base58 = $alphabet[$rem] . $base58;
        $num = intdiv($num, 58);
    }
    return $base58;
}

function base58_decode($data) {
    $alphabet = "123456789ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz";
    $base58 = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $length = strlen($data);
    $num = 0;
    for ($i = 0; $i < $length; $i++) {
        $num = $num * 58 + strpos($alphabet, $data[$i]);
    }
    return hex2bin(base_convert($num, 10, 16)); // Convert back to binary
}

// Base32 Encoding/Decoding
function base32_encode($data) {
    $alphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ234567";
    $data = unpack('C*', $data);
    $output = '';
    $buffer = '';
    foreach ($data as $byte) {
        $buffer .= str_pad(decbin($byte), 8, '0', STR_PAD_LEFT);
    }
    while (strlen($buffer) >= 5) {
        $output .= $alphabet[bindec(substr($buffer, 0, 5))];
        $buffer = substr($buffer, 5);
    }
    if (strlen($buffer) > 0) {
        $output .= $alphabet[bindec($buffer)];
    }
    return $output;
}

function base32_decode($data) {
    $alphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ234567";
    $data = strtoupper($data);
    $output = '';
    $buffer = '';
    foreach (str_split($data) as $char) {
        $buffer .= str_pad(decbin(strpos($alphabet, $char)), 5, '0', STR_PAD_LEFT);
    }
    while (strlen($buffer) >= 8) {
        $output .= chr(bindec(substr($buffer, 0, 8)));
        $buffer = substr($buffer, 8);
    }
    return $output;
}

// Rot13 Encoding/Decoding
function rot13($data) {
    return str_rot13($data);
}

// URL Encoding/Decoding
function url_encode($data) {
    return urlencode($data);
}

function url_decode($data) {
    return urldecode($data);
}

// MD5 Hashing
function md5_hash($data) {
    return md5($data);
}

// Processing the form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = $_POST['data'];
    $action = $_POST['action'];
    $method = $_POST['method'];

    if ($method == 'base64') {
        if ($action == 'encode') {
            $output = base64_encode($data);
        } elseif ($action == 'decode') {
            $output = base64_decode($data);
        }
    } elseif ($method == 'base32') {
        if ($action == 'encode') {
            $output = strtoupper(rtrim(str_pad(base32_encode($data), ceil(strlen($data) / 5) * 8, '=', STR_PAD_RIGHT)));
        } elseif ($action == 'decode') {
            $output = base32_decode($data);
        }
    } elseif ($method == 'base58') {
        if ($action == 'encode') {
            $output = base58_encode($data);
        } elseif ($action == 'decode') {
            $output = base58_decode($data);
        }
    } elseif ($method == 'base16') {
        if ($action == 'encode') {
            $output = base16_encode($data);
        } elseif ($action == 'decode') {
            $output = base16_decode($data);
        }
    } elseif ($method == 'base64url') {
        if ($action == 'encode') {
            $output = base64url_encode($data);
        } elseif ($action == 'decode') {
            $output = base64url_decode($data);
        }
    } elseif ($method == 'rot13') {
        if ($action == 'encode' || $action == 'decode') {
            $output = rot13($data);
        }
    } elseif ($method == 'url') {
        if ($action == 'encode') {
            $output = url_encode($data);
        } elseif ($action == 'decode') {
            $output = url_decode($data);
        }
    } elseif ($method == 'md5') {
        $output = md5_hash($data);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cryptography Lab</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #121212;
            color: #f0f0f0;
            padding: 50px 0;
            margin: 0;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .container {
            max-width: 1000px;
            width: 100%;
            background: #1d1d1d;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            padding: 40px;
        }
        h2 {
            text-align: center;
            color: #ff5722;
            margin-bottom: 20px;
        }
        form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        textarea, select, button {
            width: 100%;
            padding: 15px;
            margin: 10px 0;
            border-radius: 8px;
            border: 1px solid #333;
            font-size: 16px;
            color: #fff;
            background: #222;
            transition: background-color 0.3s ease-in-out;
        }
        textarea:focus, select:focus, button:focus {
            background-color: #333;
            border-color: #ff5722;
            outline: none;
        }
        button {
            background-color: #ff5722;
            color: white;
            cursor: pointer;
            transition: background-color 0.3s ease-in-out;
        }
        button:hover {
            background-color: #f44336;
        }
        .result {
            margin-top: 20px;
            padding: 15px;
            background-color: #2c2c2c;
            border-radius: 5px;
            text-align: center;
            color: #ff5722;
            font-size: 18px;
        }
        .result p {
            word-wrap: break-word;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Cryptography - Encode / Decode Lab</h2>
        <form method="POST">
            <textarea name="data" placeholder="Enter your data here..." required></textarea>
            <select name="method" required>
                <option value="base64">Base64</option>
                <option value="base32">Base32</option>
                <option value="base58">Base58</option>
                <option value="base16">Base16 (Hex)</option>
                <option value="base64url">Base64url</option>
                <option value="rot13">Rot13</option>
                <option value="url">URL Encoding</option>
                <option value="md5">MD5 Hash</option>
            </select>
            <select name="action" required>
                <option value="encode">Encode</option>
                <option value="decode">Decode</option>
            </select>
            <button type="submit">Submit</button>
        </form>

        <?php
        if (isset($output)) {
            echo "<div class='result'><p><strong>Result:</strong><br>$output</p></div>";
        }
        ?>
    </div>
</body>
</html>
