<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Manejo de Puntos/Reglas Puntos por compras
      </h1>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-md-9">        
            <table class="table">
                <thead style="background-color:#C97C15; color:white">
                    <tr>
                        <th style="text-align: center;">Loteria</th>
                        <th style="text-align: center;">Valor $</th>
                        <th style="text-align: center;">Cant. Puntos</th>
                        <th style="text-align: center;">Fecha inicio</th>
                        <th style="text-align: center;">Fecha final</th>
                        <th style="text-align: center;">Activar/Desactivar</th>
                    </tr>
                </thead>
                <tbody style="text-align: center;">
                <?php if($puntosCompras) { 
                        foreach($puntosCompras as $puntosCompra){
                            $estado = $puntosCompra->ESTADO;
                            if($estado == 0) {                                
                                $estado = '<input type="checkbox" data-toggle="toggle" data-size="mini" data-onstyle="warning" data-on="Activo" data-off="Inactivo" onchange="cambioEstadoReglaPuntosCompras('.$puntosCompra->ID.',1)">'; 
                            } else {                                
                                $estado = '<input type="checkbox" checked data-toggle="toggle" data-size="mini" data-onstyle="warning" data-on="Activo" data-off="Inactivo" onchange="cambioEstadoReglaPuntosCompras('.$puntosCompra->ID.',0)">';
                            }
                    ?>
                    <tr>
                        <td><?=$puntosCompra->LOTERIA?></td>
                        <td><?=$puntosCompra->VALOR?></td>
                        <td><?=$puntosCompra->PUNTOS?></td>
                        <td><?=$puntosCompra->FECHA_INICIO?></td>
                        <td><?=$puntosCompra->FECHA_FIN?></td>
                        <td><?=$estado?></td>
                    </tr>
                <?php } 
                }?>
                </tbody>
            </table>
        </div>
        <!-- /.col-->
        <div class="col-md-3" style="border: 1px solid #A8A8A8;">
            <h3 style="color:#C97C15"><b>Crea una regla de puntos</b></h3>
            <form id="frmPuntosCompras">
                <div class="form-group">
                    <label for="loterias">Selecciona Loteria</label>
                    <select name="loterias" id="loterias" class="form-control">
                        <option>Seleccione loteria</option>
                        <?php if($loterias) { foreach($loterias as $loteria){ ?>
                            <option value="<?= $loteria->LOTERIA?>"><?= $loteria->NOMBRE?></option>
                        <?php }} ?>
                    </select>
                </div>

                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="valor">Valor</label>
                            <input type="text" class="form-control" id="valor" name="valor" placeholder="$">
                        </div>
                        <!-- /form-group -->
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="puntos">Cantidad puntos</label>
                            <input type="text" class="form-control" id="puntos" name="puntos">
                        </div>
                        <!-- /form-group --> 
                    </div>
                </div> 
                <!-- /.col-lg-6 -->                                          
                
                <div class="row">
                    <div class="col-lg-6">                        
                        <div class="form-group">
                            <label for="fechaIni">Fecha de inicio</label>
                            <input type="date" class="form-control" id="fechaIni" name="fechaIni">
                        </div>
                        <!-- /form-group -->
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="fechaIni">Fecha fin</label>
                            <input type="date" class="form-control" id="fechaFin" name="fechaFin">
                        </div>
                        <!-- /form-group -->
                    </div> 
                </div>
                <!-- /.col-lg-6 -->                                  
                <div class="form-group">
                    
                <button type="submit" class="btn" style="background-color:#F5CE16; color:#0C6BB0; width:245px"><b>Crear</b></button>
                </div>
        </form>
        </div>
      </div>
      <!-- ./row -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <script>
	
  </script>
