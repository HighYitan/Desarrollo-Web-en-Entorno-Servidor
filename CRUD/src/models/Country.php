<?php
namespace models;
use config\Database;
class Country extends Model{
    protected static $table = "countries"; // Definir la taula associada a la classe
    public function __construct(
        private string $country_id,
        private ?string $country_name = null,
        private ?int $region_id = null
    ){}
    public function save() : void{ // Mètode per guardar el país a la base de dades
        error_reporting(E_ALL);
        try{
            $db = new Database();
            $db->dbConnect("C:/temp/config.db"); // Realitzar la connexió a la base de dades - Carregar la connexió a la base de dades
            //$db->conn->autocommit(false);
			//$db->conn->begin_transaction();
            $table = static::$table; // Obtenir el nom de la taula de la classe filla
            // Preparar la consulta d'INSERT
            if (isset($this->country_id)) {
                // Variant per a MySQL: executa INSERT/UPDATE a la vegada - Preparar l'INSERT / UPDATE
                $sql = "INSERT INTO $table (country_id, country_name, region_id) 
                        VALUES (?, ?, ?)
                        ON DUPLICATE KEY
                            UPDATE
                                country_name     = VALUES(country_name),
                                region_id        = VALUES(region_id)";
                $stmt = $db->getConn()->prepare($sql);
                $stmt->bind_param("ssi",
                                        $this->country_id, 
                                        $this->country_name, 
                                        $this->region_id
				);
                $stmt->execute();// Executar la consulta
            }
            else {
                throw new \Exception ("ID país no informat.");
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
    public function destroy() : void{ // Mètode per eliminar el país a la base de dades
        error_reporting(E_ALL);
        try{
            $db = new Database();
            $db->dbConnect("C:/temp/config.db"); // Realitzar la connexió a la base de dades - Carregar la connexió a la base de dades
            //$db->conn->autocommit(false);
			//$db->conn->begin_transaction();
            $table = static::$table; // Obtenir el nom de la taula de la classe filla
            // Preparar la consulta del delete
            if (isset($this->country_id)) {
                $sql = "SELECT * FROM $table WHERE country_id = $this->country_id";
                $result = $db->getConn()->query($sql);
                if($result->num_rows == 1){ // Comprovar si hi ha resultats
                    $sql = "DELETE FROM $table
                            WHERE country_id = ?";
                    $stmt = $db->getConn()->prepare($sql);
                    $stmt->bind_param("s", $this->country_id); // Vincular els valors
                    $stmt->execute();// Executar la consulta
                }
                else{
                    throw new \Exception ("El país no existeix.");
                }
            }
            else {
                throw new \Exception ("ID país no informat.");
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
    public function getCountryId(){
        return $this->country_id;
    }
    public function getCountryName(){
        return $this->country_name;
    }
    public function getRegionId(){
        return $this->region_id;
    }
}
?>