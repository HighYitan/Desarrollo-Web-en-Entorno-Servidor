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
    try{
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
        echo '<br>';
        // Eliminar l'empleat de la base de dades
        $employee = new Employee(1000);
        $employee->destroy();
    }
    catch(\Exception $e) {
        echo "S'ha produït el següent error:" . "<br>" . $e->getMessage();
    }
    $employees = Employee::all(); //SELECT
    echo "<pre>";
    print_r($employees);
    echo "</pre>";

    $customers = Customer::all(); //SELECT
    echo "<pre>";
    print_r($customers); // en comptes d'un foreach, també 'var_dump'
    echo "</pre>";
?>