<div class="login-section nav-link">
  <!-- Login Section -->
  <div class="login-dropdown hidden-section display-none">
    <svg class="login-exit-svg" xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-box-arrow-right" viewBox="0 0 16 16">
      <path fill-rule="evenodd" d="M10 12.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 0 1 0v-2A1.5 1.5 0 0 0 9.5 2h-8A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-2a.5.5 0 0 0-1 0z" />
      <path fill-rule="evenodd" d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708z" />
    </svg>
    <form class="login-form mt-10" id="login-form" action="<?php echo site_url('login/validate_login_frontend'); ?>" method="post">
      <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
      <div class="mb-4 mt-4 login-input">
        <label for="loginEmail" class="login-input-label text-uppercase"><?php echo get_phrase("e-mail") ?> <span class="required"> * </span></label>
        <input type="email" class="form-control shadow-none" id="loginEmail" aria-describedby="emailHelp" name="login_email">
      </div>
      <div class="mb-3 login-input">
        <label for="loginPassword" class="login-input-label text-uppercase"><?php echo get_phrase("password") ?> <span class="required"> * </span></label>
        <input type="password" class="form-control shadow-none" id="loginPassword" name="login_password">
      </div>
      <button type="submit" id="loginSubmit" class="login-button text-uppercase mb-3" style="background-color: rgba(210, 130, 45, 0.7);"><?php echo get_phrase("login") ?></button>
    </form>
    <a class="register-phrase text-uppercase"><?php echo get_phrase("no account yet? ") ?> <span class="ml-1 register-link"><span>(</span> <?php echo get_phrase("register") ?> <span class="ml-1">)</span></span></a>
    <a class="forget-phrase text-uppercase"><?php echo get_phrase("Forgot account?") ?> <span class="ml-1 forget-link"><span>(</span> <?php echo get_phrase("forget password") ?> <span class="ml-1">)</span></span></a>
  </div>

  <!-- Forget Section (independent) -->
  <div class="forget-dropdown hidden-section display-none">
    <a class="text-uppercase"><span class="loginforge-link"><svg class="m-1" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-bar-left" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M12.5 15a.5.5 0 0 1-.5-.5v-13a.5.5 0 0 1 1 0v13a.5.5 0 0 1-.5.5M10 8a.5.5 0 0 1-.5.5H3.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L3.707 7.5H9.5a.5.5 0 0 1 .5.5" /></svg><?php echo get_phrase("login") ?></span></a>
    <form class="forget-form mt-10" id="forget-form" method="post" enctype="multipart/form-data" action="<?php echo site_url('login/send_reset_link'); ?>">
      <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
      <div class="mb-4 login-input">
        <label for="forgetEmail" class="login-input-label text-uppercase"><?php echo get_phrase("Email") ?><span class="required"> * </span></label>
        <input type="text" class="form-control shadow-none information" id="forgotEmail" name="email" required data-msg="<?php echo get_phrase("required") ?>">
      </div>
      <button type="submit" id="registerSubmit" class="register-button text-uppercase"><?php echo get_phrase("sent_password_reset_link") ?></button>
    </form>
  </div>

  <!-- Register Section -->
  <div class="register-dropdown hidden-section display-none">
    <svg class="register-exit-svg" xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-box-arrow-right" viewBox="0 0 16 16">
      <path fill-rule="evenodd" d="M10 12.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 0 1 0v-2A1.5 1.5 0 0 0 9.5 2h-8A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-2a.5.5 0 0 0-1 0z" />
      <path fill-rule="evenodd" d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708z" />
    </svg>
    <div class="register-choice-dropdown hidden-section display-none">
      <div class="register-choice mt-10 ml-9">
      <a class="text-uppercase"><span class="login-link"><svg class="m-1" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-bar-left" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M12.5 15a.5.5 0 0 1-.5-.5v-13a.5.5 0 0 1 1 0v13a.5.5 0 0 1-.5.5M10 8a.5.5 0 0 1-.5.5H3.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L3.707 7.5H9.5a.5.5 0 0 1 .5.5" /></svg><?php echo get_phrase("login") ?></span></a>
        <button class="btn learner-btn mt-3" style="background-color: rgba(210, 130, 45, 0.7); color: white;">I am a learner</button>
        <button class="btn mentor-btn mt-3" style="background-color: rgba(210, 130, 45, 0.7); color: white;">I am a mentor</button>
      </div>
      <div class="learner-form-container hidden-section display-none">
        <form class="learner-form" id="learner-form" method="post" enctype="multipart/form-data" action="<?php echo site_url('admission/online_admission_student/submit/student'); ?>">
          <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
          <div class="form-layout">
            <div class="step-indicators">
              <div class="step" data-step="1">1</div>
              <div class="step" data-step="2">2</div>
              <div class="step" data-step="3">3</div>
              <div class="step" data-step="4">4</div>
            </div>
            <div class="form-steps-container">
              <div class="form-step" data-step="1">
                <div class="mb-4 login-input">
                  <label class="login-input-label text-uppercase">First Name <span class="required"> * </span></label>
                  <input type="text" class="form-control shadow-none" name="first_name" required placeholder="First Name">
                </div>
                <div class="mb-4 login-input">
                  <label class="login-input-label text-uppercase">Last Name <span class="required"> * </span></label>
                  <input type="text" class="form-control shadow-none" name="last_name" required placeholder="Last Name">
                </div>
                <div class="mb-4 login-input">
                  <label class="login-input-label text-uppercase">Email <span class="required"> * </span></label>
                  <input type="email" class="form-control shadow-none" name="student_email" required placeholder="Email">
                </div>
                <div class="form-buttons">
                <button type="button" class="back-btn text-uppercase" style="background-color: rgba(210, 130, 45, 0.7);">Back</button>
                  <button type="button" class="next-btn text-uppercase" style="background-color: rgba(210, 130, 45, 0.7);">Next</button>
                </div>
              </div>
              <div class="form-step display-none" data-step="2">
                <div class="mb-4 login-input">
                  <label class="login-input-label text-uppercase">Phone</label>
                  <input type="text" class="form-control shadow-none" name="phone" placeholder="+971 22 222 2222" pattern="(?=(?:\D*\d){7,15}\D*$)\+?\d+\s?\d{1,3}\s?\d{1,4}\s?\d{1,4}">
                </div>
                <div class="mb-4 login-input">
                  <label class="login-input-label text-uppercase">Gender <span class="required"> * </span></label>
                  <select name="gender" class="form-control shadow-none" required>
                    <option value="">Select your gender</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                    <option value="Others">Others</option>
                  </select>
                </div>
                <div class="mb-4 login-input">
                  <label class="login-input-label text-uppercase">Date of Birth <span class="required"> * </span></label>
                  <input type="date" class="form-control shadow-none" name="date_of_birth" required>
                </div>
                <div class="form-buttons">
                  <button type="button" class="back-btn text-uppercase" style="background-color: rgba(210, 130, 45, 0.7);">Back</button>
                  <button type="button" class="next-btn text-uppercase" style="background-color: rgba(210, 130, 45, 0.7);">Next</button>
                </div>
              </div>
              <div class="form-step display-none" data-step="3">
                <div class="mb-4 login-input">
                  <label class="login-input-label text-uppercase">Address <span class="required"> * </span></label>
                  <input type="text" class="form-control shadow-none" name="address" required placeholder="Address">
                </div>
                <div class="mb-4 login-input">
                  <label class="login-input-label text-uppercase">Password <span class="required"> * </span></label>
                  <input type="password" class="form-control shadow-none" name="password-student" id="password-student" required>
                </div>
                <div class="mb-4 login-input">
                  <label class="login-input-label text-uppercase">Repeat Password <span class="required"> * </span></label>
                  <input type="password" class="form-control shadow-none" name="repeat-password-student" id="repeat-password-student" required>
                  <span id="errorMessage" class="text-danger display-none">Passwords need to match.</span>
                </div>
                <div class="form-buttons">
                  <button type="button" class="back-btn text-uppercase" style="background-color: rgba(210, 130, 45, 0.7);">Back</button>
                  <button type="button" class="next-btn text-uppercase" style="background-color: rgba(210, 130, 45, 0.7);">Next</button>
                </div>
              </div>
              <div class="form-step display-none" data-step="4">
                <div class="mb-4 login-input">
                  <label class="login-input-label text-uppercase" style="padding-bottom: 30px !important; right: 70px !important;">Your Photo</label>
                  <div id="popup_photo_preview" class="photo-preview photo-preview-popup">
                    <img src="<?php echo base_url() . 'uploads/users/placeholder.jpg' ?>" alt="Default Avatar" id="default-avatar">
                  </div>
                  <input type="file" class="form-control shadow-none" name="student_image" id="popup_student_image" accept=".jpg, .jpeg, .png">
                </div>
                <div class="form-buttons">
                  <button type="button" class="back-btn text-uppercase" style="background-color: rgba(210, 130, 45, 0.7);">Back</button>
                  <button type="submit" class="register-btn text-uppercase" style="background-color: rgba(210, 130, 45, 0.7);">Register</button>
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
      <!-- Mentor Form Container -->
      <div class="mentor-form-container hidden-section display-none">
  <form class="mentor-form" id="mentor-form" method="post" enctype="multipart/form-data" action="<?php echo site_url('admission/online_admission/submit/school'); ?>">
    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
    <div class="form-layout">
      <div class="step-indicators">
        <div class="step" data-step="1">1</div>
        <div class="step" data-step="2">2</div>
        <div class="step" data-step="3">3</div>
        <div class="step" data-step="4">4</div>
        <div class="step" data-step="5">5</div>
      </div>
      <div class="form-steps-container">
        <!-- Step 1 -->
        <div class="form-step" data-step="1">
          <div class="mb-4 login-input">
            <label class="login-input-label text-uppercase"><?php echo get_phrase('school_name'); ?> <span class="required"> * </span></label>
            <input type="text" class="form-control shadow-none" name="school_name" required placeholder="<?php echo get_phrase('school_name'); ?>">
          </div>
          <div class="mb-4 login-input">
            <label class="login-input-label text-uppercase"><?php echo get_phrase('category'); ?> <span class="required"> * </span></label>
            <select name="category" class="form-control shadow-none" required>
              <option value=""><?php echo get_phrase('select_a_category'); ?></option>
              <?php $categories = $this->db->get_where('categories', array())->result_array(); ?>
              <?php foreach ($categories as $categorie): ?>
                <option value="<?php echo $categorie['name']; ?>"><?php echo $categorie['name']; ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="mb-4 login-input">
            <label class="login-input-label text-uppercase"><?php echo get_phrase('school_phone'); ?> <span class="required"> * </span></label>
            <input type="tel" class="form-control shadow-none" name="school_phone" required placeholder="+971 22 222 2222" pattern="(?=(?:\D*\d){7,15}\D*$)\+?\d+\s?\d{1,3}\s?\d{1,4}\s?\d{1,4}">
          </div>
          <div class="mb-4 login-input">
            <label class="login-input-label text-uppercase"><?php echo get_phrase('address'); ?> <span class="required"> * </span></label>
            <input type="text" class="form-control shadow-none" name="school_adress" required placeholder="<?php echo get_phrase('address'); ?>">
          </div>
          <div class="form-buttons">
            <button type="button" class="back-btn text-uppercase" style="background-color: rgba(210, 130, 45, 0.7);">Back</button>
            <button type="button" class="next-btn text-uppercase" style="background-color: rgba(210, 130, 45, 0.7);">Next</button>
          </div>
        </div>

        <!-- Step 2 -->
        <div class="form-step display-none" data-step="2">
          <div class="mb-4 login-input">
            <label class="login-input-label text-uppercase"><?php echo get_phrase('description'); ?> <span class="required"> * </span></label>
            <textarea class="form-control shadow-none" rows="3" name="school_description" required></textarea>
          </div>
          <div class="mb-4 login-input">
  <label class="login-input-label text-uppercase"><?php echo get_phrase('access_mode'); ?> <span class="required"> * </span></label>
  <div class="visibility-selector pt-3">
    <div class="vis-button">
      <input id="popup_private" type="radio" name="visibility" value="0" checked>
      <label class="private-button form-label" for="popup_private"><?php echo get_phrase('private'); ?></label>
    </div>
    <div class="vis-button">
      <input id="popup_public" type="radio" name="visibility" value="1">
      <label class="public-button form-label" for="popup_public"><?php echo get_phrase('public'); ?></label>
    </div>
  </div>
