<?php class Persona{
    public function __construct(protected int ?$altura, protected int ?$ancho){
        
    }
    public function mostraPersona() : void{
        echo "Nom: " . $this->nom . " " . " Edat: " . $this->edat;
    }
}