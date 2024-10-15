<?php
    require_once __DIR__ . "/config/Database.php"; // Incloure l'arxiu de configuració i carregar la classe 'Config'

    use Config\Database;

    const CONFIG_FILE = "C:/temp/config.db"; // Definim la ruta del fitxer de configuració
    try{
        $config = Database::loadConfig(CONFIG_FILE); // Carreguem les variables de configuració
        // Mostrem l'array de configuració
        echo "<pre>";
        print_r($config); // en comptes d'un foreach, també 'var_dump'
        echo "</pre>";
        // Crear una instància de la classe 'Database' amb els valors de configuració
        $db = new Database(
            $config["DB_HOST"],
            $config["DB_PORT"],
            $config["DB_DATABASE"],
            $config["DB_USERNAME"],
            $config["DB_PASSWORD"]
        );
        // Realitzar la connexió a la base de dades
        $db->dbConnect();
        // Operacions amb la base de dades...
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