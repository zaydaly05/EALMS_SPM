<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Admin Dashboard</title>

    <link rel="stylesheet" href="./Public/Css/adminFinal.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>

    <script src="./Public/Js/animateNumber.js"></script>
    <script>
        // Pass base URL to JavaScript for fetch requests
        window.BASE_URL = '<?php echo isset($base_url) ? $base_url : "/EALMS/"; ?>';
    </script>

    <?php require_once './Controllers/adminC.php'; ?>

</head>


<body>
    

    <!-- SIDEBAR -->
    <div class="sidebar">
      <div class="logo"><img src="<?php echo $base_url; ?>Public/Images/EALMS Logo.png" style="width: 180px;" height="auto" alt="EALMS Logo"></div>
        <nav>
            <ul>
                <li class="active"><a href="#">Dashboard</a></li>
                <li><a href="#user-requests">User Sign-Ups</a></li>
                <li><a href="#leave-requests">Leave Requests</a></li>
                <li><a href="#announcements">Announcements</a></li>
                <li><a href="#celeb">Celebrations</a></li>
            </ul>
        </nav>
    </div>
  
    <!-- MAIN CONTENT -->
    <div class="main-content">
        <!-- LEFT COLUMN -->
        <div class="left-column">
            <h1>Welcome back, Admin 👋</h1>
                <?php
                // Define BASE_PATH once in a central config file or index.php
                define('BASE_PATH', '/EALMS/'); 
                // ... rest of your code to render the HTML ...
                ?>
                
      <!-- Announcement -->
            <section id="announcements" name="postAnnouncement" class="post-section">
                <h2>Post Announcement</h2>
                <form action="<?php echo BASE_PATH; ?>admin/post-announcement" method="POST">
                    <input type="hidden" name="postAnnouncement" value="1">
                    <textarea name="announcement" placeholder="Type your announcement.." required><?php echo htmlspecialchars($_POST['announcement'] ?? ''); ?></textarea>
                    <button type="submit">Post Announcement</button>
                </form>
            </section>

            <!-- Celebration -->
            <section id="celeb" class="post-section">
                <h2>Add Celebration Reminder</h2>
                <form action="<?php echo $base_url; ?>admin/post-celebration" method="POST" >
                    <input type="hidden" name="postCelebration" value="1">
                    
                    <label for="celebration_name">Name *</label>
                    <input type="text" id="celebration_name" name="celebration_name" placeholder="Enter person's name" required value="<?php echo htmlspecialchars($_POST['celebration_name'] ?? ''); ?>">
                    
                    <label for="celebration_type">Type *</label>
                    <select id="celebration_type" name="celebration_type" required>
                        <option value="">Select type</option>
                        <option value="Birthday" <?php echo (isset($_POST['celebration_type']) && $_POST['celebration_type'] === 'Birthday') ? 'selected' : ''; ?>>Birthday</option>
                        <option value="Work Anniversary" <?php echo (isset($_POST['celebration_type']) && $_POST['celebration_type'] === 'Work Anniversary') ? 'selected' : ''; ?>>Work Anniversary</option>
                        <option value="Other" <?php echo (isset($_POST['celebration_type']) && $_POST['celebration_type'] === 'Other') ? 'selected' : ''; ?>>Other</option>
                    </select>
                    
                    <label for="celebration_date">Date *</label>
                    <input type="date" id="celebration_date" name="celebration_date" required value="<?php echo htmlspecialchars($_POST['celebration_date'] ?? ''); ?>">
                    
                    <textarea id="celebration_message" name="celebration_message" placeholder="Additional message or note..."><?php echo htmlspecialchars($_POST['celebration_message'] ?? ''); ?></textarea>
                    
                    <button type="submit">Add Celebration Reminder</button>
                </form>
            </section>
            
            <!-- User Requests -->
          
            <section id="user-requests" class="requests-section" style="margin-bottom: 0 !important;">
                <h2>User Sign-Up Requests</h2>
                <?php 
                    if (!isset($adminController)) $adminController = new adminC();
                    $adminController->userSignupRequests(); 
                    // $adminController->handleManageRequests();
                ?>
            </section>
            <br>
            <section class="requests-section" id="leave-requests" style="width: 850px; margin-top: 0 !important;">   <!-- Leave Requests -->
                    <h2>Leave Requests</h2>
                    <?php 
                        if (!isset($adminController)) $adminController = new adminC();
                        $adminController->leaveRequests(); 
                        
                    ?>
                </section>
        </div>

        <!-- RIGHT COLUMN -->
        <div class="right-column">
                
            <!-- DASHBOARD SUMMARY -->
            <div class="dashboard-container">
                <h1 class="dashboard-title">Dashboard Summary</h1>

                <div class="summary-card" id="usersCard">
                    <div class="card-header">
                        <h2 class="card-title">Total Users</h2>
                        <div class="card-icon"><i class="fas fa-users"></i></div>
                    </div>
                    <div class="card-value" id="totalUsers">0</div>
                    <p class="card-description">Active employees registered</p>
                </div>

                <div class="summary-card" id="leavesCard">
                    <div class="card-header">
                        <h2 class="card-title">Total Leaves</h2>
                        <div class="card-icon"><i class="fas fa-calendar-times"></i></div>
                    </div>
                    <div class="card-value" id="totalLeaves">0</div>
                    <p class="card-description">Leave requests this month</p>
                </div>

                <div class="summary-card" id="celebrationCard">
                    <div class="card-header">
                        <h2 class="card-title">Celebration Reminders</h2>
                        <div class="card-icon"><i class="fas fa-birthday-cake"></i></div>
                    </div>
                    <div id="celebrationList">
                        <?php 
                            if (!isset($adminController)) $adminController = new adminC();
                            $celebrations = $adminController->getCelebrationReminders();
                            
                            if (empty($celebrations)) {
                                echo '<p class="card-description">No upcoming celebrations</p>';
                            } else {
                                echo '<div class="celebration-reminders-list">';
                                foreach ($celebrations as $celebration) {
                                    $name = htmlspecialchars($celebration['name'] ?? 'Unknown');
                                    $type = htmlspecialchars($celebration['type'] ?? 'Reminder');
                                    $date = $celebration['date'] ?? '';
                                    
                                    // Format date to match image format (e.g., "Nov 22, 2025")
                                    $formattedDate = '';
                                    if ($date) {
                                        try {
                                            $dateObj = new DateTime($date);
                                            $formattedDate = $dateObj->format('M d, Y');
                                        } catch (Exception $e) {
                                            // Try alternative format
                                            $dateObj = DateTime::createFromFormat('Y-m-d', $date);
                                            if ($dateObj) {
                                                $formattedDate = $dateObj->format('M d, Y');
                                            } else {
                                                $formattedDate = $date;
                                            }
                                        }
                                    }
                                    
                                    echo '<div class="celebration-item">';
                                    echo '<div class="celebration-name">' . $name . '</div>';
                                    echo '<div class="celebration-date"><i class="fas fa-calendar"></i> ' . $formattedDate . '</div>';
                                    echo '<span class="celebration-type">' . $type . '</span>';
                                    echo '</div>';
                                }
                                echo '</div>';
                            }
                        ?>
                    </div>
                </div>

                <button class="refresh-btn" onclick="refreshDashboard()">
                    <i class="fas fa-sync-alt"></i>   Refresh Data
                </button>
                
            </div> 
        </div><!-- RIGHT COLUMN END -->
      
                
    </div><!-- MAIN CONTENT END --> 
   
</body>

<footer>
   <?php include_once 'footer.php'; ?> 
</footer>

</html>
