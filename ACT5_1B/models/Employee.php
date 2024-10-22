<?php
    namespace models;

    class Employee extends Model{
        protected static $table = "employees"; // Definir la taula associada a la classe
        public function __construct(private int $employee_id,
                                    private ?string $first_name = null,
                                    private ?string $last_name = null,
                                    private ?string $email = null,
                                    private ?string $phone_number = null,
                                    private ?string $hire_date = null,
                                    private ?string $job_id = null,
                                    private ?float $salary = null,
                                    private ?float $commission_pct = null,
                                    private ?int $manager_id = null,
                                    private ?int $department_id = null){}
    }
    public function save(){
        try{
            $config = Database::loadConfig("C:/temp/config.db");
            $db = new Database(
                $config["DB_HOST"],
                $config["DB_PORT"],
                $config["DB_DATABASE"],
                $config["DB_USERNAME"],
                $config["DB_PASSWORD"]
            );
            $db->dbConnect();
            $table = static::$table;
            $sql = "INSERT";
            $result = $db->getConn()->save($sql);
        }
        catch(Error $e){
            echo "Error: " . $e->getMessage();
        }
        catch(Exception $e){
            echo "Exception: " . $e->getMessage();
        }
        finally{
            $db->dbClose();
        }
    }
?>