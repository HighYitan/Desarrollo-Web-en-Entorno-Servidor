<?php class Quadrat implements FiguraGeometrica, Coloreador{
    public function __construct(private ?string $color = null, private float $costat = 0){
    }
    public function calculaArea() : float{
        return pow($this->costat, 2);
    }
    public function aplicarColor(string $color) : void{
        $this->color = $color;
    }
    public function __toString() : string{
        return "Color: " . $this->color . " " . "Costat: " . $this->costat . " ";
    }
}?>