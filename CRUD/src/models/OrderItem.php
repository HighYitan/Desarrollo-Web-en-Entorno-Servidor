<?php
namespace models;
use config\Database;
class OrderItem extends Model{
    protected static $table = "order_items"; // Definir la taula associada a la classe
    public function __construct(
        private int $order_id,
        private int $product_id, //ARREGLAR, NO USAR DE PK
        private int $line_item_id,
        private ?float $unit_price = null,
        private ?float $quantity = null
    ){}
    public function save() : void{ // Mètode per guardar l'ordre a la base de dades
        error_reporting(E_ALL);
        try{
            $db = new Database();
            $db->dbConnect("C:/temp/config.db"); // Realitzar la connexió a la base de dades - Carregar la connexió a la base de dades
            //$db->conn->autocommit(false);
			//$db->conn->begin_transaction();
            $table = static::$table; // Obtenir el nom de la taula de la classe filla
            // Preparar la consulta d'INSERT
            if (isset($this->product_id)) {
                // Variant per a MySQL: executa INSERT/UPDATE a la vegada - Preparar l'INSERT / UPDATE
                $sql = "INSERT INTO $table (order_id, product_id, line_item_id, unit_price, quantity) 
                        VALUES (?, ?, ?, ?, ?)
                        ON DUPLICATE KEY
                            UPDATE
                                order_id      = VALUES(order_id),
                                line_item_id       = VALUES(line_item_id),
                                unit_price           = VALUES(unit_price),
                                quantity    = VALUES(quantity)";
                $stmt = $db->getConn()->prepare($sql);
                $stmt->bind_param("iiidd",
                                        $this->order_id, 
                                        $this->product_id, 
                                        $this->line_item_id, 
                                        $this->unit_price, 
                                        $this->quantity
				);
                $stmt->execute();// Executar la consulta
            }
            else {
                throw new \Exception ("ID ordre no informat.");
            }
            $db->getConn()->commit();
        }
        catch(\mysqli_sql_exception $e){
            if ($db->getConn())
                $db->getConn()->rollback(); 
            throw new \mysqli_sql_exception($e->getMessage());
        }
        finally{
            if($db->getConn()){
                $db->dbClose(); // Tancar la connexió
            }
        }
    }
    public function destroy() : void{ // Mètode per eliminar l'empleat a la base de dades
        error_reporting(E_ALL);
        try{
            $db = new Database();
            $db->dbConnect("C:/temp/config.db"); // Realitzar la connexió a la base de dades - Carregar la connexió a la base de dades
            //$db->conn->autocommit(false);
			//$db->conn->begin_transaction();
            $table = static::$table; // Obtenir el nom de la taula de la classe filla
            // Preparar la consulta del delete
            if (isset($this->order_id)) {
                $sql = "SELECT * FROM $table WHERE product_id = $this->product_id";
                $result = $db->getConn()->query($sql);
                if($result->num_rows == 1){ // Comprovar si hi ha resultats
                    $sql = "DELETE FROM $table
                            WHERE product_id = ?";
                    $stmt = $db->getConn()->prepare($sql);
                    $stmt->bind_param("i", $this->product_id); // Vincular els valors
                    $stmt->execute();// Executar la consulta
                }
                else{
                    throw new \Exception ("L'ordre no existeix.");
                }
            }
            else {
                throw new \Exception ("ID ordre no informat.");
            }
            $db->getConn()->commit();
        }
        catch(\mysqli_sql_exception $e){
            if ($db->conn)
                $db->conn->rollback(); 
            throw new \mysqli_sql_exception($e->getMessage());
        }
        catch(Exception $e){
            echo "Exception: " . $e->getMessage();
        }
        finally{
            if($db->getConn()){
                $db->dbClose(); // Tancar la connexió
            }
        }
    }
    public function getOrderId(){
        return $this->order_id;
    }
    public function getProductId(){
        return $this->product_id;
    }
    public function getLineItemId(){
        return $this->line_item_id;
    }
    public function getUnitPrice(){
        return $this->unit_price;
    }
    public function getQuantity(){
        return $this->quantity;
    }
}
?>