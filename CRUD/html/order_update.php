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
use models\OrderItem;
use models\ProductInformation;

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
function getOrder($orders, $order_id){ //Devuelve el Order con el id que se le pasa.
    foreach($orders as $order){
        if($order->getOrderId() == $order_id){
            return $order;
        }
    }
}
function getItems($items, $order_id) : array{ //Devuelve todos los OrderItems de un Order.
    $totalItems = [];
    foreach($items as $item){
        if($item->getOrderId() == $order_id){
            $totalItems[] = $item;
        }
    }
    return $totalItems;
}
try {
    if(isset($_POST["id"]) && !empty($_POST["id"])){//Sino order_id se queda como undefined al terminar el formulario y da error.
		$order_id = $_POST["id"];
	}
	else{
		if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
			$order_id =  trim($_GET["id"]);
		}
	}
	$text_err = "Please enter a text.";

	$employees = Employee::All(); // Utilizo el método de Model para seleccionar todas las entradas de la base de datos.
	$customers = Customer::All();
    $promotions = Promotion::All();
    $orders = Order::All();
    $items = OrderItem::All();
    $products = ProductInformation::All();

    $order = getOrder($orders, $order_id); //Order a modificar.
    $order_date = $order->getOrderDate(); //Obtengo los valores del Order.
    $order_mode = $order->getOrderMode();
    $customer_id = $order->getCustomerId();
    $order_status = $order->getOrderStatus();
    $order_total = $order->getOrderTotal();
    $sales_rep_id = $order->getSalesRepId();
    $promotion_id = $order->getPromotionId();
    $items = getItems($items, $order_id); //OrderItems del Order.

	$representatives = getRepresentatives($employees); //Representantes de ventas.
	if($_SERVER["REQUEST_METHOD"] == "POST"){ //Si se pulsa el botón de submit se ejecuta
		$order_id       = $_POST["id"]; //Obtengo los valores del formulario.
		$order_mode     = $_POST["order_mode"]; 
        $customer_id    = $_POST["customer_id"]; 
        $order_status   = $_POST["order_status"];
        $order_total    = 0;
        $sales_rep_id   = $_POST["sales_rep_id"];

        $product_id;
        $line_item_id;
        $unit_price;
        $quantity;
        if($promotion_id != $_POST["promotion_id"]){ //Si se cambia la promoción se cambia el precio de los productos.
            foreach($items as $item){
                $product_id = $item->getProductId(); //Obtengo los valores del OrderItem.
                $line_item_id = $item->getLineItemId();
                $quantity = $item->getQuantity();
                $promotion_id   = convertToNull($_POST["promotion_id"]);
                foreach($products as $product){
                    if($product->getProductId() == $item->getProductId()){
                        if($promotion_id != null){
                            $unit_price = $product->getMinPrice(); //Si hay promoción se aplica el precio menor.
                            //$order_total += $product->getMinPrice() * $item->getQuantity();
                        }
                        else{
                            $unit_price = $product->getListPrice();
                            //$order_total += $product->getListPrice() * $item->getQuantity();
                        }
                    }
                }
                $newItem = new OrderItem( //Creo un nuevo OrderItem con los valores obtenidos del formulario.
                    $order_id,
                    $product_id,
                    $line_item_id,
                    $unit_price,
                    $quantity
                );
                $order_total += $unit_price * $item->getQuantity(); //Sumo el precio del producto por su cantidad al total del pedido.
                $newItem->save(); //UPDATE al OrderItem.
            }
        }

		$newOrder = new Order( //Creo un nuevo Order con los valores obtenidos del formulario.
			$order_id,
			$order_date,
			$order_mode,
			$customer_id,
			$order_status,
			$order_total,
            convertToNull($sales_rep_id), //Convierto los valores que puedan estar vacíos o ser 0 a null.
            $promotion_id
		);
		$newOrder->save();
		header("Location: orders.php"); //Redirijo a la lista de pedidos.
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
		<title>Update Order</title>
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
					<h3>Update Order</h3>
				
					<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"> <!-- Formulario para cambiar los datos del pedido -->
						<div class="form-group">
							<label class="form-label">Order ID</label>
							<input type="text" name="id" class="form-control" required readonly value="<?php echo $order_id; ?>"> <!-- Campo de solo lectura -->
							<span class="invalid-feedback"><?php echo $text_err;?></span>
						</div>
                        <?php
						echo "<div class=form-group>"; //Select para seleccionar el Modo de Compra del Customer.
							echo "<label class=form-label>Order Mode</label>";
							echo "<select name=order_mode id=order_mode class=form-select>";
                                if($order_mode == "direct"){ //Se selecciona el valor por defecto que tenía el Order.
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
                                if($customer->getCustomerId() == $customer_id){
                                    echo "<option value=" . $customer_id . " selected>" . $customer->getCustFirstName() . " " . $customer->getCustLastName() . "</option>"; //Se selecciona el valor por defecto que tenía el Order.
                                }
                                else{
                                    echo "<option value=" . $customer_id . ">" . $customer->getCustFirstName() . " " . $customer->getCustLastName() . "</option>";
                                }
							}
							echo "</select>";
						echo "</div>";
                        echo "<div class=form-group>"; //Select para seleccionar el Status del envío del Order (0-10).
                            echo "<label class=form-label>Order Status</label>";
                            echo "<select name=order_status id=order_status class=form-select>";
                            for($status = 0; $status <= 10; $status++){
                                if($status == $order_status){
                                    echo "<option value=" . $status . " selected>" . $status . "</option>"; //Se selecciona el valor por defecto que tenía el Order.
                                }
                                else{
                                    echo "<option value=" . $status . ">" . $status . "</option>";
                                }
							}
                            echo "</select>";
                        echo "</div>";
                        echo "<div class=form-group>"; //Select para seleccionar el Sales Representative que ha vendido el Order.
                            echo "<label class=form-label>Sales Representative</label>";
                            echo "<select name=sales_rep_id id=sales_rep_id class=form-select>";
                            echo "<option value=" . 0 . ">No Representative</option>";
                            foreach($representatives as $representative){
                                if($representative->getEmployeeId() == $sales_rep_id){ //Se selecciona el valor por defecto que tenía el Order.
                                    echo "<option value=" . $sales_rep_id . " selected>" . $representative->getFirstName() . " " . $representative->getLastName() . "</option>";
                                }
                                else{
                                    echo "<option value=" . $sales_rep_id . ">" . $representative->getFirstName() . " " . $representative->getLastName() . "</option>";
                                }
                            }
                            echo "</select>";
                        echo "</div>";
                        echo "<div class=form-group>"; //Select para seleccionar la Promoción que se aplica al Order.
                            echo "<label class=form-label>Promotion</label>";
                            echo "<select name=promotion_id id=promotion_id class=form-select>";
                            echo "<option value=" . 0 . ">No Promotion</option>";
                            foreach($promotions as $promotion){
                                if($promotion->getPromotionId() == $promotion_id){
                                    echo "<option value=" . $promotion_id . " selected>" . $promotion->getPromoName() . "</option>"; //Se selecciona el valor por defecto que tenía el Order.
                                }
                                else{
                                    echo "<option value=" . $promotion->getPromotionId() . ">" . $promotion->getPromoName() . "</option>";
                                }
                            }
                            echo "</select>";
                        echo "</div>";
                        ?>
						<input type="submit" class="btn btn-primary my-2" value="Submit"> <!-- Botón para enviar el formulario -->
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