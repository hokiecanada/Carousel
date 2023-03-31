#Drag and Drop File Uploader
A simple template for a file uploader that supports drag and drop for multiple files. It also captures form details that are saved in a corresponding database for sorting/filtering.

##Setup
1. Need a config.php file with the DB connection info. Example:
```
<?php
define('DB_NAME', 'some_database');
define('DB_USER', 'some_user');
define('DB_PASSWORD', 'some_password');
define('DB_HOST', 'localhost');

$db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
?>
```

2. Database table with the following fields:
```
CREATE TABLE `gallery` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `file` varchar(100) DEFAULT '',
  `category` varchar(50) DEFAULT NULL,
  `description` varchar(500) DEFAULT '',
  `datetime` datetime DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=48 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
```

3. Update file type/limit settings based on your server limitations.
gallery.js / lines 14-15
index.php / line 3