<?php
require_once("classes/connect.php");
$action = trim($_GET["action"]);
$city_name = $_POST["city_name"];
$city_country_id = $_POST["city_country_id"];
if (isset($_GET["city_id"]))
	$city_id = $_GET["city_id"];
switch ($action)
{
	case "add":
		$stmt = $con->prepare("INSERT INTO cities(city_name,city_country_id) VALUES(?,?)");
		$stmt->bind_param("si", $city_name, $city_country_id);
		$stmt->execute();
		break;
		
	case "edit":
		$stmt = $con->prepare("UPDATE cities SET city_name=?, city_country_id=? WHERE city_id=?"); 
		$stmt->bind_param("sii", $city_name, $city_country_id, $city_id);
		$stmt->execute();
		break;
		
	case "delete":
		$stmt = $con->prepare("SELECT COUNT(*) FROM tours WHERE tour_city_id=?");
		$stmt->bind_param("i", $city_id);
		$stmt->execute();
		$result = $stmt->get_result();
		$count = $result->fetch_assoc()[0];
		if ($count > 0):
		?>
		<script>
			alert("Невозможно удалить город");
		</script>
		<?php
		else:
		{
			$stmt = $con->prepare("DELETE FROM cities WHERE city_id=?");
			$stmt->bind_param("i", $city_id);
			$stmt->execute();
		}
		endif;
		break;
}
header("Location: admin.php?view=cities");
?>