<?php class Cercle implements FiguraGeometrica{
    public function __construct(private float $radi){
    }
    public function calculaArea() : float{
        return M_PI * pow($this->radi, 2);
    }
    public function __toString() : string{
        return "Radi: " . $this->radi . " ";
    }
}?>