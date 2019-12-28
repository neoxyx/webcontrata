<?php
class Candidates_model extends CI_Model {

	public function findAll($email = '', $pass = '', $encry = false)
    {
		if(!$email){
			$query = $this->dbO->get('PORTAL_DML.COMPRADORES');
			  if( $query->num_rows() > 0){
			   return $query->result();
			  } else {
			  	  return false;
			  }
		} else {        
            if(!$encry)
			    $pass = md5($pass); 
            $sql = "SELECT * FROM PORTAL_DML.COMPRADORES WHERE EMAIL = '$email' AND CLAVE = '$pass'";    
            $query = $this->dbO->query($sql);
            if( $query->num_rows() > 0){
                return $query->row();
            } else {
                return false;
            }
		}
	}

    public function findCount($email)
    {
		$sql = "SELECT * FROM PORTAL_DML.COMPRADORES WHERE EMAIL = '$email'";    
        $query = $this->dbO->query($sql);
        if( $query->num_rows() > 0){
            return $query->row();
        } else {
            return false;
        }
		
    }
    
    public function find($email){
        $query = $this->dbO->get_where('PORTAL_DML.COMPRADORES', array('EMAIL'=> $email));
            if( $query->num_rows() > 0){
                return $query->row();
            } 
            else {
                return false;
            }
    }
    
    public function findCed($cedula){
        $sql = "SELECT * FROM PORTAL_DML.COMPRADORES WHERE CEDULA IN (select ENCRIPTARDATOS('".$cedula."') as cedula from DUAL)";
        $query = $this->dbO->query($sql);
            if( $query->num_rows() > 0){
                return $query->row();
            } 
            else {
                return false;
            }
    }

    public function findEmail($email){
        $sql = "SELECT * FROM PORTAL_DML.COMPRADORES WHERE EMAIL = '".$email."'";
        $query = $this->dbO->query($sql);
            if( $query->num_rows() > 0){
                return $query->row();
            } 
            else {
                return false;
            }
    }

    public function findXid($id){
        $sql = "SELECT C.*, DESENCRIPTARDATOS(C.CEDULA, '2011cneb') AS CEDULA1 FROM PORTAL_DML.COMPRADORES C WHERE ID = $id";
        $query = $this->dbO->query($sql);
            if( $query->num_rows() > 0){
                return $query->row();
            } 
            else {
                return false;
            }
    }

    public function getCedula($cedula){        
        $sql = "SELECT C.*, DESENCRIPTARDATOS(C.CEDULA, '2011cneb') AS CEDULA1 FROM PORTAL_DML.COMPRADORES C WHERE C.CEDULA = '".$cedula."'";
        $query = $this->dbO->query($sql);
            if( $query->num_rows() > 0){
                return $query->row();
            } 
            else {
                return false;
            }
    }
    
    public function changePassword($email, $nPass){
        $nPass = md5($nPass); 
        $this->dbO->set('CLAVE', $nPass);
        $this->dbO->set('ESTADO', 1);
        $this->dbO->where('EMAIL', $email);
        return $this->dbO->update('PORTAL_DML.COMPRADORES');
    }
    
    public function updateUser($id, $fecha, $sexo, $correo, $telefono, $celular, $Departamento, $ciudad, $direccion){
        
        $this->dbO->set('FECHANACIMIENTO', "to_date('$fecha','dd/mm/yyyy')",false);
        $this->dbO->set('SEXO', $sexo);
        $this->dbO->set('EMAIL', $correo);
        $this->dbO->set('TELEFONO', $telefono);
        $this->dbO->set('CELULAR', $celular);
        $this->dbO->set('DEPARTAMENTO', $Departamento);
        $this->dbO->set('CIUDAD', $ciudad);
        $this->dbO->set('DIRECCION', $direccion);
        $this->dbO->where('ID', $id);
        return $this->dbO->update('PORTAL_DML.COMPRADORES');
    }

    public function saveCodAct($correo, $codAct){
        $this->dbO->set('CODIGOACTIVACION', $codAct);
        $this->dbO->where('EMAIL', $correo);
        return $this->dbO->update('PORTAL_DML.COMPRADORES');
    }

    public function verificarCodigoActivacion($codAct){
        $query = $this->dbO->get_where('PORTAL_DML.COMPRADORES', array('CODIGOACTIVACION'=> $codAct));
        if($query->num_rows() > 0){               
            return true;
        } 
        else {
            return false;
        }
    }
    
    public function activarCuenta($cod,$data){
        $this->dbO->where('CODIGOACTIVACION', $cod);
        $this->dbO->update('PORTAL_DML.COMPRADORES',$data);
        if($this->dbO->affected_rows()>0) {
            return true;
        } else {
            return false;
        }
    }

