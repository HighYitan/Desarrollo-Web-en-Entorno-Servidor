<?php abstract class FiguraGeometrica{
    public function __construct(protected string $colour){

    }
    public abstract function calculaArea() : float;
    public function __toString() : string{
        return "Color: " . $this->colour . " ";
    }
    public function equals(FiguraGeometrica $e) : bool{
        return ($this->colour == $e->colour);
    }
}?>