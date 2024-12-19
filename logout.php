<?php
// Start the session
session_start();

// Destroy the session to log the user out
session_destroy();

// Redirect to sign-in page after logging out
header("Location: coverpage.php");
exit();
?>
