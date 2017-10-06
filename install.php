<?php
require_once 'config.php'; // Configuration file for turning error reporting and connection strings to database:

/*
 * I think the following is pretty self explanatory and the index.php file helps you on how to insert and read 
 * data into a database table better.
 */
try {
    $conn = new PDO('mysql:host=' . DATABASE_HOST, DATABASE_USERNAME, DATABASE_PASSWORD);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "CREATE DATABASE IF NOT EXISTS myCMS";
    $conn->exec($sql);
    $sql = "use myCMS";
    $conn->exec($sql);
    $sql = "CREATE TABLE IF NOT EXISTS myBlog (
                ID int(11) AUTO_INCREMENT PRIMARY KEY,
                title varchar(30) NOT NULL,
                comment text NOT NULL,
                date_added datetime NOT NULL DEFAULT '0000-00-00 00:00:00')";
    $conn->exec($sql);
    echo "DB created successfully";
} catch (PDOException $e) {
    echo $sql . "<br>" . $e->getMessage();
}
