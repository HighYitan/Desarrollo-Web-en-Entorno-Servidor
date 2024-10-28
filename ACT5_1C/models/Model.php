<?php
    namespace models;

    use config\Database;

    abstract class Model{
        public static function All(){ // 'select()' Mètode per obtenir totes les files de la taula
            try{
                $config = Database::loadConfig("C:/temp/config.db"); // Carregar la connexió a la base de dades
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
                $result = $db->getConn()->query($sql); // Executar la consulta
                $rows = [];
                if($result->num_rows > 0){ // Comprovar si hi ha resultats
                    while($row = $result->fetch_assoc()){
                        // Crear un nou objecte de tipus 'Employee', 'Customer', ...
					    $employee = new static( ...array_values($row) );

                        // Afegir l'objecte a l'array
                        $rows[] = $employee;
                    }
                }
            }
            catch(Error $e){
                echo "Error: " . $e->getMessage();
            }
            catch(Exception $e){
                echo "Exception: " . $e->getMessage();
            }
            finally{
                $db->dbClose(); // Tancar la connexió a la base de dades
                return $rows; // Retornar els registres obtinguts
            }
        }
    }
?>