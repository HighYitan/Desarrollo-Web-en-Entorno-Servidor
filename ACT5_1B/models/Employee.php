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
    public function save(){ // Mètode per guardar l'empleat a la base de dades
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
            $sql = "INSERT INTO $table (employee_id, first_name, last_name, email, phone_number, hire_date, job_id, salary, commission_pct, manager_id, department_id) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $db->getConn()->prepare($sql);
            //$result = $db->getConn()->save($sql);
            $stmt->bind_param( // Vincular els valors
                "issssssddii", 
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
                echo "L'empleat s'ha afegit correctament.";
            } else {
                echo "Error en afegir l'empleat: " . $stmt->error;
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