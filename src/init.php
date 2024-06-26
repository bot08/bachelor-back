<?php
$dbPath = 'database/index.db';

if (file_exists($dbPath)) {
    echo "Firstly, delete the current database for reinitialization";
    exit;
    //unlink($dbPath);
}

// CLEAR IMAGES
$files = glob("database/images" . "/*.png");
foreach ($files as $file) {
    if (is_file($file)) {
        unlink($file);
    }
}

$db = new SQLite3($dbPath);

// CREATE TABLES
$sql = file_get_contents('../sql-raw/create.sql');
$db->exec($sql);

// ADD ADMIN USER
$db->exec("
INSERT INTO Users (LastLogin, Token, RoleID, Username, Password, Email, FullName) 
VALUES 
    ('2024-05-07 12:00:00', '".bin2hex(random_bytes(32))."', 2, 'admin', '".password_hash('admin123', PASSWORD_DEFAULT)."', 'admin@admin.com', 'Admin User'),
    ('2024-05-07 12:00:00', '".bin2hex(random_bytes(32))."', 1, 'mod', '".password_hash('mod123', PASSWORD_DEFAULT)."', 'mod@admin.com', 'Mod User');
");

// ADD EXAMPLE SUNGLASSES
$sql = file_get_contents('../sql-raw/sunglasses.sql');
$db->exec($sql);

// ADD EXAMPLE ACCESSORIES
$sql = file_get_contents('../sql-raw/accessories.sql');
$db->exec($sql);

// ADD EXAMPLE FRAMES
$sql = file_get_contents('../sql-raw/frames.sql');
$db->exec($sql);

// ADD EXAMPLE LENSES
$sql = file_get_contents('../sql-raw/lenses.sql');
$db->exec($sql);

// DONE
echo 'Database initialization was successful';