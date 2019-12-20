<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Textos
        <small></small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?= base_url()?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Textos</a></li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-md-12">
		<?php if($textos) foreach($textos as $texto){ ?>
          <div class="box box-info">
            <div class="box-header">
              <h3 class="box-title">Página: <?= $texto->pagina.' Sección: '. $texto->seccion.' Posición: '. $texto->posicion ?>
                <small></small>
              </h3>
              <!-- tools box -->
              <div class="pull-right box-tools">
				<button type="button" class="btn btn-info btn-sm"
                        title="Cerrar Edicion Texto" onclick="HideUpdateSlide(<?= $texto->idTexto?>)"><i class="fa fa-minus-square"></i></button>			
                <button type="button" class="btn btn-info btn-sm"
                        title="Cambiar Texto" onclick="ShowUpdateSlide(<?=$texto->idTexto?>)">
                  <i class="fa fa-edit"></i></button>					  
                <button type="button" class="btn btn-info btn-sm" title="Eliminar texto" onclick="delete_slide(<?= $texto->idTexto?>)">
                  <i class="fa fa-times"></i></button>
              </div>
              <!-- /. tools -->
            </div>            			
			<!-- /.box-header -->
            <div class="box-body pad">
			  <form>
                    <textarea id="editor1" rows="10" cols="174" disabled><?= $texto->texto?></textarea>   
              </form>
            </div>
			<!-- Div edicion Texto -->
			<div class="box" style="display:none" id="texto_<?= $texto->idTexto?>">
				<div class="box-header">
				  <h3 class="box-title">Editar Texto Página: <?= $texto->pagina.' Sección: '. $texto->seccion.' Posición: '. $texto->posicion?>
				  </h3>              
				</div>
				<!-- /.box-header -->
				<div class="box-body pad">
				  <form action="<?= base_url()?>admin/Textos/edit"  method="post">
				  <input type="hidden" name="id" value="<?= $texto->idTexto?>">
				  <div class="form-group">
					<label for="titulo">Titulo</label>
					<input class="form-control" type="text" id="titulo" name="titulo" value="<?= $texto->titulo?>">
			  	  </div> 
				  <div class="form-group">
					<label for="exampleFormControlTextarea1">Texto</label>
					<textarea class="form-control" id="exampleFormControlTextarea1" rows="10" cols="174" name="texto"><?= $texto->texto?></textarea>
			  	  </div>
				  <div class="form-group">
					<label for="link">Link</label>
					<input class="form-control" type="text" id="link" name="link" value="<?= $texto->link?>" placeholder="Ej: http://www.lottired.com">
			  	  </div>  
				  <button type="submit" class="btn btn-primary">Actualizar</button>
				  </form>
				</div>
			</div>
			<!-- Div edicion Texto -->
          </div>
		  <?php } ?>
          <!-- /.box -->

          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Añadir Texto
                <small></small>
              </h3>              
            </div>
            <!-- /.box-header -->
            <div class="box-body pad">
              <form action="<?= base_url()?>admin/Textos/add"  method="post"> 
				  <div class="form-group">
					<label for="exampleFormControlSelect1">Página</label>
					<select class="form-control" id="exampleFormControlSelect1" name="pagina">			<option value="header">Cabecera</option>		  
					<option value="Home">Home</option>
					<option value="Jugar">Jugar</option>
					<option value="Resultados">Resultados</option>
					<option value="como_jugar">Cómo Jugar</option>
					<option value="footer">Pie de página</option>
					</select>
				  </div>
				  <div class="form-group">
					<label for="exampleFormControlSelect1">Sección</label>
					<select class="form-control" id="exampleFormControlSelect1" name="seccion">
					  <?php for($i=1; $i<=7; $i++){?>
					<option><?= $i ?></option>
					<?php } ?>
					</select>
				  </div>
				  <div class="form-group">
					<label for="exampleFormControlSelect1">Posición</label>
					<select class="form-control" id="exampleFormControlSelect1" name="posicion">
					  <?php for($i=1; $i<=7; $i++){?>
					<option><?= $i ?></option>
					<?php } ?>
					</select>
				  </div>
				  <div class="form-group">
					<label for="titulo">Titulo</label>
					<input class="form-control" type="text" id="titulo" name="titulo">
			  	  </div> 
				  <div class="form-group">
					<label for="exampleFormControlTextarea1">Texto</label>
					<textarea class="form-control" id="exampleFormControlTextarea1" rows="10" cols="174" name="texto"></textarea>
			  	  </div> 
				  <div class="form-group">
					<label for="link">Link</label>
					<input class="form-control" type="text" id="link" name="link" placeholder="Ej: http://www.lottired.com">
			  	  </div> 
			  <button type="submit" class="btn btn-primary">Guardar</button>
			  </form>
            </div>
          </div>
        </div>
        <!-- /.col-->
      </div>
      <!-- ./row -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <script>
	function ShowUpdateSlide(id){
		$("#texto_"+id).css("display","block");
	}
	function HideUpdateSlide(id){
		$("#texto_"+id).css("display","none");
	}
	
  </script>
