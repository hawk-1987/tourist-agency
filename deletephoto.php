<?php
require_once("classes/connect.php");
$photo_id = $_GET["photo_id"];
$stmt = $con->prepare("SELECT photo_tour_id FROM photos WHERE photo_id=?");
$stmt->bind_param("i", $photo_id);
$stmt->execute();
$result = $stmt->get_result();
$tour_id = $result->fetch_assoc();
$stmt = $stmt->prepare("DELETE FROM photos WHERE photo_id=?");
$stmt->bind_param("i", $photo_id);
$stmt->execute();
header("Location: admin.php?view=tour&action=edit&tour_id=".$tour_id);
?>