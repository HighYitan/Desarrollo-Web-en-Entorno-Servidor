<?php abstract class Coloreador{
    public function __construct(protected ?string $color = null){

    }
    public abstract function aplicarColor(string $color) : void;
    public function __toString() : string{
        return "Color: " . $this->color . " ";
    }
}?>