</div>
          <div class="form-buttons" style="margin-top: 57px;">
            <button type="button" class="back-btn text-uppercase" style="background-color: rgba(210, 130, 45, 0.7);">Back</button>
            <button type="button" class="next-btn text-uppercase" style="background-color: rgba(210, 130, 45, 0.7);">Next</button>
          </div>
        </div>

        <!-- Step 3 (School Image Upload Only) -->
        <div class="form-step display-none" data-step="3">
          <div class="mb-4 login-input">
            <label class="login-input-label text-uppercase" style="padding-bottom: 30px !important; right: 70px !important;"><?php echo get_phrase('school_image'); ?></label>
            <div id="popup_mentor-photo-preview" class="photo-preview photo-preview-popup">
              <img src="<?php echo base_url() . 'uploads/users/placeholder.jpg' ?>" alt="Default Avatar" id="default-avatar-mentor">
            </div>
            <input type="file" class="form-control shadow-none" name="school_image" id="popup_mentor_image" accept=".jpg, .jpeg, .png">
          </div>
          <div class="form-buttons">
            <button type="button" class="back-btn text-uppercase" style="background-color: rgba(210, 130, 45, 0.7);">Back</button>
            <button type="button" class="next-btn text-uppercase" style="background-color: rgba(210, 130, 45, 0.7);">Next</button>
          </div>
        </div>

        <!-- Step 4 (Full Name, Email, Gender) -->
        <div class="form-step display-none" data-step="4">
          <div class="mb-4 login-input">
            <label class="login-input-label text-uppercase"><?php echo get_phrase('full_name'); ?> <span class="required"> * </span></label>
            <input type="text" class="form-control shadow-none" name="name" required placeholder="<?php echo get_phrase('full_name'); ?>">
          </div>
          <div class="mb-4 login-input">
            <label class="login-input-label text-uppercase"><?php echo get_phrase('email'); ?> <span class="required"> * </span></label>
            <input type="email" class="form-control shadow-none" name="email" required placeholder="<?php echo get_phrase('email'); ?>">
          </div>
          <div class="mb-4 login-input">
            <label class="login-input-label text-uppercase"><?php echo get_phrase('gender'); ?> <span class="required"> * </span></label>
            <select name="gender" class="form-control shadow-none" required>
              <option value=""><?php echo get_phrase('select_your_gender'); ?></option>
              <option value="Male"><?php echo get_phrase('male'); ?></option>
              <option value="Female"><?php echo get_phrase('female'); ?></option>
              <option value="Others"><?php echo get_phrase('others'); ?></option>
            </select>
          </div>
          <div class="form-buttons">
            <button type="button" class="back-btn text-uppercase" style="background-color: rgba(210, 130, 45, 0.7);">Back</button>
            <button type="button" class="next-btn text-uppercase" style="background-color: rgba(210, 130, 45, 0.7);">Next</button>
          </div>
        </div>

        <!-- Step 5 (Phone, Password, Repeat Password) -->
        <div class="form-step display-none" data-step="5">
          <div class="mb-4 login-input">
            <label class="login-input-label text-uppercase"><?php echo get_phrase('phone'); ?> <span class="required"> * </span></label>
            <input type="tel" class="form-control shadow-none" name="phone" required placeholder="+971 22 222 2222" pattern="\+?\d{1,3}\s?(\d{1,4}\s?){4}">
          </div>
          <div class="mb-4 login-input">
            <label class="login-input-label text-uppercase"><?php echo get_phrase('password'); ?> <span class="required"> * </span></label>
            <input type="password" class="form-control shadow-none" name="password" id="password-mentor" required>
          </div>
          <div class="mb-4 login-input">
            <label class="login-input-label text-uppercase"><?php echo get_phrase('repeat_password'); ?> <span class="required"> * </span></label>
            <input type="password" class="form-control shadow-none" name="repeat-password" id="repeat-password-mentor" required>
            <span id="errorMessageMentor" class="text-danger display-none"><?php echo get_phrase('passwords_need_to_match'); ?></span>
          </div>
          <div class="form-buttons">
            <button type="button" class="back-btn text-uppercase" style="background-color: rgba(210, 130, 45, 0.7);">Back</button>
            <button type="submit" class="register-btn text-uppercase" style="background-color: rgba(210, 130, 45, 0.7);">Register</button>
          </div>
        </div>
      </div>
    </div>
  </form>
