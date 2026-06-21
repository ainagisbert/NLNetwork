<!-- Fem el logout destruint la sessió i redirigint a la plana de login/registre -->
<?php
session_start();
session_destroy();
header("Location: ../index.html");
exit;
?>
