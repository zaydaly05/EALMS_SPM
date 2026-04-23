<link rel="stylesheet" href="./Public/Css/header.css">
<?php 
$userName = $_SESSION['user_name'] ?? 'User';
$userRole = $_SESSION['user_role'] ?? 'Employee';
$avatarLetter = strtoupper(substr($userName, 0, 1));
?>
<header>
    <!-- <button class="icon-btn" aria-label="Notifications">🔔</button> -->

    <div class="profile-wrapper">
        <span class="avatar"><?php echo $avatarLetter; ?></span>
        <!-- <span class="arrow"></span> -->

        <!-- Dropdown -->
        <div class="profile-dropdown">
            <div class="profile-info">  
                <div class="avatar-large"><?php echo $avatarLetter; ?></div>
                <div class="user-details">
                    <div class="name"><?php echo htmlspecialchars($userName); ?></div>
                    <div class="handle"><?php echo htmlspecialchars($userRole); ?></div>
                    
                </div>
            </div>

            <div class="dropdown-links">
                <a href="#"><span class="icon">✏️</span>Edit Profile</a>
                <a href="<?php echo $base_url; ?>settings"><span class="icon">⚙️</span> Settings</a>
                
            </div>

            <div class="dropdown-links">
               
               
                <a onclick="window.location.href='<?php echo $base_url; ?>logout';"><span class="icon">⏻</span> Log out</a>
            </div>
        </div>
    </div>
</header>
