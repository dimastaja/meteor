<?php
error_reporting(E_ALL^E_NOTICE);
if ($_SERVER["HTTP_HOST"]=="meteor.ru"||$_SERVER["HTTP_HOST"]=="www.meteor.ru")
    $db = new mysqli("localhost", "root", "123", "meteor");
else
    $db = new mysqli("localhost", "dimas157_root", "skinhead", "dimas157_meteor");
if (mysqli_connect_errno())
{
    die("Подключение к серверу MySQL невозможно. Код ошибки: %s\n". mysqli_connect_error());
}

?>