</div>
    </div>
  </div>
</div>
<style>
.visibility-selector {
  list-style-type: none;
  position: relative;
  display: flex; /* Ensure buttons are side by side */
  width: 100%;
}

.visibility-selector .vis-button {
  width: 50%; /* Each button takes half the width */
  position: relative;
  min-height: 36px; /* Consistent height */
}

.visibility-selector label {
  display: block;
  position: relative;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  min-height: 36px;
  cursor: pointer;
  text-align: center;
  align-content: center;
  background: #2e2e2e; /* Default background */
  color: #fff; /* Text color */
  transition: all 0.5s ease-in-out;
  border-radius: 0; /* Reset any default rounding */
}

.visibility-selector input[type="radio"] {
  opacity: 0; /* Completely hide the radio input */
  position: absolute; /* Remove it from the flow */
  width: 0; /* Ensure it doesn't take up space */
  height: 0;
}

.visibility-label {
  position: relative;
  top: -22px;
}

.public-button {
  border-top-right-radius: 5px;
  border-bottom-right-radius: 5px;
  box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 15px;
}

.private-button {
  border-top-left-radius: 5px;
  border-bottom-left-radius: 5px;
  box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 15px;
}


.visibility-selector input[type="radio"] + label {
  background: #2e2e2e;
  transition: all 0.5s ease-in-out;
  box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 15px;
}


