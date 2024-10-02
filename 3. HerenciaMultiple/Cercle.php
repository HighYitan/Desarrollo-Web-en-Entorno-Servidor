<?php class Cercle implements FiguraGeometrica, Coloreador{
    public function __construct(private ?string $color = null, private float $radi = 0){
    }
    public function calculaArea() : float{
        return M_PI * pow($this->radi, 2);
    }
    public function aplicarColor(string $color) : void{
        $this->color = $color;
    }
    public function __toString() : string{
        return "Color: " . $this->color . " " . "Radi: " . $this->radi . " ";
    }
}?>