<?php class Database{
    public function loadConfig(){
        try{
            $fitxer = "C:/temp/config.db";
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
                }*/
            }
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
    public function connectDatabase($map) : void{
        foreach($map as $key -> $value){
            echo $key . $value;
        }
    }
}?>