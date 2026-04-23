<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Reset Password</title>
  <link rel="stylesheet" href="<?php echo $base_url; ?>Public/Css/ResetPassword.css" />
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
        
        <button type="button" class="btn-login" onclick="window.location.href='<?php echo $base_url; ?>dashboard'; return false;">
          Login
        </button>
      </form>
    </section>
  </div>     
  </div>

  <!-- Reset Password Modal -->
  <div class="modal-overlay" role="dialog" aria-modal="true" aria-labelledby="modal-title" aria-describedby="modal-desc">
    <div class="modal">
      <header class="modal-header">
        <h2 id="modal-title">Reset Password</h2>
        <button class="close-btn" aria-label="Close modal">&times;</button>
      </header>

      <form class="reset-password-form" action="<?php echo $base_url; ?>reset-password" method="POST" novalidate>
        <label for="reset-email">Your Email</label>
        <input type="email" id="reset-email" name="email" placeholder="Enter Email Here" autocomplete="email" required />
    
        <button type="submit" class="btn-submit" onclick="window.location.href='<?php echo $base_url; ?>'; return false;">
          Submit
        </button>
      </form>

      <p class="modal-note">• Please Check Your mail for Password Reset Link</p>
    </div>
  </div>

  <script>
    // Close modal on clicking close button
    document.querySelector('.close-btn').addEventListener('click', () => {
      document.querySelector('.modal-overlay').style.display = 'none';
    });
  </script>
</body>
</html>