<?php class Cercle extends FiguraGeometrica{
    public function __construct(private float $radi, string $colour){
        parent::__construct($colour);
    }
    public function calculaArea() : float{
        return M_PI * pow($this->radi, 2);
    }
    public function __toString() : string{
        return parent::__toString() . "Radi: " . $this->radi . " ";
    }
    public function equals(Cercle $e) : bool{
        return (parent::equals($e)) && ($this->radi == $e->radi);
    }
}?>