.visibility-selector input[type="radio"]:checked + label {
  background: rgba(210, 130, 45, 0.937); /* Active color */
  box-shadow: none; /* Remove shadow when active */
  transition: all 0.5s ease-in-out;
}

  /* Ensure the parent container has a proper background */
  .login-section {
    position: relative; /* Ensure dropdowns are positioned correctly */
  }

  /* Style for hidden sections */
  .hidden-section {
    opacity: 0;
    transition: opacity 0.1s ease-in-out;
    position: absolute;
  }

  .hidden-section:not(.display-none) {
  opacity: 1;
}

  /* When hidden */
  .display-none {
    display: none;
  }

  /* When shown */
  .show {
    opacity: 1;
  }

  /* Specific styling for register-dropdown */
  .register-dropdown {
    width: 300px; /* Adjust as needed */
    padding: 10px; /* Optional: Add padding for spacing */
    z-index: 1000; /* Ensure it’s on top */
  }

  /* Specific styling for register-choice-dropdown */
  .register-choice-dropdown {
    background-color: transparent; /* Ensure no black background */
    width: 100%; /* Match parent width or set explicitly */
    padding: 10px; /* Optional: Add padding */
  }

  /* Style the buttons to ensure they look good */
  .learner-btn,
  .mentor-btn {
    background-color: rgba(210, 130, 45, 0.7); /* Your original color */
    color: white;
    border: none; /* Remove any default borders */
    padding: 10px 20px; /* Adjust padding */
    margin: 5px 0; /* Space between buttons */
    cursor: pointer !important;
  }

  .learner-form-container,
  .mentor-form-container {
    width: 90%;
    margin-top: 10%;
    padding: 20px;
    
  }

  .form-buttons-popup{
      display: flex;
      justify-content: space-between;
      margin-top: 68px;
  }

  .form-layout {
    display: flex;
    flex-direction: row; /* Indicateurs à gauche, champs à droite */
    align-items: flex-start; /* Alignement en haut */
    gap: 20px; /* Espacement entre les indicateurs et les champs */
  }

  /* Indicateurs d'étapes en colonne à gauche */
  .step-indicators {
    display: flex;
    flex-direction: column; /* Étapes empilées verticalement */
    align-items: center; /* Centrage horizontal des cercles */
    gap: 20px; /* Espacement vertical entre les étapes */
  }

  /* Style des étapes */
  .step {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    background-color: #ccc;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: default;
  }

  .step.active {
    background-color: rgba(210, 130, 45, 0.7);
  }

  /* Conteneur des étapes du formulaire */
  .form-steps-container {
    flex: 1; /* Prend tout l'espace restant à droite */
  }

  /* Transition pour les étapes */
  .form-step {
    opacity: 0;
    transition: opacity 0.3s ease-in-out;
  }

  .form-step:not(.display-none) {
  opacity: 1;
}

  /* Boutons */
  .form-buttons {
    display: flex;
    justify-content: space-between;
    margin-top: 20px;
  }

  .back-btn, .next-btn, .register-btn {
    padding: 5px 10px;
    border: none;
    border-radius: 5px;
    color: white;
    cursor: pointer;
    font-size: 0.8em;
  }

  .photo-preview-popup {
    display: flex;
    justify-content: center;
    align-items: center;
    width: 150px;
    height: 150px;
    border-radius: 50%;
    background-color: #f0f0f0;
    overflow: hidden;
    margin: 0 auto;
    margin-bottom: 8%;
  }

  .photo-preview img {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }

