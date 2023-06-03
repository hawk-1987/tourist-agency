<?php
require_once("classes/connect.php");
$action = trim($_GET["action"]);
$country_name = $_POST["country_name"];
if (isset($_GET["country_id"]))
	$country_id = $_GET["country_id"];
switch ($action)
{
	case "add":
		$stmt = $con->prepare("INSERT INTO countries(country_name) VALUES(?)");
		$stmt->bind_param("s", $country_name);
		$stmt->execute();
		break;
	
	case "edit":
		$stmt = $con->prepare("UPDATE countries SET country_name=? WHERE country_id=?");
		$stmt->bind_param("si", $country_name, $country_id);
		$stmt->execute();
		break;
		
	case "delete":
		$stmt = $con->prepare("SELECT COUNT(*) FROM cities WHERE city_country_id=?");
		$stmt->bind_param("i", $country_id);
		$stmt->execute();
		$result = $stmt->get_result();
		$count = $result->fetch_assoc()[0];
		if ($count > 0):
		?>
		<script>
			alert("Невозможно удалить страну");
		</script>
		<?php
		else:
		{
			$stmt = $con->prepare("DELETE FROM countries WHERE country_id=?");
			$stmt->bind_param("i", $country_id);
			$stmt->execute();
		}
		endif;
		break;
}
header("Location: admin.php?view=countries");
?>