<?php
$host = "127.0.0.1:3306";
$db = "tourist_agency";
$user = "root";
$password = "root";
$con = new mysqli($host, $user, $password, $db);
if ($con->connect_error)
{
	die("Ошибка подключения к БД: ".$con->connect_error);
}
?>