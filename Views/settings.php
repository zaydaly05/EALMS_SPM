<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Settings | Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="./Public/Css/adminFinal.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <?php require_once './Controllers/adminC.php'; ?>
</head>

<body>

<!-- SIDEBAR -->
<div class="sidebar">
    <div class="logo">
        <img src="<?php echo $base_url; ?>Public/Images/EALMS Logo.png" style="width:180px;" alt="EALMS Logo">
    </div>
    <nav>
        <ul>
            <li><a href="adminDashboard.php">Dashboard</a></li>
            <li class="active"><a href="#">Settings</a></li>
            <li><a href="<?php echo $base_url; ?>logout">Logout</a></li>
        </ul>
    </nav>
</div>

<!-- TOP HEADER -->
<div class="top-header">
    <div class="top-header-left">
        <h2 class="page-title">Settings</h2>
    </div>

    <div class="top-header-right">
        <i class="fas fa-bell header-icon"></i>
        <div class="avatar">A</div>
    </div>
</div>

<!-- MAIN CONTENT -->
<div class="main-content">

    <div class="left-column">

        <!-- PROFILE SETTINGS -->
        <section class="post-section">
            <h2>Profile Settings</h2>

            <form method="POST" action="#">
                <label>Full Name</label>
                <input type="text" value="Admin Name">

                <label>Email</label>
                <input type="email" value="admin@email.com">

                <label>Username</label>
                <input type="text" value="admin">

                <button type="submit">
                    <i class="fas fa-save"></i> Save Profile
                </button>
            </form>
        </section>

        <!-- PASSWORD SETTINGS -->
        <section class="post-section">
            <h2>Change Password</h2>

            <form method="POST" action="#">
                <label>Current Password</label>
                <input type="password">

                <label>New Password</label>
                <input type="password">

                <label>Confirm New Password</label>
                <input type="password">

                <button type="submit">
                    <i class="fas fa-lock"></i> Update Password
                </button>
            </form>
        </section>

        <!-- SYSTEM SETTINGS -->
        <section class="post-section">
            <h2>System Preferences</h2>

            <form method="POST" action="#">
                <label>
                    <input type="checkbox" checked> Enable Email Notifications
                </label>

                <label>
                    <input type="checkbox"> Enable SMS Notifications
                </label>

                <label>
                    <input type="checkbox" checked> Auto-approve Leave Requests
                </label>

                <button type="submit">
                    <i class="fas fa-cog"></i> Save Preferences
                </button>
            </form>
        </section>

    </div>

    <!-- RIGHT COLUMN -->
    <div class="right-column">

        <div class="dashboard-container">
            <h1 class="dashboard-title">Account</h1>

            <div class="summary-card">
                <h3>Status</h3>
                <p><strong>Role:</strong> User</p>
                <p><strong>Account:</strong> Active</p>
            </div>

            <div class="summary-card">
                <h3>Danger Zone</h3>
                <button style="background:#dc3545;">
                    <i class="fas fa-trash"></i> Deactivate Account
                </button>
            </div>
        </div>

    </div>

</div>

</body>
</html>
