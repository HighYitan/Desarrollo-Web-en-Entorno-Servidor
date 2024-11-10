<!DOCTYPE html>
<html lang="es-ES">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="../css/estils.css">
		<title>Read Employee</title>
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
			
				<div class="col-md-10 table-responsive">
					<h3>Employee</h3>
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
					use Carbon\Carbon; //Uso Carbon para enseñar el año de forma dinámica en el footer.
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
						$departments = Department::All();
						$jobs = Job::All();
						$readEmployee;
						$deptName;
						$jobName;
						$managerName;
						$managers = getManagers($employees); // Filtro los Employees con este método para obtener los empleados que son managers.
						echo '<table class="table table-bordered table-dark table-striped">'; //He añadido algunos estilos en Bootstrap y los he retocado un poco con CSS para que el layout y las tablas queden más bonitas.
						echo 
							"<thead>" .
								"<tr>" . //Muestro todos los campos porque estamos en la página de los detalles.
									"<th>Employee ID</th>" 	.
									"<th>First Name</th>"  	.
									"<th>Last Name</th>" 	.
									"<th>Email</th>" 		.
									"<th>Phone Number</th>" .
									"<th>Hire Date</th>" 	.
									"<th>Job</th>" 			.
									"<th>Salary</th>" 		.
									"<th>Commission Percentage</th>" .
									"<th>Manager</th>" 		.
									"<th>Department</th>" 	.
									"<th>Actions "     		. //Nueva entrada de Employee en la Base de Datos.
									'<a href="employee_new.php' . '" class="mr-2" title="New File" data-toggle="tooltip"><span class="fa fa-pencil-square-o"></span></a>'      . 
									"</th>" .
								"</tr>" .
							"</thead>";
							echo "<tbody>";
							foreach($employees as $employee){ //Obtengo el Empleado por medio del $_GET["id"] para mostrarlo en la tabla.
								if($employee->getEmployeeId() == $_GET["id"]){
									$readEmployee = $employee;
								}
							}
							foreach($departments as $department){ //Comparando los ID's para mostrar el nombre del Departamento en lugar del ID para que sea más entendible para el usuario.
								if(($readEmployee->getDepartmentId()) == ($department->getDepartmentId())){
									$deptName = $department->getDepartmentName();
								}
							}
							foreach($jobs as $job){ //Comparando los ID's para mostrar el nombre del Job en lugar del ID para que sea más entendible para el usuario.
								if(($readEmployee->getJobId()) == ($job->getJobId())){
									$jobName = $job->getJobTitle();
								}
							}
							if($readEmployee->getManagerId() != null){ //Si el Employee tiene un Manager, se muestra el nombre del Manager.
								foreach($managers as $manager){
									if($manager->getEmployeeId() == $readEmployee->getManagerId()){
										$managerName = $manager->getFirstName() . " " . $manager->getLastName();
									}
								}
							}
							else{//Sino se muestra "No manager".
								$managerName = "No manager";
							}
							echo 
								"<tr>" . 
									"<td>" . $readEmployee->getEmployeeId()    . "</td>" .
									"<td>" . $readEmployee->getFirstName()       . "</td>" .
									"<td>" . $readEmployee->getLastName()      . "</td>" .
									"<td>" . $readEmployee->getEmail()      . "</td>" .
									"<td>" . $readEmployee->getPhoneNumber()      . "</td>" .
									"<td>" . $readEmployee->getHireDate()      . "</td>" .
									"<td>" . $jobName      . "</td>" . //Muestro el nombre del Trabajo asociado al ID del Trabajo del Empleado.
									"<td>" . $readEmployee->getSalary()      . "</td>" .
									"<td>" . $readEmployee->getCommisionPct()      . "</td>" .
									"<td>" . $managerName      . "</td>" . //Muestro el nombre del Manager asociado al ID del Manager del Empleado.
									"<td>" . $deptName . "</td>" . //Muestro el nombre del Departamento asociado al ID del Departamento del Empleado.
									"<td>" . //Estos botones utilizan $_GET en la página asociada a través del ID para CREAR. LEER. ACTUALIZAR. BORRAR. (CRUD) de la BD.
										'<a href="employee_update.php?id=' . $readEmployee->getEmployeeId() . '" class="mr-2" title="Update File" data-toggle="tooltip"><span class="fa fa-pencil"></span></a>' .
										'<a href="employee_delete.php?id=' . $readEmployee->getEmployeeId() . '" class="mr-2" title="Delete File" data-toggle="tooltip"><span class="fa fa-trash"></span></a>'               .
									"</td>" .
								"</tr>";
							echo "</tbody>"; 
						echo "</table>";
					}
					catch (mysqli_sql_exception $e) {
						echo  "<p> ERROR:" . $e-> getMessage() . "</p>";
					} 
					catch (Exception $e) {
						echo "<p>" . $e-> getMessage() . "</p>";
					} 
					catch (Error $e) {
						echo "<p>" . $e-> getMessage() . "</p>";
					}
					?>
				</div>
			</div>
			<div class="row bg-dark pt-3">
				<p class="text-white">(c) IES Emili Darder - <?php echo Carbon::now()->year; ?></p>
			</div>
		</div>
	</body>
</html>