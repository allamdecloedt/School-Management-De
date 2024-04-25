<?php $base_url = base_url(); ?>

<!-- JS Global Compulsory -->
<script
  src="<?php echo base_url(); ?>assets/frontend/<?php echo $theme; ?>/vendor/jquery-migrate/dist/jquery-migrate.min.js"></script>


<!-- JS Implementing Plugins -->

<script
  src="<?php echo base_url(); ?>assets/frontend/<?php echo $theme; ?>/vendor/svg-injector/dist/svg-injector.min.js"></script>
<script
  src="<?php echo base_url(); ?>assets/frontend/<?php echo $theme; ?>/vendor/fancybox/jquery.fancybox.min.js"></script>
<script
  src="<?php echo base_url(); ?>assets/frontend/<?php echo $theme; ?>/vendor/slick-carousel/slick/slick.js"></script>
<script
  src="<?php echo base_url(); ?>assets/frontend/<?php echo $theme; ?>/vendor/jquery-validation/dist/jquery.validate.min.js"></script>
<script
  src="<?php echo base_url(); ?>assets/frontend/<?php echo $theme; ?>/vendor/cubeportfolio/js/jquery.cubeportfolio.min.js"></script>


<!-- JS Front -->
<script src="<?php echo base_url(); ?>assets/frontend/<?php echo $theme; ?>/js/hs.core.js"></script>
<script src="<?php echo base_url(); ?>assets/frontend/<?php echo $theme; ?>/js/components/hs.header.js"></script>
<script src="<?php echo base_url(); ?>assets/frontend/<?php echo $theme; ?>/js/components/hs.unfold.js"></script>
<script src="<?php echo base_url(); ?>assets/frontend/<?php echo $theme; ?>/js/components/hs.fancybox.js"></script>
<script
  src="<?php echo base_url(); ?>assets/frontend/<?php echo $theme; ?>/js/components/hs.slick-carousel.js"></script>
<script src="<?php echo base_url(); ?>assets/frontend/<?php echo $theme; ?>/js/components/hs.validation.js"></script>
<script src="<?php echo base_url(); ?>assets/frontend/<?php echo $theme; ?>/js/components/hs.focus-state.js"></script>

<script src="<?php echo base_url(); ?>assets/frontend/<?php echo $theme; ?>/js/components/hs.g-map.js"></script>
<script src="<?php echo base_url(); ?>assets/frontend/<?php echo $theme; ?>/js/components/hs.cubeportfolio.js"></script>
<script src="<?php echo base_url(); ?>assets/frontend/<?php echo $theme; ?>/js/components/hs.svg-injector.js"></script>
<script src="<?php echo base_url(); ?>assets/frontend/<?php echo $theme; ?>/js/components/hs.go-to.js"></script>
<script src="<?php echo base_url(); ?>assets/jquery-form/jquery.form.min.js"></script>
<script src="<?php echo base_url(); ?>assets/toastr/toastr.min.js"></script>

<!-- JS Bootstrap 5 -->

<script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
  integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>


<!-- JS Owl carousel -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
  integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
  crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"
  integrity="sha512-bPs7Ae6pVvhOSiIcyUClR7/q2OAsRiovw4vAkX+zJbw3ShAeeqezq50RIIcIURq7Oa20rW2n2q+fyXBNcU9lrw=="
  crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<!-- JS Rellax -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/rellax/1.12.1/rellax.min.js"
  integrity="sha512-f5HTYZYTDZelxS7LEQYv8ppMHTZ6JJWglzeQmr0CVTS70vJgaJiIO15ALqI7bhsracojbXkezUIL+35UXwwGrQ=="
  crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<!-- Custom JS. -->





<?php



if ($page_name == "home") {

  echo '<script src="' . $base_url . 'assets/frontend/ultimate/js/sketch.min.js"></script>';
  echo '<script src="' . $base_url . 'assets/frontend/ultimate/js/home.js"></script>';


} elseif ($page_name == "about") {

  echo '<script defer >var rellax = new Rellax(".rellax");</script>';



} elseif ($page_name == "contact") {

    echo ' <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBFK8O-Fqob7VAuakxtTd66YA44hkdFEk8&callback=console.debug&libraries=maps,marker&v=beta"></script>';
  echo '<script src="' . $base_url . 'assets/frontend/ultimate/js/contact-map.js"></script>';
  echo '<script defer >var rellax = new Rellax(".rellax");</script>';


}

?>





<script type="text/javascript">
  'use strict';
  toastr.options = {
    "closeButton": true,
    "debug": false,
    "newestOnTop": true,
    "progressBar": true,
    "positionClass": "toast-top-right",
    "preventDuplicates": false,
    "onclick": null,
    "showDuration": "300",
    "hideDuration": "1000",
    "timeOut": "5000",
    "extendedTimeOut": "1000",
    "showEasing": "swing",
    "hideEasing": "linear",
    "showMethod": "fadeIn",
    "hideMethod": "slideUp"
  }
  function success_notify(message) {
    toastr.success(message);
  }

  function error_notify(message) {
    toastr.error(message);
  }
</script>

<!-- JS Plugins Init. -->
<script>
  $(window).on('load', function () {
    // initialization of HSMegaMenu component
    $('.js-mega-menu').HSMegaMenu({
      event: 'hover',
      pageContainer: $('.container'),
      breakpoint: 767.98,
      hideTimeOut: 0
    });

    // initialization of svg injector module
    $.HSCore.components.HSSVGIngector.init('.js-svg-injector');
  });

  $(document).on('ready', function () {
    // initialization of header
    $.HSCore.components.HSHeader.init($('#header'));

    // initialization of unfold component
    $.HSCore.components.HSUnfold.init($('[data-unfold-target]'));

    // initialization of fancybox
    $.HSCore.components.HSFancyBox.init('.js-fancybox');

    // initialization of slick carousel
    $.HSCore.components.HSSlickCarousel.init('.js-slick-carousel');

    // initialization of form validation
    $.HSCore.components.HSValidation.init('.js-validate');

    // initialization of forms
    $.HSCore.components.HSFocusState.init();

    // initialization of cubeportfolio
    $.HSCore.components.HSCubeportfolio.init('.cbp');

    // initialization of go to
    $.HSCore.components.HSGoTo.init('.js-go-to');
  });
</script>