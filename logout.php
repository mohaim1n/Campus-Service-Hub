<?php
session_start();
session_destroy(); // Padam semua data session
header("Location: login.php");
exit();
?>