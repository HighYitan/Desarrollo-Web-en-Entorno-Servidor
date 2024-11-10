<?php
namespace models;
use config\Database;
class ProductInformation extends Model{
    protected static $table = "product_information"; // Definir la taula associada a la classe
    public function __construct(
        private int $product_id,
        private ?string $product_name = null,
        private ?string $product_description = null,
        private ?int $category_id = null,
        private ?float $weight_class = null,
        private ?string $warranty_period = null,
        private ?float $supplier_id = null,
        private ?string $product_status = null,
        private ?float $list_price = null,
        private ?float $min_price = null,
        private ?string $catalog_url = null
    ){}
    public function save() : void{ // Mètode per guardar el producte a la base de dades
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
                $sql = "INSERT INTO $table (product_id, product_name, product_description, category_id, weight_class, warranty_period, supplier_id, product_status, list_price, min_price, catalog_url) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                        ON DUPLICATE KEY
                            UPDATE
                                product_name      = VALUES(product_name),
                                product_description       = VALUES(product_description),
                                category_id           = VALUES(category_id),
                                weight_class    = VALUES(weight_class),
                                warranty_period       = VALUES(warranty_period),
                                supplier_id          = VALUES(supplier_id),
                                product_status          = VALUES(product_status),
                                list_price          = VALUES(list_price),
                                min_price          = VALUES(min_price),
                                catalog_url          = VALUES(catalog_url)";
                $stmt = $db->getConn()->prepare($sql);
                $stmt->bind_param("issidsdsdds",
                                        $this->product_id, 
                                        $this->product_name, 
                                        $this->product_description, 
                                        $this->category_id, 
                                        $this->weight_class, 
                                        $this->warranty_period, 
                                        $this->supplier_id, 
                                        $this->product_status,
                                        $this->list_price,
                                        $this->min_price,
                                        $this->catalog_url
				);
                $stmt->execute();// Executar la consulta
            }
            else {
                throw new \Exception ("ID producte no informat.");
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
    public function destroy() : void{ // Mètode per eliminar el producte a la base de dades
        error_reporting(E_ALL);
        try{
            $db = new Database();
            $db->dbConnect("C:/temp/config.db"); // Realitzar la connexió a la base de dades - Carregar la connexió a la base de dades
            //$db->conn->autocommit(false);
			//$db->conn->begin_transaction();
            $table = static::$table; // Obtenir el nom de la taula de la classe filla
            // Preparar la consulta del delete
            if (isset($this->product_id)) {
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
                    throw new \Exception ("El producte no existeix.");
                }
            }
            else {
                throw new \Exception ("ID producte no informat.");
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
    public function getProductId(){
        return $this->product_id;
    }
    public function getProductName(){
        return $this->product_name;
    }
    public function getProductDescription(){
        return $this->product_description;
    }
    public function getCategoryId(){
        return $this->category_id;
    }
    public function getWeightClass(){
        return $this->weight_class;
    }
    public function getWarrantyPeriod(){
        return $this->warranty_period;
    }
    public function getSupplierId(){
        return $this->supplier_id;
    }
    public function getProductStatus(){
        return $this->product_status;
    }
    public function getListPrice(){
        return $this->list_price;
    }
    public function getMinPrice(){
        return $this->min_price;
    }
    public function getCatalogUrl(){
        return $this->catalog_url;
    }
}
?>