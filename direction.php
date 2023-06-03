<?php
require_once("classes/connect.php");
?>
<!doctype html>
<html>
<!-- Заголовок документа -->	
<head>
	<meta charset="utf-8">
	<title>Туристическое агентство - Информация о направлении</title>
	<link rel="stylesheet" href="css/styles.css" type="text/css">
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
			<?php
			$direction_id = $_GET["direction_id"];
			$stmt = $con->prepare("SELECT * FROM tours JOIN cities ON tours.tour_city_id=cities.city_id JOIN countries ON cities.city_country_id=countries.country_id WHERE tours.tour_id=?");
			$stmt->bind_param("i", $direction_id);
			$stmt->execute();
			$result = $stmt->get_result();
			$tour = $result->fetch_assoc();
			$stmt = $con->prepare("SELECT photo_filename FROM photos WHERE photo_tour_id=?");
			$stmt->bind_param("i", $direction_id);
			$stmt->execute();
			$photos = $stmt->get_result();
			?>
			<h1 class="centered"><?php echo $tour["country_name"] . ", " . $tour["city_name"]; ?></h1>
			<p>Продолжительность, дней: <?php echo $tour["tour_duration"]; ?></p>
			<p>Категория отеля (количество звезд): <?php echo $tour["tour_hotel_category"]; ?></p>
			<p>Стоимость, руб.: <?php echo $tour["tour_price"]; ?></p>
			<div class="list">
			<ul>
			<?php
			while ($photo = $photos->fetch_assoc())
			{
				?>
				<li>
				  <img src="<?php echo $photo["photo_filename"]; ?>" width="175" height="120" />
    		    </li>	  
			<?php	
			}
			?>
	 	  </ul>
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