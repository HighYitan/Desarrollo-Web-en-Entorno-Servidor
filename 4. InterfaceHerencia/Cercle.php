<?php class Cercle extends Coloreador implements FiguraGeometrica{
    public function __construct(private ?string $color = null, private float $radi = 0){
        parent::__construct($color);
    }
    public function calculaArea() : float{
        return M_PI * pow($this->radi, 2);
    }
    public function aplicarColor(string $color) : void{
        $this->color = $color;
    }
    public function __toString() : string{
        return parent::__toString() . "Radi: " . $this->radi . " ";
    }
}?>