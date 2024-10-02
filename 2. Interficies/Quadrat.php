<?php class Quadrat implements FiguraGeometrica{
    public function __construct(private float $costat){
    }
    public function calculaArea() : float{
        return pow($this->costat, 2);
    }
    public function __toString() : string{
        return "Costat: " . $this->costat . " ";
    }
}?>