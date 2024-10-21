<?php
    session_start();
    // To log out, unset the session variable and destroy the session
    $_SESSION = array(); // Clear all session variables
    session_destroy();   // Destroy the session

    // Redirect to the login page or another page after logging out
    header("Location: identification.php");
    exit();

    ?>