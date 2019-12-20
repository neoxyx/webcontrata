<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Manejo de Puntos/Pedidos
      </h1>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-md-12">        
            <table class="table table-striped table-bordered" style="width:100%">
                <thead style="background-color:#C97C15; color:white;">
                    <tr>
                        <th style="text-align: center;">Id Solicitud</th>
                        <th style="text-align: center;">Nombre Articulo</th>
                        <th style="text-align: center;">Nombre Cliente</th>
                        <th style="text-align: center;">Costo Puntos</th>
                        <th style="text-align: center;">Fecha Solicitud</th>
                        <th style="text-align: center;">Fecha Recibido</th>
                        <th style="text-align: center;"># Guia de envío</th>
                        <th style="text-align: center;">Estado</th>
                        <th style="text-align: center;">Cambiar estado</th>
                        <th style="text-align: center;">Cancelar pedido</th>
                    </tr>
                </thead>
                <tbody style="text-align: center;">
                <?php if($canjeos) { 
                        foreach($canjeos as $canje){
                          $fechaEntrega = '';
                          $estado = 'PROCESO DE ENTREGA';
                          if($canje->FECHAENTREGA != '01-JAN-99') {                                
                            $fechaEntrega = date_format(date_create($canje->FECHAENTREGA), 'd/m/y');
                          }
                          if($canje->ESTADO == 1) {                                
                            $estado = 'ENVIADO';
                          }
                          if($canje->ESTADO == 2) {                                
                            $estado = 'RECIBIDO';
                          }
                          if($canje->ESTADO == 3) {                                
                            $estado = 'CANCELADO';
                          }
                    ?>
                        <tr>
                            <td><?= $canje->ID?></td>
                            <td><?= $canje->DESCRIPCION?></td>
                            <td><?= $canje->NOMBRE1." ".$canje->NOMBRE2." ".$canje->APELLIDO1." ".$canje->APELLIDO2 ?></td>
                            <td><?= $canje->COSTO?></td>
                            <td><?= date_format(date_create($canje->FECHACANJE), 'd/m/y') ?></td>
                            <td><?= $fechaEntrega?></td>
                            <td><?= $canje->NUMGUIAENVIO?></td>
                            <td><?= $estado?></td>
                            <td>
                            <?php if($canje->ESTADO != 2 && $canje->ESTADO != 3) {   ?>
                              <a href="#" title="Editar" onclick="cambiarEstado(<?= $canje->ID ?>, <?= $canje->ESTADO?>)">
                                <i class="fa fa-random fa-2x" style="color:#E08E09"></i>
                              </a>
                            <?php } ?>
                            </td>
                            <td>
                              <?php if($canje->ESTADO == 0) { ?>
                                <a href="#" title="cancelar" onclick="cancelarPedido(<?= $canje->ID ?>)">
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
