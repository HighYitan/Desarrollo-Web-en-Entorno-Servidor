<?php class Quadrat extends FiguraGeometrica{
    public function __construct(private float $costat, string $colour){
        parent::__construct($colour);
    }
    public function calculaArea() : float{
        return pow($this->costat, 2);
    }
    public function __toString() : string{
        return parent::__toString() . "Costat: " . $this->costat . " ";
    }
    public function equals(Quadrat $e) : bool{
        return (parent::equals($e)) && ($this->costat == $e->costat);
    }
}?>