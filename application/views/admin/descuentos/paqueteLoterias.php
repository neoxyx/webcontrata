<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Descuentos/Paquete de loterías
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
                        <th>Compromiso</th>
                        <th>Fecha/Hora inicio</th>
                        <th>Fecha/Hora final</th>
                        <th>% Dcto.</th>
                        <th>Loterias</th>
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
                            <td><?= $descuento->COMPROMISO?></td>
                            <td><?= $descuento->FECHAHORAINICIO?></td>
                            <td><?= $descuento->FECHAHORAFIN?></td>
                            <td><?= $descuento->DCTO?></td>
                            <td><?= $descuento->LOTERIA?></td>
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
            <form id="frmDcto">
                <input type="hidden" name="id" id="id">
                <input type="hidden" name="idtipo_promocion" id="idtipo_promocion" value="1">
                <input type="hidden" name="idtipo_comprador" id="idtipo_comprador" value="3">
                <input type="hidden" name="estado" id="estado" value="0">
                <input type="hidden" name="idtipo_dcto" id="idtipo_dcto" value="3">  
                <input type="hidden" name="vrCupon" id="vrCupon" value="0">
                <input type="hidden" name="vrIn" id="vrIn" value="0">
                <input type="hidden" name="vrF" id="vrF" value="0"> 
                <input type="hidden" name="cantCuponesDisp" id="cantCuponesDisp" value="0"> 
                <input type="hidden" name="cantSorteosAbonados" id="cantSorteosAbonados" value="0"> 
                <input type="hidden" name="codigo" id="codigo" value="0">           
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
                <h5 style="color:#C97C15"><b>Elige las loterías que harán parte del combo:</b></h5>
                <div class="form-group">
                    <label for="loterias">Loterias</label>
                    <select multiple name="loteria[]" id="loteria" class="form-control" required>
                        <?php if($loterias) { foreach($loterias as $loteria){ ?>
                            <option value="<?= $loteria->NOMBRE?>"><?= $loteria->NOMBRE?></option>
                        <?php }} ?>
                    </select>
                </div>
                
                <div class="row">
                    <div class="col-lg-6">                        
                        <div class="form-group">
                            <label for="fechaIni">Fecha de inicio</label>
                            <input type="date" class="form-control" name="fechaIni" id="fechaIni" required>
                        </div>
                        <!-- /form-group -->
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="fechaIni">Fecha fin</label>
                            <input type="date" class="form-control" name="fechaFin" id="fechaFin" required>
                        </div>
                        <!-- /form-group -->
                    </div> 
                </div>
                <!-- /.col-lg-6 -->  

                <div class="form-group">
                    <label for="desc">Porcentaje de descuento</label>
                    <input type="text" class="form-control" name="desc" id="desc" placeholder="%" required>
                </div>                
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
  <script>
	
  </script>
