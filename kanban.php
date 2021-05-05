<?php
// consulta de tods los tiqckets
include_once('../../../include.inc');
include_once ('../include.inc');

$fechoy = new DateTime();

$getTodos="
SELECT 
sp.idusuario,  
cu.usuario Agente ,  
sum(case when sp.idestado in (18) then 1 else 0 end) pending,
sum(case when sp.idestado in (315,316,321) then 1 else 0 end) backlog,
sum(case when sp.idestado in (19,21,274,280) then 1 else 0 end) todo,
sum(case when sp.idestado in (313) then 1 else 0 end) delivered,
ifnull(fin.q,0)  fin
FROM ( select 
			case when idusuario > 0 and idusuario is not null 
				 then idusuario 
				 else idusuario_potencial end idusuario,
			 idtipopedido, 
			 idestado, 
			 fecalta, 
			 fecfin, 
			 proyecto, 
			 idusuario_potencial 
			 from sis_pedidos ) sp
inner join cmx_tipospedido ct on ct.id = sp.idtipopedido 
left join (
select u.id id,upper(CONCAT(re.apellidos, ', ', re.nombres)) usuario, u.activo activo from cmx_usuarios u
inner join rrhh_empleados re on re.id=u.idempleado
) cu on cu.id = sp.idusuario 
left join (  
select spx.idusuario usu, count(1) q from sis_pedidos spx 
inner join cmx_tipospedido ctx on ctx.id = spx.idtipopedido 
where 1=1
and ctx.idsector is null
and ctx.activo = 1
and spx.proyecto =0 
and CONCAT(YEAR(spx.fecfin), WEEK(spx.fecfin,4))=CONCAT(YEAR (NOW()),WEEK(NOW(),4) ) 
group by spx.idusuario
) fin on fin.usu=sp.idusuario 
where 1=1
and ct.idsector is null
and ct.activo = 1
and cu.activo = 1
and sp.fecfin ='0000-00-00'
and fecalta > '2020-01-01'
and sp.idestado not in (20)
and sp.proyecto =0   
group by sp.idusuario
order by 2
";
//$getTodos = sprintf($getTodos,'%d/%m/%Y',$fecdes,$fechas, $whereSector, $limit_ini, $limit_fin);
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

