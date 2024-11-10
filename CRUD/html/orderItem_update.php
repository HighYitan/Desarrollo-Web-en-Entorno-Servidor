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
use models\Promotion;
use models\Order;
use models\OrderItem;
use models\ProductInformation;
use models\Category;

use Faker\Factory;

function convertToNull($value) { //Convierte el valor a null si es igual a "" (Campo vacío) o 0.
	return $value == ("" OR 0) ? null : $value;
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
function getItem($items, $product_id){ //Devuelve todos los OrderItems de un Order.
    foreach($items as $item){
        if($item->getProductId() == $product_id){
            return $item;
        }
    }
}
try {
	$faker = Faker\Factory::create();
	if(isset($_POST["id"]) && !empty($_POST["id"])){//Sino order_id se queda como undefined al terminar el formulario y da error.
		$order_id = $_POST["id"];
	}
	else{
		if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
			$order_id =  trim($_GET["id"]);
		}
	}
    if(isset($_POST["product_id"]) && !empty($_POST["product_id"])){//Sino product_id se queda como undefined al terminar el formulario y da error.
		$product_id = $_POST["product_id"];
	}
	else{
		if(isset($_GET["product_id"]) && !empty(trim($_GET["product_id"]))){
			$product_id =  trim($_GET["product_id"]);
		}
	}

    $promotions = Promotion::All();
    $orders = Order::All();
    $items = OrderItem::All();
    $products = ProductInformation::All();
    
    $order_total = 0;
    $order = getOrder($orders, $order_id);
    $items = getItems($items, $order_id);
    $item = getItem($items, $product_id);

    $line_item_id = $item->getLineItemId();
    $unit_price = $item->getUnitPrice();
    $quantity = $item->getQuantity();
	$text_err = "Please enter a text.";
    
	if($_SERVER["REQUEST_METHOD"] == "POST"){
        echo "Quantity: " . $quantity;
        //$product_id = $_POST["product_id"];
        $quantity       = $_POST["quantity"];
        echo "Quantity: " . $quantity;
        foreach($products as $product){
            if($product->getProductId() == $product_id){
                if($order->getPromotionId() != null){
                    $unit_price = $product->getMinPrice();
                }
                else{
                    $unit_price = $product->getListPrice();
                }
            }
        }
        $order_total = $unit_price * $quantity; //Si todavía no hay OrderItems en el Order calcula el precio total de la siguiente manera.
        if(!empty($items)){
            foreach($items as $item){//Recorro todos los OrderItems del Order para sumar el precio total.
                foreach($products as $product){
                    if($product->getProductId() == $product_id){
                        if($order->getPromotionId() != null){
                            $unit_price = $product->getMinPrice();
                        }
                        else{
                            $unit_price = $product->getListPrice();
                        }
                    }
                }
                $order_total += $item->getUnitPrice() * $item->getQuantity(); //Sumo el precio del Producto multiplicado por la cantidad.
            }
        }
        if($unit_price == null){ //Si el precio del producto es null lo convierte a 0,0 en la base de datos.
            $unit_price = 0;
        }
		$line_item_id   = $_POST["line_item_id"];

        $newItem = new OrderItem(
            $order_id,
            $product_id,
            $line_item_id,
            $unit_price,
            $quantity
        );

        $order_date = $order->getOrderDate();
		$order_mode    = $order->getOrderMode();
        $customer_id  = $order->getCustomerId();
        $order_status  = $order->getOrderStatus();
        /*foreach($items as $item){//Recorro todos los OrderItems del Order para sumar el precio total.
            $order_total += $item->getUnitPrice() * $item->getQuantity(); //Sumo el precio del Producto multiplicado por la cantidad.
        }
        if(empty($items)){//Si todavía no hay OrderItems en el Order calcula el precio total de la siguiente manera.
            $order_total = $unit_price * $quantity;
        }*/
        $sales_rep_id   = $order->getSalesRepId();
        $promotion_id   = $order->getPromotionId();

		$newOrder = new Order(
			$order_id,
			$order_date,
			$order_mode,
			$customer_id,
			$order_status,
			$order_total,
            $sales_rep_id,
            $promotion_id
		);
        $newItem->save();
        $newOrder->save();
		header("Location: orderItem_update.php?id=" . $order_id . "&product_id=" . $product_id);
        //header("Location: orders.php");
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
		<title>Update Order Item</title>
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
					<h3>Update Order Item</h3>
				
					<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
						<div class="form-group">
							<label class="form-label">Order ID</label>
							<input type="text" name="id" class="form-control" required readonly value="<?php echo $order_id; ?>">
							<span class="invalid-feedback"><?php echo $text_err;?></span>
						</div>
                        <?php
						echo "<div class=form-group>";
                            echo "<label class=form-label>Product</label>";
                            echo "<select name=product_id id=product_id class=form-select>";
                            foreach($products as $product){
                                echo "<option value=" . $product->getProductId() . ">" . $product->getProductName() . "</option>";
                            }
                            echo "</select>";
                        echo "</div>";
                        ?>
                        <div class="form-group">
							<label class="form-label">Line Item ID</label>
							<input type="text" name="line_item_id" class="form-control" required readonly value="<?php echo $line_item_id; ?>">
							<span class="invalid-feedback"><?php echo $text_err;?></span>
						</div>
                        <div class="form-group">
							<label class="form-label">Quantity</label>
							<input type="text" name="quantity" class="form-control" value="<?php echo $quantity; ?>">
							<span class="invalid-feedback"><?php echo $text_err;?></span>
						</div>
						<input type="submit" class="btn btn-primary my-2" value="Submit">
						<a href="orders.php" class="btn btn-secondary my-2 ml-2">Cancel</a>
					</form>
				</div>
			</div>
			<div class="row bg-dark pt-3">
				<p class="text-white">(c) IES Emili Darder - 2022</p>
			</div>
		</div>
	</body>
</html>