</style>

<script type="text/javascript">
  var inputs = document.querySelectorAll('.information');
  var passwordInputs = document.querySelectorAll('.password');
  var selects = document.getElementsByTagName('select');

  for (var i = 0; i < inputs.length; i++) {
    inputs[i].addEventListener('input', function () {
      if (this.value != "")
        this.classList.remove("invalid");
    });
  }

  for (var i = 0; i < passwordInputs.length; i++) {
    passwordInputs[i].addEventListener('input', function () {
      if (this.value != "" && checkPassword()) {
        this.classList.remove("invalid");
      }
    });
  }

  for (var i = 0; i < selects.length; i++) {
    selects[i].addEventListener('change', function () {
      if (this.value != "") {
        this.classList.remove("invalid");
      } else {
        this.classList.add("invalid");
      }
    });
  }

  function check_email(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
  }

  document.getElementById("loginSubmit").addEventListener("click", function (event) {
    if (!loginSubmit()) {
      event.preventDefault();
    }
  });

  function loginSubmit() {
    var email = document.getElementById("loginEmail").value;
    var password = document.getElementById("loginPassword").value;

    emailExists(email).then((status) => {
      if (status) {
        error_notify('<?php echo get_phrase("E-mail_address_not_in_use") ?>');
      } else {
        validateCredentials(email, password).then((status) => {
          if (status) {
            document.getElementById("login-form").submit();
          } else {
            error_notify('<?php echo get_phrase("credentials_incorrect") ?>');
          }
        });
      }
    });
  }

  function validateCredentials(email, password) {
    return new Promise((resolve, reject) => {
      var emailInput = document.getElementById("loginEmail");
      var passwordInput = document.getElementById("loginPassword");
      var csrfName = $('input[name="<?= $this->security->get_csrf_token_name(); ?>"]').attr('name');
      var csrfHash = $('input[name="<?= $this->security->get_csrf_token_name(); ?>"]').val();

      $.ajax({
        type: "POST",
        url: "<?php echo site_url('login/validate_credentials'); ?>",
        data: {email: email, password: password, [csrfName]: csrfHash},
        dataType: 'json',
        success: function(response){
          if (response.status == true) {
            resolve(true);
          } else {
            resolve(false);
          }
          var newCsrfName = response.csrf.csrfName;
          var newCsrfHash = response.csrf.csrfHash;
          $('input[name="' + newCsrfName + '"]').val(newCsrfHash);
        },
        error: function(error) {
          console.error("Error:", error);
          resolve(false);
        }
      });
    });
  }
</script>