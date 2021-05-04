<?php
// consulta de tods los tiqckets
include_once('../../../include.inc');
include_once ('../include.inc');
include_once('indicadores.class.inc.php');

$fechoy = new DateTime();

$servicio = (isset($_GET['servicio']))?$_GET['servicio']:0;

//echo $getTodos;

if( $servicio=='kpi'){
    //echo $servicio;
    $kpi = new Indicadores();
    echo $kpi->responseData();
};
