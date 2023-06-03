<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
require_once("classes/connect.php");
$action = $_GET["action"];
$employee_fio = $_POST["employee_fio"];
$employee_position_id = $_POST["employee_position_id"];
$employee_can_edit = ($_POST["employee_can_edit"] == "on")? 1:0; 
$employee_login = $_POST["employee_login"];
if (!empty($_POST["employee_password"]))
	$employee_password_md5 = md5($_POST["employee_password"]);
if (!empty($_FILES))
	if (substr($_FILES["employee_photo"]["type"], 0, 5) == "image")
	{
		$employee_photo_filename = "./images/employees/" . $_FILES["employee_photo"]["name"];
		$tmp_name = $_FILES["employee_photo"]["tmp_name"];
		move_uploaded_file($tmp_name, $employee_photo_filename);
	}
if (isset($_GET["employee_id"]))
	$employee_id = $_GET["employee_id"];
switch ($action)
{
	case "add":
		$stmt = $con->prepare("INSERT INTO employees(employee_fio, employee_position_id, employee_can_edit, employee_photo_filename, employee_login, employee_password_md5) VALUES(?,?,?,?,?,?)");
		$stmt->bind_param("siisss", $employee_fio, $employee_position_id, $employee_can_edit, $employee_photo_filename, $employee_login, $employee_password_md5);
		$stmt->execute();
		break;
		
	case "edit":
		$sql = "UPDATE employees SET employee_fio=?, employee_position_id=?, employee_can_edit=?, ";
		if (!empty($employee_photo_filename))
			$sql .= "employee_photo_filename=?, ";
		$sql .= "employee_login=?";
		if (isset($employee_password_md5))
			$sql .= ", employee_password_md5=?";
		$sql .= " WHERE employee_id=?";
		$stmt = $con->prepare($sql);
		if (isset($employee_password_md5))
			if (!empty($employee_photo_filename))
				$stmt->bind_param("siisssi", $employee_fio, $employee_position_id, $employee_can_edit, $employee_photo_filename, $employee_login, $employee_password_md5, $employee_id);
			else
				$stmt->bind_param("siissi", $employee_fio, $employee_position_id, $employee_can_edit, $employee_login, $employee_password_md5, $employee_id);
		else
			if (!empty($employee_photo_filename))
				$stmt->bind_param("siissi", $employee_fio, $employee_position_id, $employee_can_edit, $employee_photo_filename, $employee_login, $employee_id);
			else
				$stmt->bind_param("siisi", $employee_fio, $employee_position_id, $employee_can_edit, $employee_login, $employee_id);
		$stmt->execute();
		break;
		
	case "delete":
		$stmt = $con->prepare("DELETE FROM employees WHERE employee_id=?");
		$stmt->bind_param("i", $employee_id);
		$stmt->execute();
		break;
}
header("Location: admin.php?view=employees");
?>