<?php
  class AdminInterface
  {
	  public static function DisplayContent($connection)
	  {
		  switch ($_GET["view"])
		  {
			  case "positions":
				  AdminInterface::Positions($connection);
				  break;
				  
			  case "position":
				  AdminInterface::Position($connection);
				  break;
				  
			  case "employees":
				  AdminInterface::Employees($connection);
				  break;
				  
			  case "employee":
				  AdminInterface::Employee($connection);
				  break;
			
			  case "countries":
				  AdminInterface::Countries($connection);
				  break;
				  
			  case "country":
				  AdminInterface::Country($connection);
				  break;
			 
			  case "cities":
				  AdminInterface::Cities($connection);
				  break;
				  
			  case "city":
				  AdminInterface::City($connection);
				  break;
				  
			  case "tours":
				  AdminInterface::Tours($connection);
				  break;
				  
			  case "tour":
				  AdminInterface::Tour($connection);
				  break;
				  
			  case "actions":
				  AdminInterface::Actions($connection);
				  break;
				  
			  case "action":
				  AdminInterface::Action($connection);
				  break;
		  }
	  }
	  
	  static function Positions($connection)
	  {
		  $result = $connection->query("SELECT * FROM positions");
		  ?>
           <h1 class="centered">Должности</h1>  
           <table>
			  <tr>
				  <th>Должность</th>
				  <th>Действия</th>
			  </tr>
			   <?php
			  while ($row = $result->fetch_assoc())
			  {
				  ?>
				   <tr>
					   <td><?php echo $row["position_name"]; ?></td>
					   <td>
						   <a href="admin.php?view=position&action=edit&position_id=<?php echo $row["position_id"]; ?>">Изменить</a>
						   <a href="position.php?action=delete&position_id=<?php echo $row["position_id"]; ?>">Удалить</a>
					   </td>
				   </tr>
				  <?php
			  }
		    ?>
           </table>
		    <button onClick="addPosition()">Добавить</button>	
           <?php 
	  }
	  
	  static function Position($connection)
	  {
		  $action = $_GET["action"];
		  if ($action == "edit")
		  {
			  $position_id = $_GET["position_id"];
			  $stmt = $connection->prepare("SELECT * FROM positions WHERE position_id = ?");
			  $stmt->bind_param("i", $position_id);
			  $stmt->execute();
			  $result = $stmt->get_result();
			  $row = $result->fetch_assoc();
		  }
		  ?>
			<div class="form__wrapper">
			   <form method="post" action="position.php?action=<?php echo $action; if ($action == "edit"): ?>&position_id=<?php echo $position_id; endif; ?>">
				   <h1 class="centered">Должность</h1>
				   <ul>
					   <li class="form__line">
						   <label for="position_name">Должность:</label>
						   <input type="text" name="position_name" <?php if ($action == "edit"): ?> value="<?php echo $row["position_name"]; endif; ?>"></input>
					   </li>
					   <li class="form__line">
						   <input type="submit" value="Сохранить">
					   </li>
				   </ul>
			   </form>
		    </div>
          <?php
	  }
	  
	  static function Employees($connection)	  
	  {
		  $result = $connection->query("SELECT * FROM employees JOIN positions ON employees.employee_position_id=positions.position_id ORDER BY employees.employee_fio");
		  ?>
           <h1 class="centered">Сотрудники</h1>  
           <table>
			  <tr>
				  <th>ФИО</th>
				  <th>Должность</th>
				  <th>Может редактировать информацию</th>
				  <th>Логин</th>
				  <th>Действия</th>
			  </tr>
			   <?php
			  while ($row = $result->fetch_assoc())
			  {
				  ?>
				   <tr>
					   <td><?php echo $row["employee_fio"]; ?></td>
					   <td><?php echo $row["position_name"]; ?></td>
					   <td><?php $can_edit = ($row["employee_can_edit"] == true)? "Да":"Нет"; echo $can_edit; ?></td>
					   <td><?php echo $row["employee_login"]; ?></td>
					   <td>
						   <a href="admin.php?view=employee&action=edit&employee_id=<?php echo $row["employee_id"]; ?>">Изменить</a>
						   <a href="employee.php?action=delete&employee_id=<?php echo $row["employee_id"]; ?>">Удалить</a>
					   </td>
				   </tr>
				  <?php
			  }
		    ?>
           </table>
		    <button onClick="addEmployee()">Добавить</button>	
           <?php
	  }
	  
	  static function Employee($connection)
	  {
		  $action = $_GET["action"];
		  $positions = $connection->query("SELECT * FROM positions ORDER BY position_name");
		  if ($action == "edit")
		  {
			  $employee_id = $_GET["employee_id"];
			  $stmt = $connection->prepare("SELECT * FROM employees WHERE employee_id=?");
			  $stmt->bind_param("i", $employee_id);
			  $stmt->execute();
			  $result = $stmt->get_result();
			  $row = $result->fetch_assoc();
		  }
		  ?>
			<div class="form__wrapper">
			   <form method="post" action="employee.php?action=<?php echo $action; if ($action == "edit"): ?>&employee_id=<?php echo $employee_id; endif; ?>" enctype="multipart/form-data">
				   <h1 class="centered">Сотрудник</h1>
				   <ul>
					   <li class="form__line">
						   <label for="employee_fio">ФИО:</label>
						   <input type="text" name="employee_fio" <?php if ($action == "edit"): ?> value="<?php echo $row["employee_fio"]; endif; ?>">
					   </li>
					   <li class="form__line">
						   <label for="employee_position_id">Должность:</label>
						   <select name="employee_position_id">
							   <option>--Выберите должность--</option>
							   <?php
		  						while ($position = $positions->fetch_assoc())
								{
									?>
							        <option value="<?php echo $position["position_id"]; ?>" <?php if ($position["position_id"] == $row["employee_position_id"]): ?> selected="selected" <?php endif; ?>><?php echo $position["position_name"]; ?></option>
							   		<?php
								}
		  					   ?>	
						   </select>
				       </li>
					   <li class="form__line">
						   <label for="employee_can_edit">Может редактировать информацию:</label>
						   <input type="checkbox" name="employee_can_edit" <?php if ($row["employee_can_edit"] == true): ?> checked <?php endif; ?>>
				       </li>
					   <li class="form__line">
						  <label for="employee_photo">Фото:</label>
						  <?php if ($action == "edit"): ?>
						   <img src="<?php echo $row["employee_photo_filename"]; ?>" width="175" height="160">
						  <?php endif; ?> 
						  <input type="file" name="employee_photo"> 
					   </li>
					   <li class="form__line">
						   <label for="employee_login">Логин:</label>
						   <input type="text" name="employee_login" value="<?php echo $row["employee_login"]; ?>">
					   </li>
					   <li class="form__line">
						   <label for="employee_password">Пароль:</label>
						   <input type="password" name="employee_password">
                       </li>
					   <li class="form__line">
						   <input type="submit" value="Сохранить">
					   </li>
				   </ul>
			   </form>
		    </div>
          <?php
	  }
		  
	  static function Countries($connection)
	  {
		  $result = $connection->query("SELECT * FROM countries ORDER BY country_name");
		  ?>
           <h1 class="centered">Страны</h1>  
           <table>
			  <tr>
				  <th>Страна</th>
				  <th>Действия</th>
			  </tr>
			   <?php
			  while ($row = $result->fetch_assoc())
			  {
				  ?>
				   <tr>
					   <td><?php echo $row["country_name"]; ?></td>
					   <td>
						   <a href="admin.php?view=country&action=edit&country_id=<?php echo $row["country_id"]; ?>">Изменить</a>
						   <a href="country.php?action=delete&country_id=<?php echo $row["country_id"]; ?>">Удалить</a>
					   </td>
				   </tr>
				  <?php
			  }
		    ?>
           </table>
		    <button onClick="addCountry()">Добавить</button>	
           <?php
	  }
	  
	  static function Country($connection)
	  {
		  $action = $_GET["action"];
		  if ($action == "edit")
		  {
			  $country_id = $_GET["country_id"];
			  $stmt = $connection->prepare("SELECT * FROM countries WHERE country_id = ?");
			  $stmt->bind_param("i", $country_id);
			  $stmt->execute();
			  $result = $stmt->get_result();
			  $row = $result->fetch_assoc();
		  }
		  ?>
			<div class="form__wrapper">
			   <form method="post" action="country.php?action=<?php echo $action; if ($action == "edit"): ?>&country_id=<?php echo $country_id; endif; ?>">
				   <h1 class="centered">Страна</h1>
				   <ul>
					   <li class="form__line">
						   <label for="country_name">Страна:</label>
						   <input type="text" name="country_name" <?php if ($action == "edit"): ?> value="<?php echo $row["country_name"]; endif; ?>"></input>
					   </li>
					   <li class="form__line">
						   <input type="submit" value="Сохранить">
					   </li>
				   </ul>
			   </form>
		    </div>
          <?php
	  }
	  
	  static function Cities($connection)
	  {
		  $result = $connection->query("SELECT * FROM cities JOIN countries ON cities.city_country_id=countries.country_id ORDER BY city_name");
		  ?>
           <h1 class="centered">Города</h1>  
           <table>
			  <tr>
				  <th>Город</th>
				  <th>Страна</th>
				  <th>Действия</th>
			  </tr>
			   <?php
			  while ($row = $result->fetch_assoc())
			  {
				  ?>
				   <tr>
					   <td><?php echo $row["city_name"]; ?></td>
					   <td><?php echo $row["country_name"]; ?></td>
					   <td>
						   <a href="admin.php?view=city&action=edit&city_id=<?php echo $row["city_id"]; ?>">Изменить</a>
						   <a href="city.php?action=delete&city_id=<?php echo $row["city_id"]; ?>">Удалить</a>
					   </td>
				   </tr>
				  <?php
			  }
		    ?>
           </table>
		    <button onClick="addCity()">Добавить</button>	
           <?php
	  }
	  
	  static function City($connection)
	  {
		  $action = $_GET["action"];
		  $countries = $connection->query("SELECT * FROM countries ORDER BY country_name");
		  if ($action == "edit")
		  {
			  $city_id = $_GET["city_id"];
			  $stmt = $connection->prepare("SELECT * FROM cities JOIN countries ON cities.city_country_id=countries.country_id WHERE cities.city_id = ?");
			  $stmt->bind_param("i", $city_id);
			  $stmt->execute();
			  $result = $stmt->get_result();
			  $row = $result->fetch_assoc();
		  }
		  ?>
			<div class="form__wrapper">
			   <form method="post" action="city.php?action=<?php echo $action; if ($action == "edit"): ?>&city_id=<?php echo $city_id; endif; ?>">
				   <h1 class="centered">Город</h1>
				   <ul>
					   <li class="form__line">
						   <label for="city_name">Город:</label>
						   <input type="text" name="city_name" <?php if ($action == "edit"): ?> value="<?php echo $row["city_name"]; endif; ?>"></input>
					   </li>
					   <li class="form__line">*
						   <label for="city_country_id">Страна:</label>
						   <select name="city_country_id">
							   <option>--Выберите страну--</option>
							   <?php
		  						while ($country = $countries->fetch_assoc())
								{
									?>
							   		<option value="<?php echo $country["country_id"]; ?>" <?php if ($country["country_id"] == $row["city_country_id"]): ?> selected="selected" <?php endif; ?>><?php echo $country["country_name"]; ?></option>
							        <?php
								}
		  					   ?>	
						   </select>
					   </li>
					   <li class="form__line">
						   <input type="submit" value="Сохранить">
					   </li>
				   </ul>
			   </form>
		    </div>
          <?php
	  }
	  
	  static function Tours($connection)
	  {
		  $result = $connection->query("SELECT * FROM tours JOIN cities ON tours.tour_city_id=cities.city_id JOIN countries ON cities.city_country_id=countries.country_id");
		  ?>
           <h1 class="centered">Туры</h1>  
           <table>
			  <tr>
				  <th>Страна</th>
				  <th>Город</th>
				  <th>Категория отеля (количество звезд)</th>
				  <th>Продолжительность, дней</th>
				  <th>Стоимость, руб.</th>
				  <?php if ($_SESSION["can_edit"] == true): ?>
				  <th>Действия</th>
				  <?php endif; ?>
			  </tr>
			   <?php
			  while ($row = $result->fetch_assoc())
			  {
				  ?>
				   <tr>
					   <td><?php echo $row["country_name"]; ?></td>
					   <td><?php echo $row["city_name"]; ?></td>
					   <td><?php echo $row["tour_hotel_category"]; ?></td>
					   <td><?php echo $row["tour_duration"]; ?></td>
					   <td><?php echo $row["tour_price"]; ?></td>
					   <?php if ($_SESSION["can_edit"] == true): ?>
					   <td>
						   <a href="admin.php?view=tour&action=edit&tour_id=<?php echo $row["tour_id"]; ?>">Изменить</a>
						   <a href="tour.php?action=delete&tour_id=<?php echo $row["tour_id"]; ?>">Удалить</a>
					   </td>
					   <?php endif; ?>
				   </tr>
				  <?php
			  }
		    ?>
           </table>
			<?php if ($_SESSION["can_edit"] == true): ?>
		    <button onClick="addTour()">Добавить</button>	
           <?php
		    endif;
	  }
	  
	  static function Tour($connection)
	  {
		  $action = $_GET["action"];
		  $countries = $connection->query("SELECT * FROM countries ORDER BY country_name");
		  if ($action == "edit")
		  {
			  $tour_id = $_GET["tour_id"];
			  $stmt = $connection->prepare("SELECT * FROM tours JOIN cities ON tours.tour_city_id=cities.city_id JOIN countries ON cities.city_country_id=countries.country_id WHERE tours.tour_id=?");
			  $stmt->bind_param("i", $tour_id);
			  $stmt->execute();
			  $result = $stmt->get_result();
			  $row = $result->fetch_assoc();
		  }
		  ?>
			<div class="form__wrapper">
			   <form method="post" action="tour.php?action=<?php echo $action; if ($action == "edit"): ?>tour_id=<?php echo $tour_id; endif; ?>" enctype="multipart/form-data">
				   <h1 class="centered">Тур</h1>
				   <ul>
					   <li class="form__line">
						   <label for="tour_country_id">Страна:</label>
						   <select name="tour_country_id" onChange="loadCities(<?php echo "'".$action."'"; ?>)">
							   <option>--Выберите страну--</option>
							   <?php
		  						while ($country = $countries->fetch_assoc())
								{
									?>
							   		<option value="<?php echo $country["country_id"]; ?>" <?php if ($country["country_id"] == $row["city_country_id"] || $country["country_id"] == $_GET["country_id"]): ?> selected="selected" <?php endif; ?>><?php echo $country["country_name"]; ?></option>
							        <?php
								}
		  					   ?>	
						   </select>
					   </li>
					   <?php 
		  				if ($action == "edit" && !isset($_GET["country_id"])):
		  				?>
					   <script>
						   window.location = "admin.php?view=tour&action=edit&tour_id=<?php echo $tour_id; ?>&country_id=" + <?php echo $row["city_country_id"]; ?>
					   </script>
					    <?
		  				endif;
		  				if (isset($_GET["country_id"]))
						{
							$stmt = $connection->prepare("SELECT * FROM cities WHERE city_country_id=?");
							$stmt->bind_param("i", $_GET["country_id"]);
							$stmt->execute();
							$cities = $stmt->get_result();
							?>
					         <li class="form__line">
								 <label for="tour_city_id">Город:</label>
								 <select name="tour_city_id">
									 <option>--Выберите город--</option>
									 <?php
									 while ($city = $cities->fetch_assoc())
									 {
										 ?>
									 	 <option value="<?php echo $city["city_id"]; ?>" <?php if ($city["city_id"] == $row["tour_city_id"]): ?> selected="selected" <?php endif; ?>><?php echo $city["city_name"]; ?></option>
									     <?php 
									 }
									 ?>
								 </select>
					   		 </li>
					   		 <li class="form__line">
								 <label for="tour_hotel_category">Категория отеля (количество звезд):</label>
								 <input type="text" name="tour_hotel_category" value="<?php echo $row["tour_hotel_category"]; ?>">
					         </li>	
					   	     <li class="form__line">
								 <label for="tour_duration">Продолжительность, дней:</label>
								 <input type="text" name="tour_duration" value="<?php echo $row["tour_duration"]; ?>">
					         </li>	
					   		 <li class="form__line">
								 <label for="tour_duration">Стоимость, руб.:</label>
								 <input type="text" name="tour_price" value="<?php echo $row["tour_price"]; ?>">
					         </li>	
					   		 <li class="form__line">
								 <label for="tour_photo[]">Фото:</label>
								 <?php
								  if ($action == "edit")
								  {
									  $stmt = $connection->prepare("SELECT * FROM photos WHERE photo_tour_id=?");
									  $stmt->bind_param("i", $tour_id);
									  $stmt->execute();
									  $photos = $stmt->get_result();
									  ?>
								      <ul class="photo_list">
								 	  <?php
									  while ($photo = $photos->fetch_assoc())
									  {
										  ?>
										  <li>
											<img src="<?php echo $photo["photo_filename"]; ?>" width="175" height="120" />
											<br>  
								 			<a href="deletephoto.php?photo_id=<?php echo $photo["photo_id"]; ?>">Удалить</a>	
										  </li>	  
								 		  <?php	
									  }
									  ?>
								 	  </ul>
									  <?php	  
								  }
							     ?>
								 <input type="file" name="tour_photo[]" multiple accept="image/*"> 
					   		 </li>
					   		 <li class="form__line">
								 <input type="submit" value="Сохранить">
					         </li>
					        <?php
						}
					   ?>
				   </ul>
			   </form>
		    </div>
          <?php
	  }
	  
	  static function Actions($connection)
	  {
		  $result = $connection->query("SELECT * FROM actions JOIN tours ON actions.action_tour_id=tours.tour_id JOIN cities ON tours.tour_city_id=cities.city_id JOIN countries ON cities.city_country_id=countries.country_id");
		  ?>
           <h1 class="centered">Акции</h1>  
           <table> 
			  <tr>
				  <th>Тур</th>
				  <th>Процент скидки</th>
				  <th>Начало действия акции</th>
				  <th>Окончание действия акции</th>
				  <?php if ($_SESSION["can_edit"] == true): ?>
				  <th>Действия</th>
				  <?php endif; ?>
			  </tr>
			   <?php
			  while ($row = $result->fetch_assoc())
			  {
				  ?>
				   <tr>
					   <td><?php echo $row["country_name"].", ".$row["city_name"].", Продолжительность (дней): ".$row["tour_duration"]."Категория отеля (звезд): ".$row["tour_hotel_category"]; ?></td>
					   <td><?php echo $row["action_discount"]; ?></td>
					   <td><?php echo $row["action_date_from"]; ?></td>
					   <td><?php echo $row["action_date_to"]; ?></td>
					   <?php if ($_SESSION["can_edit"] == true): ?>
					   <td>
						   <a href="admin.php?view=action&action=edit&action_id=<?php echo $row["action_id"]; ?>">Изменить</a>
						   <a href="action.php?action=delete&action_id=<?php echo $row["action_id"]; ?>">Удалить</a>
					   </td>
					   <?php endif; ?>
				   </tr>
				  <?php
			  }
		    ?>
           </table>
			<?php if ($_SESSION["can_edit"] == true): ?>
		    <button onClick="addAction()">Добавить</button>	
           <?php
		    endif;
	  }
	  
	  static function Action($connection)
	  {
		  $action = $_GET["action"];
		  $tours = $connection->query("SELECT * FROM tours JOIN cities ON tours.tour_city_id=cities.city_id JOIN countries ON cities.city_country_id=countries.country_id");
		  if ($action == "edit")
		  {
			  $action_id = $_GET["action_id"];
			  $stmt = $connection->prepare("SELECT * FROM actions JOIN tours ON actions.action_tour_id=tours.tour_id JOIN cities ON tours.tour_city_id=cities.city_id JOIN countries ON cities.city_country_id=countries.country_id WHERE action_id=?");
			  $stmt->bind_param("i", $action_id);
			  $stmt->execute();
			  $result = $stmt->get_result();
			  $row = $result->fetch_assoc();
		  }
		  ?>
			<div class="form__wrapper">
			   <form method="post" action="action.php?action=<?php echo $action; if ($action == "edit"): ?>&action_id=<?php echo $action_id; endif; ?>">
				   <h1 class="centered">Акция</h1>
				   <ul>
					   <li class="form__line">
						   <label for="action_tour_id">Тур:</label>
						   <select name="action_tour_id">
							   <option>--Выберите тур--</option>
							   <?php
		  						while ($tour = $tours->fetch_assoc())
								{
									?>
							   		<option value="<?php echo $tour["tour_id"]; ?>" <?php if ($tour["tour_id"] == $row["action_tour_id"]): ?> selected="selected" <?php endif; ?>><?php echo $tour["country_name"].", ".$tour["city_name"].", Продолжительность (дней): ".$tour["tour_duration"]."Категория отеля (звезд): ".$tour["tour_hotel_category"]; ?></option>
							   		<?php
								}
		  					   ?> 	
						   </select>
					   </li>
					   <li class="form__line">
						   <label for="action_discount">Процент скидки:</label>
						   <input type="text" name="action_discount" value="<?php echo $row["action_discount"]; ?>">
					   </li>
					   <li class="form__line">
						   <label for="action_date_from">Начало действия акции:</label>
						   <input type="date" name="action_date_from" value="<?php echo $row["action_date_from"]; ?>">
					   </li>
					   <li class="form__line">
						   <label for="action_date_to">Окончание действия акции:</label>
						   <input type="date" name="action_date_to" value="<?php echo $row["action_date_to"]; ?>">
					   </li>
					   <li class="form__line">
						   <input type="submit" value="Сохранить">
					   </li>
				   </ul>
			   </form>
		    </div>
          <?php
	  }
  }
?>