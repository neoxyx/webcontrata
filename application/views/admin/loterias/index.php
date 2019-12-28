<div class="loader"></div>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Loterias/Ordenamiento
      </h1>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="panel panel-default">  
          <div class="panel-body"> 
            <form action="<?= base_url()?>admin/Loterias/updateOrder" method="post">           
                <table class="table" id="tblLoterias">
                    <thead style="background-color:#C97C15; color:white">
                        <tr>
                            <th>Nombre</th>
                            <th>Posición</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if($lotterys) { for($i=0;$i<count($lotterys);$i++){                            
                        ?>
                            <tr>
                                <td><?= $lotterys[$i]['name']?></td>
                                <td>
                                  <input type="hidden" name="idLoteria[]" value="<?= $lotterys[$i]['idLoteria']?>">
                                  <input type="number" value="<?= $lotterys[$i]['order']?>" name="order[]" min="1">
                                </td>
                            </tr>
                        <?php }} ?>
                    </tbody>
                </table>
                <input type="submit" class="btn" value="Actualizar ordenamiento" onclick="loader()">
              </form>                
          </div>
        </div>
      </div>
      <!-- ./row -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->