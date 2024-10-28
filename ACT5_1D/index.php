<?php
    spl_autoload_register(function($classe){
        if(file_exists(str_replace("\\", "/", $classe) . ".php")){
            require_once(str_replace("\\", "/", $classe) . ".php");
        }
    });
    /*
    require_once __DIR__ . "/config/Database.php"; // Incloure l'arxiu de configuració i carregar la classe 'Database'
    require_once __DIR__ . "/models/Model.php";
    require_once __DIR__ . "/models/Employee.php";
    */
    use config\Database;
    use models\Employee;
    use models\Customer;
    // Crear una nova instància d'Employee i assignar valors
    $employee = new Employee(
        1000,
        "Khris",
        "Yitán",
        "cfl01@iesemilidarder.com",
        "123456789",
        "2024-08-13",
        "AD_VP",
        60000.00,
        0.05,
        null,
        60
    );
    // Guardar l'empleat a la base de dades
    $employee->save();  // INSERT / UPDATE
    // Eliminar l'empleat de la base de dades
    $employee = new Employee(1000);
    $employee->destroy();
    $employees = Employee::all(); //SELECT
    echo "<pre>";
    print_r($employees);
    echo "</pre>";

    $customers = Customer::all(); //SELECT
    echo "<pre>";
    print_r($customers); // en comptes d'un foreach, també 'var_dump'
    echo "</pre>";

    /*require_once("./config/Database.php");
    const HOST = "localhost";
    const DBNAME = "HR";
    const USERNAME = "root";
    const PASSWD = "";
    $database = new Database();
    try{
        $conn = new mysqli(HOST, USERNAME, PASSWD, DBNAME);
        $conn->autocommit(false);
        $query = 'SELECT last_name, first_name FROM employees';
        $table = $conn->query($query); 	// query the database
        foreach ($table as $row) {
            echo "<br>First name Last name</br><br></br>";  			// fetch the database
            foreach ($row as $column) {
                echo $column . ' '; 
		    }
            echo "<br></br>";
	    }
        $map = $database->loadConfig();
        $database->connectDatabase($map);
        //$conn->commit();
    }
    catch(mysqli_sql_exception $e){
        //$conn->rollback();
        echo "Error connecting to MYSQL: " . $e->getMessage();
    }
    finally{
        try{
            $conn->close();
        }
        catch(mysqli_sql_exception $e){

        }
    }*/
?>