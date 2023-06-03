<?php
require_once("classes/connect.php");
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
			<h1 class="centered">Направления</h1>
			<div id="search_form">
				<form method="get" action="directions.php#search_form" autocomplete="on">
					<ul>
						<li>
							<label for="tour_country_id">Страна:</label>
							<select name="tour_country_id" onChange="getCitiesList()">
								<option value="">--Выберите страну--</option>
								<?php
								$countries = $con->query("SELECT * FROM countries ORDER BY country_name");
								while ($country = $countries->fetch_assoc())
								{
									?>
									<option value="<?php echo $country["country_id"]; ?>" <?php if ($country["country_id"] == $_GET["tour_country_id"]): ?> selected="selected" <?php endif; ?>><?php echo $country["country_name"]; ?></option>
									<?php
								}
								?>
							</select>
						</li>
						<li>
							<label for="tour_city_id">Город:</label>
							<select name="tour_city_id">
								<option value="">--Выберите город--</option>
								<?php
								if (isset($_GET["tour_country_id"]))
								{
									$stmt = $con->prepare("SELECT * FROM cities WHERE city_country_id=? ORDER BY city_name");
									$stmt->bind_param("i", $_GET["tour_country_id"]);
									$stmt->execute();
									$cities = $stmt->get_result();
									while ($city = $cities->fetch_assoc())
									{
										?>
										<option value="<?php echo $city["city_id"]; ?>" <?php if ($city["city_id"] == $_GET["tour_city_id"]): ?> selected="selected" <?php endif; ?>><?php echo $city["city_name"]; ?></option> 
										<?php
									}
								}
								?>
							</select>
						</li>
						<li>
							<label for="tour_hotel_category">Категория отеля (звезд):</label>
							<select name="tour_hotel_category">
								<option value="">--Выберите категорию отеля--</option>
								<?php
								for ($i = 1; $i <= 5; $i++)
								{
									?>
									<option value="<?php echo $i; ?>" <?php if ($i == $_GET["tour_hotel_category"]): ?> selected="selected" <?php endif; ?>><?php echo $i; ?></option>
									<?php
								}
								?>
							</select>							
						</li>
						<li>
							<label for="tour_price_from">Стоимость от</label>
							<input type="number" name="tour_price_from" value="<?php echo $_GET["tour_price_from"]; ?>">
							<label for="tour_price_to">до</label>
							<input type="number" name="tour_price_to" value="<?php echo $_GET["tour_price_to"]; ?>">
						</li>
						<li>
							<input type="submit" value="Найти"> 
						</li>
					</ul>
				</form>
			</div>
			<div class="list">
				<ul>
					<?php
					$sql = "SELECT * FROM tours JOIN cities ON tours.tour_city_id=cities.city_id JOIN countries ON cities.city_country_id=countries.country_id"; // текст запроса
					$where = array(); // массив условий
					// добавление условий
					if (!empty($_GET["tour_country_id"]))
					{
						$where[] = "cities.city_country_id=" . $_GET["tour_country_id"];
					}
					if (!empty($_GET["tour_city_id"]))
					{
						$where[] = "tours.tour_city_id=" . $_GET["tour_city_id"];
					}
					if (!empty($_GET["tour_hotel_category"]))
					{
						$where[] = "tours.tour_hotel_category=" . $_GET["tour_hotel_category"];
					}
					if (!empty($_GET["tour_price_from"]))
					{
						$where[] = "tours.tour_price>=" . $_GET["tour_price_from"];
					}
					if (!empty($_GET["tour_price_to"]))
					{
						$where[] = "tours.tour_price<=" . $_GET["tour_price_to"];
					}
					// сборка запроса
					if (count($where))
						$sql .= " WHERE " . implode(" AND ", $where);
					$tours = $con->query($sql); // выполнение запроса
					if ($tours->num_rows == 0): // если туры не найдены - вывод уведомления
					?>
					<p class="centered">Поиск не дал результатов</p>
					<?php
					else: // иначе вывод найденных туров
						while ($tour = $tours->fetch_assoc())
						{
							?>
						   <a href="direction.php?direction_id=<?php echo $tour["tour_id"]; ?>">
							<li>
								<?php
								 $stmt = $con->prepare("SELECT photo_filename FROM photos WHERE photo_tour_id=? LIMIT 1");
								 $stmt->bind_param("i", $tour["tour_id"]);
								 $stmt->execute();
								 $result = $stmt->get_result();
								 $photo_filename = $result->fetch_assoc()["photo_filename"];
								?>
								<img src="<?php echo $photo_filename; ?>" width="175" height="120">
								<p class="list_header"><?php echo $tour["country_name"] . ", " . $tour["city_name"]; ?></p>
								<p class="list_description">Категория отеля (звезд): <?php echo $tour["tour_hotel_category"]; ?></p>
								<p class="list_description"><?php echo $tour["tour_price"]; ?> руб.</p>
							</li> 
						</a>   
						<?php
						}
						endif;
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