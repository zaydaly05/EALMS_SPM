<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Login - Leave Management System</title>
  <link rel="stylesheet" href="<?php echo $base_url; ?>Public/Css/login.css" />

</head>
<body>
  <div class="container">
    <!-- Left section -->
    <section class="left">
      <h1 style="margin-left: 252px;">Leave Management System</h1>
      <p style="text-align: center; font-size: large; margin-left: 250px;">
        To create a seamless and efficient leave management system that simplifies leave requests, approvals, and tracking—empowering
        organizations to save time, enhance transparency, and ensure fair and accurate leave management for all employees.
      </p>
      <div class="illustration" style="margin-left: 250px;">
        <img src="<?php echo $base_url; ?>Public/Images/login.png" alt="Calendar with people managing leaves" />
      </div>
    </section>

    <!-- Right section -->
    <section class="right">
      <h2>Login</h2>
      
      <form class="login-form" action="<?php echo $base_url; ?>auth/login" method="POST" validate>
        <label for="email">Email</label>
        <div class="input-group">
          <span class="input-icon" aria-hidden="true">📧</span>
          <input type="email" id="email" name="email" placeholder="Email" required autocomplete="email" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"/>
        </div>

        <label for="password">Password</label>
        <div class="input-group">
          <span class="input-icon" aria-hidden="true">🔒</span>
          <input type="password" id="password" name="password" placeholder="Password" required autocomplete="current-password" value="<?php echo htmlspecialchars($_POST['password'] ?? ''); ?>" />
        </div>
        <label for="remember-me">
        <input type="checkbox" id="remember-me" name="remember-me">
          Remember Me
        </label>

        <div class="form-links">
          <a href="<?php echo $base_url; ?>signup">Don't Have an Account? Sign Up</a>
          <a href="<?php echo $base_url; ?>reset-password" class="forgot-password">Forgot Password</a>
        </div>

        <button type="submit"class="btn-login">Login</button>
      </form>
    </section>
  </div>
<!-- <script>
   document.querySelector('.btn-login').addEventListener('click', () => {
  window.location.href = 'dashboard'; 
});
</script> -->

</body>
</html>