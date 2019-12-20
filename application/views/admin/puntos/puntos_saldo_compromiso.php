<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
      Compromiso presupuestal para cambio de puntos por saldo
      </h1>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-md-9">        
            <table class="table">
                <thead style="background-color:#C97C15; color:white">
                  <tr>
                    <th>Código de compromiso presupuestal actual</th>
                    <th style="background: white; color: black;"><?= $codigo ?></th>
                    </tr>
                </thead>
            </table>
        </div>
        <!-- /.col-->
        <div class="col-md-3" style="border: 1px solid #A8A8A8;">
            <h3 style="color:#C97C15"><b>Compromiso presupuestal</b></h3>
            <form id="frmCodigoCompromiso">
              <div class="form-group">
                <label for="compromiso">Compromiso presupuestal</label>
                <input type="text" class="form-control" id="compromiso" name="compromiso"  placeholder="Codigo compromiso">
              </div>
              <div class="form-group">                    
                <button type="submit" class="btn" style="background-color:#F5CE16; color:#0C6BB0; width:245px"><b>Guardar</b></button>
              </div>
          </form>
        </div>
      </div>
      <!-- ./row -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->