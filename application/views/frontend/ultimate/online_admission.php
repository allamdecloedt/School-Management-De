<?php if (get_common_settings('recaptcha_status')): ?>
  <script src="https://www.google.com/recaptcha/api.js" async defer></script>
<?php endif; ?>

<?php
?>

<!-- ========== MAIN ========== -->
<main id="content" role="main">

  <!-- Header Section -->
  <div class="general-container container-fluid">
    <div class="general-header align-items-center">
      <h1 class='col-6 display-4 text_fade text-uppercase text-center  text-sm-break'>
        <?php echo get_phrase('start_your_journey'); ?>
      </h1>
      <!-- Div Section For Header Background Fade In-Out Animation-->
      <div></div>
      <div></div>
      <div></div>
      <!-- End Div Section-->
    </div>
    <img class="ct-img rellax " data-rellax-speed="1.5"
      src="<?php echo base_url('assets/frontend/ultimate/img/online admission/oa-img-top.jpg') ?>" alt="">
    <div class="general-container-ol"></div>
  </div>
  <!-- End Header Section -->


  <!-- Admission Form Section -->
  <div class="container-fluid form-section pt-10">
   <!-- Display Error -->
   <?php if ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger">
            <?php echo $this->session->flashdata('error');$this->session->unset_userdata('error');  ?>
            
        </div>
    <?php endif; ?>

    <!-- Display Success -->
    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success">
            <?php echo $this->session->flashdata('success'); $this->session->unset_userdata('success'); ?>
        </div>
    <?php endif; ?>>

    <!-- Start School Admission Form -->

    <form action="<?php echo site_url('admission/online_admission/submit/school'); ?>" method="post" id="schoolform"
      class="js-validate studentform realtime-form container" enctype="multipart/form-data">
          <!-- Champ caché pour le jeton CSRF -->
     <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />

      <div class="row justify-content-center">
        <h4 class="col h2 pb-5 text-uppercase d-flex justify-content-center form-title">
          <?php echo get_phrase('school_admission'); ?>
        </h4>
        <p class="text-white h5 pb-5 text-uppercase d-flex justify-content-center form-label">
          <?php echo get_phrase('school_information'); ?>
        </p>
      </div>

      <div class="row justify-content-center">

        <!-- Input -->
        <div class="col-sm-4 col-11">
          <div class="js-form-message mb-5">
            <label class="form-label text-white">
              <?php echo get_phrase('school_name'); ?>
              <span class="text-danger">*</span>
            </label>
            <div class="input-group pt-1">
              <span class="input-group-text">
                <svg class="m-1" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                  class="bi bi-bank" viewBox="0 0 16 16">
                  <path
                    d="m8 0 6.61 3h.89a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.5.5H15v7a.5.5 0 0 1 .485.38l.5 2a.498.498 0 0 1-.485.62H.5a.498.498 0 0 1-.485-.62l.5-2A.5.5 0 0 1 1 13V6H.5a.5.5 0 0 1-.5-.5v-2A.5.5 0 0 1 .5 3h.89zM3.777 3h8.447L8 1zM2 6v7h1V6zm2 0v7h2.5V6zm3.5 0v7h1V6zm2 0v7H12V6zM13 6v7h1V6zm2-1V4H1v1zm-.39 9H1.39l-.25 1h13.72z" />
                </svg>
              </span>
              <input type="text" placeholder="<?php echo get_phrase('school_name'); ?>"
                class="form-control shadow-none rounded-end text-capitalize" name="school_name" required
                data-msg="Please enter your first name." data-error-class="u-has-error"
                data-success-class="u-has-success">
            </div>
          </div>
        </div>
        <!-- End Input -->

        <!-- Input -->
        <div class="col-sm-4 col-11">
          <div class="js-form-message mb-5">
            <label class="form-label text-white">
              <?php echo get_phrase('category'); ?>
              <span class="text-danger">*</span>
            </label>
            <div class="input-group pt-1">
              <span class="input-group-text">
                <svg class="m-1" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                  class="bi bi-tags" viewBox="0 0 16 16">
                  <path
                    d="M3 2v4.586l7 7L14.586 9l-7-7zM2 2a1 1 0 0 1 1-1h4.586a1 1 0 0 1 .707.293l7 7a1 1 0 0 1 0 1.414l-4.586 4.586a1 1 0 0 1-1.414 0l-7-7A1 1 0 0 1 2 6.586z" />
                  <path
                    d="M5.5 5a.5.5 0 1 1 0-1 .5.5 0 0 1 0 1m0 1a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3M1 7.086a1 1 0 0 0 .293.707L8.75 15.25l-.043.043a1 1 0 0 1-1.414 0l-7-7A1 1 0 0 1 0 7.586V3a1 1 0 0 1 1-1z" />
                </svg>
              </span>


              <select name="category" id="category" class="form-control selec2 rounded-end shadow-none"
                data-toggle="select2" required>
                <option value=""><?php echo get_phrase('select_a_category'); ?></option>
                <?php $categories = $this->db->get_where('categories', array())->result_array(); ?>
                <?php foreach ($categories as $categorie): ?>
                  <option value="<?php echo $categorie['name']; ?>"><?php echo $categorie['name']; ?></option>
                <?php endforeach; ?>

              </select>
            </div>
          </div>
        </div>
        <!-- End Input -->
      </div>
      <div class="row justify-content-center">

        <!-- Input -->
        <div class="col-sm-4 col-11">
          <div class="js-form-message mb-5">
            <label class="form-label text-white">
              <?php echo get_phrase('phone'); ?>
              <span class="text-danger">*</span>

            </label>
            <div class="input-group pt-1">
              <span class="input-group-text ">
                <svg class="m-1" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                  class="bi bi-telephone" viewBox="0 0 16 16">
                  <path
                    d="M3.654 1.328a.678.678 0 0 0-1.015-.063L1.605 2.3c-.483.484-.661 1.169-.45 1.77a17.6 17.6 0 0 0 4.168 6.608 17.6 17.6 0 0 0 6.608 4.168c.601.211 1.286.033 1.77-.45l1.034-1.034a.678.678 0 0 0-.063-1.015l-2.307-1.794a.68.68 0 0 0-.58-.122l-2.19.547a1.75 1.75 0 0 1-1.657-.459L5.482 8.062a1.75 1.75 0 0 1-.46-1.657l.548-2.19a.68.68 0 0 0-.122-.58zM1.884.511a1.745 1.745 0 0 1 2.612.163L6.29 2.98c.329.423.445.974.315 1.494l-.547 2.19a.68.68 0 0 0 .178.643l2.457 2.457a.68.68 0 0 0 .644.178l2.189-.547a1.75 1.75 0 0 1 1.494.315l2.306 1.794c.829.645.905 1.87.163 2.611l-1.034 1.034c-.74.74-1.846 1.065-2.877.702a18.6 18.6 0 0 1-7.01-4.42 18.6 18.6 0 0 1-4.42-7.009c-.362-1.03-.037-2.137.703-2.877z" />
                </svg>
              </span>
              <input type="tel" required
                pattern="(?=(?:\D*\d){7,15}\D*$)\+?\d+\s?\d{1,3}\s?\d{1,4}\s?\d{1,4}\s?\d{1,4}\s?\d{1,4}"
                placeholder="+971 22 222 2222" class="form-control rounded-end shadow-none" name="school_phone"
                data-msg="Please enter a valid phone number." data-error-class="u-has-error"
                data-success-class="u-has-success">
            </div>
          </div>
        </div>
        <!-- End Input -->

        <!-- Input -->
        <div class="col-sm-4 col-11">
          <div class="js-form-message mb-5">
            <label class="form-label text-white">
              <?php echo get_phrase('address'); ?>
              <span class="text-danger">*</span>

            </label>
            <div class="input-group pt-1">
              <span class="input-group-text">
                <svg class="m-1" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                  class="bi bi-globe2" viewBox="0 0 16 16">
                  <path
                    d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m7.5-6.923c-.67.204-1.335.82-1.887 1.855q-.215.403-.395.872c.705.157 1.472.257 2.282.287zM4.249 3.539q.214-.577.481-1.078a7 7 0 0 1 .597-.933A7 7 0 0 0 3.051 3.05q.544.277 1.198.49zM3.509 7.5c.036-1.07.188-2.087.436-3.008a9 9 0 0 1-1.565-.667A6.96 6.96 0 0 0 1.018 7.5zm1.4-2.741a12.3 12.3 0 0 0-.4 2.741H7.5V5.091c-.91-.03-1.783-.145-2.591-.332M8.5 5.09V7.5h2.99a12.3 12.3 0 0 0-.399-2.741c-.808.187-1.681.301-2.591.332zM4.51 8.5c.035.987.176 1.914.399 2.741A13.6 13.6 0 0 1 7.5 10.91V8.5zm3.99 0v2.409c.91.03 1.783.145 2.591.332.223-.827.364-1.754.4-2.741zm-3.282 3.696q.18.469.395.872c.552 1.035 1.218 1.65 1.887 1.855V11.91c-.81.03-1.577.13-2.282.287zm.11 2.276a7 7 0 0 1-.598-.933 9 9 0 0 1-.481-1.079 8.4 8.4 0 0 0-1.198.49 7 7 0 0 0 2.276 1.522zm-1.383-2.964A13.4 13.4 0 0 1 3.508 8.5h-2.49a6.96 6.96 0 0 0 1.362 3.675c.47-.258.995-.482 1.565-.667m6.728 2.964a7 7 0 0 0 2.275-1.521 8.4 8.4 0 0 0-1.197-.49 9 9 0 0 1-.481 1.078 7 7 0 0 1-.597.933M8.5 11.909v3.014c.67-.204 1.335-.82 1.887-1.855q.216-.403.395-.872A12.6 12.6 0 0 0 8.5 11.91zm3.555-.401c.57.185 1.095.409 1.565.667A6.96 6.96 0 0 0 14.982 8.5h-2.49a13.4 13.4 0 0 1-.437 3.008M14.982 7.5a6.96 6.96 0 0 0-1.362-3.675c-.47.258-.995.482-1.565.667.248.92.4 1.938.437 3.008zM11.27 2.461q.266.502.482 1.078a8.4 8.4 0 0 0 1.196-.49 7 7 0 0 0-2.275-1.52c.218.283.418.597.597.932m-.488 1.343a8 8 0 0 0-.395-.872C9.835 1.897 9.17 1.282 8.5 1.077V4.09c.81-.03 1.577-.13 2.282-.287z" />
                </svg>
              </span>
              <input type="text" class="form-control rounded-end shadow-none" name="school_adress" required
                data-msg="Please enter your address" data-error-class="u-has-error"
                data-success-class="u-has-success" placeholder="<?php echo get_phrase('address'); ?>">
            </div>
          </div>
        </div>
        <!-- End Input -->
      </div>

      <div class="row justify-content-center">
        <!-- Input -->
        <div class=" col-11 col-sm-8 mb-6 mt-4">
          <div class="js-form-message">
            <label class="form-label text-white">
              <?php echo get_phrase('description'); ?>
              <span class="text-danger">*</span>
            </label>
            <div class="input-group pt-1">
              <span class="input-group-text">
                <svg class="m-1" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                  class="bi bi-pen" viewBox="0 0 16 16">
                  <path
                    d="m13.498.795.149-.149a1.207 1.207 0 1 1 1.707 1.708l-.149.148a1.5 1.5 0 0 1-.059 2.059L4.854 14.854a.5.5 0 0 1-.233.131l-4 1a.5.5 0 0 1-.606-.606l1-4a.5.5 0 0 1 .131-.232l9.642-9.642a.5.5 0 0 0-.642.056L6.854 4.854a.5.5 0 1 1-.708-.708L9.44.854A1.5 1.5 0 0 1 11.5.796a1.5 1.5 0 0 1 1.998-.001m-.644.766a.5.5 0 0 0-.707 0L1.95 11.756l-.764 3.057 3.057-.764L14.44 3.854a.5.5 0 0 0 0-.708z" />
                </svg>
              </span>
              <textarea class="form-control shadow-none" rows="3" name="school_description" required
                data-msg="Please enter a description." data-error-class="u-has-error"
                data-success-class="u-has-success"></textarea>
            </div>
          </div>
        </div>
        <!-- End Input -->
      </div>

      <div class="row justify-content-center">
        <!-- Input -->
        <div class="col-11 col-sm-8 col-lg-4">
          <div class="js-form-message mb-5 ">


            <div class="visibility-label ">
              <span class="">
                <svg class="m-1" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                  class="bi bi-gender-ambiguous" viewBox="0 0 16 16">
                  <path fill-rule="evenodd"
                    d="M11.5 1a.5.5 0 0 1 0-1h4a.5.5 0 0 1 .5.5v4a.5.5 0 0 1-1 0V1.707l-3.45 3.45A4 4 0 0 1 8.5 10.97V13H10a.5.5 0 0 1 0 1H8.5v1.5a.5.5 0 0 1-1 0V14H6a.5.5 0 0 1 0-1h1.5v-2.03a4 4 0 1 1 3.471-6.648L14.293 1zm-.997 4.346a3 3 0 1 0-5.006 3.309 3 3 0 0 0 5.006-3.31z" />
                </svg>
              </span>
              <span class="form-label text-white text-end">
                <?php echo get_phrase('access_mode'); ?>
                <span class="text-danger">*</span>
              </span>
              <div class="visibility-selector pt-3">

                <div class="vis-button">
                  <input id="private" type="radio" name="visibility" value="0" checked>
                  <label class="private-button form-label" for="private"><?php echo get_phrase('private'); ?></label>
                </div>
                <div class="vis-button">
                  <input id="public" type="radio" name="visibility" value="1">
                  <label class="public-button form-label" for="public"><?php echo get_phrase('public'); ?></label>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- End Input -->

        <!-- Input -->
        <div class="school-image-modal display-none">
          <div class="modal-content">
            <div class="school-image-container">
              <img class="school-image-preview" src="" alt="">
              <span class="close-school-image">
                <svg class="m-1" xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="currentColor"
                  class="bi bi-eye-slash-fill" viewBox="0 0 16 16">
                  <path
                    d="m10.79 12.912-1.614-1.615a3.5 3.5 0 0 1-4.474-4.474l-2.06-2.06C.938 6.278 0 8 0 8s3 5.5 8 5.5a7 7 0 0 0 2.79-.588M5.21 3.088A7 7 0 0 1 8 2.5c5 0 8 5.5 8 5.5s-.939 1.721-2.641 3.238l-2.062-2.062a3.5 3.5 0 0 0-4.474-4.474z" />
                  <path
                    d="M5.525 7.646a2.5 2.5 0 0 0 2.829 2.829zm4.95.708-2.829-2.83a2.5 2.5 0 0 1 2.829 2.829zm3.171 6-12-12 .708-.708 12 12z" />
                </svg></span>
              <p class="loading text-uppercase"><?php echo get_phrase('loading_image'); ?>...</p>
            </div>
          </div>
        </div>
        <div class="col-11 col-sm-8 col-lg-4   ">
          <div class="js-form-message mb-3">
            <div class="">
              <p class="pb-3 form-label text-white text-end"><?php echo get_phrase('school_image'); ?> 

              </p>
              <div id="school-image-preview" class="">
                <a role="button" class="btn school-image-preview-btn disabled">
                  <svg class="m-1" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                    class="bi bi-eye-fill" viewBox="0 0 16 16">
                    <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0" />
                    <path
                      d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8m8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7" />
                  </svg>
                </a>
              </div>
              <label for="school_image" class="btn btn-sm button-label form-label text-white ">
                <svg class="" xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="currentColor"
                  class="bi bi-filetype-png" viewBox="0 0 16 16">
                  <path fill-rule="evenodd"
                    d="M14 4.5V14a2 2 0 0 1-2 2v-1a1 1 0 0 0 1-1V4.5h-2A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v9H2V2a2 2 0 0 1 2-2h5.5zm-3.76 8.132q.114.23.14.492h-.776a.8.8 0 0 0-.097-.249.7.7 0 0 0-.17-.19.7.7 0 0 0-.237-.126 1 1 0 0 0-.299-.044q-.427 0-.665.302-.234.301-.234.85v.498q0 .351.097.615a.9.9 0 0 0 .304.413.87.87 0 0 0 .519.146 1 1 0 0 0 .457-.096.67.67 0 0 0 .272-.264q.09-.164.091-.363v-.255H8.82v-.59h1.576v.798q0 .29-.097.55a1.3 1.3 0 0 1-.293.458 1.4 1.4 0 0 1-.495.313q-.296.111-.697.111a2 2 0 0 1-.753-.132 1.45 1.45 0 0 1-.533-.377 1.6 1.6 0 0 1-.32-.58 2.5 2.5 0 0 1-.105-.745v-.506q0-.543.2-.95.201-.406.582-.633.384-.228.926-.228.357 0 .636.1.281.1.48.275.2.176.314.407Zm-8.64-.706H0v4h.791v-1.343h.803q.43 0 .732-.172.305-.177.463-.475a1.4 1.4 0 0 0 .161-.677q0-.374-.158-.677a1.2 1.2 0 0 0-.46-.477q-.3-.18-.732-.179m.545 1.333a.8.8 0 0 1-.085.381.57.57 0 0 1-.238.24.8.8 0 0 1-.375.082H.788v-1.406h.66q.327 0 .512.182.185.181.185.521m1.964 2.666V13.25h.032l1.761 2.675h.656v-3.999h-.75v2.66h-.032l-1.752-2.66h-.662v4z" />
                </svg>
                <div class="file-spacer"></div>
                <span class="file-name-school-image file-name"><?php echo get_phrase('choose_a_file'); ?>...</span>
              </label>
              <input id="school_image" type="file" class="inputfile" name="school_image" accept=".jpg, .jpeg, .png ">
            </div>

          </div>
        </div>
        <!-- End Input -->
      </div>
      <div class="row justify-content-center">
        <div class="seperator-line mb-10"></div>
      </div>

      <div class="row justify-content-center">
        <p class="text-white h5 pb-5 text-uppercase d-flex justify-content-center form-label">
          <?php echo get_phrase('mentor_information'); ?>
        </p>
      </div>

      <div class="row justify-content-center">

        <!-- Input -->
        <div class="col-sm-4 col-11">
          <div class="js-form-message mb-5">
            <label class="form-label text-white">
              <?php echo get_phrase('full_name'); ?>
              <span class="text-danger">*</span>
            </label>
            <div class="input-group pt-1">
              <span class="input-group-text">
                <svg class="m-1" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                  class="bi bi-person-vcard" viewBox="0 0 16 16">
                  <path
                    d="M5 8a2 2 0 1 0 0-4 2 2 0 0 0 0 4m4-2.5a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 0 1h-4a.5.5 0 0 1-.5-.5M9 8a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 0 1h-4A.5.5 0 0 1 9 8m1 2.5a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 0 1h-3a.5.5 0 0 1-.5-.5" />
                  <path
                    d="M2 2a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2zM1 4a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1H8.96q.04-.245.04-.5C9 10.567 7.21 9 5 9c-2.086 0-3.8 1.398-3.984 3.181A1 1 0 0 1 1 12z" />
                </svg>
              </span>
              <input type="text" placeholder="<?php echo get_phrase('full_name'); ?>"
                class="form-control shadow-none rounded-end text-capitalize" name="name" required
                data-msg="Please enter your full name." data-error-class="u-has-error"
                data-success-class="u-has-success">
            </div>
          </div>
        </div>
        <!-- End Input -->

        <!-- Input -->
        <div class="col-sm-4 col-11">
          <div class="js-form-message mb-5">
            <label class="form-label text-white">
              <?php echo get_phrase('email'); ?>
              <span class="text-danger">*</span>
            </label>
            <div class="input-group pt-1">
              <span class="input-group-text">
                <svg class="m-1" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                  class="bi bi-envelope-at" viewBox="0 0 16 16">
                  <path
                    d="M2 2a2 2 0 0 0-2 2v8.01A2 2 0 0 0 2 14h5.5a.5.5 0 0 0 0-1H2a1 1 0 0 1-.966-.741l5.64-3.471L8 9.583l7-4.2V8.5a.5.5 0 0 0 1 0V4a2 2 0 0 0-2-2zm3.708 6.208L1 11.105V5.383zM1 4.217V4a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v.217l-7 4.2z" />
                  <path
                    d="M14.247 14.269c1.01 0 1.587-.857 1.587-2.025v-.21C15.834 10.43 14.64 9 12.52 9h-.035C10.42 9 9 10.36 9 12.432v.214C9 14.82 10.438 16 12.358 16h.044c.594 0 1.018-.074 1.237-.175v-.73c-.245.11-.673.18-1.18.18h-.044c-1.334 0-2.571-.788-2.571-2.655v-.157c0-1.657 1.058-2.724 2.64-2.724h.04c1.535 0 2.484 1.05 2.484 2.326v.118c0 .975-.324 1.39-.639 1.39-.232 0-.41-.148-.41-.42v-2.19h-.906v.569h-.03c-.084-.298-.368-.63-.954-.63-.778 0-1.259.555-1.259 1.4v.528c0 .892.49 1.434 1.26 1.434.471 0 .896-.227 1.014-.643h.043c.118.42.617.648 1.12.648m-2.453-1.588v-.227c0-.546.227-.791.573-.791.297 0 .572.192.572.708v.367c0 .573-.253.744-.564.744-.354 0-.581-.215-.581-.8Z" />
                </svg>
              </span>
              <input type="email" placeholder="<?php echo get_phrase('email'); ?>"
                class="form-control rounded-end shadow-none" name="email" required
                data-msg="Please enter a valid email address." data-error-class="u-has-error"
                data-success-class="u-has-success">
            </div>
          </div>
        </div>
        <!-- End Input -->
      </div>

      <div class="row justify-content-center">
        <!-- Input -->
        <div class="col-11 col-sm-4">
          <div class="js-form-message mb-5">
            <label class="form-label text-white">
              <?php echo get_phrase('gender'); ?>
              <span class="text-danger">*</span>
            </label>

            <div class="input-group pt-1">
              <span class="input-group-text">
                <svg class="m-1" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                  class="bi bi-gender-ambiguous" viewBox="0 0 16 16">
                  <path fill-rule="evenodd"
                    d="M11.5 1a.5.5 0 0 1 0-1h4a.5.5 0 0 1 .5.5v4a.5.5 0 0 1-1 0V1.707l-3.45 3.45A4 4 0 0 1 8.5 10.97V13H10a.5.5 0 0 1 0 1H8.5v1.5a.5.5 0 0 1-1 0V14H6a.5.5 0 0 1 0-1h1.5v-2.03a4 4 0 1 1 3.471-6.648L14.293 1zm-.997 4.346a3 3 0 1 0-5.006 3.309 3 3 0 0 0 5.006-3.31z" />
                </svg>
              </span>
              <select name="gender" id="gender" class="form-control rounded-end shadow-none" required>
                <option value=""><?php echo get_phrase('select_your_gender'); ?></option>
                <option value="Male"><?php echo get_phrase('male'); ?></option>
                <option value="Female"><?php echo get_phrase('female'); ?></option>
                <option value="Others"><?php echo get_phrase('others'); ?></option>
              </select>
            </div>
          </div>
        </div>
        <!-- End Input -->

        <!-- Input -->
        <div class="col-sm-4 col-11">
          <div class="js-form-message mb-5">
            <label class="form-label text-white">
              <?php echo get_phrase('phone'); ?>
              <span class="text-danger">*</span>

            </label>
            <div class="input-group pt-1">
              <span class="input-group-text ">
                <svg class="m-1" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                  class="bi bi-telephone" viewBox="0 0 16 16">
                  <path
                    d="M3.654 1.328a.678.678 0 0 0-1.015-.063L1.605 2.3c-.483.484-.661 1.169-.45 1.77a17.6 17.6 0 0 0 4.168 6.608 17.6 17.6 0 0 0 6.608 4.168c.601.211 1.286.033 1.77-.45l1.034-1.034a.678.678 0 0 0-.063-1.015l-2.307-1.794a.68.68 0 0 0-.58-.122l-2.19.547a1.75 1.75 0 0 1-1.657-.459L5.482 8.062a1.75 1.75 0 0 1-.46-1.657l.548-2.19a.68.68 0 0 0-.122-.58zM1.884.511a1.745 1.745 0 0 1 2.612.163L6.29 2.98c.329.423.445.974.315 1.494l-.547 2.19a.68.68 0 0 0 .178.643l2.457 2.457a.68.68 0 0 0 .644.178l2.189-.547a1.75 1.75 0 0 1 1.494.315l2.306 1.794c.829.645.905 1.87.163 2.611l-1.034 1.034c-.74.74-1.846 1.065-2.877.702a18.6 18.6 0 0 1-7.01-4.42 18.6 18.6 0 0 1-4.42-7.009c-.362-1.03-.037-2.137.703-2.877z" />
                </svg>
              </span>
              <input type="tel" pattern="\+?\d{1,3}\s?(\d{1,4}\s?){4}" placeholder="+971 22 222 2222"
                class="form-control rounded-end shadow-none" name="phone" data-msg="Please enter a valid phone number."
                data-error-class="u-has-error" data-success-class="u-has-success" required>
            </div>
          </div>
        </div>
        <!-- End Input -->

      </div>



      <div class="row justify-content-center">

        <!-- Input -->
        <div class="col-sm-4 col-11">
          <div class="js-form-message mb-5">
            <label class="form-label text-white">
              <?php echo get_phrase('password'); ?>
              <span class="text-danger">*</span>

            </label>
            <div class="input-group pt-1">
              <span class="input-group-text">
                <svg class="m-1" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                  class="bi bi-key" viewBox="0 0 16 16">
                  <path
                    d="M0 8a4 4 0 0 1 7.465-2H14a.5.5 0 0 1 .354.146l1.5 1.5a.5.5 0 0 1 0 .708l-1.5 1.5a.5.5 0 0 1-.708 0L13 9.207l-.646.647a.5.5 0 0 1-.708 0L11 9.207l-.646.647a.5.5 0 0 1-.708 0L9 9.207l-.646.647A.5.5 0 0 1 8 10h-.535A4 4 0 0 1 0 8m4-3a3 3 0 1 0 2.712 4.285A.5.5 0 0 1 7.163 9h.63l.853-.854a.5.5 0 0 1 .708 0l.646.647.646-.647a.5.5 0 0 1 .708 0l.646.647.646-.647a.5.5 0 0 1 .708 0l.646.647.793-.793-1-1h-6.63a.5.5 0 0 1-.451-.285A3 3 0 0 0 4 5" />
                  <path d="M4 8a1 1 0 1 1-2 0 1 1 0 0 1 2 0" />
                </svg>
              </span>
              <input type="password" id="password" class="form-control rounded-end shadow-none" name="password" required
                data-msg="Please enter a password" data-error-class="u-has-error" data-success-class="u-has-success">
            </div>
          </div>
        </div>
        <!-- End Input -->

        <div class="col-sm-4 col-11">
          <div class="js-form-message mb-5" id="password-repeat-div">
            <label class="form-label text-white">
              <?php echo get_phrase('repeat_password'); ?>
              <span class="text-danger">*</span>

            </label>
            <div class="input-group pt-1">
              <span class="input-group-text">
                <svg class="m-1" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                  class="bi bi-key" viewBox="0 0 16 16">
                  <path
                    d="M0 8a4 4 0 0 1 7.465-2H14a.5.5 0 0 1 .354.146l1.5 1.5a.5.5 0 0 1 0 .708l-1.5 1.5a.5.5 0 0 1-.708 0L13 9.207l-.646.647a.5.5 0 0 1-.708 0L11 9.207l-.646.647a.5.5 0 0 1-.708 0L9 9.207l-.646.647A.5.5 0 0 1 8 10h-.535A4 4 0 0 1 0 8m4-3a3 3 0 1 0 2.712 4.285A.5.5 0 0 1 7.163 9h.63l.853-.854a.5.5 0 0 1 .708 0l.646.647.646-.647a.5.5 0 0 1 .708 0l.646.647.646-.647a.5.5 0 0 1 .708 0l.646.647.793-.793-1-1h-6.63a.5.5 0 0 1-.451-.285A3 3 0 0 0 4 5" />
                  <path d="M4 8a1 1 0 1 1-2 0 1 1 0 0 1 2 0" />
                </svg>
              </span>
              <input type="password" id="repeat-password" class="form-control rounded-end shadow-none"
                name="repeat-password" required data-msg="Please repeat your password" data-error-class="u-has-error"
                data-success-class="u-has-success">
            </div>
            <span id="errorMessage"
              class="text-danger display-none"><?php echo get_phrase('passwords_need_to_match'); ?>.</span>
          </div>
        </div>
        <!-- End Input -->
      </div>


      <?php if (get_common_settings('recaptcha_status')): ?>
        <div class="js-form-message mb-6">
          <div class="form-group">
            <div class="g-recaptcha" data-sitekey="<?php echo get_common_settings('recaptcha_sitekey'); ?>"></div>
          </div>
        </div>
      <?php endif; ?>

      <div class="text-center">
        <button type="submit" id="submitBtnSchool"
          class="btn btn-wide mb-11 text-uppercase submit-button"><?php echo get_phrase('apply'); ?></button>
        <button type="reset" id="resetBtn" style="display: none;"></button>
      </div>

    </form>


    <!-- End School Admission Form -->





  </div>
  </div>
  <!-- End Contact Form Section -->

  <div class="general-container g-0 container-fluid">
    <img class="ct-img rellax " data-rellax-speed="1.5"
      src="<?php echo base_url('assets/frontend/ultimate/img/online admission/oa-img-bot.jpg') ?>" alt="">
    <div class="general-container-ol-bot"></div>

  </div>



  <!-- <script type="text/javascript">
    $(function () {
      $('.realtime-form').ajaxForm({
        beforeSend: function () {
        },
        uploadProgress: function (event, position, total, percentComplete) {

        },
        complete: function (xhr) {
          setTimeout(function () {
            var jsonResponse = JSON.parse(xhr.responseText);
            if (jsonResponse.status == 1) {
              success_notify(jsonResponse.message);
              $('#resetBtn').click();
            } else {
              error_notify(jsonResponse.message);
            }
          }, 500);
        },
        error: function () {
          //You can write here your js error message

        }
      });
    });
  </script> -->

  <style>
    /* Ensure Toastr is fully opaque and matches Bootstrap styling */
