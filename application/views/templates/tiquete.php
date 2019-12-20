<?php
$arrContextOptions = array(
  "ssl" => array(
      "verify_peer" => false,
      "verify_peer_name" => false,
  ),
);
//Logo
$urllogo = base_url().'dist/portal/images/home/logo.png';
$type = pathinfo($urllogo, PATHINFO_EXTENSION);
$logoData = file_get_contents($urllogo, false, stream_context_create($arrContextOptions));
$logoBase64Data = base64_encode($logoData);
$logo = 'data:image/' . $type . ';base64,' .
        $logoBase64Data;
        //Firma
        $urlFirma = base_url().'dist/portal/images/lrFirma.png';
        $typeFirma = pathinfo($urlFirma, PATHINFO_EXTENSION);
        $firmaData = file_get_contents($urlFirma, false, stream_context_create($arrContextOptions));
        $firmaBase64Data = base64_encode($firmaData);
        $firma = 'data:image/' . $typeFirma . ';base64,' .
                $firmaBase64Data;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Tiquete de compra</title>
</head>

<body>
    <?php for($i=0; $i<count($sale); $i++) {
      if($details[$i]["ITEM"]!=1){                  
        $query = $this->db->get_where('loterias',array('loteria'=>$sale[$i]["loteria"]))->row();
        $urllogoLottery = base_url().'dist/portal/images/home/'.$query->logo;
        $typelogoLottery = pathinfo($urllogoLottery, PATHINFO_EXTENSION);
        $logoLotteryData = file_get_contents($urllogoLottery, false, stream_context_create($arrContextOptions));
        $logoLotteryBase64Data = base64_encode($logoLotteryData);
        $logoLottery = 'data:image/' . $typelogoLottery . ';base64,' .
        $logoLotteryBase64Data;
        //
        $urlBarCode = base_url().'Barcode/index?code='.$sale[$i]["codigoBarras"];
        $typeBarCode = pathinfo($urlBarCode, PATHINFO_EXTENSION);
        $barCodeData = file_get_contents($urlBarCode, false, stream_context_create($arrContextOptions));
        $barCodeDataBase64Data = base64_encode($barCodeData);
        $barCode = 'data:image/' . $typeBarCode . ';base64,' .
        $barCodeDataBase64Data;        
    ?>
    <div style="background-color: #FFFFFF;
      width: 350px;
      margin-left: auto;
      margin-right: auto;
      padding: 0px;
      text-align: center;
      border: 2px solid #B3B2B2;">
    <header>
        <img src="<?= $logo ?>" alt="logo lottired">
        <!-- / #logo-header -->
    </header>
        <hr style="height: 0px;
        border: 1px dashed; margin-left: 15px; margin-right: 15px;">
        <img src="<?= $barCode ?>" alt="cod" style="width: 50%;" />
    <hr style="height: 0px;
        border: 1px dashed; margin-left: 15px; margin-right: 15px;"/>
    <table style="text-align: center; margin-left: 15px; margin-right: 15px;">
        <tr>
          <td style="font-family: Helvetica">Loteria: <?= $sale[$i]["loteria"]?> &nbsp;&nbsp;</td>
          <td style="font-family: Helvetica">|&nbsp;&nbsp; <?= $sale[$i]["nombreLoteria"]?></td>
          <td><img src="<?= $logoLottery?>" alt="logo loteria" style="width: 120px;
        height: 80px;"/></td>
        </tr>
      </table>
    <hr style="height: 0px;
        border: 1px dashed; margin-left: 15px; margin-right: 15px;">
    <p style="font-family: Helvetica">Premio Mayor</p>
    <h1 style="font-family: Helvetica">$<?= number_format($sale[$i]["valorPremioMayor"], 0, ',', '.')?></h1>
    <p style="font-family: Helvetica"><?= date("Y-m-d H:i:s",strtotime($sale[$i]["fechaHoraVenta"]))?></p>
    <div style="background-color: #f57c00; font-family: Helvetica; margin-left: 15px; margin-right: 15px;">Vendedor: www.lottired.net</div><br>
    <table style="text-align: center;">
        <tr>
          <th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
          <th style="font-family: Helvetica">Sorteo: <?= $sale[$i]["sorteo"]?></th>
          <th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
          <th style="font-family: Helvetica"><h5><?= date("d-m-Y",strtotime($sale[$i]["fechaJuega"]))?></h5></th>
        </tr>
      </table>
      <table style="text-align: center;">
        <thead>
          <tr>
            <th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
            <th>
                        <h3 style="font-family: Helvetica">Número</h3>
                    </th>
                    <th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                    <th>
                        <h3 style="font-family: Helvetica">Serie</h3>
                    </th>
          </tr>
        </thead>
        <tbody>
        <tr>
                    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                    <td><b style="font-family: Helvetica"><?= $sale[$i]["numero"]?></b></td>
                    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                    <td><b style="font-family: Helvetica"><?= $sale[$i]["serie"]?></b></td>
                </tr>
        </tbody>
      </table>
    <p style="font-family: Helvetica"><?= $sale[$i]["numeroEnLetras"]?></p><br> <?php $fractions = count($sale[$i]["fracciones"]);?>
    <p style="text-align: left; font-family: Helvetica; margin-left: 15px;">Cantidad de fracciones: <?= $fractions ?></p>
    <p style="text-align: left; font-family: Helvetica; margin-left: 15px;">Valor de la fraccion: $<?= number_format($sale[$i]["valorFraccion"], 0, ',', '.')?></p>
    <p style="text-align: left; font-family: Helvetica; margin-left: 15px;">Fraccion: <?php for($x=1;$x<=$fractions;$x++){
     echo $x. ' de ' .$sale[$i]["numeroFraccionesBilleteLoteria"].' | ';
    } ?></p>
    <h3 style="text-align: left; font-family: Helvetica; margin-left: 15px;">Total: $<?= number_format($sale[$i]["totalVenta"], 0, ',', '.')?></h3>
    <p style="text-align: right; font-family: Helvetica; margin-right: 15px;"><img src="<?= $firma ?>" alt="firma lottired"></p>
    <hr style="height: 0px;
        border: 1px dashed; margin-left: 15px; margin-right: 15px;">
    </div>
    <div style="page-break-before: always;"></div>
    <?php }} ?>         
</body>
</html>