<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Iconos Redes Sociales
        <small></small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Iconos</a></li>
        <li class="active">Redes Sociales</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-md-12">        
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Icono</th>
                        <th>Nombre</th>
                        <th>Link</th>
                        <th>Sección</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($redes as $red){ if($red->estado==0){
                         $class='btn btn-success'; $desc='Activar'; $estado = 1;
                         } else {
                             $class = 'btn btn-danger'; $desc = 'Desactivar'; $estado = 0;
                         }?>
                         <form id="frmRedes" action="<?= base_url()?>admin/Redes/add" method="post">                         
                        <tr>
                            <td><input type="hidden" name="id" value="<?= $red->idIcono?>">
                            <image src="<?= base_url()?>dist/portal/images/home/<?= $red->image?>" heigth="30" width="30"/>
                            </td>
                            <td><input type="hidden" name="estado" value="<?= $estado?>"><?= $red->name?></td>
                            <td><input type="text" name="link" value="<?= $red->link?>"></td>
                            <td><?= $red->seccion?></td>
                            <td><button type="submit" class="<?= $class?>"><?= $desc?></button></td>
                        </tr>                        
                        </form>
                    <?php } ?>
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
