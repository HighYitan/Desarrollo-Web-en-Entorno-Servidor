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
use models\Employee; // Importando las clases model.
use models\Customer;
use models\Country;

use Carbon\Carbon;

function getCustomerById($customer_id, $customers){ //Obtiene un customer por su ID obtenido por $_GET inicialmente.
	foreach($customers as $customer){
		if($customer->getCustomerId() == $customer_id){
			return $customer;
		}
	}
	return null;
}
function convertToNull($value) { //Convierte un valor vació o 0 a null
	return $value == ("" OR 0) ? null : $value;
}
function getManagers($employees){ //Encuentra todos los employees que son managers de ventas
	$managers = [];
	foreach($employees as $employee){
		if($employee->getJobId() == "SA_MAN"){//Comprueba si employee es manager de ventas
			if(!in_array($employee, $managers)){//Comprueba si ya está en la lista de managers
				$managers[] = $employee;//Se añade a la lista de managers
			}
		}
	}
	return $managers;
}
function getIncomeLevels($customers){ //Encuentra los IncomeLevel posibles de los Customers y los ordena para ser utilizados en el formulario.
	$incomes = [];
	foreach($customers as $customer){
		if(!in_array($customer->getIncomeLevel(), $incomes)){//Comprueba si ya está en la lista de managers
			$incomes[] = $customer->getIncomeLevel();//Se añade a la lista de managers
		}
	}
    sort($incomes); //Ordena los Incomes
	return $incomes;
}
try {
	if(isset($_POST["id"]) && !empty($_POST["id"])){//Sino customer_id se queda como undefined al terminar el formulario y da error.
		$customer_id = $_POST["id"];
	}
	else{
		if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
			$customer_id =  trim($_GET["id"]);
		}
	}
	$employees = Employee::All(); // Utilizo el método de Model para seleccionar todas las entradas de la base de datos.
	$countries = Country::All();
	$customers = Customer::All();
	
	$cust = getCustomerById($customer_id, $customers);
	$cust_first_name = $cust->getCustFirstName();
	$cust_last_name = $cust->getCustLastName();
	$cust_street_address = $cust->getCustStreetAddress();
	$cust_postal_code = $cust->getCustPostalCode();
	$cust_city = $cust->getCustCity();
	$cust_state = $cust->getCustState();
	$country_id = $cust->getCustCountry();
	$phone_number = $cust->getPhoneNumbers();
	$credit_limit = $cust->getCreditLimit();
	$cust_email = $cust->getCustEmail();
	$account_mgr_id = $cust->getAccountMgrId();
	$geo_location = json_decode($cust->getCustGeoLocation());
	$date_of_birth = $cust->getDateOfBirth();
	$marital_status = $cust->getMaritalStatus();
	$gender = $cust->getGender();
	$income_level = $cust->getIncomeLevel();
	$text_err = "Please enter a text.";
	
	$managers = getManagers($employees);
	$incomes = getIncomeLevels($customers);
	$chosenCountry;
	if($_SERVER["REQUEST_METHOD"] == "POST"){ //Si se pulsa el botón de submit se ejecuta
        foreach($countries as $country){ //Encuentro el país seleccionado en el formulario y lo asigno a otro campo de la Base de Datos, adaptándolo a los campos de la tabla.
            if($country->getCountryId() == $_POST["cust_country"]){
                $chosenCountry = $country;
            }
        }
		$customer_id            = isset($_POST['id'])?$_POST['id']:""; //Obtengo los valores del formulario.
        $cust_first_name        = $_POST["cust_first_name"];
		$cust_last_name         = $_POST["cust_last_name"];
        $cust_street_address    = $_POST["cust_street_address"];
        $cust_postal_code       = $_POST["cust_postal_code"];
        $cust_city              = $_POST["cust_city"];
		$cust_state             = $_POST["cust_state"];
        $cust_country           = $_POST["cust_country"];
        $phone_number           = $_POST["phone_number"];
        $nls_language           = strtolower($chosenCountry->getCountryId()); //Convierto el ID del país a minúsculas para el campo de idioma.
        $nls_territory          = strtoupper($chosenCountry->getCountryName()); //Convierto el nombre del país a mayúsculas para el campo de territorio.
        $credit_limit           = $_POST["credit_limit"];
        $cust_email             = $_POST["cust_email"];
        $account_mgr_id         = $_POST["manager_id"];
        $geo_location           = $_POST["geo_location"];
        $date_of_birth          = $_POST["date_of_birth"];
        $marital_status         = $_POST["marital_status"];
        $gender                 = $_POST["gender"];
        $income_level           = $_POST["income_level"];

		$newCustomer = new Customer( //Creo un nuevo Customer con los valores obtenidos del formulario.
			$customer_id,
			convertToNull($cust_first_name), //Convierto los valores que puedan estar vacíos o ser 0 a null.
			convertToNull($cust_last_name),
			convertToNull($cust_street_address),
			convertToNull($cust_postal_code),
			convertToNull($cust_city),
            convertToNull($cust_state),
            $cust_country,
			convertToNull($phone_number),
            $nls_language,
            $nls_territory,
            convertToNull($credit_limit),
            convertToNull($cust_email),
            $account_mgr_id,
            convertToNull(json_encode($geo_location)), //Convierto las coordenadas geográficas a un formato JSON ya que el campo de la tabla lo exige así.
            convertToNull($date_of_birth),
            $marital_status,
			$gender,
            $income_level
		);
		$newCustomer->save();
		header("Location: customers.php"); //Redirige a la lista de Customers.
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
		<title>Update Customer</title>
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
					<h3>Update Customer</h3>
				
					<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"> <!-- Formulario para cambiar los datos del Customer -->
						<div class="form-group"> 
							<label class="form-label">Customer ID</label>
							<input type="text" name="id" class="form-control" required readonly value="<?php echo $customer_id; ?>">
							<span class="invalid-feedback"><?php echo $text_err;?></span>
						</div>
						<div class="form-group">
							<label class="form-label">First Name</label>
							<input type="text" name="cust_first_name" class="form-control" required value="<?php echo $cust_first_name; ?>">
							<span class="invalid-feedback"><?php echo $text_err;?></span>
						</div>
						<div class="form-group">
							<label class="form-label">Last Name</label>
							<input type="text" name="cust_last_name" class="form-control" required value="<?php echo $cust_last_name; ?>">
							<span class="invalid-feedback"><?php echo $text_err;?></span>
						</div>
						<div class="form-group">
							<label class="form-label">Address</label>
							<input type="text" name="cust_street_address" class="form-control" value="<?php echo $cust_street_address; ?>">
							<span class="invalid-feedback"><?php echo $text_err;?></span>
						</div>
						<div class="form-group">
							<label class="form-label">Postal Code</label>
							<input type="text" name="cust_postal_code" class="form-control" value="<?php echo $cust_postal_code; ?>">
							<span class="invalid-feedback"><?php echo $text_err;?></span>
						</div>
						<div class="form-group">
							<label class="form-label">City</label>
							<input type="text" name="cust_city" class="form-control" value="<?php echo $cust_city; ?>">
							<span class="invalid-feedback"><?php echo $text_err;?></span>
						</div>
						<div class="form-group">
							<label class="form-label">State</label>
							<input type="text" name="cust_state" class="form-control" value="<?php echo $cust_state; ?>">
							<span class="invalid-feedback"><?php echo $text_err;?></span>
						</div>
                        <?php
						echo "<div class=form-group>"; //Select para seleccionar el País del Customer, enseño el nombre del Customer para que sea más fácil seleccionar el Customer correcto.
							echo "<label class=form-label>Country</label>";
							echo "<select name=cust_country id=cust_country class=form-select>";
							foreach($countries as $country){
								if($country->getCountryId() == $country_id){
									echo "<option value=" . $country_id . " selected>" . $country->getCountryName() . "</option>"; //El que tenía se elige por defecto.
								}
								else{
									echo "<option value=" . $country->getCountryId() . ">" . $country->getCountryName() . "</option>";
								}
							}
							echo "</select>";
						echo "</div>";
						?>
                        <div class="form-group">
							<label class="form-label">Phone</label>
							<input type="text" name="phone_number" class="form-control" value="<?php echo $phone_number; ?>">
							<span class="invalid-feedback"><?php echo $text_err;?></span>
						</div>
                        <div class="form-group">
							<label class="form-label">Credit Limit</label>
							<input type="text" name="credit_limit" class="form-control" value="<?php echo $credit_limit; ?>">
							<span class="invalid-feedback"><?php echo $text_err;?></span>
						</div>
                        <div class="form-group">
							<label class="form-label">Email</label>
							<input type="text" name="cust_email" class="form-control" value="<?php echo $cust_email; ?>">
							<span class="invalid-feedback"><?php echo $text_err;?></span>
						</div>
						<?php
						echo "<div class=form-group>"; //Select para seleccionar el Manager de ventas para el Customer, enseño el nombre del Manager para que sea más fácil seleccionar el Manager correcto.
							echo "<label class=form-label>Manager</label>";
							echo "<select name=manager_id id=manager_id class=form-select>";
							foreach($managers as $manager){
								if($manager->getEmployeeId() == $account_mgr_id){
									echo "<option value=" . $account_mgr_id . " selected>" . $manager->getFirstName() . " " . $manager->getLastName() . "</option>"; //El que tenía se elige por defecto.
								}
								else{
									echo "<option value=" . $manager->getEmployeeId() . ">" . $manager->getFirstName() . " " . $manager->getLastName() . "</option>";
								}
							}
							echo "</select>";
						echo "</div>";
                        ?>
                        <div class="form-group">
							<label class="form-label">Geolocation</label>
							<input type="text" name="geo_location" class="form-control" value="<?php echo $geo_location; ?>">
							<span class="invalid-feedback"><?php echo $text_err;?></span>
						</div>
                        <div class="form-group">
							<label class="form-label">Date of Birth</label>
							<input type="text" name="date_of_birth" class="form-control" value="<?php echo $date_of_birth; ?>">
							<span class="invalid-feedback"><?php echo $text_err;?></span>
						</div>
                        <?php
						echo "<div class=form-group>"; //Select para seleccionar el Estado Civil del Customer.
							echo "<label class=form-label>Marital Status</label>";
							echo "<select name=marital_status id=marital_status class=form-select>";
                                if($marital_status == "single"){ //Se selecciona el que tenía por defecto.
                                    echo "<option value=single selected>Single</option>";
                                    echo "<option value=married>Married</option>";
                                }
                                else{
                                    echo "<option value=single>Single</option>";
                                    echo "<option value=married selected>Married</option>";
                                }
							echo "</select>";
						echo "</div>";
                        echo "<div class=form-group>"; //Select para seleccionar el Sexo del Customer.
                            echo "<label class=form-label>Gender</label>";
                            echo "<select name=gender id=gender class=form-select>";
                                if($gender == "M"){ //Se selecciona el que tenía por defecto.
                                    echo "<option value=M selected>Male</option>";
                                    echo "<option value=F>Female</option>";
                                }
                                else{
                                    echo "<option value=M>Male</option>";
                                    echo "<option value=F selected>Female</option>";
                                }
                            echo "</select>";
                        echo "</div>";
						echo "<div class=form-group>"; //Select para seleccionar el Income del Customer, enseño el nombre del Income para que sea más fácil seleccionar el Income correcto.
							echo "<label class=form-label>Income Level</label>";
							echo "<select name=income_level id=income_level class=form-select>";
							foreach($incomes as $income){
								if($income == $income_level){
									echo "<option value='" . $income_level . "' selected>" . $income_level . "</option>"; //El que tenía se elige por defecto.
								}
								else{
									echo "<option value='" . $income . "'>" . $income . "</option>";
								}
							}
							echo "</select>";
						echo "</div>";
						?>
						<input type="submit" class="btn btn-primary my-2" value="Submit"> <!-- Botón para enviar el formulario -->
						<a href="customers.php" class="btn btn-secondary my-2 ml-2">Cancel</a>
					</form>
				</div>
			</div>
			<div class="row bg-dark pt-3">
				<p class="text-white">(c) IES Emili Darder - <?php echo Carbon::now()->year; ?></p>
			</div>
		</div>
	</body>
</html>