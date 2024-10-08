<?php
    require_once("FiguraGeometrica.php");
    require_once("Coloreador.php");
    require_once("Cercle.php");
    require_once("Quadrat.php");
    $cercle = new Cercle(radi: 10);
    $quadrat = new Quadrat(costat: 10);
    echo $cercle->calculaArea();
    $cercle->aplicarColor("rojo");
    echo $cercle->__toString();
    echo $quadrat->calculaArea();
    $quadrat->aplicarColor("lila");
    echo $quadrat->__toString();
?>