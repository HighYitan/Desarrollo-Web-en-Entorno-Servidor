<?php
namespace models;
use config\Database;
class Promotion extends Model{
    protected static $table = "promotions"; // Definir la taula associada a la classe
    public function __construct(
        private int $promotion_id,
        private ?string $promo_name
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
            if (isset($this->promotion_id)) {
                // Variant per a MySQL: executa INSERT/UPDATE a la vegada - Preparar l'INSERT / UPDATE
                $sql = "INSERT INTO $table (promotion_id, promo_name) 
                        VALUES (?, ?)
                        ON DUPLICATE KEY
                            UPDATE
                                promo_name      = VALUES(promo_name)";
                $stmt = $db->getConn()->prepare($sql);
                $stmt->bind_param("is",
                                        $this->promotion_id, 
                                        $this->promo_name
				);
                $stmt->execute();// Executar la consulta
            }
            else {
                throw new \Exception ("ID promo no informat.");
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
    public function destroy() : void{ // Mètode per eliminar la promo a la base de dades
        error_reporting(E_ALL);
        try{
            $db = new Database();
            $db->dbConnect("C:/temp/config.db"); // Realitzar la connexió a la base de dades - Carregar la connexió a la base de dades
            //$db->conn->autocommit(false);
			//$db->conn->begin_transaction();
            $table = static::$table; // Obtenir el nom de la taula de la classe filla
            // Preparar la consulta del delete
            if (isset($this->promotion_id)) {
                $sql = "SELECT * FROM $table WHERE promotion_id = $this->promotion_id";
                $result = $db->getConn()->query($sql);
                if($result->num_rows == 1){ // Comprovar si hi ha resultats >= 1
                    $sql = "DELETE FROM $table
                            WHERE promotion_id = ?";
                    $stmt = $db->getConn()->prepare($sql);
                    $stmt->bind_param("i", $this->promotion_id); // Vincular els valors
                    $stmt->execute();// Executar la consulta
                }
                else{
                    throw new \Exception ("La promo no existeix.");
                }
            }
            else {
                throw new \Exception ("ID promo no informat.");
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
    public function getPromotionId(){
        return $this->promotion_id;
    }
    public function getPromoName(){
        return $this->promo_name;
    }
}
?>