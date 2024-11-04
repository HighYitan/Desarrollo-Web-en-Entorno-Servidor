<?php
namespace models;
use config\Database;
class Customer extends Model{
    protected static $table = "customers"; // Definir la taula associada a la classe
    public function __construct(
        private int $customer_id,
        private ?string $cust_first_name = null,
        private ?string $cust_last_name = null,
        private ?string $cust_street_address = null,
        private ?string $cust_postal_code = null,
        private ?string $cust_city = null,
        private ?string $cust_state = null,
        private ?string $cust_country = null,
        private ?string $phone_numbers = null,
        private ?string $nls_language = null,
        private ?string $nls_territory = null,
        private ?float $credit_limit = null,
        private ?string $cust_email = null,
        private ?int $account_mgr_id = null,
        private ?string $cust_geo_location = null,
        private ?string $date_of_birth = null,
        private ?string $marital_Status = null,
        private ?string $gender = null,
        private ?string $income_level = null
    ){}
    public function save() : void{ // Mètode per guardar el client a la base de dades
        error_reporting(E_ALL);
        try{
            $db = new Database();
            $db->dbConnect("C:/temp/config.db"); // Realitzar la connexió a la base de dades - Carregar la connexió a la base de dades
            //$db->conn->autocommit(false);
			//$db->conn->begin_transaction();
            $table = static::$table; // Obtenir el nom de la taula de la classe filla
            // Preparar la consulta d'INSERT
            if (isset($this->customer_id)) {
                // Variant per a MySQL: executa INSERT/UPDATE a la vegada - Preparar l'INSERT / UPDATE
                $sql = "INSERT INTO $table (customer_id, cust_first_name, cust_last_name, cust_street_address, cust_postal_code, cust_city, cust_state, cust_country, phone_numbers, nls_language, nls_territory, credit_limit, cust_email, account_mgr_id, cust_geo_location, date_of_birth, marital_Status, gender, income_level) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                        ON DUPLICATE KEY
                            UPDATE
                                cust_first_name     = VALUES(cust_first_name),
                                cust_last_name      = VALUES(cust_last_name),
                                cust_street_address = VALUES(cust_street_address),
                                cust_postal_code    = VALUES(cust_postal_code),
                                cust_city           = VALUES(cust_city),
                                cust_state          = VALUES(cust_state),
                                cust_country        = VALUES(cust_country),
                                phone_numbers       = VALUES(phone_numbers),
                                nls_language        = VALUES(nls_language),
                                nls_territory       = VALUES(nls_territory),
                                credit_limit        = VALUES(credit_limit),
                                cust_email          = VALUES(cust_email),
                                account_mgr_id      = VALUES(account_mgr_id),
                                cust_geo_location   = VALUES(cust_geo_location),
                                date_of_birth       = VALUES(date_of_birth),
                                marital_Status      = VALUES(marital_Status),
                                gender              = VALUES(gender),
                                income_level        = VALUES(income_level)";
                $stmt = $db->getConn()->prepare($sql);
                $stmt->bind_param("issssssssssdsisssss",
                                        $this->customer_id, 
                                        $this->cust_first_name, 
                                        $this->cust_last_name, 
                                        $this->cust_street_address, 
                                        $this->cust_postal_code, 
                                        $this->cust_city, 
                                        $this->cust_state, 
                                        $this->cust_country, 
                                        $this->phone_numbers, 
                                        $this->nls_language, 
                                        $this->nls_territory,
                                        $this->credit_limit,
                                        $this->cust_email,
                                        $this->account_mgr_id,
                                        $this->cust_geo_location,
                                        $this->date_of_birth,
                                        $this->marital_Status,
                                        $this->gender,
                                        $this->income_level
				);
                $stmt->execute();// Executar la consulta
            }
            else {
                throw new \Exception ("ID client no informat.");
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
    public function destroy() : void{ // Mètode per eliminar el client a la base de dades
        error_reporting(E_ALL);
        try{
            $db = new Database();
            $db->dbConnect("C:/temp/config.db"); // Realitzar la connexió a la base de dades - Carregar la connexió a la base de dades
            //$db->conn->autocommit(false);
			//$db->conn->begin_transaction();
            $table = static::$table; // Obtenir el nom de la taula de la classe filla
            // Preparar la consulta del delete
            if (isset($this->customer_id)) {
                $sql = "SELECT * FROM $table WHERE customer_id = $this->customer_id";
                $result = $db->getConn()->query($sql);
                if($result->num_rows == 1){ // Comprovar si hi ha resultats
                    $sql = "DELETE FROM $table
                            WHERE customer_id = ?";
                    $stmt = $db->getConn()->prepare($sql);
                    $stmt->bind_param("i", $this->customer_id); // Vincular els valors
                    $stmt->execute();// Executar la consulta
                }
                else{
                    throw new \Exception ("El client no existeix.");
                }
            }
            else {
                throw new \Exception ("ID client no informat.");
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
    public function getCustomerId(){
        return $this->customer_id;
    }
    public function getCustFirstName(){
        return $this->cust_first_name;
    }
    public function getCustLastName(){
        return $this->cust_last_name;
    }
    public function getCustStreetAddress(){
        return $this->cust_street_address;
    }
    public function getCustPostalCode(){
        return $this->cust_postal_code;
    }
    public function getCustCity(){
        return $this->cust_city;
    }
    public function getCustState(){
        return $this->cust_state;
    }
    public function getCustCountry(){
        return $this->cust_country;
    }
    public function getPhoneNumbers(){
        return $this->phone_numbers;
    }
    public function getNlsLanguage(){
        return $this->nls_language;
    }
    public function getNlsTerritory(){
        return $this->nls_territory;
    }
    public function getCreditLimit(){
        return $this->credit_limit;
    }
    public function getCustEmail(){
        return $this->cust_email;
    }
    public function getAccountMgrId(){
        return $this->account_mgr_id;
    }
    public function getCustGeoLocation(){
        return $this->cust_geo_location;
    }
    public function getDateOfBirth(){
        return $this->date_of_birth;
    }
    public function getMaritalStatus(){
        return $this->marital_Status;
    }
    public function getGender(){
        return $this->gender;
    }
    public function getIncomeLevel(){
        return $this->income_level;
    }
}
?>