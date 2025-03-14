<?php
session_start(); // si ja existeix sessió, associa la sessió a l'actual
ob_start();  // necessari per a la redirecció de 'header()': resetea el buffer de salida

// Comprova si l'usuari ha iniciat la sessió
if (!isset($_SESSION['username'])) {  // si està definida amb un valor no null -> true
	// Si no es troba una sessió, cal treure l'usuari fora
	header("Location: login.php");  // redirigeix a 'login'
	exit();  // garanteix que no s'executi més codi
}
ob_end_flush();  // necessari per a la redirecció de 'header()': envia la sortida enmagatzemada en el buffer
require "../vendor/autoload.php";
use models\Warehouse; // Importando las clases model.
use models\Location;
use Faker\Factory; // Utilizaré Faker y Carbon para generar datos aleatorios.
use Carbon\Carbon;

function convertToNull($value) { //Convierte un valor vació o 0 a null
	return $value == ("" OR 0) ? null : $value;
}

try {
	$faker = Faker\Factory::create();
	$warehouse_id = $faker->numberBetween(10, 100);
	$warehouse_name = $faker->city();
	$warehouse_spec = $faker->word();
	$whGeoLocation = $faker->latitude() . ", " . $faker->longitude();
	$text_err = "Please enter a text.";

    $warehouses = Warehouse::All(); // Utilizo el método de Model para seleccionar todas las entradas de la base de datos.
    $locations = Location::All();
	if($_SERVER["REQUEST_METHOD"] == "POST"){ //Si se pulsa el botón de submit se ejecuta
		$warehouse_id = 		$_POST["id"]; //Obtengo los valores del formulario.
        $warehouse_name = 		$_POST["warehouse_name"];
		$location_id = 		    $_POST["location_id"];
        $warehouse_spec = 		$_POST["warehouse_spec"];
		$whGeoLocation = 		$_POST["whGeoLocation"];

		$newWarehouse = new Warehouse( //Creo un nuevo Almacén con los valores obtenidos del formulario.
			$warehouse_id,
			convertToNull($warehouse_name), //Convierto los valores que puedan estar vacíos o ser 0 a null.
			$location_id,
			convertToNull($warehouse_spec),
			convertToNull($whGeoLocation)
		);
		$newWarehouse->save();
		header("Location: warehouses.php"); //Redirijo a la lista de almacenes.
	}
} 
catch (mysqli_sql_exception $e) {
	echo  "</p> ERROR:" . $e-> getMessage() . "</p>";
} 
catch (Exception $e) {
	echo "</p>" . $e-> getMessage() . "</p>";
} 
catch (Error $e) {
	echo "</p>" . $e-> getMessage() . "</p>";
}
?>
<!DOCTYPE html>
<html lang="es-ES">
	<head>
		<meta charset="UTF-8">
		<link rel="stylesheet" href="../css/estils.css">
		<title>New Warehouse</title>
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
		<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
		<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
		<style>
            .opciones:hover{
                background-color: #7000FF !important;
            }
            .nav-link.mb-1.py-1.ps-1.bg-dark:hover{
                background-color: black !important;
            }
            .mb-2.mx-2.py-1:hover{
                background-color: black !important;
            }
            a.link-info{
	            display: block;
            }
            a.link-primary{
	            display: block;
            }
		</style>
		<script>
			$(document).ready(function(){
				$('[data-toggle="tooltip"]').tooltip();   
			});
		</script>
	</head>
	<body>
		<div class="container-fluid">
			<div class="row bg-dark mb-3">
				<h1 class="text-white py-3">HR & OE Management</h1>
			</div>
			<div class="row">
				<div class="col-md-2">
					<div class="mb-2">
						<div class="nav-link mb-1 py-1 ps-1 bg-dark"><a href="../index.php" class="link-primary">Home</a></div>
						<div class="nav-link mb-1 py-1 bg-dark opciones">
							<div class="text-white px-2">HR
								<div class="mb-2 mx-2 py-1"><a href="employees.php" class="link-info">Employees</a></div>
								<div class="mb-2 mx-2 py-1"><a href="departments.php" class="link-info">Departments</a></div>
								<div class="mb-2 mx-2 py-1"><a href="jobs.php" class="link-info">Jobs</a></div>
								<div class="mb-2 mx-2 py-1"><a href="locations.php" class="link-info">Locations</a></div>
							</div>
						</div>
						<div class="nav-link mb-1 py-1 bg-dark opciones">
							<div class="text-white px-2">OE
								<div class="mb-2 mx-2 py-1"><a href="warehouses.php" class="link-info">Warehouses</a></div>
								<div class="mb-2 mx-2 py-1"><a href="categories.php" class="link-info">Categories</a></div>
								<div class="mb-2 mx-2 py-1"><a href="customers.php" class="link-info">Customers</a></div>
								<div class="mb-2 mx-2 py-1"><a href="products.php" class="link-info">Products</a></div>
								<div class="mb-2 mx-2 py-1"><a href="orders.php" class="link-info">Orders</a></div>
							</div>
						</div>
					</div>
				</div>

				<div class="col-md-10">
					<h3>New Warehouse</h3>
				
					<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"> <!-- Formulario para introducir los datos del nuevo almacén -->
						<div class="form-group">
							<label class="form-label">Warehouse ID</label>
							<input type="text" name="id" class="form-control" required value="<?php echo $warehouse_id; ?>">
							<span class="invalid-feedback"><?php echo $text_err;?></span>
						</div>
						<div class="form-group">
							<label class="form-label">Warehouse Name</label>
							<input type="text" name="warehouse_name" class="form-control" required value="<?php echo $warehouse_name; ?>">
							<span class="invalid-feedback"><?php echo $text_err;?></span>
						</div>
                        <?php
						echo "<div class=form-group>"; //Select para seleccionar el Lugar del nuevo Almacén, enseño la dirección para que sea más fácil seleccionar el Lugar correcto.
							echo "<label class=form-label>Location</label>";
							echo "<select name=location_id id=location_id class=form-select>";
							foreach($locations as $location){
								echo "<option value=" . $location->getLocationId() . ">" . $location->getStreetAddress() . "</option>";
							}
							echo "</select>";
						echo "</div>";
						?>
						<div class="form-group">
							<label class="form-label">Warehouse Specialty</label>
							<input type="text" name="warehouse_spec" class="form-control" value="<?php echo $warehouse_spec; ?>">
							<span class="invalid-feedback"><?php echo $text_err;?></span>
						</div>
						<div class="form-group">
							<label class="form-label">Geo Location</label>
							<input type="text" name="whGeoLocation" class="form-control" value="<?php echo $whGeoLocation; ?>">
							<span class="invalid-feedback"><?php echo $text_err;?></span>
						</div>
						<input type="submit" class="btn btn-primary my-2" value="Submit">
						<a href="warehouses.php" class="btn btn-secondary my-2 ml-2">Cancel</a>
					</form>
				</div>
			</div>
			<div class="row bg-dark pt-3">
				<p class="text-white">(c) IES Emili Darder - <?php echo Carbon::now()->year; ?></p>
			</div>
		</div>
	</body>
</html>