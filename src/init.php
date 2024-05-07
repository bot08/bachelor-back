<?php
$dbPath = 'database/index.db';

if (file_exists($dbPath)) {
    unlink($dbPath);
    //exit;
}

$db = new SQLite3($dbPath);

$sql = file_get_contents('../sql-raw/create.sql');
$db->exec($sql);

$db->exec("
INSERT INTO Users (LastLogin, Token, RoleID, Username, Password, Email, FullName) 
VALUES ('2024-05-07 12:00:00', '".bin2hex(random_bytes(32))."', 2, 'admin', '".password_hash('admin123', PASSWORD_DEFAULT)."', 'admin@admin.com', 'Admin User');
");
