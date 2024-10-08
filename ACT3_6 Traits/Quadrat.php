<?php class Quadrat extends Coloreador implements FiguraGeometrica{
    public function __construct(protected ?string $color = null, private float $costat = 0){
        parent::__construct($color);
    }
    public function calculaArea() : float{
        return pow($this->costat, 2);
    }
    public function aplicarColor(string $color) : void{
        $this->color = $color;
    }
    public function __toString() : string{
        return parent::__toString() . "Costat: " . $this->costat . " ";
    }
}?>