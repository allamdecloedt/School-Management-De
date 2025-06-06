<?php if (get_common_settings('recaptcha_status')): ?>
  <script src="https://www.google.com/recaptcha/api.js" async defer></script>
<?php endif; ?>
<!-- ========== MAIN ========== -->
<main id="content" role="main">
  <!-- Header Section -->
  <div class="general-container container-fluid">
    <div class="row general-header align-items-center">
      <h1 class='col-12 text_fade text-uppercase text-center'>contact us</h1>
    </div>
    <img class="ct-img rellax" data-rellax-speed="1.5" src="
			<?php echo base_url('assets/frontend/ultimate/img/contact us/cu-img-top.jpg') ?>" alt="">
    <div class="general-container-ol"></div>
  </div>
  <!-- End Header Section --> <?php $this->load->view('frontend/alert_view'); ?>
  <!-- Contact Content Section -->
  <div class="container-fluid form-section">
    <div class="row justify-content-center">
      <!-- Contacts Form -->
      <div class="col-12 col-md-8 col-lg-6 mb-4">
        <div class="form container g-0">
        <form  action="<?php echo site_url('home/contact/send'); ?>" method="post" id="contact_send" class="pt-8 js-validate contact_send realtime-form container" enctype="multipart/form-data">
                <!-- Champ caché pour le jeton CSRF -->
                <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />
            <div class="row">
            <div class="col-12 col-md-6">
                <div class="js-form-message">
                  <div class="input-group mb-3">
                    <span class="input-group-text">
                      <svg class="m-1" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                        class="bi bi-person-lines-fill" viewBox="0 0 16 16">
                        <path
                          d="M6 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6m-5 6s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zM11 3.5a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 0 1h-4a.5.5 0 0 1-.5-.5m.5 2.5a.5.5 0 0 0 0 1h4a.5.5 0 0 0 0-1zm2 3a.5.5 0 0 0 0 1h2a.5.5 0 0 0 0-1zm0 3a.5.5 0 0 0 0 1h2a.5.5 0 0 0 0-1z" />
                      </svg>
                    </span>
                    <input type="text" class="form-control shadow-none"
                      placeholder="<?php echo get_phrase('First name'); ?>" name="first_name" required
                      data-msg="Please enter your first name." data-error-class="u-has-error"
                      data-success-class="u-has-success">
                    <span class="text-danger required">*</span>
                  </div>
                </div>
              </div>
              <div class="col-12 col-md-6">
                <div class="js-form-message">
                  <div class="input-group mb-3">
                    <span class="input-group-text">
                      <svg class="m-1" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                        class="bi bi-person-lines-fill" viewBox="0 0 16 16">
                        <path
                          d="M6 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6m-5 6s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zM11 3.5a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 0 1h-4a.5.5 0 0 1-.5-.5m.5 2.5a.5.5 0 0 0 0 1h4a.5.5 0 0 0 0-1zm2 3a.5.5 0 0 0 0 1h2a.5.5 0 0 0 0-1zm0 3a.5.5 0 0 0 0 1h2a.5.5 0 0 0 0-1z" />
                      </svg>
                    </span>
                    <input type="text" class="form-control shadow-none"
                      placeholder="<?php echo get_phrase('Last name'); ?>" name="last_name" required
                      data-msg="Please enter your last name." data-error-class="u-has-error"
                      data-success-class="u-has-success">
                    <span class="text-danger required">*</span>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
            <div class="col-12 col-md-6">
                <div class="js-form-message">
                  <div class="mb-3 input-group">
                    <span class="input-group-text">
                      <svg class="m-1" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                        class="bi bi-envelope-at-fill" viewBox="0 0 16 16">
                        <path
                          d="M2 2A2 2 0 0 0 .05 3.555L8 8.414l7.95-4.859A2 2 0 0 0 14 2zm-2 9.8V4.698l5.803 3.546zm6.761-2.97-6.57 4.026A2 2 0 0 0 2 14h6.256A4.5 4.5 0 0 1 8 12.5a4.49 4.49 0 0 1 1.606-3.446l-.367-.225L8 9.586zM16 9.671V4.697l-5.803 3.546.338.208A4.5 4.5 0 0 1 12.5 8c1.414 0 2.675.652 3.5 1.671" />
                        <path
                          d="M15.834 12.244c0 1.168-.577 2.025-1.587 2.025-.503 0-1.002-.228-1.12-.648h-.043c-.118.416-.543.643-1.015.643-.77 0-1.259-.542-1.259-1.434v-.529c0-.844.481-1.4 1.26-1.4.585 0 .87.333.953.63h.03v-.568h.905v2.19c0 .272.18.42.411.42.315 0 .639-.415.639-1.39v-.118c0-1.277-.95-2.326-2.484-2.326h-.04c-1.582 0-2.64 1.067-2.64 2.724v.157c0 1.867 1.237 2.654 2.57 2.654h.045c.507 0 .935-.07 1.18-.18v.731c-.219.1-.643.175-1.237.175h-.044C10.438 16 9 14.82 9 12.646v-.214C9 10.36 10.421 9 12.485 9h.035c2.12 0 3.314 1.43 3.314 3.034zm-4.04.21v.227c0 .586.227.8.581.8.31 0 .564-.17.564-.743v-.367c0-.516-.275-.708-.572-.708-.346 0-.573.245-.573.791" />
                      </svg>
                    </span>
                    <input type="email" class="form-control shadow-none" name="email"
                      placeholder="<?php echo get_phrase('Email address'); ?>" required
                      data-msg="Please enter a valid email address." data-error-class="u-has-error"
                      data-success-class="u-has-success">
                    <span class="text-danger required">*</span>
                  </div>
                </div>
              </div>
              <div class="col-12 col-md-6">
                <div class="js-form-message">

                  <div class="input-group mb-3">
                    <span class="input-group-text ">
                      <svg class="m-1" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                        class="bi bi-telephone-fill" viewBox="0 0 16 16">
                        <path fill-rule="evenodd"
                          d="M1.885.511a1.745 1.745 0 0 1 2.61.163L6.29 2.98c.329.423.445.974.315 1.494l-.547 2.19a.68.68 0 0 0 .178.643l2.457 2.457a.68.68 0 0 0 .644.178l2.189-.547a1.75 1.75 0 0 1 1.494.315l2.306 1.794c.829.645.905 1.87.163 2.611l-1.034 1.034c-.74.74-1.846 1.065-2.877.702a18.6 18.6 0 0 1-7.01-4.42 18.6 18.6 0 0 1-4.42-7.009c-.362-1.03-.037-2.137.703-2.877z" />
                      </svg>
                    </span>
                    <input type="tel" class="form-control shadow-none" placeholder="+971 22 222 2222" name="phone"
                      required data-msg="Please enter a valid phone number." data-error-class="u-has-error"
                      data-success-class="u-has-success">
                    <span class="text-danger required">*</span>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
            <div class="col-12 ">
                <div class="js-form-message">
                  <div class="input-group mb-3">
                    <span class="input-group-text">
                      <svg class="m-1" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                        class="bi bi-book-fill" viewBox="0 0 16 16">
                        <path
                          d="M8 1.783C7.015.936 5.587.81 4.287.94c-1.514.153-3.042.672-3.994 1.105A.5.5 0 0 0 0 2.5v11a.5.5 0 0 0 .707.455c.882-.4 2.303-.881 3.68-1.02 1.409-.142 2.59.087 3.223.877a.5.5 0 0 0 .78 0c.633-.79 1.814-1.019 3.222-.877 1.378.139 2.8.62 3.681 1.02A.5.5 0 0 0 16 13.5v-11a.5.5 0 0 0-.293-.455c-.952-.433-2.48-.952-3.994-1.105C10.413.809 8.985.936 8 1.783" />
                      </svg>
                    </span>
                    <input type="text" class="form-control shadow-none"
                      placeholder="<?php echo get_phrase('Location'); ?>" name="address" required
                      data-msg="Please enter your location." data-error-class="u-has-error"
                      data-success-class="u-has-success">
                    <span class="text-danger required">*</span>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
            <div class="col-12">
                <div class="js-form-message">
                  <div class="input-group mb-3">
                    <span class="input-group-text">
                      <svg class="m-1" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                        class="bi bi-chat-right-text-fill" viewBox="0 0 16 16">
                        <path
                          d="M16 2a2 2 0 0 0-2-2H2a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2h9.586a1 1 0 0 1 .707.293l2.853 2.853a.5.5 0 0 0 .854-.353zM3.5 3h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1 0-1m0 2.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1 0-1m0 2.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1 0-1" />
                      </svg>
                    </span>
                    <textarea type="text" class="form-control shadow-none" rows="5"
                      placeholder="<?php echo get_phrase('comments_or_questions'); ?>" name="comment" required
                      data-msg="Please enter your message." data-error-class="u-has-error"
                      data-success-class="u-has-success"></textarea>
                    <span class="text-danger required">*</span>
                  </div>
                </div>
              </div>
            </div>
            <div class="row justify-content-center"> <?php if (get_common_settings('recaptcha_status')): ?> <div class="js-form-message mb-3">
                <div class="form-group">
                  <div class="g-recaptcha" data-sitekey="
																	<?php echo get_common_settings('recaptcha_sitekey'); ?>">
                  </div>
                </div>
              </div> <?php endif; ?> <div class="text-center">
                <button type="submit" id="submitBtn" class="btn btn-send btn-wide col-3 mb-3 submit-button text-uppercase"> <?php echo get_phrase('Send'); ?> </button>
                <button type="reset" id="resetBtn" style="display: none;"></button>
              </div>
            </div>
          </form>
        </div>
      </div>
      <!-- Nouveau box à droite -->
      <div class="col-12 col-md-8 col-lg-4 mb-10">
        <div class="additional-box container ">
          <div class="card additional-card">
            <div class="card-body">
              <p class="contact-item">
                <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-telephone-fill" viewBox="0 0 16 16">
                  <path fill-rule="evenodd" d="M1.885.511a1.745 1.745 0 0 1 2.61.163L6.29 2.98c.329.423.445.974.315 1.494l-.547 2.19a.68.68 0 0 0 .178.643l2.457 2.457a.68.68 0 0 0 .644.178l2.189-.547a1.75 1.75 0 0 1 1.494.315l2.306 1.794c.829.645.905 1.87.163 2.611l-1.034 1.034c-.74.74-1.846 1.065-2.877.702a18.6 18.6 0 0 1-7.01-4.42 18.6 18.6 0 0 1-4.42-7.009c-.362-1.03-.037-2.137.703-2.877z" />
                </svg><a class="contact-phone" href="tel:<?php echo get_settings('phone'); ?>">
              <?php echo get_settings('phone'); ?>
              </a>
              </p>
              <p class="contact-item">
                <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-envelope-fill" viewBox="0 0 16 16">
                  <path d="M.05 3.555A2 2 0 0 1 2 2h12a2 2 0 0 1 1.95 1.555L8 8.414zM0 4.697v7.104l5.803-3.558zM6.761 8.83l-6.57 4.027A2 2 0 0 0 2 14h12a2 2 0 0 0 1.808-1.144l-6.57-4.027L8 9.586zm3.436-.586L16 11.801V4.697z" />
                </svg><a class="contact-email" href="mailto:<?php echo get_settings('system_email'); ?>">
                <?php echo get_settings('system_email'); ?>
              </a>
              </p>
              <p class="contact-item">
                <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-geo-alt-fill" viewBox="0 0 16 16">
                  <path d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10m0-7a3 3 0 1 1 0-6 3 3 0 0 1 0 6" />
                </svg><a class="contact-address" href="<?php echo site_url('home/contact#map'); ?>">
              <?php echo get_settings('address'); ?>
              </a>
              </p>
              <div class="social-icons">
                <a href="https://www.facebook.com/people/Wayo-Academy/61572524656807/" target="_blank">
                  <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="#0E7AE7" class="bi bi-facebook" viewBox="0 0 16 16">
                    <path d="M16 8.049c0-4.446-3.582-8.05-8-8.05C3.58 0-.002 3.603-.002 8.05c0 4.017 2.926 7.347 6.75 7.951v-5.625h-2.03V8.05H6.75V6.275c0-2.017 1.195-3.131 3.022-3.131.876 0 1.791.157 1.791.157v1.98h-1.009c-.993 0-1.303.621-1.303 1.258v1.51h2.218l-.354 2.326H9.25V16c3.824-.604 6.75-3.934 6.75-7.951" />
                  </svg>
                </a>
                <a href="https://www.instagram.com/wayo_academy/" target="_blank">
                  <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="#D70CE4" class="bi bi-instagram" viewBox="0 0 16 16">
                    <path d="M8 0C5.829 0 5.556.01 4.703.048 3.85.088 3.269.222 2.76.42a3.9 3.9 0 0 0-1.417.923A3.9 3.9 0 0 0 .42 2.76C.222 3.268.087 3.85.048 4.7.01 5.555 0 5.827 0 8.001c0 2.172.01 2.444.048 3.297.04.852.174 1.433.372 1.942.205.526.478.972.923 1.417.444.445.89.719 1.416.923.51.198 1.09.333 1.942.372C5.555 15.99 5.827 16 8 16s2.444-.01 3.298-.048c.851-.04 1.434-.174 1.943-.372a3.9 3.9 0 0 0 1.416-.923c.445-.445.718-.891.923-1.417.197-.509.332-1.09.372-1.942C15.99 10.445 16 10.173 16 8s-.01-2.445-.048-3.299c-.04-.851-.175-1.433-.372-1.941a3.9 3.9 0 0 0-.923-1.417A3.9 3.9 0 0 0 13.24.42c-.51-.198-1.092-.333-1.943-.372C10.443.01 10.172 0 7.998 0zm-.717 1.442h.718c2.136 0 2.389.007 3.232.046.78.035 1.204.166 1.486.275.373.145.64.319.92.599s.453.546.598.92c.11.281.24.705.275 1.485.039.843.047 1.096.047 3.231s-.008 2.389-.047 3.232c-.035.78-.166 1.203-.275 1.485a2.5 2.5 0 0 1-.599.919c-.28.28-.546.453-.92.598-.28.11-.704.24-1.485.276-.843.038-1.096.047-3.232.047s-2.39-.009-3.233-.047c-.78-.036-1.203-.166-1.485-.276a2.5 2.5 0 0 1-.92-.598 2.5 2.5 0 0 1-.6-.92c-.109-.281-.24-.705-.275-1.485-.038-.843-.046-1.096-.046-3.233s.008-2.388.046-3.231c.036-.78.166-1.204.276-1.486.145-.373.319-.64.599-.92s.546-.453.92-.598c.282-.11.705-.24 1.485-.276.738-.034 1.024-.044 2.515-.045zm4.988 1.328a.96.96 0 1 0 0 1.92.96.96 0 0 0 0-1.92m-4.27 1.122a4.109 4.109 0 1 0 0 8.217 4.109 4.109 0 0 0 0-8.217m0 1.441a2.667 2.667 0 1 1 0 5.334 2.667 2.667 0 0 1 0-5.334" />
                  </svg>
                </a>
                <a href="https://www.linkedin.com/company/wayoacademy/about/" target="_blank">
                  <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="#1469C7" class="bi bi-linkedin" viewBox="0 0 16 16">
                    <path d="M0 1.146C0 .513.526 0 1.175 0h13.65C15.474 0 16 .513 16 1.146v13.708c0 .633-.526 1.146-1.175 1.146H1.175C.526 16 0 15.487 0 14.854zm4.943 12.248V6.169H2.542v7.225zm-1.2-8.212c.837 0 1.358-.554 1.358-1.248-.015-.709-.52-1.248-1.342-1.248S2.4 3.226 2.4 3.934c0 .694.521 1.248 1.327 1.248zm4.908 8.212V9.359c0-.216.016-.432.08-.586.173-.431.568-.878 1.232-.878.869 0 1.216.662 1.216 1.634v3.865h2.401V9.25c0-2.22-1.184-3.252-2.764-3.252-1.274 0-1.845.7-2.165 1.193v.025h-.016l.016-.025V6.169h-2.4c.03.678 0 7.225 0 7.225z" />
                  </svg>
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <img class="cm-img rellax pb-11" data-rellax-speed="1.5" src="
										<?php echo base_url('assets/frontend/ultimate/img/contact us/cu-img-mid.jpg') ?>" alt="">
  </div>
  <div class="container-fluid location-container">
    <div class="row">
      <h1 class="office-title text-center text-break py-10 text-uppercase">WAYO ACADEMY LOCATION</h1>
    </div>
    <div class="row">
      <div id="map" class="g-0 col-12"></div>
    </div>
  </div>
</main>

<?php if ($this->session->flashdata('toast_message')): ?>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const toastData = <?php echo json_encode($this->session->flashdata('toast_message')); ?>;
    const toastType = toastData.type === 'success' ? 'success' : 'error';
    const toastMessage = toastData.message;
    if (toastType === 'success') {
      toastr.success(toastMessage, 'Success', { timeOut: 5000 });
    } else {
      toastr.error(toastMessage, 'Error', { timeOut: 5000 });
    }
  });
</script>
<?php endif; ?>

<script>
const contactform = document.getElementById("contact_send");
if (contactform) {
  document.getElementById('submitBtn').addEventListener('click', function (event) {
    if (contactform.checkValidity()) {
      setTimeout(function () {
        contactform.reset();
      }, 500);
    } else {
      contactform.reportValidity();
    }
  });
}
</script>