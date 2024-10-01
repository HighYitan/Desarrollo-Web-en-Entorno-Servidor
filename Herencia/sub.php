<?php class Estudiant{
    public function __construct(private string curs, string $nom, int $edat){
        parent::__construct($nom, $edat);
    }
    public function mostraEstudiant() : void{
        echo parent::mostraPersona() . "Curs: " . this->$edat;
    }
}
?>