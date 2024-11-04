<?php
namespace models;
use config\Database;
class Location extends Model{
    protected static $table = "locations"; // Definir la taula associada a la classe
    public function __construct(
        private int $location_id,
        private ?string $street_address = null,
        private ?string $postal_code = null,
        private ?string $city = null,
        private ?string $state_province = null,
        private ?string $country_id = null
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
            if (isset($this->location_id)) {
                // Variant per a MySQL: executa INSERT/UPDATE a la vegada - Preparar l'INSERT / UPDATE
                $sql = "INSERT INTO $table (location_id, street_address, postal_code, city, state_province, country_id) 
                        VALUES (?, ?, ?, ?, ?, ?)
                        ON DUPLICATE KEY
                            UPDATE
                                street_address     = VALUES(street_address),
                                postal_code        = VALUES(postal_code),
                                city               = VALUES(city),
                                state_province     = VALUES(state_province),
                                country_id         = VALUES(country_id)";
                $stmt = $db->getConn()->prepare($sql);
                $stmt->bind_param("isssss",
                                        $this->location_id, 
                                        $this->street_address, 
                                        $this->postal_code
                                        $this->city,
                                        $this->state_province,
                                        $this->country_id
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
            if (isset($this->location_id)) {
                $sql = "SELECT * FROM $table WHERE location_id = $this->location_id";
                $result = $db->getConn()->query($sql);
                if($result->num_rows == 1){ // Comprovar si hi ha resultats
                    $sql = "DELETE FROM $table
                            WHERE location_id = ?";
                    $stmt = $db->getConn()->prepare($sql);
                    $stmt->bind_param("i", $this->location_id); // Vincular els valors
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
    public function getLocationId(){
        return $this->location_id;
    }
    public function getStreetAddress(){
        return $this->street_address;
    }
    public function getPostalCode(){
        return $this->postal_code;
    }
    public function getCity(){
        return $this->city;
    }
    public function getStateProvince(){
        return $this->state_province;
    }
    public function getCountryId(){
        return $this->country_id;
    }
}
?>