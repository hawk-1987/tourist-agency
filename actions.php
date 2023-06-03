<?php
require_once("classes/connect.php");

function print_date($date)
{
	$month = [
		1  => 'января',
		2  => 'февраля',
		3  => 'марта',
		4  => 'апреля',
		5  => 'мая', 
		6  => 'июня',
		7  => 'июля',
		8  => 'августа',
		9  => 'сентября',
		10 => 'октября',
		11 => 'ноября',
		12 => 'декабря'
	];
	
	$date = strtotime($date);
	echo date('d', $date) . " " . $month[date('n', $date)] . " " . date('Y', $date);
}
?>
<!doctype html>
<html>
<!-- Заголовок документа -->	
<head>
	<meta charset="utf-8">
	<title>Туристическое агентство - Направления</title>
	<link rel="stylesheet" href="css/styles.css" type="text/css">
	<script src="js/scripts.js"></script>
</head>

<!-- Тело документа -->
<body>
	<!-- Основной контейнер -->
	<div id="wrap">
		<!-- Меню --> 
		<nav class="row" role="navigation">
			<a href="index.php">Главная</a>
			<a href="personal.php">Наши сотрудники</a>
			<a href="directions.php">Направления</a>
			<a href="actions.php">Акции</a>
			<a href="contacts.php">Контакты</a>
		</nav>
		<!-- Баннер -->
		<header class="row" role="banner"><p>Сайт<br>туристического<br>агентства</p></header>
		<!-- Основное содержимое --> 
		<main class="row" role="main">
			<h1 class="centered">Действующие акции</h1>
			<div class="list">
				<ul>
					<?php
					$actions = $con->query("SELECT * FROM actions JOIN tours ON actions.action_tour_id=tours.tour_id JOIN cities ON tours.tour_city_id=cities.city_id JOIN countries ON cities.city_country_id=countries.country_id WHERE actions.action_date_to>=current_date()");
					while ($action = $actions->fetch_assoc())
					{
						?>
						<li>
								<?php
								 $stmt = $con->prepare("SELECT photo_filename FROM photos WHERE photo_tour_id=? LIMIT 1");
								 $stmt->bind_param("i", $action["tour_id"]);
								 $stmt->execute();
								 $result = $stmt->get_result();
								 $photo_filename = $result->fetch_assoc()["photo_filename"];
								?>
								<img src="<?php echo $photo_filename; ?>" width="175" height="120">
								<p class="list_header"><?php echo $action["country_name"] . ", " . $action["city_name"]; ?></p>
								<p class="list_description">Категория отеля (звезд): <?php echo $action["tour_hotel_category"]; ?></p>
								<p class="list_description"><?php echo $action["tour_price"]; ?> руб.</p>
								<p class="list_description">Скидка: <?php echo $action["action_discount"]; ?>%</p>
								<p class="list_description">Срок действия: с <?php print_date($action["action_date_from"]); ?> по <?php print_date($action["action_date_to"]); ?></p> 
						</li>
					<?php
					}
					?>
				</uul>
			</div>
		</main>
		<!-- Футер -->
		<footer class="row" role="contentinfo">
		  Туристическое агентство
			<br>
			&copy;
			Все права защищены
		</footer>
	</div>
</body>
</html>