<?php
namespace models;
use config\Database;
class Order extends Model{
    protected static $table = "orders"; // Definir la taula associada a la classe
    public function __construct(
        private int $order_id,
        private ?string $order_date = null,
        private ?string $order_mode = null,
        private ?int $customer_id = null,
        private ?int $order_status = null,
        private ?float $order_total = null,
        private ?int $sales_rep_id = null,
        private ?int $promotion_id = null
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
            if (isset($this->order_id)) {
                // Variant per a MySQL: executa INSERT/UPDATE a la vegada - Preparar l'INSERT / UPDATE
                $sql = "INSERT INTO $table (order_id, order_date, order_mode, customer_id, order_status, order_total, sales_rep_id, promotion_id) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
                        ON DUPLICATE KEY
                            UPDATE
                                order_date      = VALUES(order_date),
                                order_mode       = VALUES(order_mode),
                                customer_id           = VALUES(customer_id),
                                order_status    = VALUES(order_status),
                                order_total       = VALUES(order_total),
                                sales_rep_id          = VALUES(sales_rep_id),
                                promotion_id          = VALUES(promotion_id)";
                $stmt = $db->getConn()->prepare($sql);
                $stmt->bind_param("issiidii",
                                        $this->order_id, 
                                        $this->order_date, 
                                        $this->order_mode, 
                                        $this->customer_id, 
                                        $this->order_status, 
                                        $this->order_total, 
                                        $this->sales_rep_id, 
                                        $this->promotion_id
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
                $sql = "SELECT * FROM $table WHERE order_id = $this->order_id";
                $result = $db->getConn()->query($sql);
                if($result->num_rows == 1){ // Comprovar si hi ha resultats
                    $sql = "DELETE FROM $table
                            WHERE order_id = ?";
                    $stmt = $db->getConn()->prepare($sql);
                    $stmt->bind_param("i", $this->order_id); // Vincular els valors
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
    public function getOrderDate(){
        return $this->order_date;
    }
    public function getOrderMode(){
        return $this->order_mode;
    }
    public function getCustomerId(){
        return $this->customer_id;
    }
    public function getOrderStatus(){
        return $this->order_status;
    }
    public function getOrderTotal(){
        return $this->order_total;
    }
    public function getSalesRepId(){
        return $this->sales_rep_id;
    }
    public function getPromotionId(){
        return $this->promotion_id;
    }
}
?>