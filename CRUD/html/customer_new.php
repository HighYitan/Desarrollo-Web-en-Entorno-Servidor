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
use models\Employee;
use models\Customer;
use models\Country;
use Faker\Factory;
use Carbon\Carbon;

function convertToNull($value) {
	return $value == ("" OR 0) ? null : $value;
}
function getManagers($employees){
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
function getIncomeLevels($customers){
	$incomes = [];
	foreach($customers as $customer){
		if(!in_array($customer->getIncomeLevel(), $incomes)){//Comprueba si ya está en la lista de managers
			$incomes[] = $customer->getIncomeLevel();//Se añade a la lista de managers
		}
	}
    sort($incomes);
	return $incomes;
}
try {
	$faker = Faker\Factory::create();
	$customer_id = $faker->numberBetween(1000, 9999);
	$cust_first_name = $faker->firstname();
	$cust_last_name = $faker->lastname();
	$cust_street_address = $faker->streetAddress();
	$cust_postal_code = $faker->postcode();
	$phone_number = $faker->phoneNumber();
	$credit_limit = $faker->randomFloat(1, 100, 5000);
	$cust_email = $faker->email();
	$geo_location = $faker->latitude() . ", " . $faker->longitude();
	$date_of_birth = $faker->date();
	$marital_status = $faker->randomElement(["single", "married"]);
	$gender = $faker->randomElement(["M", "F"]);
	$text_err = "Please enter a text.";

	$employees = Employee::All();
	$countries = Country::All();
	$customers = Customer::All();
	$managers = getManagers($employees);
	$incomes = getIncomeLevels($customers);
	$chosenCountry;
	if($_SERVER["REQUEST_METHOD"] == "POST"){
        foreach($countries as $country){
            if($country->getCountryId() == $_POST["cust_country"]){
                $chosenCountry = $country;
            }
        }
		$customer_id            = $_POST["id"];
        $cust_first_name        = $_POST["cust_first_name"];
		$cust_last_name         = $_POST["cust_last_name"];
        $cust_street_address    = $_POST["cust_street_address"];
        $cust_postal_code       = $_POST["cust_postal_code"];
        $cust_city              = $_POST["cust_city"];
		$cust_state             = $_POST["cust_state"];
        $cust_country           = $_POST["cust_country"];
        $phone_number           = $_POST["phone_number"];
        $nls_language           = strtolower($chosenCountry->getCountryId());
        $nls_territory          = strtoupper($chosenCountry->getCountryName());
        $credit_limit           = $_POST["credit_limit"];
        $cust_email             = $_POST["cust_email"];
        $account_mgr_id         = $_POST["manager_id"];
        $geo_location           = $_POST["geo_location"];
        $date_of_birth          = $_POST["date_of_birth"];
        $marital_status         = $_POST["marital_status"];
        $gender                 = $_POST["gender"];
        $income_level           = $_POST["income_level"];

		$newCustomer = new Customer(
			$customer_id,
			convertToNull($cust_first_name),
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
            convertToNull(json_encode($geo_location)),
            convertToNull($date_of_birth),
            $marital_status,
			$gender,
            $income_level
		);
		$newCustomer->save();
		header("Location: customers.php");
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
		<title>New Customer</title>
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
					<h3>New Customer</h3>
				
					<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
						<div class="form-group">
							<label class="form-label">Customer ID</label>
							<input type="text" name="id" class="form-control" required value="<?php echo $customer_id; ?>">
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
							<input type="text" name="cust_city" class="form-control">
							<span class="invalid-feedback"><?php echo $text_err;?></span>
						</div>
						<div class="form-group">
							<label class="form-label">State</label>
							<input type="text" name="cust_state" class="form-control">
							<span class="invalid-feedback"><?php echo $text_err;?></span>
						</div>
                        <?php
						echo "<div class=form-group>";
							echo "<label class=form-label>Country</label>";
							echo "<select name=cust_country id=cust_country class=form-select>";
							foreach($countries as $country){
								echo "<option value=" . $country->getCountryId() . ">" . $country->getCountryName() . "</option>";
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
						echo "<div class=form-group>";
							echo "<label class=form-label>Manager</label>";
							echo "<select name=manager_id id=manager_id class=form-select>";
							foreach($managers as $manager){
								echo "<option value=" . $manager->getEmployeeId() . ">" . $manager->getFirstName() . " " . $manager->getLastName() . "</option>";
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
						echo "<div class=form-group>";
							echo "<label class=form-label>Marital Status</label>";
							echo "<select name=marital_status id=marital_status class=form-select>";
                                if($marital_status == "single"){
                                    echo "<option value=single selected>Single</option>";
                                    echo "<option value=married>Married</option>";
                                }
                                else{
                                    echo "<option value=single>Single</option>";
                                    echo "<option value=married selected>Married</option>";
                                }
							echo "</select>";
						echo "</div>";
                        echo "<div class=form-group>";
                            echo "<label class=form-label>Gender</label>";
                            echo "<select name=gender id=gender class=form-select>";
                                if($gender == "M"){
                                    echo "<option value=M selected>Male</option>";
                                    echo "<option value=F>Female</option>";
                                }
                                else{
                                    echo "<option value=M>Male</option>";
                                    echo "<option value=F selected>Female</option>";
                                }
                            echo "</select>";
                        echo "</div>";
						echo "<div class=form-group>";
							echo "<label class=form-label>Income Level</label>";
							echo "<select name=income_level id=income_level class=form-select>";
							foreach($incomes as $income){
								echo "<option value='" . $income . "'>" . $income . "</option>";
							}
							echo "</select>";
						echo "</div>";
						?>
						<input type="submit" class="btn btn-primary my-2" value="Submit">
						<a href="employees.php" class="btn btn-secondary my-2 ml-2">Cancel</a>
					</form>
				</div>
			</div>
			<div class="row bg-dark pt-3">
				<p class="text-white">(c) IES Emili Darder - 2022</p>
			</div>
		</div>
	</body>
</html>