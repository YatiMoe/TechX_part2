<?php
require_once("settings.php");
$conn = @mysqli_connect($host, $user, $pwd, $sql_db);
if ($conn) echo "DB CONNECTED SUCCESSFULLY";
else echo "DB CONNECTION FAILED";
