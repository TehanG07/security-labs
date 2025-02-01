<?php
// Increase execution time
set_time_limit(300);
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Function to get IPs from a domain
if (!function_exists('getIpsFromDomain')) {
    function getIpsFromDomain($domain) {
        $ips = dns_get_record($domain, DNS_A);
        if (empty($ips)) {
            return ["<span class='error-text'>Error: Unable to resolve domain {$domain}</span>"];
        }
        return array_column($ips, 'ip');
    }
}

// Function to scan ports and determine their status
if (!function_exists('scanPorts')) {
    function scanPorts($ip) {
        $ports = [21, 22, 23, 25, 53, 80, 110, 139, 389, 443, 445, 636, 3306, 3389, 8080, 8443, 8888];
        $results = [];
        foreach ($ports as $port) {
            $conn = @fsockopen($ip, $port, $errno, $errstr, 1);
            if ($conn) {
                $results[$port] = "<span class='success-text'>Open</span>";
                fclose($conn);
            } else {
                $results[$port] = ($errno == 111) ? "<span class='warning-text'>Closed</span>" : "<span class='error-text'>Filtered</span>";
            }
        }
        return $results;
    }
}

// Function to get domain from IP
if (!function_exists('getDomainFromIp')) {
    function getDomainFromIp($ip) {
        return gethostbyaddr($ip) ?: "<span class='warning-text'>No domain found for IP {$ip}</span>";
    }
}

// Main logic
function main() {
    if (!empty($_POST['type']) && !empty($_POST['input'])) {
        $type = htmlspecialchars($_POST['type']);
        $input = htmlspecialchars(trim($_POST['input']));

        echo "<div class='result-box animated-fade-in'>";
        if ($type == "ip" || $type == "domain") {
            if ($type == "domain") {
                echo "<h3>Scanning Domain: <strong>{$input}</strong></h3>";
                $ips = getIpsFromDomain($input);
                echo "<p>Resolved IPs: " . implode(", ", $ips) . "</p>";
            } else {
                $ips = [$input];
            }
            
            foreach ($ips as $ip) {
                echo "<h3>Scanning IP: <strong>{$ip}</strong></h3>";
                $portResults = scanPorts($ip);
                if (!empty($portResults)) {
                    echo "<p>Port Scan Results for {$ip}:</p>";
                    echo "<table class='port-table'>";
                    echo "<tr><th>Port</th><th>Status</th></tr>";
                    foreach ($portResults as $port => $status) {
                        echo "<tr><td>{$port}</td><td>{$status}</td></tr>";
                    }
                    echo "</table>";
                } else {
                    echo "<p class='error-text'>No ports scanned.</p>";
                }
            }
        }
        echo "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Advanced Network Scanner</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Raleway:wght@300;400;600&family=Orbitron:wght@500;700&display=swap');
        body { font-family: 'Raleway', sans-serif; background: linear-gradient(135deg, #0A0F24, #1E3C72); color: #FFF; text-align: center; padding: 20px; margin: 0; overflow-x: hidden; }
        .container { display: flex; flex-direction: column; align-items: center; justify-content: center; min-height: 100vh; }
        .form-container { background: rgba(255, 255, 255, 0.1); padding: 30px; border-radius: 15px; backdrop-filter: blur(10px); width: 90%; max-width: 500px; box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3); border: 1px solid rgba(255, 255, 255, 0.1); transition: all 0.3s ease-in-out; }
        .form-container:hover { transform: translateY(-5px); box-shadow: 0 12px 25px rgba(0, 0, 0, 0.4); }
        input, select { margin: 12px 0; padding: 12px; width: 100%; border: none; border-radius: 6px; background: rgba(255, 255, 255, 0.2); color: white; font-weight: bold; font-family: 'Orbitron', sans-serif; transition: background 0.3s ease; }
        input:focus, select:focus { background: rgba(255, 255, 255, 0.3); outline: none; }
        input[type="submit"] { background: linear-gradient(135deg, #00FF7F, #00BFFF); cursor: pointer; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; }
        input[type="submit"]:hover { background: linear-gradient(135deg, #00BFFF, #00FF7F); }
        .result-box { background: rgba(255, 255, 255, 0.1); padding: 20px; border-radius: 12px; margin-top: 20px; width: 90%; max-width: 600px; backdrop-filter: blur(10px); box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3); border: 1px solid rgba(255, 255, 255, 0.1); }
        .port-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .port-table th, .port-table td { padding: 10px; border: 1px solid rgba(255, 255, 255, 0.1); }
        .port-table th { background: rgba(255, 255, 255, 0.1); }
        .animated-fade-in { animation: fadeIn 1s ease-in-out; }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
    </style>
</head>
<body>
    <div class="container">
        <h2><i class="fas fa-network-wired"></i> Advanced Network Scanner</h2>
        <div class="form-container">
            <form method="POST">
                <label for="type">Select Scan Type:</label>
                <select name="type" id="type">
                    <option value="domain">Domain</option>
                    <option value="ip">IP Address</option>
                </select>
                <input type="text" name="input" placeholder="Enter Domain or IP" required>
                <input type="submit" value="Start Scan">
            </form>
        </div>
        <?php main(); ?>
    </div>
</body>
</html>
