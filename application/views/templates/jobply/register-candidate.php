<div class="hero-wrap js-fullheight">
      <div class="overlay"></div>
      <div class="container-fluid px-0">
      	<div class="row d-md-flex no-gutters slider-text align-items-end js-fullheight justify-content-end">
	      	<img class="one-third align-self-end order-md-last img-fluid" src="<?= base_url()?>dist/images/undraw_work_time_lhoj.svg" alt="">
	        <div class="one-forth d-flex align-items-center ftco-animate js-fullheight">                            
            <div class="text"><br><br><br>
                <h3>Crea tu CV gratis en WebContrata</h3>
            <form id="frmRegisterCandidate">
  <div class="form-row">
    <div class="form-group col-md-6">
      <label for="inputName">Nombre</label>
      <input type="text" class="form-control" id="inputName" name="name" placeholder="Nombre">
    </div>
    <div class="form-group col-md-6">
      <label for="inputSurname">Apellidos</label>
      <input type="text" class="form-control" id="inputSurname" name="surname" placeholder="Apellidos">
    </div>
  </div>
  <div class="form-row">
    <div class="form-group col-md-6">
      <label for="inputEmail">Correo Electronico</label>
      <input type="email" class="form-control" id="inputEmail" name="email" placeholder="Correo Electronico">
    </div>
    <div class="form-group col-md-6">
      <label for="inputPassword">Contraseña</label>
      <input type="password" class="form-control" id="inputPassword" name="pass" placeholder="Contraseña">
    </div>
  </div>
  <div class="form-row">
    <div class="form-group col-md-6">
      <label for="inputJob">Puesto de trabajo deseado</label>
      <input type="text" class="form-control" id="inputJob" name="job">
    </div>
    <div class="form-group col-md-6">
      <label for="inputState">Localidad</label>
      <select id="inputState" class="form-control" name="locations_idlocation">
        <option selected>Seleccione...</option>
        <?php foreach($locations as $location){?>
            <option value="<?= $location->idlocation?>"><?= $location->location?></option>
        <?php } ?>        
      </select>
    </div>
    <input type="hidden" name="profiles_idprofile" value="1">
  </div>  
  <button type="submit" class="btn btn-primary">Únirme ahora</button>
</form>
</div>
	            </div>
	        </div>
	    </div>
      </div>
    </div>
