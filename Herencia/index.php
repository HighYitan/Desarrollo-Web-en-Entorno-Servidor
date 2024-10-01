<?php
    require_once("FiguraGeometrica.php");
    require_once("Cercle.php");
    require_once("Quadrat.php");
    $cercle = new Cercle(10, "rojo");
    $quadrat = new Quadrat(10, "lila");
    echo $cercle->calculaArea();
    echo $cercle->__toString();
    echo $quadrat->calculaArea();
    echo $quadrat->__toString();
?>