<!DOCTYPE html>
<html lang="es-ES">
	<head>
		<meta charset="UTF-8">
		<link rel="stylesheet" href="../css/estils.css">
		<title>Human Resource</title>
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
		<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
		<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
		<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
		<style>
			.wrapper{
				width: 600px;
				margin: 0 auto;
			}
			table tr td:last-child{
				width: 120px;
			}
		</style>
		<script>
			$(document).ready(function(){
				$('[data-toggle="tooltip"]').tooltip();   
			});
		</script>
	</head>
	<body>
		<div id="header">
			<h1>HR & OE Management</h1>
		</div>
		<div id="content">
			<div id="menu">
				<ul>
					<li><a href="../index.php">Home</a></li>
					<li>
						<ul> HR
							<li><a href="employees.php">Employees</a></li>
							<li><a href="departments.php">Departments</a></li>
							<li><a href="jobs.php">Jobs</a></li>
							<li><a href="locations.php">Locations</a></li>
						</ul>
					</li>
					<li>
						<ul> OE
							<li><a href="warehouses.php">Warehouses</a></li>
							<li><a href="categories.php">Categories</a></li>
							<li><a href="customers.php">Customers</a></li>
							<li><a href="products.php">Products</a></li>
							<li><a href="orders.php">Orders</a></li>
						</ul>
					</li>
				</ul>
			</div>

			<div id="section">
				<h3>Employees</h3>
				<?php
				// Include config file
				//require_once "../src/config/config.php";
				require "../vendor/autoload.php";
				use config\Database;
				use models\Employee;
				use models\Department;
				use models\Customer;
				$employees = Employee::All();

				EmployeeModel::init('your_host', 'your_dbname', 'your_username', 'your_password');

				// Retrieve the employee ID from the query parameter
				$employee_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

				// Fetch the employee data
				$employee = EmployeeModel::getEmployeeById($employee_id);

				if ($employee) {
					echo "<h1>Employee Details</h1>";
					echo "<p>Employee ID: " . $employee->getEmployeeId() . "</p>";
					echo "<p>First Name: " . $employee->getFirstName() . "</p>";
					echo "<p>Last Name: " . $employee->getLastName() . "</p>";
					echo "<p>Department: " . $employee->getDepartmentName() . "</p>";
				} else {
					echo "<p>Employee not found.</p>";
				}
				
				try {
					/* Attempt to connect to MySQL database */
					//$employees = Employee::AllDepartment(); //SELECT
					/*echo "<pre>";
					print_r($employees);
					echo "</pre>";*/
					//$employees = array_values($query);
					/*$conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
					mysqli_autocommit($conn, true);
					
					// Attempt select query execution
					$query = "SELECT employee_id, first_name, last_name, department_name 
								FROM departments d INNER JOIN employees e ON d.department_id = e.department_id 
								ORDER BY employee_id";
					$table = mysqli_query($conn, $query);*/
					echo '<table class="table table-bordered table-striped">';
					echo 
						"<thead>" .
							"<tr>" . 
								"<th>#</th>"          .
								"<th>Last Name</th>"  .
								"<th>First Name</th>" .
								"<th>Department</th>" .
								"<th>Actions "     .
								'<a href="employee_new.php' . '" class="mr-2" title="New File" data-toggle="tooltip"><span class="fa fa-pencil-square-o"></span></a>'      . 
								"</th>" .
							"</tr>" .
						"</thead>";
						echo "<tbody>";
						foreach($employees as $employee){
							echo 
							"<tr>" . 
								"<td>" . $employee->getEmployeeId()    . "</td>" .
								"<td>" . $employee->getLastName()       . "</td>" .
								"<td>" . $employee->getFirstName()      . "</td>" .
								"<td>" . $employee->getDepartmentId() . "</td>" .
								"<td>" .
									'<a href="employee_read.php?id='   . $employee->getEmployeeId() . '" class="mr-2" title="View File" data-toggle="tooltip"><span class="fa fa-eye"></span></a>'      . 
									'<a href="employee_update.php?id=' . $employee->getEmployeeId() . '" class="mr-2" title="Update File" data-toggle="tooltip"><span class="fa fa-pencil"></span></a>' .
									'<a href="employee_delete.php?id=' . $employee->getEmployeeId() . '" class="mr-2" title="Delete File" data-toggle="tooltip"><span class="fa fa-trash"></span></a>'               .
								"</td>" .
							"</tr>";
						}
					echo "</tbody>"; 
					echo "</table>";
						// Free result set
						//mysqli_free_result($table);
					/*else {
						echo '<div class="alert alert-danger"><em>No records were found.</em></div>';
					}*/
				} catch (mysqli_sql_exception $e) {
					echo  "<p> ERROR:" . $e-> getMessage() . "</p>";
				} catch (Exception $e) {
					echo "<p>" . $e-> getMessage() . "</p>";
				} catch (Error $e) {
					echo "<p>" . $e-> getMessage() . "</p>";
				} finally {
					try {
						mysqli_close($conn);
					} catch (Exception $e) {
						// Nothing to do
					} catch (Error $e) {
						// Nothing to do
					}
				}
				?>
			</div>
		</div>

		<div id="footer">
            <p>(c) IES Emili Darder - 2022</p>
		</div>
	</body>
</html>

