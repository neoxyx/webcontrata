<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Lottired | Login</title>
  <link rel="shortcut icon" type="image/png" href="<?= base_url()?>dist/admin/img/Logo_Lottired3.png"/>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="<?= base_url()?>plugins/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?= base_url()?>plugins/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="<?= base_url()?>plugins/Ionicons/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?= base_url()?>dist/admin/css/AdminLTE.min.css">
  <!-- Jquery Confirm -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <a href="home.html"><img src="<?= base_url()?>dist/admin/img/Logo_Lottired2.png" alt="Logo"></a>
  </div>
  <!-- /.login-logo -->
  <div class="login-box-body">
    <p class="login-box-msg">Iniciar sesión</p>

    <form id="frmLogin">
      <div class="form-group has-feedback">
        <input type="email" class="form-control" name="user" placeholder="Correo Electronico">
        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <input type="password" name="pass" class="form-control" placeholder="Contraseña">
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>
      <div class="row">
        <!--<div class="col-xs-8">
          <div class="checkbox icheck">
            <label>
              <input type="checkbox"> Recuerdame
            </label>
          </div>
        </div>-->
        <div class="col-xs-8">
          <div class="checkbox icheck">
            <label>
              <a href="#">Recuerda tu contraseña</a>
            </label>
          </div>
        </div>
        <!-- /.col -->
        <div class="col-xs-4">
          <button type="submit" class="btn btn-primary btn-block btn-flat">Ingresar</button>
        </div>
        <!-- /.col -->
      </div>
    </form>
    <!--<div class="social-auth-links text-center">
      <p>- OR -</p>
      <a href="#" class="btn btn-block btn-social btn-facebook btn-flat"><i class="fa fa-facebook"></i> Sign in using
        Facebook</a>
      <a href="#" class="btn btn-block btn-social btn-google btn-flat"><i class="fa fa-google-plus"></i> Sign in using
        Google+</a>
    </div>-->
    <!-- /.social-auth-links -->

    <br>
    <!--<a href="register.html" class="text-center">Register a new membership</a>-->
  </div>
  <!-- /.login-box-body -->
</div>
<!-- /.login-box -->

<!-- jQuery 3 -->
<script src="<?= base_url()?>plugins/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="<?= base_url()?>plugins/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- Jquery Confirm -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
<script>
  $(function () {
	$('#frmLogin').submit(function(event){
		event.preventDefault();
		$.ajax({
			url: '<?= base_url()?>admin/Login/very_login',
			data: $('#frmLogin').serialize(),
			type: 'post',
			beforeSend: function(){
				
			},
			success: function(result){
				if (result == 'ok') {
					location.href= '<?= base_url()?>admin/Home';
				} else {
					$.confirm({
									title: 'Error!',
									content: 'Usuario y/o contraseña erronea',
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
			fail: function(){
				$.confirm({
					title: 'Error!',
					content: 'Fallo en red',
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
		});
	});
  });
</script>
</body>
</html>
