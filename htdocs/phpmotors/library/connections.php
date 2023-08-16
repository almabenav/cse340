<?php
require_once 'C:\xampp\htdocs\phpmotors\library\connections.php';

function phpmotorsConnect()
{
    $server = 'localhost';
    $dbname = 'phpmotors';
    $username = 'Admin';
    $password = '7Z_--aHhzrIeiC3B';
    $dsn = "mysql:host=$server;dbname=$dbname";
    $options = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION);

    try {
        $link = new PDO($dsn, $username, $password, $options);
        // if (is_object($link)) {
        //     echo 'It worked!';
        // }
        return $link;
    } catch(PDOException $e) {
        //echo "It didnt work, error: " . $e->getMessage();
        header('Location: /phpmotors/view/500.php');
        exit;
    }
}
phpmotorsConnect();
