<!DOCTYPE html>
<html lang="es-ES">
	<head>
		<meta charset="UTF-8">
		<link rel="stylesheet" href="./css/estils.css">
		<title>Human Resources</title>
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
				<?php
					require "vendor/autoload.php";
				?>
				<ul>
					<li><a href="index.php">Home</a></li>
					<li>
						<ul> HR
							<li><a href="./html/employees.php">Employees</a></li>
							<li><a href="./html/departments.php">Departments</a></li>
							<li><a href="./html/jobs.php">Jobs</a></li>
							<li><a href="./html/locations.php">Locations</a></li>
						</ul>
					</li>
					<li>
						<ul> OE
							<li><a href="./html/warehouses.php">Warehouses</a></li>
							<li><a href="./html/categories.php">Categories</a></li>
							<li><a href="./html/customers.php">Customers</a></li>
							<li><a href="./html/products.php">Products</a></li>
							<li><a href="./html/orders.php">Orders</a></li>
						</ul>
					</li>
				</ul>
			</div>

			<div id="section">
				<h3>Human Resources and Order Entries Management</h3>
			</div>
		</div>

		<div id="footer">
            <p>(c) IES Emili Darder - 2022</p>
		</div>
	</body>
</html>