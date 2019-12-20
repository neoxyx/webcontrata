<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Manejo de Puntos/Productos
      </h1><br>
      <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalAddProduct">Añadir Nuevo Producto</button>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-md-12">        
            <table class="table">
                <thead style="background-color:#C97C15; color:white">
                    <tr>
                        <th><i class="fa fa-image"></i></th>
                        <th>Código Producto</th>
                        <th>Nombre producto</th>
                        <th>Inventario</th>
                        <th>Puntos</th>
                        <th>Activar/desactivar</th>
                        <th>Editar</th>
                    </tr>
                </thead>
                <tbody  style="text-align: center;">
                <?php if($productos) { 
                        foreach($productos as $producto){
                            $estado = $producto->ESTADO;
                            if($estado == 0) {                                
                                $estado = '<input type="checkbox" data-toggle="toggle" data-size="mini" data-onstyle="warning" data-on="Activo" data-off="Inactivo" onchange="cambioEstadoProducto('.$producto->ID.',1)">'; 
                            } else {                                
                                $estado = '<input type="checkbox" checked data-toggle="toggle" data-size="mini" data-onstyle="warning" data-on="Activo" data-off="Inactivo" onchange="cambioEstadoProducto('.$producto->ID.',0)">';
                            }
                    ?>
                        <tr>
                            <td><img _ngcontent-skx-c7="" style="width: 30%;" src="<?= base_url()?>assets/images/prizes/<?=$producto->IMAGEN?>"></td>
                            <td><?=$producto->ID?></td>
                            <td><?=$producto->DESCRIPCION?></td>
                            <td><?=$producto->UNIDADES?></td>
                            <td><?=$producto->PUNTOS?></td>
                            <td><?=$estado?></td>
                            <td>
                              <a href="#" title="Editar" onclick="editarProducto(<?= $producto->ID?>,<?=$producto->PUNTOS?>,<?=$producto->UNIDADES?>,'<?=$producto->DESCRIPCION?>')">
                                  <i class="fa fa-edit fa-2x" style="color:#E08E09"></i>
                              </a>
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
  
  <!-- Modal -->
<div class="modal fade" id="modalAddProduct" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header" style="background-color:#C97C15; color:white; text-align:center">
        <h4 class="modal-title">Añadir Nuevo Producto</h4>        
      </div>
      <div class="modal-body">
      <form id="frmAddProduct" method="post" enctype="multipart/form-data">
        <input type="hidden" class="form-control" id="idProducto">
        <div class="form-group col-md-6">
          <label for="imagen">Agregar imagen producto</label>
          <input type='file' name="imagen" id="imagen"/>
        </div>
        <div class="form-group col-md-6">
          <label for="puntos">Puntos</label>
          <input type="number" class="form-control" id="puntos">
        </div>
        <div class="form-group">
          <label for="unidades">Unidades</label>
          <input type="number" class="form-control" id="unidades">
        </div>
        <div class="form-group">
          <label for="descripcion">Descripción</label>
          <input type="text" class="form-control" id="descripcion">
        </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
          <button type="submit" class="btn btn-primary">Guardar</button>
        </div>
      </form>
    </div>
  </div>
</div>
