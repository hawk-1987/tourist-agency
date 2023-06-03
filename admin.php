<?php
session_start(); 
require_once("classes/connect.php");
require_once("classes/AdminInterface.php");
setlocale(LC_ALL, "ru_RU");
?>
<!doctype html>
<html>
<!-- Заголовок документа -->	
<head>
	<meta charset="utf-8">
	<title>Туристическое агентство - Администрирование</title>
	<link rel="stylesheet" href="css/styles.css" type="text/css">
	<script src="js/scripts.js"></script>
</head>

<!-- Тело документа -->
<body>
	<!-- Основной контейнер -->
	<div id="wrap">
		<!-- Меню --> 
		<nav class="row" role="navigation">
			<?php if (isset($_SESSION["uid"])):
			        if ($_SESSION["uid"] == 0): ?>
			<a href="admin.php?view=positions">Должности</a>
			<a href="admin.php?view=employees">Сотрудники</a>
			<a href="admin.php?view=countries">Страны</a>
			<a href="admin.php?view=cities">Города</a>
			<?php endif; ?>
			<a href="admin.php?view=tours">Туры</a>
			<a href="admin.php?view=actions">Акции</a>
			<?php endif; ?>
		</nav>
		<!-- Основное содержимое --> 
		<main class="row" role="main">
			<?php if ($_SESSION["login_error"] == true): ?>
			  <p class="error_box">Неверный логин или пароль</p>
			<?php endif; ?>
			<?php if (!isset($_SESSION["uid"]) || $_SESSION["login_error"] == true): ?>
			 <div class="form__wrapper">
			  <form method="post" action="login.php" autocomplete="on">
				  <h1 class="centered">Авторизация</h1>
				  <ul>
				  <li class="form__line">
					  <label for="login" data-icon="u">Логин:</label>
					  <input type="text" name="login">
				  </li>
				  <li class="form__line">
					  <label for="password" data-icon="p">Пароль:</label>
					  <input type="password" name="password">
				  </li>
				  <li class="form__line">
					  <input type="submit" value="Войти">
				  </li>
				  </ul>	  
			  </form>
			</div>	 
			<?php
			 else:
			 	 AdminInterface::DisplayContent($con);
			 endif;
			?>
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