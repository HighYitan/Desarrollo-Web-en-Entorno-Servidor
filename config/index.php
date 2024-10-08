<?php
    const HOST = "localhost";
    const DBNAME = "HR";
    const USERNAME = "root";
    const PASSWD = "";

    try{
        $conn = new mysqli(HOST, USERNAME, PASSWD, DBNAME); 
        $query = 'SELECT last_name, first_name FROM employees';
        $table = $conn->query($query); 	// query the database
        foreach ($table as $row) {
            echo "<br>First name Last name</br><br></br>";  			// fetch the database
            foreach ($row as $column) {
                echo $column . ' '; 
		    }
            echo "<br></br>";
	    }
   
    }
    catch(mysqli_sql_exception $e){
        echo "Error connecting to MYSQL: " . $e->getMessage();
    }
    finally{
        $conn->close();
    }
?>