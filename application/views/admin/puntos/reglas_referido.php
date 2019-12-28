<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Manejo de Puntos/Reglas Puntos por referido
      </h1>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-md-9">        
            <table class="table">
                <thead style="background-color:#C97C15; color:white">
                    <tr>
                        <th style="text-align: center;">Cant. Puntos * Referido</th>
                        <th style="text-align: center;">Fecha inicio</th>
                        <th style="text-align: center;">Fecha final</th>
                    </tr>
                </thead>
                <tbody style="text-align: center;">
                <?php if($puntosReferidos) {  ?>
                    <tr>
                        <td><?=$puntosReferidos->PUNTOS?></td>
                        <td><?=$puntosReferidos->FECHA_INICIO?></td>
                        <td><?=$puntosReferidos->FECHA_FIN?></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
        <!-- /.col-->
        <div class="col-md-3" style="border: 1px solid #A8A8A8;">
            <h3 style="color:#C97C15"><b>Crea una regla de puntos por referidos</b></h3>
            <form id="frmPointReferens">
                <div class="form-group">
                    <label for="puntos">Cantidad de Puntos * Referido</label>
                    <input type="number" name="puntos" id="puntos" class="form-control">

                    <div class="row">
                        <div class="col-lg-6">        
                            <label for="fechaIni">Fecha de inicio</label>
                            <input type="date" class="form-control" id="fechaIni" name="fechaIni">
                        </div>
                        <div class="col-lg-6">
                            <label for="fechaIni">Fecha fin</label>
                            <input type="date" class="form-control" id="fechaFin" name="fechaFin">
                        </div> 
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