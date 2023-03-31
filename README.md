A simple template for a drag and drop file uploader than accepts multiple files along with some form data that gets saved in a corresponding database.

Required setup:
1. Need a config.php file with the DB connection info. Example:
<?php

define('DB_NAME', 'some_database');
define('DB_USER', 'some_user');
define('DB_PASSWORD', 'some_password');
define('DB_HOST', 'localhost');

$db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

?>

2. Database table with the following fields:
id
file (VARCHAR 100)
category (VARCHAR 50)
description (VARCHAR 255)