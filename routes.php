<?php
// routes.php

/*
|--------------------------------------------------------------------------
| Paths
|--------------------------------------------------------------------------
*/
$viewPath       = __DIR__ . '/Views/';
$controllerPath = __DIR__ . '/Controllers/';
$modelPath      = __DIR__ . '/Models/';

/*
|--------------------------------------------------------------------------
| Base URL
|--------------------------------------------------------------------------
*/
$scriptName = dirname($_SERVER['SCRIPT_NAME']);
$base_url = rtrim(str_replace('\\', '/', $scriptName), '/');

if ($base_url === '' || $base_url === '.' || $base_url === '/') {
    $base_url = '';
} else {
    if ($base_url[0] !== '/') {
        $base_url = '/' . $base_url;
    }
    $base_url .= '/';
}

/*
|--------------------------------------------------------------------------
| Load Models
|--------------------------------------------------------------------------
*/
require_once $modelPath . 'dbConnect.php';

/*
|--------------------------------------------------------------------------
| Load Controllers
|--------------------------------------------------------------------------
*/
require_once $controllerPath . 'userC.php';
require_once $controllerPath . 'leaveC.php';
require_once $controllerPath . 'attendanceC.php';
require_once $controllerPath . 'adminC.php';

/*
|--------------------------------------------------------------------------
| Controller Objects
|--------------------------------------------------------------------------
*/
$userController       = new UserC();
$leaveController      = new LeaveC();
$attendanceController = new AttendanceC();
$adminController      = new adminC();

/*
|--------------------------------------------------------------------------
| Session
|--------------------------------------------------------------------------
*/
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/*
|--------------------------------------------------------------------------
| Check Path
|--------------------------------------------------------------------------
*/
if (!isset($path)) {
    die("Routing path not found.");
}

$route = strtolower($path);

/*
|--------------------------------------------------------------------------
| Routes
|--------------------------------------------------------------------------
*/
switch ($route) {

    /*
    |--------------------------------------------------------------------------
    | Public Pages
    |--------------------------------------------------------------------------
    */
    case '/':
        require $viewPath . 'login.php';
        break;

    case '/signup':
        require $viewPath . 'SignUp.php';
        break;

    case '/reset-password':
        require $viewPath . 'resetPassword.php';
        break;

    /*
    |--------------------------------------------------------------------------
    | User Pages
    |--------------------------------------------------------------------------
    */
    case '/dashboard':

        if (!isset($_SESSION['user_id'])) {
            header("Location: {$base_url}");
            exit;
        }

        if ($_SESSION['user_role'] === 'admin') {
            header("Location: {$base_url}admin");
            exit;
        }

        require $viewPath . 'userDashboard.php';
        break;


    case '/history':

        if (!isset($_SESSION['user_id'])) {
            header("Location: {$base_url}");
            exit;
        }

        require $viewPath . 'history dashboard.php';
        break;


    case '/leave-summary':

        if (!isset($_SESSION['user_id'])) {
            header("Location: {$base_url}");
            exit;
        }

        $leave = $leaveController->getLeaveForView();
        require $viewPath . 'leave_summary.php';
        break;


    case '/request-time-off':

        if (!isset($_SESSION['user_id'])) {
            header("Location: {$base_url}");
            exit;
        }

        require $viewPath . 'requestTimeOff.php';
        break;


    case '/settings':

        if (!isset($_SESSION['user_id'])) {
            header("Location: {$base_url}");
            exit;
        }

        require $viewPath . 'settings.php';
        break;


    /*
    |--------------------------------------------------------------------------
    | Admin Pages
    |--------------------------------------------------------------------------
    */
    case '/admin':

        if (!isset($_SESSION['user_id'])) {
            header("Location: {$base_url}");
            exit;
        }

        if ($_SESSION['user_role'] !== 'admin') {
            header("Location: {$base_url}dashboard");
            exit;
        }

        require $viewPath . 'admin.php';
        break;


    /*
    |--------------------------------------------------------------------------
    | Authentication
    |--------------------------------------------------------------------------
    */
    case '/logout':
        session_destroy();
        header("Location: {$base_url}");
        exit;

    case '/auth/login':
        $userController->handleLogin();
        break;

    case '/auth/signup':
        $userController->handleSignup();
        break;

    case '/auth/logout':
        $userController->handleLogout();
        break;


    /*
    |--------------------------------------------------------------------------
    | Leave
    |--------------------------------------------------------------------------
    */
    case '/leave/request':
        $leaveController->handleRequestLeave();
        break;

    case '/leave/summary':
        $leaveController->getLeaveSummary();
        break;


    /*
    |--------------------------------------------------------------------------
    | Attendance
    |--------------------------------------------------------------------------
    */
    case '/attendance/mark':
        $attendanceController->handleMarkAttendance();
        break;

    case '/attendance/history':
        $attendanceController->getAttendanceHistory();
        break;


    /*
    |--------------------------------------------------------------------------
    | Admin Actions
    |--------------------------------------------------------------------------
    */
    case '/admin/post-announcement':
        $adminController->handlePostAnnouncement();
        break;

    case '/admin/post-celebration':
        $adminController->handlePostCelebration();
        break;

    case '/admin/manage-requests':
        $adminController->handleManageRequests();
        break;

    case '/admin/leave-requests':
        $adminController->leaveRequests();
        break;

    case '/admin/user-signup-requests':
        $adminController->userSignupRequests();
        break;


    /*
    |--------------------------------------------------------------------------
    | 404
    |--------------------------------------------------------------------------
    */
    default:
        http_response_code(404);
        echo "<h1>404 Page Not Found</h1>";
        echo "<p>The page you requested does not exist.</p>";
        break;
}
?>