    public function get_departamentos(){
        $sql = 'SELECT * FROM PORTAL_DML.DEPARTAMENTOS ORDER BY NOMBRE ASC';
        $query = $this->dbO->query($sql);
        if($query->num_rows() > 0){               
            return $query->result();
        } 
        else {
            return false;
        }
    }

    public function misCompras($userId)
        {
            $sql = "SELECT * FROM Ventasloteriasportal v WHERE v.idComprador IN (SELECT CEDULA FROM COMPRADORES WHERE ID = '".$userId."') ORDER BY v.fechaTransaccion DESC";
            $query = $this->dbO->query($sql);
            if($query->num_rows() > 0){
                return $query->result();
            } else {
               return array();;
            }
        }
        //Obtiene compras del día de un usuario
        public function misComprasXdia($userId)
        {
            $day = date("d/m/y");
            $sql = "SELECT sum(VALORTOTAL) AS SUMDAY FROM VENTASLOTERIASPORTAL v WHERE v.idComprador IN (SELECT CEDULA FROM COMPRADORES WHERE ID = '".$userId."') AND v.ESTADOTRANSACCION = 'APPROVED' AND TO_CHAR(v.FECHATRANSACCION,'DD/MON/YY') = '".$day."'";
            $query = $this->dbO->query($sql);
            if($query->num_rows() > 0){
                return $query->row();
            } else {
               return array();
            }
		}
        
        public function get_misReferidos($userId)
        {
			$sql = "SELECT * FROM INVITACION_REFERIDOS r WHERE r.referidor = (select DESENCRIPTARDATOS((SELECT CEDULA FROM COMPRADORES WHERE ID = '".$userId."'), '2011cneb') as cedula from DUAL) ORDER BY r.FECHA_INVITACION DESC";
            $query = $this->dbO->query($sql);
            if($query->num_rows() > 0){
                return $query->result();
            } else {
               return array();
            }
        }
        
        public function get_mrAprobados($userId)
        {
			$sql = "SELECT * FROM Compradores c WHERE c.padre = (select DESENCRIPTARDATOS((SELECT CEDULA FROM COMPRADORES WHERE ID = '".$userId."'), '2011cneb') as cedula from DUAL) ORDER BY c.FECHA_REGISTRO DESC";
            $query = $this->dbO->query($sql);
            if($query->num_rows() > 0){
                return $query->result();
            } else {
               return array();
            }
        }
        
        public function misPremios()
        {
            $sql = "SELECT * FROM(SELECT l.nombre as producto, h.sorteoloteria, h.serie, h.numero, e.DESCRIPCION as estado, 
                    count(*) as fracciones, p.consecutivo as consecutivo, p.VALORPREMIONETO
                    FROM historicoventasloterias  h, premiosloterias p, loterias l, 
                    (SELECT * FROM estados WHERE TIPO = 'PAGOPREMIOLOT') e 
                    WHERE h.agencia = '01' AND h.comprador in (SELECT CEDULA FROM COMPRADORES WHERE ID = '".$this->session->id."')
                    AND h.producto = p.producto AND h.sorteoloteria = p.sorteoloteria 
                    AND h.numero = p.numero AND h.serie = p.serie AND h.fraccion = p.fraccion 
                    AND h.producto = l.loteria AND p.estado = e.estado GROUP BY l.nombre, 
                    h.sorteoloteria, h.serie, h.numero,e.DESCRIPCION,p.consecutivo,h.fechaventa, p.VALORPREMIONETO
                    ORDER BY h.fechaventa DESC) WHERE ROWNUM <= 10";
                    
