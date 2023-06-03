<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
require_once("classes/connect.php");
$action = $_GET["action"];
$action_tour_id = $_POST["action_tour_id"];
$action_discount = $_POST["action_discount"];
$action_date_from = $_POST["action_date_from"];
$action_date_to = $_POST["action_date_to"];
if (isset($_GET["action_id"]))
	$action_id = $_GET["action_id"];
switch ($action)
{
	case "add":
		$stmt = $con->prepare("INSERT INTO actions(action_tour_id, action_discount, action_date_from, action_date_to) VALUES(?,?,?,?)");
		$stmt->bind_param("iiss", $action_tour_id, $action_discount, $action_date_from, $action_date_to);
		$stmt->execute();
		break;
		
	case "edit":
		$stmt = $con->prepare("UPDATE actions SET action_tour_id=?, action_discount=?, action_date_from=?, action_date_to=? WHERE action_id=?");
		$stmt->bind_param("iissi", $action_tour_id, $action_discount, $action_date_from, $action_date_to, $action_id);
		$stmt->execute();
		break;
		
	case "delete":
		$stmt = $con->prepare("DELETE FROM actions WHERE action_id=?");
		$stmt->bind_param("i", $action_id);
		$stmt->execute();
		break;
}
header("Location: admin.php?view=actions");
?>