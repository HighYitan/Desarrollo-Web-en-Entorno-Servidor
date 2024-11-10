<?php
namespace models;
use config\Database;
class Warehouse extends Model{
    protected static $table = "warehouses"; // Definir la taula associada a la classe
    public function __construct(
        private int $warehouse_id,
        private ?string $warehouse_name = null,
        private ?int $location_id = null,
        private ?string $warehouse_spec = null,
        private ?string $wh_geo_location = null,
    ){}
    public function save() : void{ // Mètode per guardar la localitat a la base de dades
        error_reporting(E_ALL);
        try{
            $db = new Database();
            $db->dbConnect("C:/temp/config.db"); // Realitzar la connexió a la base de dades - Carregar la connexió a la base de dades
            //$db->conn->autocommit(false);
			//$db->conn->begin_transaction();
            $table = static::$table; // Obtenir el nom de la taula de la classe filla
            // Preparar la consulta d'INSERT
            if (isset($this->warehouse_id)) {
                // Variant per a MySQL: executa INSERT/UPDATE a la vegada - Preparar l'INSERT / UPDATE
                $sql = "INSERT INTO $table (warehouse_id, warehouse_name, location_id, warehouse_spec, wh_geo_location) 
                        VALUES (?, ?, ?, ?, ?)
                        ON DUPLICATE KEY
                            UPDATE
                                warehouse_name     = VALUES(warehouse_name),
                                location_id        = VALUES(location_id),
                                warehouse_spec               = VALUES(warehouse_spec),
                                wh_geo_location     = VALUES(wh_geo_location)";
                $stmt = $db->getConn()->prepare($sql);
                $stmt->bind_param("isiss",
                                        $this->warehouse_id, 
                                        $this->warehouse_name, 
                                        $this->location_id,
                                        $this->warehouse_spec,
                                        $this->wh_geo_location
				);
                $stmt->execute();// Executar la consulta
            }
            else {
                throw new \Exception ("ID localitat no informat.");
            }
            $db->getConn()->commit();
        }
        catch(\mysqli_sql_exception $e){
            if ($db->conn)
                $db->getConn()->rollback(); 
            throw new \mysqli_sql_exception($e->getMessage());
        }
        finally{
            if($db->getConn()){
                $db->dbClose(); // Tancar la connexió
            }
        }
    }
    public function destroy() : void{ // Mètode per eliminar la localitat a la base de dades
        error_reporting(E_ALL);
        try{
            $db = new Database();
            $db->dbConnect("C:/temp/config.db"); // Realitzar la connexió a la base de dades - Carregar la connexió a la base de dades
            //$db->conn->autocommit(false);
			//$db->conn->begin_transaction();
            $table = static::$table; // Obtenir el nom de la taula de la classe filla
            // Preparar la consulta del delete
            if (isset($this->warehouse_id)) {
                $sql = "SELECT * FROM $table WHERE warehouse_id = $this->warehouse_id";
                $result = $db->getConn()->query($sql);
                if($result->num_rows == 1){ // Comprovar si hi ha resultats
                    $sql = "DELETE FROM $table
                            WHERE warehouse_id = ?";
                    $stmt = $db->getConn()->prepare($sql);
                    $stmt->bind_param("i", $this->warehouse_id); // Vincular els valors
                    $stmt->execute();// Executar la consulta
                }
                else{
                    throw new \Exception ("La localitat no existeix.");
                }
            }
            else {
                throw new \Exception ("ID localitat no informat.");
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
    public function getWarehouseId(){
        return $this->warehouse_id;
    }
    public function getWarehouseName(){
        return $this->warehouse_name;
    }
    public function getLocationId(){
        return $this->location_id;
    }
    public function getWarehouseSpec(){
        return $this->warehouse_spec;
    }
    public function getWhGeoLocation(){
        return $this->wh_geo_location;
    }
}
?>