<?php
// index.php
// This is the single entry point for your application.

// 1. Start the session (for login)
session_start();

// 2. Get the requested URL path
$request_uri = $_SERVER['REQUEST_URI'];
$path = parse_url($request_uri, PHP_URL_PATH);

// Decode URL encoding (handles spaces in folder names)
$path = urldecode($path);

// Get the directory where index.php is located
$script_dir = dirname($_SERVER['SCRIPT_NAME']);
$script_dir = str_replace('\\', '/', $script_dir);

// Remove query string if present
$path = strtok($path, '?');

// Get the folder name (project directory name)
$folder_name = basename(__DIR__);
$folder_name_encoded = str_replace(' ', '%20', $folder_name);

// Remove the script directory from the beginning of the path
if ($script_dir !== '/' && $script_dir !== '.' && $script_dir !== '') {
    // Normalize script_dir - ensure it starts with /
    if ($script_dir[0] !== '/') {
        $script_dir = '/' . $script_dir;
    }
    // Remove trailing slash
    $script_dir = rtrim($script_dir, '/');
    
    // If path starts with script_dir, remove it
    if (strpos($path, $script_dir) === 0) {
        $path = substr($path, strlen($script_dir));
    }
}

// Also try to remove folder name if it's still in the path
if (strpos($path, '/' . $folder_name . '/') === 0) {
    $path = substr($path, strlen('/' . $folder_name));
} elseif (strpos($path, '/' . $folder_name_encoded . '/') === 0) {
    $path = substr($path, strlen('/' . $folder_name_encoded));
} elseif ($path === '/' . $folder_name) {
    $path = '/';
} elseif ($path === '/' . $folder_name_encoded) {
    $path = '/';
}

// Remove index.php from path if present
$path = str_replace('/index.php', '', $path);
$path = str_replace('index.php', '', $path);

// Remove .php extension if present (for direct file access attempts)
$path = preg_replace('/\.php$/', '', $path);

// Normalize the path
if (empty($path) || $path === false || trim($path) === '') {
    $path = '/';
} else {
    // Ensure path starts with /
    if ($path[0] !== '/') {
        $path = '/' . $path;
    }
    // Remove trailing slash except for root
    if ($path !== '/' && substr($path, -1) === '/') {
        $path = rtrim($path, '/');
    }
    // Convert to lowercase for case-insensitive routing
    $path = strtolower($path);
}

// 3. Define the project's root directory
$root_dir = __DIR__;

// 4. Include the router
// This file will now have access to the $path variable
require_once $root_dir . '/routes.php';





?>