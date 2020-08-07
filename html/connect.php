<?php
$db_server = 'localhost';
$db_user = 'isma';
$db_password = 'password';
$db_name = 'devices';
$mysqli = new mysqli($db_server,$db_user,$db_password,$db_name);
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}
// Устанавливаем кодировку подключения
$mysqli->set_charset('utf8');
?>