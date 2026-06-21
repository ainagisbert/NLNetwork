<!-- Constants necessàries accedir a la base de dades -->
<?php

define("SERVERNAME", getenv("MYSQLHOST"));
define("USERNAME",   getenv("MYSQLUSER"));
define("PASSWORD",   getenv("MYSQLPASSWORD"));
define("DBNAME",     getenv("MYSQLDATABASE"));
define("PORT",       getenv("MYSQLPORT"));

?>