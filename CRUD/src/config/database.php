<?php 
namespace config;
class Database{
    private $conn; 
    public static function loadConfig($fitxer) : array{ // Mètode per carregar la configuració des del fitxer
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
        }
        catch(Error $e){
            echo "Error: " . $e->getMessage();
        }
        catch(Exception $e){
            echo "Exception: " . $e->getMessage();
        }
        finally{
            return $config; // Retornam l'array associatiu amb les variables del fitxer
        }
    }
    public function dbConnect($configPath) : void{ // Mètode per connectar-se a la base de dades
        $config = self::loadConfig($configPath); // Cridar a l'arxiu
        // Connectar a la base de dades
        $this->conn = new \mysqli($config['DB_HOST'], $config['DB_USERNAME'], $config['DB_PASSWORD'], $config['DB_DATABASE'], $config['DB_PORT']);
        $this->conn->autocommit(false);
        $this->conn->begin_transaction();
        if ($this->conn->connect_error) { // Comprovem si hi ha errors en la connexió
            die("Error en la connexió: " . $this->conn->connect_error);
        }
        //echo "Connexió a la base de dades correcta.<br>";
    }
    public function dbClose() : void{ // Mètode per tancar la connexió a la base de dades
        if($this->conn){
            $this->conn->close();
            //echo "Connexió a la base de dades tancada.<br>";
        }
    }
    public function getConn(){
        return $this->conn;
    }
}
?>