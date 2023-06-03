<?php
require_once("classes/connect.php");
$action = trim($_GET["action"]);
if (isset($_GET["position_id"])) 
	$position_id = $_GET["position_id"];
$position_name = $_POST["position_name"];
switch ($action)
{
	case "add":
		$stmt = $con->prepare("INSERT INTO positions(position_name) VALUES(?)");
		$stmt->bind_param("s", $position_name);
		$stmt->execute();
		break;
	
	case "edit":
		$stmt = $con->prepare("UPDATE positions SET position_name=? WHERE position_id=?");
		$stmt->bind_param("si", $position_name, $position_id);
		$stmt->execute();
		break;
	
	case "delete":
		$stmt = $con->prepare("SELECT COUNT(*) FROM employees WHERE employee_position_id=?");
		$stmt->bind_param("i", $position_id);		
		$stmt->execute();
		$result = $stmt->get_result();
		$count = $result->fetch_assoc()[0];
		if ($count > 0):
		?>
        <script>
			alert("Невозможно удалить должность");
		</script>
        <?php
		else:
		{
			$stmt = $con->prepare("DELETE FROM positions WHERE position_id=?");
			$stmt->bind_param("i", $position_id);		
			$stmt->execute();
		}
		endif;
		break;
}
header("Location: admin.php?view=positions");
?>