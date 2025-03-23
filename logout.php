<?php
session_start();
session_unset();  // Verwijder alle sessievariabelen
session_destroy(); // Beëindig de sessie

// Verwijder ook eventuele cookies (optioneel maar aanbevolen)
setcookie("user_id", "", time() - 3600, "/");

// Stuur gebruiker terug naar de inlogpagina
header("Location: login.php");
exit();
?>
