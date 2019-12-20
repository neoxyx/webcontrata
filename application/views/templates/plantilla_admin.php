<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Compromiso presupuestal agotado Lottired</title>

    <style type="text/css">
    #main-header {
        background: #dc7c29;
        color: white;
        height: 120px;
    }

    h1 {
        color: #dc7c29
    }

    footer {
        background-color: #dc7c29;
        color: white;
        height: 40px;
    }

    body {
        border-style: solid;
        border-width: 1px;
        border-color: gray;
        color: gray;
    }

    #pfooter {
        text-align: center;
    }
    </style>
</head>

<body>
    <header id="main-header">
        <img src="<?= base_url()?>dist/portal/images/plantilla_mail/header-mail-2-01.png" alt="">
        <!-- / #logo-header -->
    </header>
    <!-- / #main-header -->
    <div style="margin: 2%">
        <h1><?= $nombre?></h1>
        <p>Lottired avisa saldo bajo para el compromiso presupuestal No: <?= $compromiso?>, se ha agotado</p>
    </div>
    <footer>
        <p id="pfooter">
            SERVICIO AL CLIENTE: 01 8000 41 56 84 &nbsp; | &nbsp; LottiRed.Net &nbsp; | &nbsp; servicio@lottired.com
        </p>
    </footer>
</body>

</html>