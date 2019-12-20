<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">  
  <title>Lottired | Dashboard</title>
  <link rel="shortcut icon" type="image/png" href="<?= base_url()?>dist/admin/img/Logo_Lottired3.png"/>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="<?= base_url()?>plugins/bootstrap/dist/css/bootstrap.min.css">
  <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?= base_url()?>plugins/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="<?= base_url()?>plugins/Ionicons/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?= base_url()?>dist/admin/css/estilos.css">
  <link rel="stylesheet" href="<?= base_url()?>dist/admin/css/AdminLTE.min.css">
	<!-- Theme style -->
  <link rel="stylesheet" href="<?= base_url()?>dist/admin/css/dropzone.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="<?= base_url()?>dist/admin/css/skins/_all-skins.min.css">
  <!-- multiple select -->
  <link rel="stylesheet" href="<?= base_url()?>plugins/jquery/dist/jquery.multiselect.css">
  
  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
  <!-- Jquery Confirm -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap.min.css"> 
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <header class="main-header">
    <!-- Logo -->
    <a href="<?= base_url()?>admin/Home" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b><img src="<?= base_url()?>dist/admin/img/Logo_Lottired3.png" alt="Logo"></b></span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b><img src="<?= base_url()?>dist/admin/img/Logo_Lottired2.png" alt="Logo"></b></span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>

      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          
          <!-- User Account: style can be found in dropdown.less -->
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <img src="<?= base_url()?>dist/admin/img/user2-160x160.jpg" class="user-image" alt="User Image">
              <span class="hidden-xs"><?= $this->session->name?></span>
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header">
                <img src="<?= base_url()?>dist/admin/img/user2-160x160.jpg" class="img-circle" alt="User Image">

                <p>
                  <?= $this->session->name?>
                  <small><?= $this->session->created?></small>
                </p>
              </li>
              <!-- Menu Body -->
              <!--<li class="user-body">
                <div class="row">
                  <div class="col-xs-4 text-center">
                    <a href="#">Followers</a>
                  </div>
                  <div class="col-xs-4 text-center">
                    <a href="#">Ventas</a>
                  </div>
                  <div class="col-xs-4 text-center">
                    <a href="#">Friends</a>
                  </div>
                </div>
                <!-- /.row -->
              <!--</li>-->
              <!-- Menu Footer-->
              <li class="user-footer">
                <div class="pull-left">
                  <a href="#" class="btn btn-default btn-flat">Perfil</a>
                </div>
                <div class="pull-right">
                  <a href="<?= base_url()?>admin/Login/logout" class="btn btn-default btn-flat">Cerrar Sesión</a>
                </div>
              </li>
            </ul>
          </li>
          <!-- Control Sidebar Toggle Button -->
          
        </ul>
      </div>
    </nav>
  </header>
  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left image">
          <img src="<?= base_url()?>dist/admin/img/user2-160x160.jpg" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">        
          <p><?= $this->session->name?></p>
          <a href="#"><i class="fa fa-circle text-success"></i> En Línea</a>
        </div>
      </div>
      <!-- search form -->
      <!--<form action="#" method="get" class="sidebar-form">
        <div class="input-group">
          <input type="text" name="q" class="form-control" placeholder="Search...">
          <span class="input-group-btn">
                <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
        </div>
      </form>-->
      <!-- /.search form -->
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu" data-widget="tree">
        <li class="header">MENU NAVEGACIÓN</li>
        <li class="active">
          <a href="<?= base_url()?>admin/Home">
            <i class="fa fa-dashboard"></i> <span>Dashboard</span>            
          </a>
        </li>
        <li class="treeview">
          <a href="#">
            <i class="fa fa-laptop"></i>
            <span>Elementos Gráficos</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="<?= base_url()?>admin/Textos"><i class="fa fa-circle-o"></i> Textos</a></li>
            <li class="treeview">
          <a href="#">
            <i class="fa fa-circle-o"></i> <span>Iconos</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="<?= base_url()?>admin/Redes"><i class="fa fa-circle-o"></i> Redes Sociales</a></li>
            <!--<li class="treeview">
              <a href="#"><i class="fa fa-circle-o"></i> Level One
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                <li><a href="#"><i class="fa fa-circle-o"></i> Level Two</a></li>
                <li class="treeview">
                  <a href="#"><i class="fa fa-circle-o"></i> Level Two
                    <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                    </span>
                  </a>
                  <ul class="treeview-menu">
                    <li><a href="#"><i class="fa fa-circle-o"></i> Level Three</a></li>
                    <li><a href="#"><i class="fa fa-circle-o"></i> Level Three</a></li>
                  </ul>
                </li>
              </ul>
            </li>-->
            <li><a href="#"><i class="fa fa-circle-o"></i> Otros</a></li>
          </ul>
        </li>            
            <li><a href="<?= base_url()?>admin/Logos"><i class="fa fa-circle-o"></i> Logos</a></li>
            <li><a href="<?= base_url()?>admin/Slider"><i class="fa fa-circle-o"></i> Sliders</a></li>
          </ul>
        </li>
        <li>
          <a href="<?= base_url()?>admin/Loterias">
            <i class="fa fa-sort"></i> <span>Loterias</span>            
          </a>
        </li>
        <li class="treeview">
          <a href="#">
            <i class="fa fa-percent"></i> <span>Descuentos</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="<?= base_url()?>admin/Descuentos/diasEspeciales"><i class="fa fa-circle-o"></i> Días especiales</a></li>
            <li><a href="<?= base_url()?>admin/Descuentos/rangoCompras"><i class="fa fa-circle-o"></i> Rango de compras</a></li>
            <li><a href="<?= base_url()?>admin/Descuentos/paqueteLoterias"><i class="fa fa-circle-o"></i> Paquete de loterías</a></li>
            <li><a href="<?= base_url()?>admin/Descuentos/abonado"><i class="fa fa-circle-o"></i> Abonado</a></li>
            <li><a href="<?= base_url()?>admin/Descuentos/codPromocionales"><i class="fa fa-circle-o"></i> Códigos promocionales</a></li>
          </ul>
        </li>
        <li class="treeview">
          <a href="#">
            <i class="fa fa-gift"></i> <span>Premios Promocionales</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="<?= base_url()?>admin/Promocionales/registrarPromocional"><i class="fa fa-circle-o"></i> Registrar promocional</a></li>
            <li><a href="<?= base_url()?>admin/Promocionales/solicitudesEnvio"><i class="fa fa-circle-o"></i> Solicitudes de envío</a></li>            
          </ul>
        </li>
        <li class="treeview">
          <a href="#">
            <i class="fa fa-star"></i> <span>Manejo de Puntos</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="<?= base_url()?>admin/Puntos/reglas_compras"><i class="fa fa-circle-o"></i> Reglas Puntos por compras</a></li>
            <li><a href="<?= base_url()?>admin/Puntos/reglas_referido"><i class="fa fa-circle-o"></i> Reglas Puntos por Referido</a></li>            
            <li><a href="<?= base_url()?>admin/Puntos/productos"><i class="fa fa-circle-o"></i> Productos</a></li>            
            <li><a href="<?= base_url()?>admin/Puntos/pedidos"><i class="fa fa-circle-o"></i> Pedidos</a></li> 
            <li><a href="<?= base_url()?>admin/Puntos/puntos_saldo_compromiso"><i class="fa fa-circle-o"></i> Compromiso puntos por saldo</a></li>            
          </ul>
        </li>
        <li class="treeview">
          <a href="#">
            <i class="fa fa-usd"></i> <span>Saldos</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="<?= base_url()?>admin/Saldos"><i class="fa fa-circle-o"></i> Saldo inicial</a></li>            
          </ul>
        </li>
        <!--<li class="treeview">
          <a href="#">
            <i class="fa fa-table"></i> <span>Tables</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="pages/tables/simple.html"><i class="fa fa-circle-o"></i> Simple tables</a></li>
            <li><a href="pages/tables/data.html"><i class="fa fa-circle-o"></i> Data tables</a></li>
          </ul>
        </li>
        <li>
          <a href="pages/calendar.html">
            <i class="fa fa-calendar"></i> <span>Calendar</span>
            <span class="pull-right-container">
              <small class="label pull-right bg-red">3</small>
              <small class="label pull-right bg-blue">17</small>
            </span>
          </a>
        </li>
        <li>
          <a href="pages/mailbox/mailbox.html">
            <i class="fa fa-envelope"></i> <span>Mailbox</span>
            <span class="pull-right-container">
              <small class="label pull-right bg-yellow">12</small>
              <small class="label pull-right bg-green">16</small>
              <small class="label pull-right bg-red">5</small>
            </span>
          </a>
        </li>
        <li class="treeview">
          <a href="#">
            <i class="fa fa-folder"></i> <span>Examples</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="pages/examples/invoice.html"><i class="fa fa-circle-o"></i> Invoice</a></li>
            <li><a href="pages/examples/profile.html"><i class="fa fa-circle-o"></i> Perfil</a></li>
            <li><a href="pages/examples/login.html"><i class="fa fa-circle-o"></i> Login</a></li>
            <li><a href="pages/examples/register.html"><i class="fa fa-circle-o"></i> Register</a></li>
            <li><a href="pages/examples/lockscreen.html"><i class="fa fa-circle-o"></i> Lockscreen</a></li>
            <li><a href="pages/examples/404.html"><i class="fa fa-circle-o"></i> 404 Error</a></li>
            <li><a href="pages/examples/500.html"><i class="fa fa-circle-o"></i> 500 Error</a></li>
            <li><a href="pages/examples/blank.html"><i class="fa fa-circle-o"></i> Blank Page</a></li>
            <li><a href="pages/examples/pace.html"><i class="fa fa-circle-o"></i> Pace Page</a></li>
          </ul>
        </li>
        
        <li><a href="https://adminlte.io/docs"><i class="fa fa-book"></i> <span>Documentation</span></a></li>
        <li class="header">LABELS</li>
        <li><a href="#"><i class="fa fa-circle-o text-red"></i> <span>Important</span></a></li>
        <li><a href="#"><i class="fa fa-circle-o text-yellow"></i> <span>Warning</span></a></li>
        <li><a href="#"><i class="fa fa-circle-o text-aqua"></i> <span>Information</span></a></li>-->
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>
