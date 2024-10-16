<?php 
    namespace Config;

    class Database{
        public $host, $port, $database, $username, $password, $conn; 
        public function __construct($host, $port, $database, $username, $password){ // Constructor que utilitza el mètode 'loadConfig' per carregar les dades de configuració
            $this->host = $host;
            $this->port = $port;
            $this->database = $database;
            $this->username = $username;
            $this->password = $password;
        }
        public static function loadConfig($fitxer) : array{
            try{
                $config = [];  // Inicialitzem un array buit per emmagatzemar les variables
                if(file_exists($fitxer)){// Verifiquem que el fitxer existeix
                    // Llegim el fitxer línia per línia
                    $linies = file($fitxer, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                    // Recórrer cada línia del fitxer
                    foreach($linies as $linia){
                        // Comprovam si la línia és un comentari
                        $linia = trim($linia); // netejam la línia d'espais
                        if(strpos(trim($linia), "#") !== 0){
                            list($clau, $valor) = explode("=", $linia, 2);
                            $config[trim($clau)] = trim($valor); // Emmagatzemam un element 'clau' i 'valor' a l'array associatiu
                        }
                    }
                }
                else{
                    die("El fitxer de configuració no existeix.");
                }
                return $config; // Retornam l'array associatiu amb les variables del fitxer
                               
                /*$fitxer = "C:/temp/config.db";
                if(file_exists($fitxer)){
                    //$file = fopen("C:/temp/config.db", "r");
                    $linies = file($fitxer, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                    $map = [];
                    foreach($linies as $linia){
                        if(!(str_starts_with($linia, "#"))){
                            echo $linia;
                            list($key, $value) = array_map("trim", explode("=", $linia, 2));
                            $map = array($key => $value);
                        }
                    }
                    return $map;
                    /*while(! feof($file)) {
                        $line = fgets($file);
                        if(!(str_starts_with($line, "#"))){
                            echo $line . "<br>";
                            str_split($line, )
                        }
                        //echo $line . "<br>";
                    }
                }*/
            }
            catch(Error $e){
                echo "Error: " . $e->getMessage();
            }
            catch(Exception $e){
                echo "Exception: " . $e->getMessage();
            }
            finally{
                //fclose($fitxer);
            }
        }
        public function dbConnect() : void{ // Mètode per connectar-se a la base de dades
            $this->conn = new \mysqli($this->host, $this->username, $this->password, $this->database, $this->port);
            if($this->conn->connect_error){ // Comprovem si hi ha errors en la connexió
                die("Error de la connexió: " . $this->conn->connect_error);
            }
            echo "Connexió a la base de dades correcta.<br>";
            /*foreach($config as $key -> $value){
                echo $key . $value;
            }*/
        }
        public function dbClose() : void{ // Mètode per tancar la connexió a la base de dades
            if($this->conn){
                $this->conn->close();
                echo "Connexió a la base de dades tancada.<br>";
            }
        }
        public function getConn(){
            return $this->conn;
        }
    }
?>