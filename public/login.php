<?php
require_once __DIR__ . '/../includes/layout.php';
renderHeader('Login');
?>
<h3>Secure Login</h3>
<form method="post" action="/public/auth/login_handler.php">
  <label>Email</label><input type="email" name="email" required>
  <label>Password</label><input type="password" name="password" required>
  <button type="submit">Login</button>
</form>
<?php renderFooter(); ?>
