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
use models\Department;
use models\Job;
use Faker\Factory;
use Carbon\Carbon;

function convertToNull($value) {
	return $value == ("" OR 0) ? null : $value;
}

function getManagers($employees){
	$managers = [];
	foreach($employees as $employee1){//Comprueba si tiene jefe
		foreach($employees as $employee2){//Comprueba si es jefe
			if($employee1->getManagerId() == $employee2->getEmployeeId()){//Comprueba si employee2 es jefe de employee1
				if(!in_array($employee2, $managers)){//Comprueba si ya está en la lista de managers
					$managers[] = $employee2;//Se añade a la lista de managers
				}
			}
		}
	}
	return $managers;
}
try {
	$faker = Faker\Factory::create();
	$employee_id = $faker->numberBetween(1000, 9999);
	$first_name = $faker->firstname();
	$last_name = $faker->lastname();
	$email = $faker->email();
	$phone = $faker->phoneNumber();
	$hire_date = Carbon::now();
	$salary = $faker->numberBetween(5000, 40000);
	$commission = $faker->randomFloat(1, 0, 0.4);
	$text_err = "Please enter a text.";

	$employees = Employee::All();
	$managers = getManagers($employees);
	if($_SERVER["REQUEST_METHOD"] == "POST"){
		$employee_id = 		$_POST["id"];
		$last_name = 		$_POST["last_name"];
		$first_name = 		$_POST["first_name"];
		$job_id = 			$_POST["job_id"];
		$salary = 			$_POST["salary"];
		foreach($employees as $employee){
			if($employee->getEmail() == $_POST["email"]){
				$email = null;
				break;
			}
			else{
				$email = 	$_POST["email"];
			}
		}
		$phone = 			$_POST["phone"];
		$commission = 		$_POST["commission"];
		$manager_id = 		$_POST["manager_id"];
		$department_id = 	$_POST["department_id"];

		$newEmployee = new Employee(
			$employee_id,
			convertToNull($first_name),
			convertToNull($last_name),
			convertToNull($email),
			convertToNull($phone),
			$hire_date,
			$job_id,
			convertToNull($salary),
			convertToNull($commission),
			$manager_id,
			$department_id
		);
		$newEmployee->save();
		header("Location: employees.php");
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
		<title>New Employee</title>
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
					<h3>New Employee</h3>
				
					<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
						<div class="form-group">
							<label class="form-label">Employee ID</label>
							<input type="text" name="id" class="form-control" required value="<?php echo $employee_id; ?>">
							<span class="invalid-feedback"><?php echo $text_err;?></span>
						</div>
						<div class="form-group">
							<label class="form-label">First Name</label>
							<input type="text" name="first_name" class="form-control" required value="<?php echo $first_name; ?>">
							<span class="invalid-feedback"><?php echo $text_err;?></span>
						</div>
						<div class="form-group">
							<label class="form-label">Last Name</label>
							<input type="text" name="last_name" class="form-control" required value="<?php echo $last_name; ?>">
							<span class="invalid-feedback"><?php echo $text_err;?></span>
						</div>
						<div class="form-group">
							<label class="form-label">Email</label>
							<input type="text" name="email" class="form-control" value="<?php echo $email; ?>">
							<span class="invalid-feedback"><?php echo $text_err;?></span>
						</div>
						<div class="form-group">
							<label class="form-label">Phone Number</label>
							<input type="text" name="phone" class="form-control" value="<?php echo $phone; ?>">
							<span class="invalid-feedback"><?php echo $text_err;?></span>
						</div>
						<?php
						$jobs = Job::All();
						echo "<div class=form-group>";
							echo "<label class=form-label>Job</label>";
							echo "<select name=job_id id=job_id class=form-select>";
							foreach($jobs as $job){
								echo "<option value=" . $job->getJobId() . ">" . $job->getJobTitle() . "</option>";
							}
							echo "</select>";
						echo "</div>";
						?>
						<div class="form-group">
							<label class="form-label">Salary</label>
							<input type="text" name="salary" class="form-control" value="<?php echo $salary; ?>">
							<span class="invalid-feedback"><?php echo $text_err;?></span>
						</div>
						<div class="form-group">
							<label class="form-label">Commission Percentage</label>
							<input type="text" name="commission" class="form-control" value="<?php echo $commission; ?>">
							<span class="invalid-feedback"><?php echo $text_err;?></span>
						</div>
						<?php
						echo "<div class=form-group>";
							echo "<label class=form-label>Manager</label>";
							echo "<select name=manager_id id=manager_id class=form-select>";
							foreach($managers as $manager){
								echo "<option value=" . $manager->getEmployeeId() . ">" . $manager->getFirstName() . " " . $manager->getLastName() . "</option>";
							}
							echo "<option value=" . 0 . ">" . "No manager" . "</option>";
							echo "</select>";
						echo "</div>";
						$departments = Department::All();
						echo "<div class=form-group>";
							echo "<label class=form-label>Department</label>";
							echo "<select name=department_id id=department_id class=form-select>";
							foreach($departments as $department){
								echo "<option value=" . $department->getDepartmentId() . ">" . $department->getDepartmentName() . "</option>";
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