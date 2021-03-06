<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Descuentos/Códigos promocionales
      </h1>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-md-9">        
            <table class="table">
                <thead style="background-color:#C97C15; color:white">
                    <tr>
                        <th>Nombre</th>
                        <th>Código</th>
                        <th>Cant. Cupones disponibles</th>
                        <th>Vr. Cupón</th>
                        <th>Fecha/Hora inicio</th>
                        <th>Fecha/Hora final</th>
                        <th>Tipo de promoción</th>
                        <th>Estado</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if($descuentos) { foreach($descuentos as $descuento){
                            $estado = $descuento->ESTADO;
                            if($estado == 0) {                                
                                $estado = '<input type="checkbox" data-toggle="toggle" data-size="mini" data-onstyle="warning" data-on="Activo" data-off="Inactivo" onchange="cambioEstado('.$descuento->IDDESCUENTO.',1)">'; 
                            } else {                                
                                $estado = '<input type="checkbox" checked data-toggle="toggle" data-size="mini" data-onstyle="warning" data-on="Activo" data-off="Inactivo" onchange="cambioEstado('.$descuento->IDDESCUENTO.',0)">';
                            }
                    ?>
                        <tr>
                            <td><?= $descuento->NOMBRE?></td>
                            <td><?= $descuento->CODIGO?></td>
                            <td><?= $descuento->CANTCUPONESDISP?></td>
                            <td><?= '$'.number_format($descuento->VRCUPON, 0)?></td>
                            <td><?= $descuento->FECHAHORAINICIO?></td>
                            <td><?= $descuento->FECHAHORAFIN?></td>
                            <td><?= $descuento->TIPOP?></td>
                            <td><?= $estado?></td>
                            <td><a href="#" title="Editar" onclick="editDcto(<?= $descuento->IDDESCUENTO?>)">
                                    <i class="fa fa-edit fa-2x" style="color:#E08E09"></i>
                                </a>
                            </td>
                            <td> <a href="#" title="Borrar" onclick="deleteDcto(<?= $descuento->IDDESCUENTO?>)"><i class="fa fa-trash fa-2x" style="color:#E08E09"></i></a></td>
                        </tr>
                    <?php }} ?>
                </tbody>
            </table>
        </div>
        <!-- /.col-->
        <div class="col-md-3" style="border: 1px solid #A8A8A8;">
            <h3 style="color:#C97C15" id="titleForm"><b>Crea un descuento</b></h3>
            <small>por Código promocional</small>
            <form id="frmDcto">
                <input type="hidden" name="id" id="id" value="">
                <input type="hidden" name="idtipo_comprador" id="idtipo_comprador" value="3">
                <input type="hidden" name="estado" id="estado" value="0">
                <input type="hidden" name="idtipo_dcto" id="idtipo_dcto" value="5">
                <input type="hidden" name="loteria[]" id="loteria" value="NA">  
                <input type="hidden" name="vrIn" id="vrIn" value="0"> 
                <input type="hidden" name="vrF" id="vrF" value="0">
                <input type="hidden" name="cantSorteosAbonados" id="cantSorteosAbonados" value="0">
                <input type="hidden" name="desc" id="desc" value="0">                                                                             
                <div class="form-group">
                    <label for="descuento">Nombre del descuento</label>
                    <input type="text" class="form-control" name="descuento" id="descuento" required>
                </div>
                <div class="form-group">
                    <label for="compromiso">Compromiso</label>
                    <select name="compromiso" id="compromiso" class="form-control" required>
                            <option>Seleccione</option>                            
                    </select>
                </div>
                <div class="form-group">
                    <label for="codigo">Código</label>
                    <input type="text" class="form-control" name="codigo" id="codigo" required>
                </div>

                <div class="row">
                    <div class="col-lg-7">
                        <div class="form-group">
                            <label for="cantCuponesDisp">Cupones disponibles</label>
                            <input type="number" class="form-control" name="cantCuponesDisp" id="cantCuponesDisp" required>
                        </div>
                        <!-- /form-group -->
                    </div>
                    <!-- /.col-lg-6 -->
                    <div class="col-lg-5">                        
                        <div class="form-group">
                            <label for="vrCupon">Vr Cupón</label>
                            <input type="text" class="form-control" name="vrCupon" id="vrCupon" required>
                        </div>
                        <!-- /form-group -->
                    </div>
                    <!-- /.col-lg-6 -->
                </div>
                <!-- /row --> 

                <div class="row">
                    <div class="col-lg-6">                        
                        <div class="form-group">
                            <label for="fechaIni">Fecha de inicio</label>
                            <input type="date" class="form-control" name="fechaIni" id="fechaIni" required>
                        </div>
                        <!-- /form-group -->
                    </div>
                    <!-- /.col-lg-6 --> 
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="fechaIni">Fecha fin</label>
                            <input type="date" class="form-control" name="fechaFin" id="fechaFin" required>
                        </div>
                        <!-- /form-group -->
                    </div>
                    <!-- /.col-lg-6 -->  
                </div>
                <!-- /row --> 
                 
                <div class="row">
                    <div class="col-lg-7">                        
                        <div class="form-group">
                        <label for="idtipo_promocion">Tipo de promoción</label>
                        <select name="idtipo_promocion" id="idtipo_promocion" class="form-control" required>
                            <option>Seleccione</option>                            
                        </select>
                        </div>
                        <!-- /form-group -->
                    </div>
                    <div class="col-lg-5">
                        <div class="form-group">
                            <a href="#" data-toggle="modal" data-target="#modalTipoP">Crear tipo de promoción</a>
                        </div>
                        <!-- /form-group -->
                    </div> 
                </div>
                <!-- /.col-lg-6 --> 
                <div class="form-group">
                    
                <button type="submit" class="btn" style="background-color:#F5CE16; color:#0C6BB0; width:245px"><b id="textBtnForm">Crear</b></button>
                </div>
        </form>
        </div>
      </div>
      <!-- ./row -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <!-- Modal crear tipo comprador -->
<div class="modal fade" id="modalTipoP" tabindex="-1" role="dialog" aria-labelledby="modalTipoPLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTipoPLabel">Crear Tipo de promoción</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
        <form id="frmTipop">                        
            <div class="modal-body">                
                <div class="form-group">
                    <label for="DESCP">Tipo de promoción</label>
                    <input type="text" class="form-control" name="DESCP" id="DESCP" placeholder="Descripción tipo de promoción" required>                
                </div>        
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn" style="background-color:#F5CE16; color:#0C6BB0;"><b>Crear</b></button>
                <button type="button" class="btn" style="background-color:blue; color:white;" data-dismiss="modal"><b>Cerrar</b></button>                
            </div>
        </form>
    </div>
  </div>
</div>
<!-- /Modal crear tipo comprador -->
