<?php
namespace models;
use config\Database;
class Employee extends Model{
    protected static $table = "employees"; // Definir la taula associada a la classe
    public function __construct(
        private int $employee_id,
        private ?string $first_name = null,
        private ?string $last_name = null,
        private ?string $email = null,
        private ?string $phone_number = null,
        private ?string $hire_date = null,
        private ?string $job_id = null,
        private ?float $salary = null,
        private ?float $commission_pct = null,
        private ?int $manager_id = null,
        private ?int $department_id = null
    ){}
    public function save() : void{ // Mètode per guardar l'empleat a la base de dades
        try{
            $config = Database::loadConfig("C:/temp/config.db");
            $db = new Database(
                $config["DB_HOST"],
                $config["DB_PORT"],
                $config["DB_DATABASE"],
                $config["DB_USERNAME"],
                $config["DB_PASSWORD"]
            );
            $db->dbConnect(); // Connectar a la base de dades
            $table = static::$table; // Obtenir el nom de la taula de la classe filla
            // Preparar la consulta d'INSERT
            if (isset($this->employee_id)) {
                // Variant per a MySQL: executa INSERT/UPDATE a la vegada - Preparar l'INSERT / UPDATE
                $sql = "INSERT INTO $table (employee_id, first_name, last_name, email, phone_number, hire_date, job_id, salary, commission_pct, manager_id, department_id) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                        ON DUPLICATE KEY
                            UPDATE
                                first_name      = VALUES(first_name),
                                last_name       = VALUES(last_name),
                                email           = VALUES(email),
                                phone_number    = VALUES(phone_number),
                                hire_date       = VALUES(hire_date),
                                job_id          = VALUES(job_id),
                                salary          = VALUES(salary),
                                commission_pct  = VALUES(commission_pct),
                                manager_id      = VALUES(manager_id),
                                department_id   = VALUES(department_id)";
                $stmt = $db->getConn()->prepare($sql);
                $stmt->bind_param("issssssddii",
                                        $this->employee_id, 
                                        $this->first_name, 
                                        $this->last_name, 
                                        $this->email, 
                                        $this->phone_number, 
                                        $this->hire_date, 
                                        $this->job_id, 
                                        $this->salary, 
                                        $this->commission_pct, 
                                        $this->manager_id, 
                                        $this->department_id
				);
                			// Executar la consulta
                if ($stmt->execute()) {
                    echo "L'empleat s'ha afegit/modificat correctament.";
                } 
                else {
                    echo "Error en afegir/modificar l'empleat: " . $stmt->error;
                }
            }
            else {
                echo "Error, ID no informat";
            }
        }
        catch(Error $e){
            echo "Error: " . $e->getMessage();
        }
        catch(Exception $e){
            echo "Exception: " . $e->getMessage();
        }
        finally{
            $db->dbClose(); // Tancar la connexió
        }
    }
    public function destroy() : void{ // Mètode per eliminar l'empleat a la base de dades
        try{
            $config = Database::loadConfig("C:/temp/config.db"); // Carregar la configuració de la base de dades
            $db = new Database(
                $config["DB_HOST"],
                $config["DB_PORT"],
                $config["DB_DATABASE"],
                $config["DB_USERNAME"],
                $config["DB_PASSWORD"]
            );
            $db->dbConnect(); // Connectar a la base de dades
            $table = static::$table; // Obtenir el nom de la taula de la classe filla
            // Preparar la consulta del delete
            if (isset($this->employee_id)) {
                $sql = "SELECT * FROM $table WHERE employee_id = $this->employee_id";
                $result = $db->getConn()->query($sql);
                if($result->num_rows == 1){
                    $sql = "DELETE FROM $table WHERE employee_id = ?";
                    $stmt = $db->getConn()->prepare($sql);
                    $stmt->bind_param("i", $this->employee_id);
                    // Executar la consulta
                    if ($stmt->execute()) {
                        echo "L'empleat s'ha eliminat correctament.";
                    } 
                    else {
                        echo "Error en eliminar l'empleat: " . $stmt->error;
                    }
                }
                else{
                    echo "L'empleat no existeix.";
                }
            }
            else {
                echo "Error, ID no informat";
            }
        }
        catch(Error $e){
            echo "Error: " . $e->getMessage();
        }
        catch(Exception $e){
            echo "Exception: " . $e->getMessage();
        }
        finally{
            $db->dbClose(); // Tancar la connexió
        }
    }
}
?>