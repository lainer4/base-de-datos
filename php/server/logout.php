<?php
session_unset();
session_destroy();
header("Location: ../client/index.html");
die();
?>
