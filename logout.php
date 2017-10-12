<?php

require_once 'lib/includes/config.php'; // Configuration file for turning error reporting and connection strings to database:
session_unset();
session_destroy();

header("Location: index.php");
exit();

