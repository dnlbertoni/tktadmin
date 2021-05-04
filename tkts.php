<?php
// consulta de tods los tiqckets
include_once('../../../include.inc');
include_once ('../include.inc');

$fechoy = new DateTime();
$fecdes=(isset($_GET['fecdes']))?$_GET['fecdes']:'2020-02-19';
$fechas=(isset($_GET['fechas']))?$_GET['fechas']:$fechoy->format('Y-m-d');
$sector=(isset($_GET['sector']))?$_GET['sector']:false;
$limit_ini=(isset($_GET['pag_ini']))?$_GET['pag_ini']:0;
$limit_fin=(isset($_GET['pag_fin']))?$_GET['pag_fin']:25;
$agente=(isset($_GET['agente'])&& trim($_GET['agente']) != '' )?' and s.idusuario = '. $_GET['agente'] .' ':' ';
$estados=(isset($_GET['estados']) && trim($_GET['estados']) != '')?' and s.idestado in (' .$_GET['estados'] .') ':'';
$est_default = "
AND (
    s.idestado not IN(25,20)
    or (
    s.idestado in (25,20) AND (
    fecalta BETWEEN '%s' AND NOW() OR fecfin BETWEEN '%s' AND NOW()
		 )
		) 
	)
";
$est_default = sprintf($est_default,$fecdes,$fechas);
$estados=($estados=='')?$est_default:$estados;

if(!$sector){
    $whereSector=' is null';
}else{
    $whereSector = '='.$sector;
}

$getTodos="SELECT 
date_format(s.fecalta,'%s') Fecha, s.id NroPedido,
 if (s.proyecto = 0 ,p.tipopedido, 'PROYECTO') Tipopedido,
 s.titulo Titulo,
 ifnull(upper (concat(usuempl.apellidos,', ',usuempl.nombres)),'') UsuarioAsignado,
 v.valor Estado,
  CONCAT('https://intranet.dilfer.com.ar/modulos/sistemas/index.php?modulo=pedido&comando=mostrar&id=',s.id) Link,
  ifnull(upper (concat(usuempl.apellidos,', ',usuempl.nombres)), upper (concat(usupot.apellidos,', ',usupot.nombres)) ) Usuariopotencial,
  vc.valor Complejidad,
  s.horasestimadas Horasestimadas,
  s.idproveedor,
  s.nro_tkt_externo
  FROM sis_pedidos s
inner join cmx_sectores sect on sect.id=s.idsector
left JOIN sis_pedidos_datos sd ON sd.idpedido=s.id
INNER JOIN cmx_valores v ON v.id=s.idestado
INNER JOIN cmx_tipospedido p ON p.id=s.idtipopedido
INNER JOIN cmx_usuarios cli ON cli.id=s.idcliente
INNER JOIN rrhh_empleados cliempl ON cliempl.id=cli.idempleado
left JOIN cmx_usuarios usu ON usu.id=s.idusuario
left JOIN rrhh_empleados usuempl ON usuempl.id=usu.idempleado
left JOIN cmx_usuarios upot ON upot.id=s.idusuario_potencial
left JOIN rrhh_empleados usupot ON usupot.id=upot.idempleado
left JOIN cmx_valores vc ON vc.id=s.complejidad
WHERE 1=1
AND p.idsector %s 
AND p.activo = 1
%s
order by s.fecalta desc
limit %d, %d
";
$getTodos = sprintf($getTodos,'%d/%m/%Y', $whereSector,$agente . $estados ,$limit_ini, $limit_fin);
//echo $getTodos;
$db = new ConsultasDAO();
$quey = $db->consultarMySQL($getTodos);

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");
header('Content-Type: application/json');

//echo $getTodos;
foreach ($quey as $index => $item) {
    foreach ($item as $i => $t) {
        $quey[$index][$i]=utf8_encode($t);
    }
    $res[$index]=$item;
}
echo json_encode($res);

