<?php
require_once __DIR__ . '/../includes/layout.php';
renderHeader('Register');
?>
<h3>User Registration</h3>
<form method="post" action="/public/auth/register_handler.php">
  <label>Full Name</label><input type="text" name="full_name" required>
  <label>Email</label><input type="email" name="email" required>
  <label>Phone</label><input type="text" name="phone" required>
  <label>Password</label><input type="password" name="password" required>
  <label>Role</label>
  <select name="role" required>
    <option value="donor">Donor</option>
    <option value="patient">Patient</option>
    <option value="hospital">Hospital</option>
  </select>
  <label>City</label><input type="text" name="location_city" required>
  <button type="submit">Create Account</button>
</form>
<?php renderFooter(); ?>
