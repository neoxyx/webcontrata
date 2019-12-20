<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Confirmación compra Lottired</title>

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
        <p>
            Gracias por su compra en LottiRed.Net, el portal de juegos de azar
            confiable y seguro de la loteria de Medellín.
        </p>
        <p>
            Si presenta alguna duda o inquietud relacionado con el producto o
            servicio adquirido en LottiRed.Net, escribanos a servicio@lottired.com ó
            llámenos a la línea nacional gratuita 01 8000 41 56 84.
        </p>
        <p>
            Anexamos el ticket de compra de su loteria con el número elegido en
            formato PDF. La contraseña para abrirlo es su número de identificación.
            Se recomienda conservar este e-mail a manera de constancia.
        </p>
        <p>Le deseamos suerte electrónica a sus jugadas.</p>
        <p>La oportunidad de ser millonario y hacer realidad sus sueños.</p>
    </div>
    <footer>
        <p id="pfooter">
            SERVICIO AL CLIENTE: 01 8000 41 56 84 &nbsp; | &nbsp; LottiRed.Net &nbsp; | &nbsp; servicio@lottired.com
        </p>
    </footer>
</body>

</html>