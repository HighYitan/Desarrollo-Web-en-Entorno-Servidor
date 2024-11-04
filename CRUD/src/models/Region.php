<?php
namespace models;
use config\Database;
class Region extends Model{
    protected static $table = "regions"; // Definir la taula associada a la classe
    public function __construct(
        private int $region_id,
        private ?string $region_name = null
    ){}
    public function save() : void{ // Mètode per guardar la regió a la base de dades
        error_reporting(E_ALL);
        try{
            $db = new Database();
            $db->dbConnect("C:/temp/config.db"); // Realitzar la connexió a la base de dades - Carregar la connexió a la base de dades
            //$db->conn->autocommit(false);
			//$db->conn->begin_transaction();
            $table = static::$table; // Obtenir el nom de la taula de la classe filla
            // Preparar la consulta d'INSERT
            if (isset($this->region_id)) {
                // Variant per a MySQL: executa INSERT/UPDATE a la vegada - Preparar l'INSERT / UPDATE
                $sql = "INSERT INTO $table (region_id, region_name) 
                        VALUES (?, ?)
                        ON DUPLICATE KEY
                            UPDATE
                                region_name = VALUES(region_name)";
                $stmt = $db->getConn()->prepare($sql);
                $stmt->bind_param("is",
                                        $this->region_id, 
                                        $this->region_name, 
				);
                $stmt->execute();// Executar la consulta
            }
            else {
                throw new \Exception ("ID regió no informat.");
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
    public function destroy() : void{ // Mètode per eliminar la regió a la base de dades
        error_reporting(E_ALL);
        try{
            $db = new Database();
            $db->dbConnect("C:/temp/config.db"); // Realitzar la connexió a la base de dades - Carregar la connexió a la base de dades
            //$db->conn->autocommit(false);
			//$db->conn->begin_transaction();
            $table = static::$table; // Obtenir el nom de la taula de la classe filla
            // Preparar la consulta del delete
            if (isset($this->region_id)) {
                $sql = "SELECT * FROM $table WHERE region_id = $this->region_id";
                $result = $db->getConn()->query($sql);
                if($result->num_rows == 1){ // Comprovar si hi ha resultats
                    $sql = "DELETE FROM $table
                            WHERE region_id = ?";
                    $stmt = $db->getConn()->prepare($sql);
                    $stmt->bind_param("i", $this->region_id); // Vincular els valors
                    $stmt->execute();// Executar la consulta
                }
                else{
                    throw new \Exception ("La regió no existeix.");
                }
            }
            else {
                throw new \Exception ("ID regió no informat.");
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
    public function getRegionId(){
        return $this->region_id;
    }
    public function getRegionName(){
        return $this->region_name;
    }
}
?>