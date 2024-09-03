<?php
session_start();
session_unset(); // Unset all session variables
session_destroy(); // Destroy the session
header("Location: register.php"); // Redirect to the login page
exit;
?>

<!-- added scripts for login/signup links -->
<script>
localStorage.removeItem('loggedIn');
localStorage.removeItem('username');
</script>
