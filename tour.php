<?php
require_once("classes/connect.php"); // модуль подключения к БД
$action = $_GET["action"]; // выполняемое действие
$tour_city_id = $_POST["tour_city_id"]; // id города тура
$tour_hotel_category = $_POST["tour_hotel_category"]; // категория отеля
$tour_duration = $_POST["tour_duration"]; // продолжительность тура
$tour_price = $_POST["tour_price"]; // стоимость тура
if (isset($_GET["tour_id"]))
	$tour_id = $_GET["tour_id"]; // id тура
switch ($action)
{
	case "add": // добавление
		$stmt = $con->prepare("INSERT INTO tours(tour_city_id, tour_hotel_category, tour_duration, tour_price) VALUES(?,?,?,?)"); // текст запроса
		$stmt->bind_param("iiii", $tour_city_id, $tour_hotel_category, $tour_duration, $tour_price); // привязка параметров
		$stmt->execute(); // выпполнение запроса
		$tour_id = $stmt->insert_id; // шid последней добавленной записи
		for ($i = 0; $i < count($_FILES["tour_photo"]["name"]); $i++) // цикл по фото
		{
			$name = $_FILES["tour_photo"]["name"][$i];
			$filename = "./images/tours/" . $name; // формирование имени файлав
			// добавление записи
			$stmt = $con->prepare("INSERT INTO photos(photo_tour_id, photo_filename) VALUES(?,?)");
			$stmt->bind_param("is", $tour_id, $filename);
			$stmt->execute();
			// копирование файла
			$tmp_name = $_FILES["tour_photo"]["tmp_name"][$i];
			move_uploaded_file($tmp_name, $filename);
		}
		break;
		
	case "edit": // редактирование
		$stmt = $con->prepare("UPDATE tours SET tour_city_id=?, tour_hotel_category=?, tour_duration=?, tour_price=? WHERE tour_id=?");
		$stmt->bind_param("iiiii", $tour_city_id, $tour_hotel_category, $tour_duration, $tour_price, $tour_id);
		$stmt->execute();
		for ($i = 0; $i < count($_FILES["tour_photo"]["name"]); $i++)
		{
			$name = $_FILES["tour_photo"]["name"][$i];
			$filename = "./images/tours/" . $name;
			$stmt = $con->prepare("INSERT INTO photos(photo_tour_id, photo_filename) VALUES(?,?)");
			$stmt->bind_param("is", $tour_id, $filename);
			$stmt->execute();
			$tmp_name = $_FILES["tour_photo"]["tmp_name"][$i];
			move_uploaded_file($tmp_name, $filename);
		}
		break;
		
	case "delete": // удаление
		// удаление фото
		$stmt = $con->prepare("DELETE FROM photos WHERE photo_tour_id=?");
		$stmt->bind_param("i", $tour_id);
		$stmt->execute();
		// удаление аккций
		$stmt = $con->prepare("DELETE FROM actions WHERE action_tour_id=?");
		$stmt->bind_param("i", $tour_id);
		$stmt->execute();
		// удаление тура
		$stmt = $con->prepare("DELETE FROM tours WHERE tour_id=?");
		$stmt->bind_param("i", $tour_id);
		$stmt->execute();
}
header("Location: admin.php?view=tours"); // перенаправление на страницу списка туров
?>