.toast {
    margin-top : 50px !important;
    border-radius: 5px;
    font-size: 16px;
    font-weight: 500;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
    opacity: 1 !important; /* Remove transparency */
}

.toast-success {
    background-color: #28a745 !important; /* Bootstrap success green, solid */
}

.toast-error {
    background-color: #dc3545 !important; /* Bootstrap danger red, solid */
}

.toast-close-button {
    color: #fff !important;
    opacity: 0.8 !important; /* Slightly transparent for aesthetics */
}

.toast-close-button:hover {
    color: #f0f0f0 !important;
    opacity: 1 !important; /* Fully opaque on hover */
}
  </style>
  
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Configure Toastr options
    toastr.options = {
        closeButton: true,
        progressBar: true,
        positionClass: 'toast-top-right',
        timeOut: 5000,
        showMethod: 'fadeIn',
        hideMethod: 'fadeOut'
    };

    const schoolForm = document.getElementById('schoolform');
    const submitBtn = document.getElementById('submitBtnSchool');
    const resetBtn = document.getElementById('resetBtn');

    if (schoolForm && submitBtn) {
        schoolForm.addEventListener('submit', function (event) {
            event.preventDefault(); // Prevent default form submission

            // Validate form
            if (!schoolForm.checkValidity()) {
                schoolForm.reportValidity();
                return;
            }

            // Get CSRF token from the hidden input
            const csrfName = document.querySelector(`input[name="${<?php echo json_encode($this->security->get_csrf_token_name()); ?>}"]`).name;
            const csrfHash = document.querySelector(`input[name="${<?php echo json_encode($this->security->get_csrf_token_name()); ?>}"]`).value;

            // Prepare form data
            const formData = new FormData(schoolForm);
            formData.append(csrfName, csrfHash); // Ensure CSRF token is included

            // Perform AJAX submission
            fetch(schoolForm.action, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json()) // Expect JSON response
            .then(data => {
                // Update CSRF token for the next request
                if (data.csrf) {
                    document.querySelector(`input[name="${data.csrf.csrfName}"]`).value = data.csrf.csrfHash;
                }

                if (data.status) {
                    // Success case
                    toastr.success(data.message); // Afficher le toast de succès
                    resetBtn.click(); // Reset form
                    setTimeout(() => {
                        window.location.href = '<?php echo site_url('home'); ?>'; // Rediriger vers la page d'accueil
                    }, 2000); // Attendre 2 secondes pour que le toast soit visible
                } else {
                    // Error case (e.g., duplicate email, school name, or validation error)
                    toastr.error(data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                toastr.error('<?php echo get_phrase('an_error_occurred'); ?>');
            });
        });
    }

    // Password match validation
    const password = document.getElementById('password');
    const repeatPassword = document.getElementById('repeat-password');
    const errorMessage = document.getElementById('errorMessage');

    if (password && repeatPassword && errorMessage) {
        repeatPassword.addEventListener('input', function () {
            if (password.value !== repeatPassword.value) {
                errorMessage.classList.remove('display-none');
                submitBtn.disabled = true;
            } else {
                errorMessage.classList.add('display-none');
                submitBtn.disabled = false;
            }
        });
    }
});
</script>


 