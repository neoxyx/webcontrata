<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Registrar Promocional
      </h1>
      <br>
      <form id="frmRegistrarPromocional">
        <div class="row">
            <div class="form-group col-md-6 col-lg-6">
              <label for="loterias">Loterias:</label>
              <select name="loterias" id="loterias" class="form-control" required>
                  <option value="0">Seleccione</option>
                  <?php if($loterias) { foreach($loterias as $loteria){ ?>
                      <option value="<?= $loteria->LOTERIA?>"><?= $loteria->NOMBRE?></option>
                  <?php }} ?>
              </select>
            </div>
            <div class="form-group col-md-6 col-lg-6">
              <label for="tipo">Tipo promocional:</label>
              <select name="tipo" id="tipo" class="form-control" required>
                  <option value="0">Seleccione</option>
                  <?php if($tipos) { foreach($tipos as $tipo){ ?>
                      <option value="<?= $tipo->ID?>"><?= $tipo->NOMBRE?></option>
                  <?php }} ?>
                  <option value="4">PROMOCIONAL CON COBRO</option>
              </select>
            </div>
        </div> 
        <div class="row" id="dvArticulo" hidden>
          <div class="form-group col-md-6 col-lg-6">
            <label for="articulo">Articulo:</label>
            <input type="text" class="form-control" name="articulo" id="articulo" placeholder="Nombre del artículo">
          </div>
          <div class="form-group col-md-6 col-lg-6">
              <label for="unidadesArticulo">Unidades:</label>
              <input type="number" class="form-control" pattern="[0-9]" name="unidadesArticulo" id="unidadesArticulo" placeholder="cantidad de unidades">
          </div>
        </div>
        <div class="row" id="dvBono" hidden>
          <div class="form-group col-md-4 col-lg-4">
            <label for="bono">Valor del bono:</label>
            <input type="number" class="form-control" name="bono" id="bono" placeholder="Valor del bono">
          </div>
          <div class="form-group col-md-4 col-lg-4">
              <label for="unidadesBono">Unidades:</label>
              <input type="number" class="form-control" name="unidadesBono" id="unidadesBono" pattern="[0-9]" placeholder="cantidad de unidades">
          </div>
          <div class="form-group col-md-4 col-lg-4">
              <label for="compromiso">Compromiso:</label>
              <input type="number" class="form-control" name="compromiso" id="compromiso" pattern="[0-9]" placeholder="compromiso presupuestal">
          </div>
        </div>
        <div class="row" id="dvFraccion" hidden>
          <div class="form-group col-md-6 col-lg-6">
              <label for="unidadesFracciones">Unidades:</label>
              <input type="number" class="form-control" name="unidadesFracciones" pattern="[0-9]" id="unidadesFracciones" placeholder="cantidad de unidades">
          </div>
        </div>
        <div class="row" id="dvPromCobro" hidden>
          <div class="form-group col-md-6 col-lg-6">
              <label for="valorPromCobro">Valor:</label>
              <input type="number" class="form-control" name="valorPromCobro" pattern="[0-9]" id="valorPromCobro" placeholder="valor del promocional con cobro">
          </div>
          <div class="form-group col-md-6 col-lg-6">
              <label for="sorteo">Sorteo:</label>
              <input type="number" class="form-control" name="sorteo" pattern="[0-9]" id="sorteo" placeholder="Sorteo">
          </div>
        </div>
        <div class="row">
          <div class="form-group col-md-6 col-lg-6 col-md-offset-3 col-lg-offset-3">
            <button type="submit" style="width: 100%" class="btn btn-primary">Guardar</button>
          </div>
        </div>
      </form>
    </section>
</div>

