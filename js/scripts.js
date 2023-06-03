function addPosition()
{
	window.location = "admin.php?view=position&action=add";
}

function addCountry()
{
	window.location = "admin.php?view=country&action=add";
}

function addCity()
{
	window.location = "admin.php?view=city&action=add";
}

function addEmployee()
{
	window.location = "admin.php?view=employee&action=add";
}

function addTour()
{
	window.location = "admin.php?view=tour&action=add";
}

function addAction()
{
	window.location = "admin.php?view=action&action=add";
}

function loadCities(action) 
{
	let countryId = document.getElementsByName("tour_country_id")[0];
	window.location = "admin.php?view=tour&action=" + action + "&country_id=" + countryId.options[countryId.selectedIndex].value;
}

function getCitiesList()
{
	let countryId = document.getElementsByName("tour_country_id")[0];
	window.location = "directions.php?tour_country_id=" + countryId.options[countryId.selectedIndex].value + "#search_form";
}