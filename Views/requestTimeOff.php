<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Dashboard</title>
<link rel="stylesheet" href="<?php echo $base_url; ?>Public/Css/User Dashboard.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>

</head>
<body>


  <div class="container">
    <!-- Sidebar -->  
    <aside class="sidebar">
      <div class="logo"><img src="<?php echo $base_url; ?>Public/Images/EALMS Logo.png" style="width: 180px;" height="auto" alt="EALMS Logo"></div>
      <nav>
        <a href="#" class="active">
          <span class="icon" aria-hidden="true">📊</span>
          Dashboard
        </a>
        <a href="<?php echo $base_url; ?>history">
          <span class="icon" aria-hidden="true">⏰</span>
          History
        </a>
      </nav>
    </aside>
    <!-- Main content -->
    <div class="main">
      <header>
        <!-- <button aria-label="Notifications" class="icon-button">🔔</button>
        <button aria-label="Profile" class="icon-button" title="Profile">
          <div class="profile-circle">Z</div>
        </button>
        <button aria-label="Expand Menu" class="icon-button dropdown" title="Expand Menu">⌵</button>
      </header> -->

      <?php  include_once 'header.php';?>
      </header>
      <main class="content" role="main">
        <!-- Welcome and Request Time Off button -->
        <div style="display:flex; align-items:center; gap: 12px; margin-bottom: 12px; flex-wrap:wrap;">
     
          <div class="welcome">Welcome back, <?php echo htmlspecialchars($_SESSION['user_name'] ?? 'User'); ?> <span class="wave" aria-label="waving hand" role="img">👋</span></div>
          
          <a href="<?php echo $base_url; ?>request-time-off" class="btn-request" aria-label="Request Time Off" role="button">
            <svg aria-hidden="true" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              viewBox="0 0 24 24"><path d="M8 7V3M16 7V3M3 11h18M5 19h14a2 2 0 002-2v-5H3v5a2 2 0 002 2z"></path></svg>
            Request Time Off
          </a>

        </div>

          <!-- Leave cards -->
        <?php
        // Example dynamic leave data from your backend
        $leaveTypes = [
            ['name' => 'Casual Leave', 'available' => 3, 'used' => 5, 'color' => '#8b3ee5', 'class' => 'casual'],
            ['name' => 'Sick Leave', 'available' => 3, 'used' => 2, 'color' => '#2ec2f9', 'class' => 'sick'],
            ['name' => 'Earned Leave', 'available' => 6, 'used' => 6, 'color' => '#7ed859', 'class' => 'earned'],
            ['name' => 'Unpaid Leave', 'available' => 5, 'used' => 6, 'color' => '#ff2e61', 'class' => 'unpaid'],
            ['name' => 'Half Leave', 'available' => 6, 'used' => 6, 'color' => '#fcb63a', 'class' => 'half'],
        ];
        ?>

        <section class="leave-cards" aria-label="Leave Summary">
            <?php foreach ($leaveTypes as $leave):
                $total = $leave['available'] + $leave['used'];
                $usedPercent = $total > 0 ? round(($leave['used'] / $total) * 100) : 0;

                // Correct SVG math
                $radius = 24;
                $circumference = 2 * M_PI * $radius;
                $strokeDashArray = $circumference;
                $strokeDashOffset = $circumference * (1 - $usedPercent / 100);
            ?>
            <article class="leave-card <?= htmlspecialchars($leave['class']) ?>" aria-label="<?= htmlspecialchars($leave['name']) ?>">
                <div class="title" style="font-size: 15px;"><?= htmlspecialchars($leave['name']) ?></div>
                <br>
                <div class="circle-bg">
                    <svg width="72" height="72" viewBox="0 0 54 54" aria-hidden="true" focusable="false">
                        <circle cx="27" cy="27" r="24" class="bg" />
                        <circle
                            class="progress"
                            cx="27"
                            cy="27"
                            r="24"
                            transform="rotate(-90 27 27)"
                            stroke-dasharray="<?= $strokeDashArray ?>"
                            stroke-dashoffset="<?= $strokeDashOffset ?>"
                            style="stroke: <?= htmlspecialchars($leave['color']) ?>;"
                        />
                    </svg>
                    <div class="percent" style="color: <?= htmlspecialchars($leave['color']) ?>;">
                        <?= $usedPercent ?>%
                    </div>
                </div>
                <div class="details">
                    <span>Available - <?= htmlspecialchars($leave['available']) ?></span>
                    <span>Used - <?= htmlspecialchars($leave['used']) ?></span>
                </div>
            </article>
            <?php endforeach; ?>
        </section>

          <div class="attendance-container card">
            <div class="section flex-row">
              <div style="flex: 2 1 0;">

            <h3>Daily Attendance</h3>
            <br>

          <?php if (isset($_SESSION['attendance_message'])): ?>
            <p class="attendance-message">
             <?= htmlspecialchars($_SESSION['attendance_message']) ?>
            </p>
               <?php unset($_SESSION['attendance_message']); ?>
          <?php endif; ?>


            <form method="POST" action="<?php echo $base_url; ?>attendance/mark">

                <!-- Required by controller -->
                <input type="hidden" name="employee_id" value="<?php echo $_SESSION['user_id']; ?>">
               

                <label>
                    <input type="radio" name="status" value="present" required> Present
                </label>

                <label>
                    <input type="radio" name="status" value="absent"> Absent
                </label>

                <label>
                    <input type="radio" name="status" value="remote"> Remote
                </label>
                <input type="hidden" name="date" value="<?php echo date('Y-m-d'); ?>">
                <button type="submit" class="btn-request" style="background-color: darkgreen;" name="mark_attendance">
                    Mark Attendance
                </button>

            </form>


            <?php if(isset($attendance_message)): ?>
                <p class="attendance-message"><?= htmlspecialchars($attendance_message) ?></p>
            <?php endif; ?>
              </div>
            </div>
        </div>
       
        <!-- Main sections in two columns -->
        <div class="section flex-row">
          <div style="flex: 2 1 0;">
              <section class="section announcements-section" aria-label="Announcements">
              <?php 
                $admin = new adminC();
                $admin->expireOldAnnouncements();
                $announcements = $admin->getAnnouncements();
                
                if (!function_exists('formatAnnouncementTime')) {
                  function formatAnnouncementTime($timestamp) {
                    $time = strtotime($timestamp);
                    if (!$time) {
                      return '';
                    }
                    $diff = time() - $time;
                    if ($diff < 20) {
                      return 'Just now';
                    }
                    if ($diff < 3600) {
                      return floor($diff / 60) . ' min ago';
                    }
                    if ($diff < 86400) {
                      return floor($diff / 3600) . ' hrs ago';
                    }
                    return date('M j - h:i A', $time);
                  }
                }

                $announcementCount = count($announcements);
              ?>

              <div class="section-title announcements-title">
                <span>Announcements</span>
                <span class="announcement-badge"><?= $announcementCount; ?> new</span>
              </div>

              <?php if (!empty($announcements)): ?>
                <div class="announcement-list">
                  <?php foreach ($announcements as $a): ?>
                    <article class="announcement-item">
                      <div class="announcement-icon" aria-hidden="true">📢</div>
                      <div class="announcement-content">
                        <div class="announcement-text">
                          <?= nl2br(htmlspecialchars(strip_tags($a['message'], '<br>'))) ?>
                        </div>
                        <div class="announcement-meta">
                          <span><?= formatAnnouncementTime($a['created_at']); ?></span>
                          <?php if (!empty($a['admin_name'])): ?>
                            <span class="dot">•</span>
                            <span>By <?= htmlspecialchars($a['admin_name']); ?></span>
                          <?php endif; ?>
                        </div>
                      </div>
                    </article>
                  <?php endforeach; ?>
                </div>
              <?php else: ?>
                <div class="announcement-empty">
                  <p>No announcements available.</p>
                  <br>
                  <svg class="placeholder-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 2L2 12h20L12 2z"></path>
                    <line x1="12" y1="8" x2="12" y2="13"></line>
                    <circle cx="12" cy="17" r="1"></circle>
                  </svg>
                </div>
              <?php endif; ?>
            </section>

            

            <!-- Leave Request -->
            <section class="section leave-request-section" aria-label="Leave Request">
                <div class="section-title leave-request-title">
                    <span>Leave Request</span>
                    <span class="info-icon" title="Info">ⓘ</span>
                </div>

                <?php
                $leave = new leaveC();
                $leaveRequests = $leave->getLeaves();
                
                if (!empty($leaveRequests)) : 
                    // Helper function to format date
                    if (!function_exists('formatLeaveDate')) {
                        function formatLeaveDate($date) {
                            $timestamp = strtotime($date);
                            if (!$timestamp) return $date;
                            return date('M j', $timestamp);
                        }
                    }
                    
                    // Helper function to calculate days
                    if (!function_exists('calculateDays')) {
                        function calculateDays($start, $end) {
                            $startTime = strtotime($start);
                            $endTime = strtotime($end);
                            if (!$startTime || !$endTime) return '01';
                            $diff = $endTime - $startTime;
                            $days = floor($diff / (60 * 60 * 24)) + 1;
                            return str_pad($days, 2, '0', STR_PAD_LEFT);
                        }
                    }
                ?>
                    <div class="leave-request-table">
                        <div class="leave-request-header">
                            <div class="leave-col-duration">Duration</div>
                            <div class="leave-col-type">Type</div>
                            <div class="leave-col-days">Days</div>
                            <div class="leave-col-status">Status</div>
                            <div class="leave-col-action"></div>
                        </div>
                        
                        <?php foreach ($leaveRequests as $leaveReq) : 
                            $duration = formatLeaveDate($leaveReq['start_date']) . ' - ' . formatLeaveDate($leaveReq['end_date']);
                            $days = calculateDays($leaveReq['start_date'], $leaveReq['end_date']);
                            $status = ucfirst(strtolower($leaveReq['status'] ?? 'Pending'));
                            $statusClass = strtolower($status);
                        ?>
                            <div class="leave-request-row">
                                <div class="leave-col-duration"><?= htmlspecialchars($duration) ?></div>
                                <div class="leave-col-type"><?= htmlspecialchars($leaveReq['leave_type']) ?></div>
                                <div class="leave-col-days"><?= $days ?></div>
                                <div class="leave-col-status">
                                    <span class="status-badge status-<?= $statusClass ?>">
                                        <?php if ($statusClass === 'pending'): ?>
                                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <circle cx="12" cy="12" r="10"></circle>
                                                <polyline points="12 6 12 12 16 14"></polyline>
                                            </svg>
                                        <?php elseif ($statusClass === 'approved'): ?>
                                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <polyline points="20 6 9 17 4 12"></polyline>
                                            </svg>
                                        <?php endif; ?>
                                        <?= htmlspecialchars($status) ?>
                                    </span>
                                </div>
                                <div class="leave-col-action">
                                    <a href="<?php echo $base_url; ?>leave-summary?id=<?= htmlspecialchars($leaveReq['id']) ?>" class="see-more-link">See More</a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else : ?>
                    <div aria-hidden="true" class="leave-request-empty">
                        <svg class="placeholder-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                          <line x1="2" y1="22" x2="22" y2="2"></line>
                          <line x1="7" y1="7" x2="12" y2="12"></line>
                          <line x1="12" y1="12" x2="17" y2="17"></line>
                        </svg>
                    </div>
                <?php endif; ?>
            </section>

          </div>

          <!-- Right side small columns -->
          <div style="flex: 1 1 0; display:flex; flex-direction: column; gap:12px;">
            <!-- Who's on Leave -->
       <section class="section whos-on-leave" aria-label="Who's on Leave">
          <div class="section-title">Who's on Leave</div>
            <?php
            require_once './Controllers/leaveC.php';

            $leaveController = new LeaveC();

            $filter  = $_GET['filter'] ?? 'today';
            $onLeave = $leaveController->getOnLeave($filter);
            $count   = count($onLeave);
            ?>


          <div class="filter" role="region" aria-label="On leave filter">
            On Leave: <span class="count"><?= $count ?></span>

            <form method="get" style="display:inline;">
              <select name="filter" onchange="this.form.submit()">
                <option value="today" <?= $filter === 'today' ? 'selected' : '' ?>>Today</option>
                <option value="tomorrow" <?= $filter === 'tomorrow' ? 'selected' : '' ?>>Tomorrow</option>
                <option value="week" <?= $filter === 'week' ? 'selected' : '' ?>>This Week</option>
              </select>
            </form>
          </div>

          <?php if (empty($onLeave)): ?>
            <!-- Empty State -->
            <div class="empty-state" style="text-align:center; padding-top:30px;">
              <svg class="placeholder-icon" viewBox="0 0 24 24" fill="none"
                  stroke="#c9c9c9" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                <line x1="9" y1="9" x2="15" y2="15"></line>
                <line x1="15" y1="9" x2="9" y2="15"></line>
              </svg>
              <p>No employees on leave</p>
            </div>
          <?php else: ?>
            <!-- Leave List -->
            <div class="celebration-reminders-list-user">
              <?php foreach ($onLeave as $emp): 
                $leaveType = strtolower($emp['leave_type']);
                $colorClass = 'blue'; // default
                if (stripos($leaveType, 'sick') !== false) $colorClass = 'light-blue';
                elseif (stripos($leaveType, 'casual') !== false) $colorClass = 'purple';
                elseif (stripos($leaveType, 'earned') !== false) $colorClass = 'green';
                elseif (stripos($leaveType, 'unpaid') !== false) $colorClass = 'red';
                elseif (stripos($leaveType, 'half') !== false) $colorClass = 'yellow';
              ?>
                <div class="celebration-item-user">
                  <div class="celebration-name-user"><?= htmlspecialchars($emp['employee_name'], ENT_QUOTES, 'UTF-8') ?></div>
                  <div class="celebration-date-user"><i class="fas fa-calendar"></i> <?= htmlspecialchars($emp['start_date']) ?> - <?= htmlspecialchars($emp['end_date']) ?></div>
                  <span class="celebration-type-user <?= $colorClass ?>"><?= htmlspecialchars($emp['leave_type']) ?></span>
                </div>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>
        </section>



            <!-- Celebrations this month -->
            <section class="section celebrations" aria-label="Celebrations this month">
              <div class="section-title">Celebrations this month</div>
              <?php 
                if (!isset($admin)) $admin = new adminC();
                $celebrations = $admin->getCelebrationReminders();
                
                if (empty($celebrations)) {
                  echo '<div aria-hidden="true" style="text-align:center; padding-top: 30px;">';
                  echo '<svg class="placeholder-icon" viewBox="0 0 24 24" fill="none" stroke="#c9c9c9" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">';
                  echo '<rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>';
                  echo '<line x1="9" y1="9" x2="15" y2="15"></line>';
                  echo '<line x1="15" y1="9" x2="9" y2="15"></line>';
                  echo '</svg>';
                  echo '<p style="color: #999; margin-top: 10px; font-size: 14px;">No upcoming celebrations</p>';
                  echo '</div>';
                } else {
                  echo '<div class="celebration-reminders-list-user">';
                  foreach ($celebrations as $celebration) {
                    $name = htmlspecialchars($celebration['name'] ?? 'Unknown');
                    $type = htmlspecialchars($celebration['type'] ?? 'Reminder');
                    $date = $celebration['date'] ?? '';
                    
                    // Format date to match format (e.g., "Nov 22, 2025")
                    $formattedDate = '';
                    if ($date) {
                      try {
                        $dateObj = new DateTime($date);
                        $formattedDate = $dateObj->format('M d, Y');
                      } catch (Exception $e) {
                        $dateObj = DateTime::createFromFormat('Y-m-d', $date);
                        if ($dateObj) {
                          $formattedDate = $dateObj->format('M d, Y');
                        } else {
                          $formattedDate = $date;
                        }
                      }
                    }
                    
                    echo '<div class="celebration-item-user">';
                    echo '<div class="celebration-name-user">' . $name . '</div>';
                    echo '<div class="celebration-date-user"><i class="fas fa-calendar"></i> ' . $formattedDate . '</div>';
                    echo '<span class="celebration-type-user">' . $type . '</span>';
                    echo '</div>';
                  }
                  echo '</div>';
                }
              ?>
            </section>
          </div>
        </div>
      </main>
    </div>
  </div>
  <!-- Request Time Off Modal -->
