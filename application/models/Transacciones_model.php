<?php
class Transacciones_model extends CI_Model {

private $dbO;

    function __construct()
    {
      $this->dbO = $this->load->database('oracle', TRUE); 
    }

    public function obtenerUltimoId()
    {
      $this->dbO->select_max('ID');
      $result= $this->dbO->get('PORTAL_DML.VENTASLOTERIASPORTAL');
      return $result->row(); 
    }

    public function obtenerUltimoIdDetail()
    {
      $this->dbO->select_max('ID');
      $result= $this->dbO->get('PORTAL_DML.HISTORIALVENTAS');
      return $result->row(); 
    }

    public function insert($data)
    {
        $this->dbO->insert('PORTAL_DML.VENTASLOTERIASPORTAL', $data);
    }

    public function insert_sale($data)
    {
      $datePay = date('d-m-Y H:i:s');
      $sql = "INSERT INTO VENTASLOTERIASPORTAL (ID, REFERENCIA, MONEDA, VALORTOTAL, VALORIMPUESTO, MONEDABANCARIA, FACTORCONVERSION, NOMBRECOMPRADOR, EMAILCOMPRADOR, CIUDADCOMPRADOR, VALOR_BANCO, VALOR_SALDO, DESCUENTO_CLIENTE, PORCENTAJE_DESCUENTO, IDCOMPRADOR, FECHAPAGO, IDPASARELA) 
      VALUES (".$data['ID'].",'','COP',".$data['VALORTOTAL'].",0,'COP',1,'".$data['NOMBRECOMPRADOR']."','".$data['EMAILCOMPRADOR']."','13001',".$data['VALOR_BANCO'].",".$data['VALOR_SALDO'].",".$data['DESCUENTO_CLIENTE'].",0,'".$data['IDCOMPRADOR']."',to_date('".$datePay."','DD/MM/YYYY HH24:MI:SS'),'')";
      $this->dbO->query($sql);      
      if($this->dbO->affected_rows()>0){
        return true;
      } else {
        return false;
      }
    }

    public function insert_detail($data)
    {
      $this->dbO->insert('PORTAL_DML.HISTORIALVENTAS', $data);
    }

    public function get_detail($data)
    {
      $sql = 'SELECT lot.*,sor.VALORPREMIOMAYOR FROM PORTAL_DML.HISTORIALVENTAS lot LEFT JOIN PORTAL_DML.SORTEOS sor ON (sor.LOTERIA = lot.LOTERIA) AND (sor.SORTEO = lot.SORTEO) WHERE (lot.IDVENTASLOTERIASPORTAL = '.$data.')';
      $query = $this->dbO->query($sql);
      return $query->result();
    }

    public function obtenerRequestId($data)
    {
      $result = $this->dbO->get_where('PORTAL_DML.VENTASLOTERIASPORTAL',array('REFERENCIA'=>$data));
      return $result->row(); 
    }

    public function update($reference,$args)
    {
      $date = date('d-m-Y H:i:s',strtotime($args['FECHATRANSACCION']));
      $datePay = date('d-m-Y H:i:s',strtotime($args['FECHAPAGO']));
      $sql = "UPDATE VENTASLOTERIASPORTAL SET FECHATRANSACCION = to_date('".$date."','DD/MM/YYYY HH24:MI:SS'),
      NOMBRECOMPRADOR = '".$args['NOMBRECOMPRADOR']."', FECHAPAGO = to_date('".$datePay."','DD/MM/YYYY HH24:MI:SS'),
      FRANQUICIA = '".$args['FRANQUICIA']."', NUMEROAUTORIZACIONTX = '".$args['NUMEROAUTORIZACIONTX']."', NUMERORECIBO = '".$args['NUMERORECIBO']."', 
      ESTADOTRANSACCION = '".$args['ESTADOTRANSACCION']."', NUMEROFACTURA = '".$args['NUMEROFACTURA']."' WHERE REFERENCIA = ".$reference."";
      $this->dbO->query($sql);      
      if($this->dbO->affected_rows()>0){
        return true;
      } else {
        return false;
      }
    }

    public function getTransactionsUnApproved()
    {
      $sql = "SELECT * FROM VENTASLOTERIASPORTAL WHERE ESTADOTRANSACCION != 'APPROVED' OR ESTADOTRANSACCION != 'REJECTED' OR ESTADOTRANSACCION IS NOT NULL";
      $query = $this->dbO->get($sql);
      if($query->num_rows() > 0 ){
        return $query->result();
      } else {
        return false;
      }
    }
}
?>