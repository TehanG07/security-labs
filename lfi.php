<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Ensure 'pages' directory exists
if (!is_dir('pages')) {
    mkdir('pages', 0777, true);
}

// Auto-create default files if missing
$default_pages = [
    'home' => "<h1>Welcome to the Home Page</h1><p>This is the home page of our Local File Inclusion (LFI) lab.</p>",
    'about' => "<h1>About Us</h1><p>This page contains information about our organization.</p>",
    'contact' => "<h1>Contact</h1><p>Contact us at: contact@company.com</p>"
];

foreach ($default_pages as $filename => $content) {
    $file_path = "pages/{$filename}.php";
    if (!file_exists($file_path)) {
        file_put_contents($file_path, $content);
    }
}

// Get user input
$page = isset($_GET['page']) ? $_GET['page'] : 'home';

// Secure Mode: Prevent directory traversal attacks
if (isset($_GET['secure']) && $_GET['secure'] === 'true') {
    $page = basename($page);  // Removes "../" to block path traversal
}

// Set file path
$file_path = "pages/{$page}.php";

// Debugging Output
echo "<p><strong>Trying to include:</strong> " . realpath($file_path) . "</p>";

// Include the page if it exists
if (file_exists($file_path)) {
    include($file_path);
} else {
    echo "<p style='color:red;'>Error: Page not found.</p>";
}

// 🛑 LFI Simulation Mode 🛑
if (isset($_GET['vuln']) && $_GET['vuln'] === 'true') {
    echo "<h2 style='color:red;'>🚨 Vulnerable Mode Enabled! 🚨</h2>";

    // Common system files attackers target (realistic LFI test)
    $lfi_targets = [
        'passwd' => '/etc/passwd',
        'shadow' => '/etc/shadow',
        'hosts' => '/etc/hosts',
        'config' => 'C:/xampp/apache/conf/httpd.conf', // Windows
        'phpini' => 'C:/xampp/php/php.ini',
        'win-sys32' => 'C:/Windows/System32/drivers/etc/hosts',
        'logs' => 'C:/xampp/apache/logs/access.log'
    ];

    // Show sample LFI exploitation examples
    echo "<h3>Common LFI Payloads:</h3>";
    echo "<ul>";
    foreach ($lfi_targets as $key => $file) {
        echo "<li><a href='?vuln=true&page=" . urlencode($file) . "'>View " . strtoupper($key) . "</a></li>";
    }
    echo "</ul>";

    // Show file contents dynamically (if a valid file is targeted)
    if (isset($_GET['page']) && !empty($_GET['page'])) {
        $target_file = $_GET['page'];
        if (file_exists($target_file)) {
            echo "<h3>📝 File Content of: <span style='color:blue;'>$target_file</span></h3>";
            echo "<pre style='background:black;color:lime;padding:10px;border-radius:5px;overflow:auto;'>" 
                . htmlspecialchars(file_get_contents($target_file)) 
                . "</pre>";
        } else {
            echo "<p style='color:orange;'>⚠ File not found or access restricted.</p>";
        }
    }
}
?>