            $query = $this->dbO->query($sql);
            if($query->num_rows() > 0){
                return $query->result();
            } else {
               return array();
            }
        }
        
        public function cambiarEstadoPremio($conse, $estado)
        {
			$sql = "UPDATE PORTAL_DML.premiosloterias SET ESTADO = '".$estado."' WHERE CONSECUTIVO = '".$conse."'";
            return $this->dbO->query($sql);
        }

        public function verificarReferido($correo)
        {
            $sql1 = "SELECT * FROM COMPRADORES WHERE email = '".$correo."'";
            $query1 = $this->dbO->query($sql1);
            $sql2 = "SELECT * FROM INVITACION_REFERIDOS WHERE REFERIDO = '".$correo."'";
            $query2 = $this->dbO->query($sql2);
            if(!($query1->num_rows() > 0 || $query2->num_rows() > 0)){
                return true;
            } else {
               return false;
            }
        }

        public function agregarReferido($correoRef, $userId)
        {
            $fech = getdate();
            $hoy = $fech['mday']."/".$fech['mon']."/".$fech['year'];
            $sql = "INSERT INTO INVITACION_REFERIDOS (ID, REFERIDOR, REFERIDO, FECHA_INVITACION) VALUES ((SELECT ID+1 FROM(SELECT ID FROM INVITACION_REFERIDOS ORDER BY ID DESC) WHERE ROWNUM = 1),(select DESENCRIPTARDATOS((SELECT CEDULA FROM COMPRADORES WHERE ID = '".$userId."'), '2011cneb') as cedula from DUAL), '".$correoRef."', to_date('".$hoy."','dd/mm/yyyy'))";
            $query = $this->dbO->query($sql);
        }

        public function getCiudades($dpto)
        {
            $sql = "SELECT CIUDAD, NOMBRE FROM ciudades WHERE CIUDAD LIKE '".$dpto."%'";
            $query = $this->dbO->query($sql);
            if($query->num_rows() > 0){               
                return $query->result();
            } 
            else {
                return false;
            }
        }

        public function getFechaNac($id){
            $sql = "SELECT TO_CHAR(FECHAnACIMIENTO, 'YYYY-MM-DD') AS FECHANACIMIENTO FROM PORTAL.COMPRADORES WHERE ID = '".$id."'";
            $query = $this->dbO->query($sql);
            if($query->num_rows() > 0){               
                return $query->row();
            } 
            else {
                return false;
            }
        }

        public function getMisPromocionales(){
            $sql = "SELECT DISTINCT * FROM(SELECT p.empresa AS empresa, p.tipoCanal AS tipoCanal, p.sorteo AS sorteoPromocional, p.consecutivoventa, 
                    p.loteria,l.nombre AS nombreloteria,p.premiopromocional,pr.descripcion AS promocional,v.sorteoloteria AS sorteo,
                    v.numero,v.serie, h.IDVENTASLOTERIASPORTAL AS Referencia, s.totalfracciones AS fracciones 
                    FROM ( 
                        SELECT consecutivo, producto, sorteoloteria, numero, serie, fraccion, fechaventa 
                        FROM ventasloterias 
                        WHERE comprador = '05BFA7F22CF10384FAF57397B8E7706A' 
                        UNION 
                        SELECT consecutivo,producto,sorteoloteria,numero,serie,fraccion,fechaventa 
                        FROM historicoventasloterias 
                        WHERE comprador = '05BFA7F22CF10384FAF57397B8E7706A' 
                    ) v, 
                    detallepremiospromocionales p, 
                    loterias l, 
                    premiospromocionales pr, 
                    historialventas h, ventasloteriasportal vt,
                    sorteos s, plandepremiospromocionales pl) WHERE ROWNUM <= 10";

            $query = $this->dbO->query($sql);
            if($query->num_rows() > 0){               
                return $query->result();
            } 
            else {
                return false;
            }
        }

        public function validarReferido($correo)
        {
            $sql = "SELECT (SELECT ID FROM COMPRADORES WHERE CEDULA = ENCRIPTARDATOS(ir.REFERIDOR)) as USERID, ir.* FROM invitacion_referidos ir WHERE ir.REFERIDO = '".$correo."'";
            $query = $this->dbO->query($sql);
            if($query->num_rows() > 0){               
                return $query->row();
            } 
            else {
                return false;
            }
        }

        public function updatePadre($referidor, $email)
        {
            $sql = "UPDATE PORTAL_DML.COMPRADORES SET PADRE = (select DESENCRIPTARDATOS((SELECT CEDULA FROM COMPRADORES WHERE ID = '".$referidor."'), '2011cneb') as cedula from DUAL)  WHERE ID IN (SELECT ID FROM COMPRADORES WHERE EMAIL = '".$email."')";
            $this->dbO->query($sql); 
        }

        public function deleteReferido($correo)
        {
            $sql = "DELETE FROM invitacion_referidos WHERE REFERIDO = '".$correo."'"; 
            $this->dbO->query($sql); 
        }

        public function validarPC($correo)
        {
            $mac = getHostByName(getHostName())." ".$_SERVER['HTTP_USER_AGENT']; 
            $sql = "select * from LOGIN_DISPOSITIVO WHERE EMAIL = '".$correo."' AND MAC = '".$mac."'"; 
            $query = $this->dbO->query($sql); 
            if($query->num_rows() == 0){               
                return true;
            } 
            else {
                return false;
            }
        }

        public function registrarDispositivo($correo, $mac, $id){
            if($this->validarPC($correo)){
                $sql = "INSERT INTO LOGIN_DISPOSITIVO (USERID, EMAIL, MAC) values ('".$id."', '".$correo."', '".$mac."')";
                $this->dbO->query($sql);
            }
        }

        public function detalleCompra($id)
        {
            $sql = "select * from historialventas h, Ventasloteriasportal v where h.IDVENTASLOTERIASPORTAL = v.ID AND v.ID = '".$id."' ORDER BY fechaTransaccion DESC";
            $query = $this->dbO->query($sql);
            if($query->num_rows() > 0){               
                return $query->result();
            } 
            else {
                return false;
            }
        }

        public function misPuntos()
        {
            $puntos = 0;
            $sql = "SELECT * from PROMOCIONALESPORTAL";
            $query = $this->dbO->query($sql);
            if($query->num_rows() > 0){      
                $promociones = $query->result(); 
                foreach($promociones as $prom){ 
                    if(strcmp($prom->REFERIDOS, 'S') !== 0){
                        $sql = "SELECT * as cant FROM Compradores WHERE c.padre IN (select DESENCRIPTARDATOS((SELECT CEDULA FROM COMPRADORES WHERE ID = '".$this->session->id."') AND c.fechaRegistro BETWEEN '".$prom->FECHAINICIO."' AND '".$prom->FECHAFIN."'";
                        $query = $this->dbO->query($sql);
                        
                        $cantidadReferidos = $query->num_rows();
                        $indiceReferidos = $cantidadReferidos / $prom->R_CANT_REFERIDOS_PUNTO;
                        $puntos += $indiceReferidos * $prom->R_PUNTOS_ENTREGAR;
                    }
                    if(strcmp($prom->VENTAS, 'S') !== 0){
                        $sql = "select sum(venta) as totalVenta FROM ".
                         "(select sum(ventabruta) as venta from ventasloterias WHERE fechaventa ".
                         "BETWEEN to_date('".$prom->FECHAINICIO."','YYYY-MM-DD HH24:MI:SS') ".
                         "AND  to_date('".$prom->FECHAFIN."','YYYY-MM-DD HH24:MI:SS') ".
                         "AND comprador IN (SELECT CEDULA FROM COMPRADORES WHERE ID = '".$this->session->id."') AND estado = '02' ".
                         "union ".
                         "select sum(ventabruta) as venta from historicoventasloterias WHERE fechaventa ".
                         "BETWEEN to_date('".$prom->FECHAINICIO."','YYYY-MM-DD HH24:MI:SS') ".
                         "AND  to_date('".$prom->FECHAFIN."','YYYY-MM-DD HH24:MI:SS') ".
                         "AND comprador IN (SELECT CEDULA FROM COMPRADORES WHERE ID = '".$this->session->id."'))";
                        $query = $this->dbO->query($sql);

                        $indice = ($query->row()->totalVenta / $prom->V_CANT_VENTAS_PUNTO);
                        $puntos += $indice * $prom->V_PUNTOS_ENTREGAR;

                        $sql = "select sum(venta) as totalVenta FROM ".
                         "(select sum(ventabruta) as venta from ventasloterias v, compradores c WHERE v.fechaventa ".
                         "BETWEEN to_date('".$prom->FECHAINICIO."','YYYY-MM-DD HH24:MI:SS') ".
                         "AND  to_date('".$prom->FECHAFIN."','YYYY-MM-DD HH24:MI:SS') ".
                         "AND c.padre IN (select DESENCRIPTARDATOS((SELECT CEDULA FROM COMPRADORES WHERE ID = '".$this->session->id."') AND v.comprador = c.cedula AND v.estado = '02'".
                         "union ".
                         "select sum(ventabruta) as venta from historicoventasloterias v, compradores c WHERE v.fechaventa ".
                         "BETWEEN to_date('".$prom->FECHAINICIO."','YYYY-MM-DD HH24:MI:SS') ".
                         "AND  to_date('".$prom->FECHAFIN."','YYYY-MM-DD HH24:MI:SS') ".
                         "AND c.padre IN (select DESENCRIPTARDATOS((SELECT CEDULA FROM COMPRADORES WHERE ID = '".$this->session->id."') AND v.comprador = c.cedula)";
                        $query = $this->dbO->query($sql);

                        $indice = ($query->row()->totalVenta / $prom->V_CANT_VENTAS_PUNTO);
                        $puntos += $indice * $prom->V_PUNTOS_ENTREGAR;
                    }
                }
            } 
            return $puntos;
        }

        public function registrarSaldoInicial($valor, $compromiso){
            $sql = "UPDATE SALDO_INICIAL SET ESTADO = 0";
            $this->dbO->query($sql);

            $sql = "INSERT INTO SALDO_INICIAL (VALOR, ESTADO, ID_COMPROMISO) values ('".$valor."', '1', '".$compromiso."')";
            $this->dbO->query($sql);
        }
}
?>