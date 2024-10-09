<?php
    require_once("./config/Database.php");
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
    }
?>