<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Sign Up Modal</title>
  <link rel="stylesheet" href="<?php echo $base_url; ?>Public/Css/SignUp.css" />
</head>
<body>
  <!-- Main page background content (dimmed) -->
  <div class="page-content">
    <link rel="stylesheet" href="<?php echo $base_url; ?>Public/Css/login.css" />
    <div class="container">
    <!-- Left section -->
    <section class="left">
      <h1>Leave Management System</h1>
      <p>
        To create a seamless and efficient leave management system that simplifies leave requests, approvals, and tracking—empowering
        organizations to save time, enhance transparency, and ensure fair and accurate leave management for all employees.
      </p>
      <div class="illustration">
        <img src="<?php echo $base_url; ?>Public/Images/login.png" alt="Calendar with people managing leaves" />
      </div>
    </section>

    <!-- Right section -->
    <section class="right">
      <h2>Login</h2>
      <form class="login-form" action="<?php echo $base_url; ?>auth/login" method="POST" novalidate>
        <label for="email">Email</label>
        <div class="input-group">
          <span class="input-icon" aria-hidden="true">📧</span>
          <input type="email" id="email" name="email" placeholder="Email" required autocomplete="email" />
        </div>

        <label for="password">Password</label>
        <div class="input-group">
          <span class="input-icon" aria-hidden="true">🔒</span>
          <input type="password" id="password" name="password" placeholder="Password" required autocomplete="current-password" />
        </div>

        <div class="form-links">
          <a href="<?php echo $base_url; ?>signup">Don't Have an Account? Sign Up</a>
          <a href="<?php echo $base_url; ?>reset-password" class="forgot-password">Forgot Password</a>
        </div>

        <button type="button" class="btn-login" onclick="window.location.href='<?php echo $base_url; ?>dashboard';"></button>
          Login
        </button>
      </form>
    </section>
  </div>
  </div>

  <!-- Sign Up Modal -->
  <div class="modal-overlay" role="dialog" aria-modal="true" aria-labelledby="modal-title" aria-describedby="modal-desc">
    <div class="modal">
      <header class="modal-header">
        <h2 id="modal-title">Sign Up</h2>
        <button class="close-btn" aria-label="Close modal">&times;</button>
      </header>

      <?php if (isset($_SESSION['flash'])): ?>
        <div class="flash-message" style="padding: 10px; margin: 10px 0; background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; border-radius: 4px;">
          <?php 
            echo htmlspecialchars($_SESSION['flash']); 
            unset($_SESSION['flash']);
          ?>
        </div>
      <?php endif; ?>

      <form class="signup-form" id="signupForm" action="<?php echo $base_url; ?>auth/signup" method="POST" enctype="multipart/form-data" novalidate>
        <label for="name">Name</label>
        <input type="text" id="name" name="name" placeholder="Enter Your Name" autocomplete="name" required value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>" />
        
        <label for="email">Email</label>
        <input type="email" id="email" name="email" placeholder="Your Email" autocomplete="email" required  value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"/>
        
        <label for="password">Password</label>
        <input type="password" id="password" name="password" placeholder="Password" autocomplete="new-password" required value="<?php echo htmlspecialchars($_POST['password'] ?? ''); ?>"/>
        
        <label for="confirm-password">Confirm Password</label>
        <input type="password" id="confirm-password" name="confirmPassword" placeholder="Confirm Password" autocomplete="new-password" required  value="<?php echo htmlspecialchars($_POST['confirmPassword'] ?? ''); ?>"/>
        <span id="password-error" style="color: red; font-size: 12px; display: none;">Passwords do not match</span>

        <label for="role">Role (Default: User)</label>
        <input type="text" id="role-display" value="User" readonly class="form-control-plaintext mb-3" style="font-weight: bold;" value="<?php echo htmlspecialchars($_POST['role'] ?? ''); ?>"/>

        <input type="hidden" name="role" id="actual-role-input" value="employee" />

        <label for="admin-key">Admin Key (Optional)</label>
        <input type="password" id="admin-key" name="adminKey" placeholder="Enter key to register as Admin" autocomplete="off" />
        
        <!-- <label for="picture-upload" class="file-label">
          Picture Upload
          <div class="upload-button-wrapper">
            <input type="file" id="picture-upload" name="picture" accept="image/*" />
            <span class="upload-text">Upload File <span class="upload-icon">⬆</span></span>
          </div>
        </label> -->

        <button type="submit" class="btn-submit">
          Submit
        </button>
      </form>
    </div>
  </div>

  <script>
    // Close modal on clicking close button
    document.querySelector('.close-btn').addEventListener('click', () => {
      window.location.href = '<?php echo $base_url; ?>';
    });

  
   
  </script>
</body>
</html>