<!-- Codi per tancar la sessió
 Simplement esborra tots els valors de la sessió i redirigeix al usuari a la pàgina d'inici -->
<?php
session_start();
session_destroy();
header("Location: ../index.html");
exit;
?>
