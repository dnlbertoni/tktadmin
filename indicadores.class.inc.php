<?php
// consulta de tods los tiqckets
include_once('../../../include.inc');
include_once ('../include.inc');


class Indicadores {
    private $sql = null;
    private $query = null; 
    private $filtros = array();
    private $modo = 'json';

    function __construct(){
        $fechoy = new DateTime();
        $this->filtros['fecdes']=(isset($_GET['fecdes']))?$_GET['fecdes']:'2020-02-19';
        $this->filtros['fechas']=(isset($_GET['fechas']))?$_GET['fechas']:$fechoy->format('Y-m-d');
        $this->filtros['sector']=(isset($_GET['sector']))?$_GET['sector']:false;
        $this->filtros['agente']=(isset($_GET['agente'])&& trim($_GET['agente']) != '' )?' and s.idusuario = '. $_GET['agente'] .' ':' ';
        $this->filtros['estados']=(isset($_GET['estados']) && trim($_GET['estados']) != '')?' and s.idestado in (' .$_GET['estados'] .') ':'';
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
        $est_default = sprintf($est_default,$this->filtros['fecdes'],$this->filtros['fechas']);
        $this->filtros['estados']=($this->filtros['estados']=='')?$est_default:$this->filtros['estados'];
        
        if(!$this->filtros['sector']){
            $this->filtros['sector']=' is null';
        }else{
            $this->filtros['sector'] = '='.$this->filtros['sector'];
        }
    }

    function getSQL(){
        $getTodos="
            SELECT 'pendiente' kpi,format(count(1),0) q, 10 max, format(if(count(1)/10*100>99,99,count(1)/10*100),2) avance FROM sis_pedidos
            where idestado=18
            union 
            SELECT 'sol_bas_proy' kpi,
            format(count(1),0) q, 
            12 max, 
            format(if(count(1)/12*100>99,99,count(1)/12*100),2) avance 
            FROM sis_pedidos
            where idtipopedido=137 and idestado not in (25,20,312)
            union 
            select 
            'backlog' kpi,  
            format(sum(case when idestado in (25,20,312) then 1 else 0 end) /count(1),2) backlog,
            1 max,
            format(if(((sum(case when idestado in (25,20,312) then 1 else 0 end) /count(1)))*100>99,99,((sum(case when idestado in (25,20,312) then 1 else 0 end) /count(1)))*100),2) avance
            from sis_pedidos s 
            INNER JOIN cmx_tipospedido p ON p.id=s.idtipopedido
            WHERE 1=1
            AND p.idsector is null 
            AND p.activo = 1
            and fecalta > '2020-01-01'
        ";
        $this->sql = sprintf($getTodos);
        return $this->sql;
    }
    

    function setPagination(){
        $ini=(isset($_GET['pag_ini']))?$_GET['pag_ini']:0;
        $fin=(isset($_GET['pag_fin']))?$_GET['pag_fin']:25;
        $limit = sprintf(" limit %d, %d ",$ini,$fin);
        return $limit;
    }

    function runSql(){
        //echo $getTodos;
        if($this->sql === null){
            $this->getSQL();
        }
        $db = new ConsultasDAO();
        $this->query = $db->consultarMySQL($this->sql);
        return $this->query;
    }

    function setHeadersJson(){
        if($this->modo=='json'){
            header('Access-Control-Allow-Origin: *');
            header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
            header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
            header("Allow: GET, POST, OPTIONS, PUT, DELETE");
            header('Content-Type: application/json');        
        }
        return true;
    }

    function responseData(){
        setlocale(LC_NUMERIC,'nl_NL.UTF-8@euro');
        if($this->query ===  null ){
            $this->runSql();
        }
        foreach ($this->query as $index => $item) {
            foreach ($item as $i => $t) {
                
                if(is_numeric($t)){
                    $quey[$index][$i]=number_format($t, 2, ',', '.');
                }else{
                    $quey[$index][$i]=utf8_encode($t);
                }
            }
            $res[$index]=$item;
        };
        $this->setHeadersJson();
        if ($this->modo=='json'){
            return json_encode($res);
        }else{
            return $res;
        }
    }

}
