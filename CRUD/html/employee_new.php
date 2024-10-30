<?php
	// Include config file
	//require_once "config.php";
	require "../vendor/autoload.php";
	use config\Database;
	use models\Employee;
	use models\Department;
	use models\Customer;
	use models\Job;
	$conn = null;
	$employee_id = $last_name = $first_name = "";
	$department_id = 80;
	$job_id = 'IT_PROG';
	$salary = "1";
	$text_err = "Please enter a text.";
	
	try {
		if($_SERVER["REQUEST_METHOD"] == "POST"){
			$employee_id = trim($_POST["id"]);
			$last_name = trim($_POST["last_name"]);
			$first_name = trim($_POST["first_name"]);
			$job_id = trim($_POST["job_id"]);
			$salary = trim($_POST["salary"]);
			
			/* Attempt to connect to MySQL database */
			$conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
			mysqli_autocommit($conn, true);
			
			// Attempt select query execution
			$query = "INSERT INTO employees ( employee_id, first_name, last_name, job_id, department_id, salary )
					  VALUES( " . $employee_id . ", '" . $first_name ."','" . $last_name ."','" . $job_id . "'," . $department_id . "," . $salary .")";
			$table = mysqli_query($conn, $query);
			$employee = new Employee(
				$employee_id,
				$first_name,
				$last_name,
				null,
				null,
				null,
				$job_id,
				$salary,
				null,
				null,
				null
			);
			// mysqli_commit($conn);

		}
	} catch (mysqli_sql_exception $e) {
		echo  "</p> ERROR:" . $e-> getMessage() . "</p>";
	} catch (Exception $e) {
		echo "</p>" . $e-> getMessage() . "</p>";
	} catch (Error $e) {
		echo "</p>" . $e-> getMessage() . "</p>";
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



<!DOCTYPE html>
<html>
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
			/*table tr td:last-child{
				width: 120px;
			}*/
		</style>
		<script>
			$(document).ready(function(){
				$('[data-toggle="tooltip"]').tooltip();   
			});
			var mealsByCategory = {
				A: ["Soup", "Juice", "Tea", "Others"],
				B: ["Soup", "Juice", "Water", "Others"],
				C: ["Soup", "Juice", "Coffee", "Tea", "Others"]
			}
			const menuEl = document.querySelector('.menu');
			const selectEl1 = document.querySelector('#meal');
			const selectEl2 = document.querySelector('#category')

			selectEl1.addEventListener('change', event => {
				const meals = mealsByCategory[selectEl1.value]

				// clear the content of the second select and populate it with new options
				selectEl2.innerHTML = '';
				meals.forEach(item => {
					const option = document.createElement('option');
					selectEl2.appendChild(option);
					option.textContent = item;
				});
			});

			selectEl2.addEventListener('change', event => {
				if (selectEl1.value === 'A' && selectEl2.value === 'Tea') {
					document.querySelector('.menu1').style.display = 'none';
					document.querySelector('.menu2').style.display = 'block';
				}
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
					<li><a href="index.php">Home</a></li>
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
			
				<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group">
                            <label>ID</label>
                            <input type="text" name="id" class="form-control " value="<?php echo $employee_id; ?>">
                            <span class="invalid-feedback"><?php echo $text_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>First Name</label>
                            <input type="text" name="first_name" class="form-control " value="<?php echo $first_name; ?>">
                            <span class="invalid-feedback"><?php echo $text_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Last Name</label>
                            <input type="text" name="last_name" class="form-control " value="<?php echo $last_name; ?>">
                            <span class="invalid-feedback"><?php echo $text_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Job ID</label>
                            <input type="text" name="job_id" class="form-control " value="<?php echo $job_id; ?>">
                            <span class="invalid-feedback"><?php echo $text_err;?></span>
                        </div>
						<div class="form-group">
                            <label>Salary</label>
                            <input type="text" name="salary" class="form-control " value="<?php echo $salary; ?>">
                            <span class="invalid-feedback"><?php echo $text_err;?></span>
                        </div>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="employees.php" class="btn btn-secondary ml-2">Cancel</a>
                    </form>
					<select name="select" id="select" onChange="">
						<option value="job">Job</option>
						<option value="department">Department</option>
						<option value="manager_id">Manager id</option>
					</select>
					<?php
					$jobs = Job::All();
					echo "<select name="select" id="select" onChange="">";
					foreach($jobs as $job){
						echo "<option value=" . $job->getJobId() . ">" . $job->getJobTitle() . "</option>";
					}
					echo "</select>";


					try {
						echo '<table class="table table-bordered table-striped">';
						echo 
							"<thead>" .
								"<tr>" . 
									"<th>ID</th>"          .
									"<th>Title</th>"  .
									"<th>Minimum Salary</th>" .
									"<th>Maximum Salary</th>" .
								"</tr>" .
							"</thead>";
							echo "<tbody>";
							foreach($jobs as $job){
								echo 
								"<tr>" . 
									"<td>" . $job->getJobId()    . "</td>" .
									"<td>" . $job->getJobTitle()       . "</td>" .
									"<td>" . $job->getMinSalary()      . "</td>" .
									"<td>" . $job->getMaxSalary() . "</td>" .
								"</tr>";
							}
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
					finally {
						try {
							mysqli_close($conn);
						}
						catch (Exception $e) {
							// Nothing to do
						}
						catch (Error $e) {
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

