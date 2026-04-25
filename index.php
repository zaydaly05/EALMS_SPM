<?php
// index.php
// Single entry point for the application

session_start();

/*
|--------------------------------------------------------------------------
| Get Current URL Path
|--------------------------------------------------------------------------
*/
$requestUri = $_SERVER['REQUEST_URI'];
$path = parse_url($requestUri, PHP_URL_PATH);
$path = urldecode($path);

/*
|--------------------------------------------------------------------------
| Project Folder Path
|--------------------------------------------------------------------------
*/
$scriptDir = dirname($_SERVER['SCRIPT_NAME']);
$scriptDir = str_replace('\\', '/', $scriptDir);

/*
|--------------------------------------------------------------------------
| Remove Query String
|--------------------------------------------------------------------------
*/
$path = strtok($path, '?');

/*
|--------------------------------------------------------------------------
| Remove Project Folder Name
|--------------------------------------------------------------------------
*/
$folderName = basename(__DIR__);
$folderEncoded = str_replace(' ', '%20', $folderName);

/*
|--------------------------------------------------------------------------
| Remove Script Directory
|--------------------------------------------------------------------------
*/
if ($scriptDir !== '/' && $scriptDir !== '.' && $scriptDir !== '') {

    if ($scriptDir[0] !== '/') {
        $scriptDir = '/' . $scriptDir;
    }

    $scriptDir = rtrim($scriptDir, '/');

    if (strpos($path, $scriptDir) === 0) {
        $path = substr($path, strlen($scriptDir));
    }
}

/*
|--------------------------------------------------------------------------
| Remove Folder Name if Still Exists
|--------------------------------------------------------------------------
*/
if (strpos($path, '/' . $folderName . '/') === 0) {
    $path = substr($path, strlen('/' . $folderName));

} elseif (strpos($path, '/' . $folderEncoded . '/') === 0) {
    $path = substr($path, strlen('/' . $folderEncoded));

} elseif ($path === '/' . $folderName || $path === '/' . $folderEncoded) {
    $path = '/';
}

/*
|--------------------------------------------------------------------------
| Remove index.php
|--------------------------------------------------------------------------
*/
$path = str_replace('/index.php', '', $path);
$path = str_replace('index.php', '', $path);

/*
|--------------------------------------------------------------------------
| Remove .php Extension
|--------------------------------------------------------------------------
*/
$path = preg_replace('/\.php$/', '', $path);

/*
|--------------------------------------------------------------------------
| Normalize Path
|--------------------------------------------------------------------------
*/
if (empty($path) || trim($path) === '') {

    $path = '/';

} else {

    if ($path[0] !== '/') {
        $path = '/' . $path;
    }

    if ($path !== '/' && substr($path, -1) === '/') {
        $path = rtrim($path, '/');
    }

    $path = strtolower($path);
}

/*
|--------------------------------------------------------------------------
| Root Directory
|--------------------------------------------------------------------------
*/
$rootDir = __DIR__;

/*
|--------------------------------------------------------------------------
| Load Routes
|--------------------------------------------------------------------------
*/
require_once $rootDir . '/routes.php';
?>