<div class="modal-overlay" role="dialog" aria-modal="true" aria-labelledby="modal-title" aria-describedby="modal-desc" model-desc>
  <div class="modal">
    <header class="modal-header">
      <h2 id="modal-title">Request Time Off</h2>
      <button class="close-btn" aria-label="Close modal">&times;</button>
      <link rel="stylesheet" href="<?php echo $base_url; ?>Public/Css/RequestTimeOff.css" />
    </header>

    <form class="request-timeoff-form" action="<?php echo $base_url; ?>leave/request" method="POST" novalidate>
      <label for="leave-type">Type</label>
      <select id="leave-type" name="leave_type" required>
        <option value="" disabled selected>Select Leave Type</option>
        <option value="Casual Leave">Casual Leave</option>
        <option value="Sick Leave">Sick Leave</option>  
        <option value="Earned Leave">Earned Leave</option>
        <option value="Unpaid Leave">Unpaid Leave</option>
        <option value="Half Leave">Half Leave</option>
      </select>

      <label for="start-date">Start Date</label>
      <input type="date" id="start-date" name="start_date" placeholder="MM-DD-YYYY" required autocomplete="off" />

      <label for="end-date">End Date</label>
      <input type="date" id="end-date" name="end_date" placeholder="MM-DD-YYYY" required autocomplete="off" />

      <label for="reason">Reason</label>
      <textarea id="reason" name="reason" placeholder="Reason" rows="4" required></textarea>

      <!-- <button type="submit" class="btn-submit">Done</button> -->


       <button type="submit" class="btn-submit" >Done</button>
    </form>
  </div>
</div>
  
<script>
    // Close modal on clicking close button
 document.querySelector('.close-btn').addEventListener('click', () => {
  window.location.href = '<?php echo $base_url; ?>dashboard'; 
});


  </script>
</body>
<footer>
  <?php  include_once 'footer.php';?>
</footer>
</html>