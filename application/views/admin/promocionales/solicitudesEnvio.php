<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Premios Promocionales/Bienes o Servicios
      </h1>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-md-12">        
        <table class="table table-striped table-bordered" style="width:100%">
                <thead style="background-color:#C97C15; color:white;">
                    <tr>          
                        <th style="text-align: center;">Promocional</th>
                        <th style="text-align: center;">Loteria</th>
                        <th style="text-align: center;">Fecha Solicitud</th>
                        <th style="text-align: center;">Fecha recibido</th>
                        <th style="text-align: center;">Estado</th>
                        <th style="text-align: center;">Comprador</th>   
                        <th style="text-align: center;">Dirección</th>
                        <th style="text-align: center;">Email</th>
                        <th style="text-align: center;">Telefono</th> 
                        <th style="text-align: center;">Guia de envío</th> 
                        <th style="text-align: center;">Archivo cedula</th>
                        <th style="text-align: center;">Archivo formulario</th>
                        <th style="text-align: center;">Cambiar estado</th>
                        <th style="text-align: center;">Cancelar pedido</th>
                    </tr>
                </thead>
                <tbody style="text-align: center;">
                <?php if($solicitudes) { 
                        foreach($solicitudes as $solicitud){
                          $estado = 'SOLICITADO';
                          
                          if($solicitud->STATE == 3) {                                
                            $estado = 'ENTREGADO';
                          }
                          if($solicitud->STATE == 4) {                                
                            $estado = 'ENVIADO';
                          }
                          if($solicitud->STATE == 5) {                                
                            $estado = 'CANCELADO';
                          }
                    ?>
                        <tr>
                            <td><?= $solicitud->VALUE?></td>
                            <td><?= $solicitud->LOTTERY?></td>
                            <td><?= date_format(date_create($solicitud->CLAIM_DATE), 'd/m/y')?></td>
                            <td><?= date_format(date_create($solicitud->RECEPTION_DATE), 'd/m/y')?></td>
                            <td><?= $estado?></td>
                            <td><?= $solicitud->NAME?></td>
                            <td><?= $solicitud->ADDRESS?></td>
                            <td><?= $solicitud->EMAIL?></td>
                            <td><?= $solicitud->PHONE?></td>
                            <td><?= $solicitud->NUMGUIAENVIO?></td>
                            <td><a target="_blank" href="C:/xampp/htdocs/admin-lottired/assets/promocionales/<?= $solicitud->DNI_FILE?>">Descargar</a></td>
                            <td><a target="_blank" href="C:/xampp/htdocs/admin-lottired/assets/promocionales/<?= $solicitud->FORM_FILE?>">Descargar</a></td>
                            <td>
                            <?php if($solicitud->STATE != 5) {   ?>
                              <a href="#" title="Editar" onclick="cambiarEstadoSP(<?= $solicitud->ID ?>, <?= $solicitud->STATE?>)">
                                <i class="fa fa-random fa-2x" style="color:#E08E09"></i>
                              </a>
                            <?php } ?>
                            </td>
                            <td>
                            <?php if($solicitud->STATE == 2) {   ?>
                              <a href="#" title="cancelar" onclick="cancelarSolicitudSP(<?= $solicitud->ID ?>)">
                                <i class="fa fa-ban fa-2x" style="color:#E08E09"></i>
                              </a>
                            <?php } ?>
                            </td>
                        </tr>
                    <?php }} ?>
                </tbody>
            </table>
        </div>
        <!-- /.col-->        
      </div>
      <!-- ./row -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <script>
	
  </script>
