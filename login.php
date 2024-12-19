<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - PaPapDol MotoBlogs</title>
  <link rel="stylesheet" href="css/login.css">
</head>
<body>
  <div class="login-container">
    <div id="login-form" class="login-box">
      <h1>Welcome to PaPapDol MotoBlogs</h1>
      <h2>🏍️ Login to Your Account 🏍️</h2>
      <form action="login_process.php" method="POST">
        <div class="input-group">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" required>
        </div>
        <div class="input-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
        </div>
        <button type="submit" class="login-btn">Login</button>
    </form>
      <p>Don't have an account? <a href="javascript:void(0)" onclick="toggleForms()">Sign up here</a></p>
    </div>
  </div>

  <div id="signup-form" class="signup-container" style="display: none;">
    <div class="signup-box">
      <h2>🏍️ Create Your Account 🏍️</h2>
      <form action="signup_process.php" method="POST">
        <div class="input-group">
            <label for="new-name">Full Name</label>
            <input type="text" id="new-name" name="full_name" required>
        </div>
        <div class="input-group">
            <label for="new-address">Address</label>
            <input type="text" id="new-address" name="address" required>
        </div>
        <div class="input-group">
            <label for="new-username">Username</label>
            <input type="text" id="new-username" name="username" required>
        </div>
        <div class="input-group">
            <label for="new-password">Password</label>
            <input type="password" id="new-password" name="password" required>
        </div>
        <div class="input-group">
            <label for="confirm-password">Confirm Password</label>
            <input type="password" id="confirm-password" name="confirm_password" required>
        </div>
        <button type="submit" class="signup-btn">Sign Up</button>
    </form>

      <p>Already have an account? <a href="javascript:void(0)" onclick="toggleForms()">Login here</a></p>
    </div>
  </div>

  <div class="background-overlay">
    <img src="images/honda_tmx_155_1629795147_4656eef2_progressive.jpg" alt="Motorcycle Background" class="background-image">
  </div>
  
  <script src = "js/script.js"></script>
</body>
</html>
