<?php
require_once("classes/connect.php"); // модуль подключения к БД
?>
<!doctype html>
<html>
<!-- Заголовок документа -->	
<head>
	<meta charset="utf-8">
	<title>Туристическое агентство - Наши сотрудники</title>
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
			<h1 class="centered">Наши сотрудники</h1>
			<p>Выбрать тур и оформить все необходимые для путешествия документы Вам помогут наши сотрудники</p>
			<div class="list">
				<ul>
					<?php
					$employees = $con->query("SELECT * FROM employees JOIN positions ON employees.employee_position_id=positions.position_id"); // запрос на выбор информмации о сотрудниках 
					while ($employee = $employees->fetch_assoc()) // цикл вывода информации о сотрудниках
					{
						?>
						<li>
							<img src="<?php echo $employee["employee_photo_filename"]; ?>" width="175" height="160">
							<p class="list_header"><?php echo $employee["employee_fio"]; ?></p>
							<p class="list_description"><?php echo $employee["position_name"]; ?></p>
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