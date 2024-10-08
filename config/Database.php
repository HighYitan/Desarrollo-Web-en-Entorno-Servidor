<?php class Database{
    public function readFile(){
        try{
            if(file_exists("/temp/config.db")){
                $file = fopen("/tmp/example.txt", "r");
                while(! feof($file)) {
                    $line = fgets($file);
                    echo $line . "<br>";
                }    
            }
        }
        catch(Error $e){
            echo "Error: " . $e->getMessage();
        }
        catch(Exception $e){
            echo "Exception: " . $e->getMessage();
        }
        finally{
            fclose($file);
        }
    }
    public function connectDatabase() : void{

    }
}?>