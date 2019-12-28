<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Logos
        <small></small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Imagenes</a></li>
        <li class="active">Logos</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-md-4">
		<?php if($images) foreach($images as $image){ ?>
          <div class="box box-info">
            <div class="box-header">
              <h3 class="box-title">Logo <?= $image->class?>
                <small></small>
              </h3>
              <!-- tools box -->
              <div class="pull-right box-tools">
				<button type="button" class="btn btn-info btn-sm"
                        title="Cerrar Edicion Imagen" onclick="HideUpdateSlide(<?= $image->idImage?>)"><i class="fa fa-minus-square"></i></button>			
                <button type="button" class="btn btn-info btn-sm"
                        title="Cambiar Imagén" onclick="ShowUpdateSlide(<?= $image->idImage?>)">
                  <i class="fa fa-edit"></i></button>				  
                <button type="button" class="btn btn-info btn-sm" title="Eliminar Imagén" onclick="delete_slide(<?= $image->idImage?>,'<?= $image->name?>')">
                  <i class="fa fa-times"></i></button>
              </div>
              <!-- /. tools -->
            </div>            			
			<!-- /.box-header -->
            <div class="box-body pad">
			<form>
                    <img src="<?= base_url()?>dist/portal/images/home/<?= $image->name?>" id="editor1" name="slide" heigth="100%" width="100%">                                             
              </form>
            </div>
			<!-- Div edicion Imagen -->
			<div class="box" style="display:none" id="slide_<?= $image->idImage?>">
				<div class="box-header">
				  <h3 class="box-title">Editar Logo <?= $image->class?>
					<small></small>
				  </h3>              
				</div>
				<!-- /.box-header -->
				<div class="box-body pad">
				  <form id="updateSlide" action="<?= base_url()?>admin/Logos/update_logo" class="dropzone" method="post" enctype="multipart/form-data">
				  <input type="hidden" name="id" value="<?= $image->idImage?>">
				  <div class="dz-message">Suelta tu logo para cambiar el actual</div>
				  </form>
				</div>
			</div>
			<!-- Div edicion Imagen -->
          </div>
		  <?php } ?>
          <!-- /.box -->
		  <?php if(count($images)<3){ ?>			
          <div class="box" id="boxAdd">
            <div class="box-header">
              <h3 class="box-title">Añadir Logo Lottired
                <small></small>
              </h3>              
            </div>
            <!-- /.box-header -->
            <div class="box-body pad">
              <form id="addLogoLottired" action="<?= base_url()?>admin/Logos/set_logo_lottired" class="dropzone" method="post" enctype="multipart/form-data">
					<div class="dz-message">Suelta tu imagen aqui</div>
			  </form>
            </div>
		  </div>

		  <div class="box" id="boxAdd">
            <div class="box-header">
              <h3 class="box-title">Añadir Logo Loteria de Medellín
                <small></small>
              </h3>              
            </div>
            <!-- /.box-header -->
            <div class="box-body pad">
              <form id="addLogoMed" action="<?= base_url()?>admin/Logos/set_logo_lotmedellin" class="dropzone" method="post" enctype="multipart/form-data">
					<div class="dz-message">Suelta tu imagen aqui</div>
			  </form>
            </div>
		  </div>

		  <div class="box" id="boxAdd">
            <div class="box-header">
              <h3 class="box-title">Añadir Logo PlacetoPay
                <small></small>
              </h3>              
            </div>
            <!-- /.box-header -->
            <div class="box-body pad">
              <form id="addLogoPlace" action="<?= base_url()?>admin/Logos/set_logo_placetopay" class="dropzone" method="post" enctype="multipart/form-data">
					<div class="dz-message">Suelta tu imagen aqui</div>
			  </form>
            </div>
		  </div>

		  <?php } ?>
        </div>
        <!-- /.col-->
      </div>
      <!-- ./row -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <script>
	// Restricciones de subida de imagenes para logos
	Dropzone.options.addLogoLottired = {
		maxFilesize: 1, // MB
		acceptedFiles: 'image/*',
		dictInvalidFileType: "Tipo de archivo invalido"
	}

	Dropzone.options.addLogoMed = {
		maxFilesize: 1, // MB
		acceptedFiles: 'image/*',
		dictInvalidFileType: "Tipo de archivo invalido"
	}

	Dropzone.options.addLogoPlace = {
		maxFilesize: 1, // MB
		acceptedFiles: 'image/*',
		dictInvalidFileType: "Tipo de archivo invalido"
	}

	// Restricciones de actualización de imagenes para logos
	Dropzone.options.updateSlide = {
		maxFilesize: 1, // MB
		acceptedFiles: 'image/*',
		dictInvalidFileType: "Tipo de archivo invalido"
	}

	function ShowUpdateSlide(id){
		$("#slide_"+id).css("display","block");
	}
	function HideUpdateSlide(id){
		$("#slide_"+id).css("display","none");
	}

	function delete_slide(id,name){
		$.confirm({
			title: 'Confirma que desea eliminar esta imagén?',
			content: '',
			type: 'orange',
			buttons: {
				confirmar: function () {
					$.alert('Confirmado!');
						$.ajax({
							url: '<?= base_url()?>admin/Logos/delete_logo',
							data: {id:id,name:name},
							type: 'post',
							beforeSend: function(){
				
							},
							success: function(result){
								if (result == 'ok') {
									location.reload();
								} else {
									$.confirm({
													title: 'Error!',
													content: 'La imagen no pudo ser eliminada',
													type: 'red',
													typeAnimated: true,
													buttons: {
														tryAgain: {
															text: 'Intentar de nuevo',
															btnClass: 'btn-red',
															action: function(){
															}
														},
														close: function () {
															location.reload();
														}
													}
												});
								}				
							},			
						});
				},
				cancelar: function () {
					$.alert('Cancelado!');
				}
			}
		});		
	}

  </script>
