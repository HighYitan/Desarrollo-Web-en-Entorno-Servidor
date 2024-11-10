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
use models\Promotion;
use models\Order;

use Faker\Factory; // Utilizaré Faker y Carbon para generar datos aleatorios.
use Carbon\Carbon;

function convertToNull($value) { //Convierte un valor vació o 0 a null
	return $value == ("" OR 0) ? null : $value;
}
function getRepresentatives($employees){ //Obtiene los employees que sean representantes de ventas
    $representatives = [];
    foreach($employees as $employee){
        if($employee->getJobId() == "SA_REP"){//Comprueba si employee es representante de ventas
            if(!in_array($employee, $representatives)){//Comprueba si ya está en la lista de representativos
                $representatives[] = $employee;//Se añade a la lista de representativos
            }
        }
    }
    return $representatives;
}
try {
	$faker = Faker\Factory::create(); //Genero datos aleatorios del tipo correspondiente con Faker.
	$order_id = $faker->numberBetween(2500, 9999);
	$order_date = Carbon::now(); //Fecha del Pedido.
	$order_mode = $faker->randomElement(["direct", "online"]);
	$text_err = "Please enter a text.";

	$employees = Employee::All(); // Utilizo el método de Model para seleccionar todas las entradas de la base de datos.
	$customers = Customer::All();
    $promotions = Promotion::All();

	$representatives = getRepresentatives($employees); //Obtengo los representantes de ventas.
	if($_SERVER["REQUEST_METHOD"] == "POST"){ //Si se pulsa el botón de submit se ejecuta
		$order_id       = $_POST["id"]; //Obtengo los valores del formulario.
		$order_date     = $_POST["order_date"];
		$order_mode     = $_POST["order_mode"];
        $customer_id    = $_POST["customer_id"];
        $order_status   = $_POST["order_status"];
        $order_total    = 0;
        $sales_rep_id   = $_POST["sales_rep_id"];
        $promotion_id   = $_POST["promotion_id"];

		$newOrder = new Order( //Creo un nuevo Order con los valores obtenidos del formulario.
			$order_id,
			$order_date,
			$order_mode,
			$customer_id,
			$order_status,
			$order_total,
            convertToNull($sales_rep_id), //Convierto los valores que puedan estar vacíos o ser 0 a null.
            convertToNull($promotion_id)
		);
		$newOrder->save();
		header("Location: orderItem_new.php?id=" . $order_id); //Redirijo a la la creación de los OrderItems del Order y le paso el ID del Order.
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
		<title>New Order</title>
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
					<h3>New Order</h3>
				
					<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"> <!-- Formulario para introducir los datos del nuevo almacén -->
						<div class="form-group">
							<label class="form-label">Order ID</label>
							<input type="text" name="id" class="form-control" required value="<?php echo $order_id; ?>">
							<span class="invalid-feedback"><?php echo $text_err;?></span>
						</div>
                        <?php
						echo "<div class=form-group>";
							echo "<label class=form-label>Order Mode</label>"; //Select para seleccionar el Modo de Compra del Customer.
							echo "<select name=order_mode id=order_mode class=form-select>";
                                if($order_mode == "direct"){ //Faker selecciona uno aleatoriamente por defecto.
                                    echo "<option value=direct selected>Direct</option>";
                                    echo "<option value=online>Online</option>";
                                }
                                else{
                                    echo "<option value=direct>Direct</option>";
                                    echo "<option value=online selected>Online</option>";
                                }
							echo "</select>";
						echo "</div>";
						echo "<div class=form-group>"; //Select para seleccionar el Customer que ha comprado enseño el nombre del Customer para que sea más fácil seleccionar al Customer correcto.
							echo "<label class=form-label>Customer</label>";
							echo "<select name=customer_id id=customer_id class=form-select>";
							foreach($customers as $customer){
								echo "<option value=" . $customer->getCustomerId() . ">" . $customer->getCustFirstName() . " " . $customer->getCustLastName() . "</option>";
							}
							echo "</select>";
						echo "</div>";
                        echo "<div class=form-group>"; //Select para seleccionar el Status del envío del Order (0-10).
                            echo "<label class=form-label>Order Status</label>";
                            echo "<select name=order_status id=order_status class=form-select>";
                            for($status = 0; $status <= 10; $status++){ 
                                echo "<option value=" . $status . ">" . $status . "</option>";
                            }
                            echo "</select>";
                        echo "</div>";
                        echo "<div class=form-group>"; //Select para seleccionar el Representante de ventas del Order, enseño el nombre del Rep para que sea más fácil seleccionar el Rep correcto.
                            echo "<label class=form-label>Sales Representative</label>";
                            echo "<select name=sales_rep_id id=sales_rep_id class=form-select>";
                            echo "<option value=" . 0 . ">No Representative</option>"; //Si no hay representante seleccionado el valor es 0. (NULL)
                            foreach($representatives as $representative){
                                echo "<option value=" . $representative->getEmployeeId() . ">" . $representative->getFirstName() . " " . $representative->getLastName() . "</option>";
                            }
                            echo "</select>";
                        echo "</div>";
                        echo "<div class=form-group>"; //Select para seleccionar la Promoción del Order, enseño el nombre de la promoción para que sea más fácil seleccionar la promoción correcta.
                            echo "<label class=form-label>Promotion</label>";
                            echo "<select name=promotion_id id=promotion_id class=form-select>";
                            echo "<option value=" . 0 . ">No Promotion</option>"; //Si no hay promoción seleccionada el valor es 0. (NULL)
                            foreach($promotions as $promotion){
                                echo "<option value=" . $promotion->getPromotionId() . ">" . $promotion->getPromoName() . "</option>";
                            }
                            echo "</select>";
                        echo "</div>";
                        ?>
						<input type="submit" class="btn btn-primary my-2" value="Add Order"> <!-- Botón para enviar el formulario -->
						<a href="orders.php" class="btn btn-secondary my-2 ml-2">Cancel</a>
					</form>
				</div>
			</div>
			<div class="row bg-dark pt-3">
				<p class="text-white">(c) IES Emili Darder - <?php echo Carbon::now()->year; ?></p>
			</div>
		</div>
	</body>
</html>