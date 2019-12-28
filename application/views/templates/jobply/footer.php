<footer class="ftco-footer ftco-bg-dark ftco-section">
      <div class="container">
        <div class="row mb-5">
        	<div class="col-md">
             <div class="ftco-footer-widget mb-4">
              <h2 class="ftco-heading-2">Nosotros</h2>
              <p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts.</p>
              <ul class="ftco-footer-social list-unstyled float-md-left float-lft mt-3">
                <li class="ftco-animate"><a href="#"><span class="icon-twitter"></span></a></li>
                <li class="ftco-animate"><a href="#"><span class="icon-facebook"></span></a></li>
                <li class="ftco-animate"><a href="#"><span class="icon-instagram"></span></a></li>
              </ul>
            </div>
          </div>
          <div class="col-md">
            <div class="ftco-footer-widget mb-4">
              <h2 class="ftco-heading-2">Reclutadores</h2>
              <ul class="list-unstyled">
                <li><a href="#" class="py-2 d-block">Como empezar</a></li>
                <li><a href="#" class="py-2 d-block">Registrarse</a></li>
                <li><a href="#" class="py-2 d-block">Postula ofertas</a></li>
                <li><a href="#" class="py-2 d-block">Buscador avanzado de habilidades</a></li>
                <li><a href="#" class="py-2 d-block">Servicio de reclutamiento</a></li>
                <li><a href="#" class="py-2 d-block">Blog</a></li>
                <li><a href="#" class="py-2 d-block">Faq</a></li>
              </ul>
            </div>
          </div>
          <div class="col-md">
            <div class="ftco-footer-widget mb-4 ml-md-4">
              <h2 class="ftco-heading-2">Candidatos</h2>
              <ul class="list-unstyled">
                <li><a href="#" class="py-2 d-block">Como empezar</a></li>
                <li><a href="#" class="py-2 d-block">Registrarse</a></li>
                <li><a href="#" class="py-2 d-block">Postula tu hv</a></li>
                <li><a href="#" class="py-2 d-block">Buscar trabajo</a></li>
                <li><a href="#" class="py-2 d-block">Buscar empleador</a></li>
              </ul>
            </div>
          </div>
          <div class="col-md">
            <div class="ftco-footer-widget mb-4">
            	<h2 class="ftco-heading-2">Tienes una pregunta?</h2>
            	<div class="block-23 mb-3">
	              <ul>
	                <li><span class="icon icon-map-marker"></span><span class="text">Armenia, Quindio, Colombia</span></li>
	                <li><a href="#"><span class="icon icon-phone"></span><span class="text">+57 1 5802321</span></a></li>
	                <li><a href="#"><span class="icon icon-envelope"></span><span class="text">info@webcontrata.com</span></a></li>
	              </ul>
	            </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12 text-center">

            <p><!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
  Copyright &copy;<script>document.write(new Date().getFullYear());</script> All rights reserved | WebContrata</a>
  <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. --></p>
          </div>
        </div>
      </div>
    </footer>

    <script src="<?= base_url()?>dist/js/jquery.min.js"></script>
  <script src="<?= base_url()?>dist/js/jquery-migrate-3.0.1.min.js"></script>
  <script src="<?= base_url()?>dist/js/popper.min.js"></script>
  <script src="<?= base_url()?>dist/js/bootstrap.min.js"></script>
  <script src="<?= base_url()?>dist/js/jquery.easing.1.3.js"></script>
  <script src="<?= base_url()?>dist/js/jquery.waypoints.min.js"></script>
  <script src="<?= base_url()?>dist/js/jquery.stellar.min.js"></script>
  <script src="<?= base_url()?>dist/js/owl.carousel.min.js"></script>
  <script src="<?= base_url()?>dist/js/jquery.magnific-popup.min.js"></script>
  <script src="<?= base_url()?>dist/js/aos.js"></script>
  <script src="<?= base_url()?>dist/js/jquery.animateNumber.min.js"></script>
  <script src="<?= base_url()?>dist/js/scrollax.min.js"></script>
  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBVWaKrjvy3MaE7SQ74_uJiULgl1JY0H2s&sensor=false"></script>
  <script src="<?= base_url()?>dist/js/google-map.js"></script>
  <script src="<?= base_url()?>dist/js/main.js"></script>

  <!-- Needed html code here -->
  <?php if (is_array($js_to_load)) { ?>
  <?php foreach ($js_to_load as $row){ ?>
      <script type="text/javascript" src="<?= base_url()?>dist/js/<?=$row;?>"></script>
  <?php } } ?>  
  </body>

</html>