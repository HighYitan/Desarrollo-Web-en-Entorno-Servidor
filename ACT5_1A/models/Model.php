<?php
    namespace models;

    use Config\Database;

    class Model{
        public static function All(){ // 'select()'
            try{
                $config = Database::loadConfig('C:/temp/config.db'); // Carregar la connexió a la base de dades
                $db = new Database(
                    $config["DB_HOST"],
                    $config["DB_PORT"],
                    $config["DB_DATABASE"],
                    $config["DB_USERNAME"],
                    $config["DB_PASSWORD"]
                );
                $db->dbConnect(); // Realitzar la connexió a la base de dades
                $table = static::$table; // Obtenir el nom de la taula de la classe filla
                $sql = "SELECT * FROM $table";
                $result = $db->conn->query($sql); // Executar la consulta
                if($result->num_rows > 0){ // Comprovar si hi ha resultats
                    $rows = [];
                    while($row = $result->fetch_assoc()){
                        $rows[] = $row; // Guardar els registres en un array
                    }
                }
                else{
                    $rows = [];
                }
                $db->dbClose(); // Tancar la connexió a la base de dades
                return $rows; // Retornar els registres obtinguts
            }
            catch(Error $e){
                echo "Error: " . $e->getMessage();
            }
            catch(Exception $e){
                echo "Exception: " . $e->getMessage();
            }
            finally{
                $db->dbClose(); // Tancar la connexió a la base de dades
            }
        }
    }
?>