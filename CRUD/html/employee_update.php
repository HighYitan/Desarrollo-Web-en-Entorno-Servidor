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
use models\Department;
use models\Job;
	
use Carbon\Carbon;

function getEmployeeById($employee_id, $employees){ //Encuentra al Employee a modificar por su ID
	foreach($employees as $employee){
		if($employee->getEmployeeId() == $employee_id){
			return $employee;
		}
	}
	return null;
}

function convertToNull($value) { //Convierte un valor vació o 0 a null
	return $value == ("" OR 0) ? null : $value;
}

function getManagers($employees){ //Encuentra todos los employees que son managers
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
	$employees = Employee::All(); // Utilizo el método de Model para seleccionar todas las entradas de la base de datos.
	$jobs = Job::All();
	$departments = Department::All();
	if(isset($_POST["id"]) && !empty($_POST["id"])){
		$employee_id = $_POST["id"];
	}
	else{
		if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
			$employee_id =  trim($_GET["id"]);
		}
	}
	//$employee_id = isset($_GET['id'])?$_GET['id']:"";
	$employee = getEmployeeById($employee_id, $employees); //Obtengo el empleado a modificar
	$first_name = $employee->getFirstName(); //Obtengo los valores del empleado y los pongo por defecto en el formulario.
	$last_name = $employee->getLastName();
	$email = $employee->getEmail();
	$phone = $employee->getPhoneNumber();
	$hire_date = $employee->getHireDate();
	$job_id = $employee->getJobId();
	$salary = $employee->getSalary();
	$commission = $employee->getCommisionPct();
	$manager_id = $employee->getManagerId();
	$department_id = $employee->getDepartmentId();
	$text_err = "Please enter a text.";

	$managers = getManagers($employees); //Obtengo los managers para el formulario.
	if($_SERVER["REQUEST_METHOD"] == "POST"){ //Si se pulsa el botón de submit se ejecuta
		$employee_id = 		isset($_POST['employee_id'])?$_POST['employee_id']:""; //Obtengo los valores del formulario.
		$last_name = 		$_POST["last_name"];
		$first_name = 		$_POST["first_name"];
		$job_id = 			$_POST["job_id"];
		$salary = 			$_POST["salary"];
		foreach($employees as $emp){ //Comprueba si el email ya existe en la base de datos
			if($emp->getEmail() == $_POST["email"]){
				$email = $employee->getEmail();
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

		$newEmployee = new Employee( //Creo un nuevo Employee con los valores obtenidos del formulario.
			$employee_id,
			convertToNull($first_name), //Convierto los valores que puedan estar vacíos o ser 0 a null.
			convertToNull($last_name),
			convertToNull($email),
			convertToNull($phone),
			$hire_date,
			$job_id,
			convertToNull($salary),
			convertToNull($commission),
			convertToNull($manager_id),
			$department_id
		);
		$newEmployee->save();
		header("Location: employees.php"); //Redirijo a la lista de Empleados.
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
	//Si pongo el header aquí (Finally) no me muestra el formulario
?>
<!DOCTYPE html>
<html lang="es-ES">
	<head>
		<meta charset="UTF-8">
		<link rel="stylesheet" href="../css/estils.css">
		<title>Update Employee</title>
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
					<h3>Update Employee</h3>
				
					<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . "?id=" . $employee_id; ?>" method="post"> <!-- Formulario para cambiar los datos del empleado -->
						<div class="form-group"> 
							<label class="form-label">Employee ID</label>
							<input type="text" name="employee_id" class="form-control" required readonly value="<?php echo $employee_id; ?>">
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
						echo "<div class=form-group>"; //Select para seleccionar el Job del Employee, enseño el nombre del Job para que sea más fácil seleccionar el Job adecuado.
							echo "<label class=form-label>Job</label>";
							echo "<select name=job_id id=job_id class=form-select>";
							foreach($jobs as $job){ 
								if($job->getJobId() == $job_id){
									echo "<option value=" . $job->getJobId() . " selected>" . $job->getJobTitle() . "</option>"; //El Job que tenía el Employee se selecciona por defecto.
								}
								else{
									echo "<option value=" . $job->getJobId() . ">" . $job->getJobTitle() . "</option>";
								}
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
						echo "<div class=form-group>"; //Select para seleccionar el Manager del Employee, enseño el nombre del Job para que sea más fácil seleccionar el Job adecuado.
							echo "<label class=form-label>Manager</label>";
							echo "<select name=manager_id id=manager_id class=form-select>";
							foreach($managers as $manager){
								if($manager->getEmployeeId() == $manager_id){
									echo "<option value=" . $manager_id . " selected>" . $manager->getFirstName() . " " . $manager->getLastName() . "</option>"; //El Manager que tenía el Employee se selecciona por defecto.
								}
								else{
									echo "<option value=" . $manager->getEmployeeId() . ">" . $manager->getFirstName() . " " . $manager->getLastName() . "</option>";
								}
							}
							if($manager_id == null){
								echo "<option value=" . 0 . " selected>" . "No manager" . "</option>"; //Si no tenía manager se selecciona por defecto.
							}
							else{
								echo "<option value=" . 0 . ">" . "No manager" . "</option>";
							}
							echo "</select>";
						echo "</div>";
						echo "<div class=form-group>"; //Select para seleccionar el Dept del Employee, enseño el nombre del Dept para que sea más fácil seleccionar el Dept adecuado.
							echo "<label class=form-label>Department</label>";
							echo "<select name=department_id id=department_id class=form-select>";
							foreach($departments as $department){
								if($department->getDepartmentId() == $department_id){
									echo "<option value=" . $department->getDepartmentId() . " selected>" . $department->getDepartmentName() . "</option>"; //El Dept que tenía el Employee se selecciona por defecto.
								}
								else{
									echo "<option value=" . $department->getDepartmentId() . ">" . $department->getDepartmentName() . "</option>";
								}
							}
							echo "</select>";
						echo "</div>";
						?>
						<input type="submit" class="btn btn-primary my-2" value="Submit"> <!-- Botón para enviar el formulario -->
						<a href="employees.php" class="btn btn-secondary my-2 ml-2">Cancel</a>
					</form>
				</div>
			</div>
			<div class="row bg-dark pt-3">
				<p class="text-white">(c) IES Emili Darder - <?php echo Carbon::now()->year; ?></p>
			</div>
		</div>
	</body>
</html>