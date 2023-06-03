<?php
session_start();
require_once("classes/connect.php");
$login = $_POST["login"];
$password = $_POST["password"];
if ($login == "admin")
{
	if ($password == "123")
	{
		$_SESSION["uid"] = 0;
		$_SESSION["can_edit"] = true;
	}
	else
	{
		$_SESSION["login_error"] = true;
	}
}
else
{
	$password_md5 = md5($password);
	$stmt = $con->prepare("SELECT employee_id, employee_password_md5, employee_can_edit FROM employees WHERE employee_login=?");
	$stmt->bind_param("s", $login);
	$stmt->execute();
	$result = $stmt->get_result();
	$row = $result->fetch_assoc();
	if ($row["employee_password_md5"] == $password_md5)
	{
		$_SESSION["uid"] = $row["employee_id"];
		$_SESSION["can_edit"] = $row["can_edit"];
	}
	else
	{
		$_SESSION["login_error"] = true;
	}
}
header("Location: admin.php");
?>