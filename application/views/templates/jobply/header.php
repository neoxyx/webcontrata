<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Web Contrata</title>
    <meta charset="utf-8">
    <link rel="shortcut icon" type="image/png" href="<?= base_url()?>dist/images/logo.PNG"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
    <link href="https://fonts.googleapis.com/css?family=Nunito+Sans:200,300,400,600,700,800,900" rel="stylesheet">

    <link rel="stylesheet" href="<?= base_url()?>dist/css/open-iconic-bootstrap.min.css">
    <link rel="stylesheet" href="<?= base_url()?>dist/css/animate.css">
    
    <link rel="stylesheet" href="<?= base_url()?>dist/css/owl.carousel.min.css">
    <link rel="stylesheet" href="<?= base_url()?>dist/css/owl.theme.default.min.css">
    <link rel="stylesheet" href="<?= base_url()?>dist/css/magnific-popup.css">

    <link rel="stylesheet" href="<?= base_url()?>dist/css/aos.css">

    <link rel="stylesheet" href="<?= base_url()?>dist/css/ionicons.min.css">

    <link rel="stylesheet" href="<?= base_url()?>dist/css/bootstrap-datepicker.css">
    <link rel="stylesheet" href="<?= base_url()?>dist/css/jquery.timepicker.css">

    
    <link rel="stylesheet" href="<?= base_url()?>dist/css/flaticon.css">
    <link rel="stylesheet" href="<?= base_url()?>dist/css/icomoon.css">
    <link rel="stylesheet" href="<?= base_url()?>dist/css/style.css">
  </head>
  <body>    
	  <nav class="navbar navbar-expand-lg navbar-dark ftco_navbar bg-dark ftco-navbar-light" id="ftco-navbar">
	    <div class="container">
	      <a class="navbar-brand" href="<?= base_url()?>"><img src="<?= base_url()?>dist/images/logo.PNG" alt="logo web contrata"></a>
	      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#ftco-nav" aria-controls="ftco-nav" aria-expanded="false" aria-label="Toggle navigation">
	        <span class="oi oi-menu"></span> Menu
	      </button>

	      <div class="collapse navbar-collapse" id="ftco-nav">
	        <ul class="navbar-nav ml-auto">
	          <li class="nav-item active"><a href="<?= base_url()?>" class="nav-link">Inicio</a></li>
	          <li class="nav-item"><a href="<?= base_url()?>About" class="nav-link">Nuestra Empresa</a></li>
	          <li class="nav-item"><a href="<?= base_url()?>Candidates" class="nav-link">Canditatos</a></li>
	          <li class="nav-item"><a href="<?= base_url()?>Contact" class="nav-link">Contacto</a></li>
			  <?php if($this->session->has_userdata('logged_in')) { ?>				
				<li class="nav-item dropdown cta mr-md-1">
					<a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Mi Perfil</a>
					<div class="dropdown-menu">
					<a class="dropdown-item" href="#">Mi Área</a>
					<a class="dropdown-item" href="#">Hoja de vida</a>
					<a class="dropdown-item" href="#">Configuración</a>
					<div class="dropdown-divider"></div>
					<a class="dropdown-item" href="<?php base_url()?>Welcome/logout">Cerrar Sesión</a>
					</div>
				</li>
			  <?php } else { ?>
	          <li class="nav-item cta mr-md-1"><a href="#" class="nav-link" data-toggle="modal" data-target="#modalLoginEmployee">Acceso Candidatos</a></li>
	          <li class="nav-item cta cta-colored"><a href="#" class="nav-link" data-toggle="modal" data-target="#modalLoginCompanys">Acceso Empresas</a></li>
			  <?php } ?>
	        </ul>
	      </div>
	    </div>
	  </nav>
    <!-- END nav -->
    <!-- Modal Employees -->
<div class="modal fade" id="modalLoginEmployee" tabindex="-1" role="dialog" aria-labelledby="modalLoginEmployeeTitle" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
	  <div class="modal-content">
		<div class="modal-header">
		  <h5 class="modal-title" id="modalLoginEmployeeTitle">Acceso Candidatos</h5>
		  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
			<span aria-hidden="true">&times;</span>
		  </button>
		</div>
		<form id="frmLogin">
		<div class="modal-body">			
				<div class="form-group">
					<label for="exampleInputEmail1">Email</label>
					<input type="email" class="form-control" name="email" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Ingrese email">
					<small id="emailHelp" class="form-text text-muted">Nunca compartiremos su correo electrónico con nadie más.</small>
				</div>
				<div class="form-group">
					<label for="exampleInputPassword1">Contraseña</label>
					<input type="password" class="form-control" name="pass" id="exampleInputPassword1" placeholder="Contraseña">
				</div>
				<div class="form-group form-check">
					<input type="checkbox" class="form-check-input" id="exampleCheck1">
					<label class="form-check-label" for="exampleCheck1">Recordarme</label>
				</div>
				<div class="form-group">
					<p>¿No tienes cuenta? <a href="<?= base_url()?>candidates/Register">Registrate</a></p>
				</div>
		</div>
		<div class="modal-footer">
		  <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
		  <button type="submit" class="btn btn-primary">Ingresar</button>
		</div>
		</form>
	  </div>
	</div>
  </div>
  <!-- Modal Employees -->

  <!-- Modal Companies -->
<div class="modal fade" id="modalLoginCompanys" tabindex="-1" role="dialog" aria-labelledby="modalLoginCompanysTitle" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
	  <div class="modal-content">
		<div class="modal-header">
		  <h5 class="modal-title" id="modalLoginCompanysTitle">Acceso Empresas</h5>
		  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
			<span aria-hidden="true">&times;</span>
		  </button>
		</div>
		<form action="">
		<div class="modal-body">
		<div class="form-group">
					<label for="exampleInputEmail1">Email</label>
					<input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter email">
					<small id="emailHelp" class="form-text text-muted">Nunca compartiremos su correo electrónico con nadie más.</small>
				</div>
				<div class="form-group">
					<label for="exampleInputPassword1">Contraseña</label>
					<input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password">
				</div>
				<div class="form-group form-check">
					<input type="checkbox" class="form-check-input" id="exampleCheck1">
					<label class="form-check-label" for="exampleCheck1">Recordarme</label>
				</div>
				<div class="form-group">
					<p>¿No tienes cuenta? <a href="<?= base_url()?>companies/Register">Registrate</a></p>
				</div>
		</div>
		<div class="modal-footer">
		  <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
		  <button type="button" class="btn btn-primary">Ingresar</button>
		</div>
		</form>
	  </div>
	</div>
  </div>
  <!-- Modal Companies -->