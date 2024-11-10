<?php
namespace models;
use config\Database;
class Category extends Model{
    protected static $table = "categories_tab"; // Definir la taula associada a la classe
    public function __construct(
        private int $category_id,
        private ?string $category_name,
        private ?string $category_description,
        private ?int $parent_category_id = null,
    ){}
    public function save() : void{ // Mètode per guardar la categoria a la base de dades
        error_reporting(E_ALL);
        try{
            $db = new Database();
            $db->dbConnect("C:/temp/config.db"); // Realitzar la connexió a la base de dades - Carregar la connexió a la base de dades
            //$db->conn->autocommit(false);
			//$db->conn->begin_transaction();
            $table = static::$table; // Obtenir el nom de la taula de la classe filla
            // Preparar la consulta d'INSERT
            if (isset($this->category_id)) {
                // Variant per a MySQL: executa INSERT/UPDATE a la vegada - Preparar l'INSERT / UPDATE
                $sql = "INSERT INTO $table (category_id, category_name, category_description, parent_category_id) 
                        VALUES (?, ?, ?, ?, ?)
                        ON DUPLICATE KEY
                            UPDATE
                                category_name      = VALUES(category_name),
                                category_description       = VALUES(category_description),
                                parent_category_id           = VALUES(parent_category_id)";
                $stmt = $db->getConn()->prepare($sql);
                $stmt->bind_param("issi",
                                        $this->category_id, 
                                        $this->category_name, 
                                        $this->category_description, 
                                        $this->parent_category_id
				);
                $stmt->execute();// Executar la consulta
            }
            else {
                throw new \Exception ("ID categoria no informat.");
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
    public function destroy() : void{ // Mètode per eliminar la categoria a la base de dades
        error_reporting(E_ALL);
        try{
            $db = new Database();
            $db->dbConnect("C:/temp/config.db"); // Realitzar la connexió a la base de dades - Carregar la connexió a la base de dades
            //$db->conn->autocommit(false);
			//$db->conn->begin_transaction();
            $table = static::$table; // Obtenir el nom de la taula de la classe filla
            // Preparar la consulta del delete
            if (isset($this->category_id)) {
                $sql = "SELECT * FROM $table WHERE category_id = $this->category_id";
                $result = $db->getConn()->query($sql);
                if($result->num_rows == 1){ // Comprovar si hi ha resultats
                    $sql = "DELETE FROM $table
                            WHERE category_id = ?";
                    $stmt = $db->getConn()->prepare($sql);
                    $stmt->bind_param("i", $this->category_id); // Vincular els valors
                    $stmt->execute();// Executar la consulta
                }
                else{
                    throw new \Exception ("La categoria no existeix.");
                }
            }
            else {
                throw new \Exception ("ID categoria no informat.");
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
    public function getCategoryId(){
        return $this->category_id;
    }
    public function getCategoryName(){
        return $this->category_name;
    }
    public function getCategoryDescription(){
        return $this->category_description;
    }
    public function getParentCategoryId(){
        return $this->parent_category_id;
    }

}
?>