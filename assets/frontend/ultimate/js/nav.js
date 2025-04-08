$(document).ready(function () {

  
  const $navbar = $(".sticky-nav");
  const $userButton = $(".user-section");
  const $userDropdown = $(".user-dropdown");
  const $loginToggle = $(".login-toggle");
  const $loginDropdown = $(".login-dropdown");
  const $registerToggle = $(".register-link");
  const $registerDropdown = $(".register-dropdown");
  const $registerChoiceDropdown = $(".register-choice-dropdown");
  const $forgetToggle = $(".forget-link");
  const $forgetDropdown = $(".forget-dropdown");


  // User section logic
  if ($userButton.length) {
    $userButton.on("click", function () {
      if ($userDropdown.hasClass("display-none")) {
        $userDropdown.removeClass("display-none");
        setTimeout(() => $userDropdown.css('opacity', '1'), 10);
      } else {
        $userDropdown.css('opacity', '0');
        setTimeout(() => $userDropdown.addClass("display-none"), 100);
      }
    });
  }

  // Login toggle logic
  if ($loginToggle.length) {
    $loginToggle.on("click", function () {
      if (!$registerDropdown.hasClass("display-none")) {
        $registerDropdown.css('opacity', '0');
        setTimeout(() => $registerDropdown.addClass("display-none"), 100);
      }
      if ($loginDropdown.hasClass("display-none")) {
        $loginDropdown.removeClass("display-none");
        setTimeout(() => $loginDropdown.css('opacity', '1'), 10);
      } else {
        $loginDropdown.css('opacity', '0');
        setTimeout(() => $loginDropdown.addClass("display-none"), 100);
      }
    });
  }

  // Register toggle logic
  if ($registerToggle.length) {
    $registerToggle.on("click", function () {
      if (!$loginDropdown.hasClass("display-none")) {
        $loginDropdown.css('opacity', '0');
        setTimeout(() => $loginDropdown.addClass("display-none"), 100);
      }
      if (!$forgetDropdown.hasClass("display-none")) {
        $forgetDropdown.css('opacity', '0');
        setTimeout(() => $forgetDropdown.addClass("display-none"), 100);
      }
      if ($registerDropdown.hasClass("display-none")) {
        $registerDropdown.removeClass("display-none");
        setTimeout(() => $registerDropdown.css('opacity', '1'), 10);
      }
      if ($registerChoiceDropdown.hasClass("display-none")) {
        $registerChoiceDropdown.removeClass("display-none");
        setTimeout(() => $registerChoiceDropdown.css('opacity', '1'), 10);
      }
    });
  }

  // Forget toggle logic
  if ($forgetToggle.length) {
    $forgetToggle.on("click", function () {
      if ($forgetDropdown.hasClass("display-none")) {
        const $loginForm = $loginDropdown.find(".login-form");
        const $registerPhrase = $loginDropdown.find(".register-phrase");
        const $forgetPhrase = $loginDropdown.find(".forget-phrase");
        if ($loginForm.length) $loginForm.addClass("display-none");
        if ($registerPhrase.length) $registerPhrase.addClass("display-none");
        if ($forgetPhrase.length) $forgetPhrase.addClass("display-none");
        $forgetDropdown.removeClass("display-none");
        setTimeout(() => $forgetDropdown.css('opacity', '1'), 10);
      }
    });
  }

  // Retour au login depuis forget-dropdown
  const $loginForgetLink = $(".loginforge-link");
  if ($loginForgetLink.length) {
    $loginForgetLink.on("click", function () {
      if (!$forgetDropdown.hasClass("display-none")) {
        $forgetDropdown.css('opacity', '0');
        setTimeout(() => $forgetDropdown.addClass("display-none"), 100);
        const $loginForm = $loginDropdown.find(".login-form");
        const $registerPhrase = $loginDropdown.find(".register-phrase");
        const $forgetPhrase = $loginDropdown.find(".forget-phrase");
        if ($loginForm.length) $loginForm.removeClass("display-none");
        if ($registerPhrase.length) $registerPhrase.removeClass("display-none");
        if ($forgetPhrase.length) $forgetPhrase.removeClass("display-none");
      }
    });
  }

  // Exit button for login-dropdown
  if ($loginDropdown.length) {
    const $loginExitSvg = $(".login-exit-svg");
    $loginExitSvg.on("click", function () {
      $loginDropdown.css('opacity', '0');
      setTimeout(() => $loginDropdown.addClass("display-none"), 100);
      if (!$forgetDropdown.hasClass("display-none")) {
        $forgetDropdown.css('opacity', '0');
        setTimeout(() => $forgetDropdown.addClass("display-none"), 100);
        const $loginForm = $loginDropdown.find(".login-form");
        const $registerPhrase = $loginDropdown.find(".register-phrase");
        const $forgetPhrase = $loginDropdown.find(".forget-phrase");
        if ($loginForm.length) $loginForm.removeClass("display-none");
        if ($registerPhrase.length) $registerPhrase.removeClass("display-none");
        if ($forgetPhrase.length) $forgetPhrase.removeClass("display-none");
      }
    });
  }

  // Step navigation function
  function updateStep(step, formId) {
    const $form = $(`#${formId}`);
    const $formContainer = $form.find('.form-steps-container');
    
    // Hide all steps
    $formContainer.find('.form-step').css('opacity', '0').addClass('display-none');
    
    // Show the selected step
    const $newStep = $formContainer.find(`.form-step[data-step="${step}"]`);
    $newStep.removeClass('display-none');
    setTimeout(() => {
      $newStep.css('opacity', '1');
    }, 10);
    
    // Update active step indicator
    $form.find('.step').removeClass('active');
    $form.find(`.step[data-step="${step}"]`).addClass('active');
    $form.data('currentStep', step);
  }



  // Initialize forms with step tracking
  $('#learner-form').data('currentStep', 1);
  $('#mentor-form').data('currentStep', 1);

  // Learner button logic
  $('.learner-btn').on('click', function () {
    const $registerChoice = $('.register-choice');
    const $learnerFormContainer = $('.learner-form-container');

    // Fade out the register-choice section
    $registerChoice.css('opacity', '0');
    setTimeout(() => {
      $registerChoice.addClass('display-none');
      $registerChoice.css('opacity', ''); // Reset opacity to default
      // Show the learner form container
      $learnerFormContainer.removeClass('display-none');
      setTimeout(() => {
        $learnerFormContainer.css('opacity', '1'); // Ensure it’s fully visible
        updateStep(1, 'learner-form');
      }, 10);
    }, 100);
  });

  // Mentor button logic
  $('.mentor-btn').on('click', function () {
    const $registerChoice = $('.register-choice');
    const $mentorFormContainer = $('.mentor-form-container');

    // Fade out the register-choice section
    $registerChoice.css('opacity', '0');
    setTimeout(() => {
      $registerChoice.addClass('display-none');
      $registerChoice.css('opacity', ''); // Reset opacity to default
      // Show the mentor form container
      $mentorFormContainer.removeClass('display-none');
      setTimeout(() => {
        $mentorFormContainer.css('opacity', '1'); // Ensure it’s fully visible
        updateStep(1, 'mentor-form');
      }, 10);
    }, 100);
  });

  // Next button logic
  $('.next-btn').on('click', function (e) {
    e.preventDefault();
    const $currentStep = $(this).closest('.form-step');
    const $form = $currentStep.closest('form');
    const formId = $form.attr('id');
    const currentStep = $form.data('currentStep') || 1;
    const inputs = $currentStep.find('input[required], select[required], textarea[required]');
    let valid = true;
  
    inputs.each(function () {
      if (!this.checkValidity()) {
        valid = false;
        $(this).addClass('invalid');
      } else {
        $(this).removeClass('invalid');
      }
    });
  
    const maxSteps = formId === 'learner-form' ? 4 : 5; // Updated to 5 for mentor-form
  
    if (valid && currentStep < maxSteps) {
      if (formId === 'mentor-form' && currentStep === 5) { // Updated to check step 5
        const password = $('#password-mentor').val();
        const repeatPassword = $('#repeat-password-mentor').val();
        if (password !== repeatPassword) {
          $('#errorMessageMentor').removeClass('display-none');
          return;
        } else {
          $('#errorMessageMentor').addClass('display-none');
        }
      }
      updateStep(currentStep + 1, formId);
    }
  });

  // Back button logic
  $('.back-btn').on('click', function (e) {
    e.preventDefault();
    const $form = $(this).closest('form');
    const formId = $form.attr('id');
    const currentStep = $form.data('currentStep') || 1;

    if (currentStep > 1) {
      // Go to the previous step
      updateStep(currentStep - 1, formId);
    } else {
      // Step 1: Go back to the register-choice section
      const $formContainer = formId === 'learner-form' ? $('.learner-form-container') : $('.mentor-form-container');
      const $registerChoice = $('.register-choice');

      // Fade out the current form container
      $formContainer.css('opacity', '0');
      setTimeout(() => {
        $formContainer.addClass('display-none');
        $formContainer.css('opacity', ''); // Reset opacity to default
        // Fade in the register-choice section
        $registerChoice.removeClass('display-none');
        setTimeout(() => $registerChoice.css('opacity', '1'), 10); // Small delay to trigger transition
      }, 100); // Match the transition duration from CSS
    }
  });

  // Photo preview for learner
  $('#popup_student_image').on('change', function (event) {
    const file = event.target.files[0];
    if (file) {
      const reader = new FileReader();
      reader.onload = function (e) {
        $('#popup_photo_preview').html(`<img src="${e.target.result}" alt="Photo preview" />`);
      };
      reader.readAsDataURL(file);
    }
  });

  // Photo preview for mentor
  $('#popup_mentor_image').on('change', function (event) {
    const file = event.target.files[0];
    if (file) {
      const reader = new FileReader();
      reader.onload = function (e) {
        $('#popup_mentor-photo-preview').html(`<img src="${e.target.result}" alt="Photo preview" />`);
      };
      reader.readAsDataURL(file);
    }
  });

  const learnerForm = document.getElementById("learner-form");

  if (learnerForm) {
    document.querySelector('#learner-form .register-btn').addEventListener('click', function (event) {
      if (learnerForm.checkValidity()) {
        // Vérifier la correspondance des mots de passe
        const password = document.getElementById('password-student').value;
        const repeatPassword = document.getElementById('repeat-password-student').value;
        if (password !== repeatPassword) {
          document.getElementById('errorMessage').classList.remove('display-none');
          event.preventDefault();
          return;
        } else {
          document.getElementById('errorMessage').classList.add('display-none');
        }

        // Soumission naturelle et rechargement
        setTimeout(function () {
          learnerForm.reset();
          location.reload();
        }, 500);
      } else {
        learnerForm.reportValidity();
        event.preventDefault();
      }
    });
  }

  const mentorForm = document.getElementById("mentor-form");

  if (mentorForm) {
    document.querySelector('#mentor-form .register-btn').addEventListener('click', function (event) {
      if (mentorForm.checkValidity()) {
        // Check password match (specific to mentor form)
        const password = document.getElementById('password-mentor').value;
        const repeatPassword = document.getElementById('repeat-password-mentor').value;
        if (password !== repeatPassword) {
          document.getElementById('errorMessageMentor').classList.remove('display-none');
          event.preventDefault(); // Prevent submission if passwords don’t match
          return;
        } else {
          document.getElementById('errorMessageMentor').classList.add('display-none');
        }

        // Allow default form submission
        setTimeout(function () {
          mentorForm.reset();
          location.reload();
        }, 500);
      } else {
        mentorForm.reportValidity();
        event.preventDefault(); // Prevent submission if form is invalid
      }
    });
  }

  if ($registerDropdown.length) {
    const $registerExitSvg = $(".register-exit-svg");
    $registerExitSvg.on("click", function () {
      $registerDropdown.css('opacity', '0');
      setTimeout(() => $registerDropdown.addClass("display-none"), 100);
    });
  }

  // Navbar toggle
  const navbarInner = document.getElementById("navBar");
  const navbartoggle = document.getElementById("navToggle");
  if (navbartoggle) {
    navbartoggle.addEventListener("click", function (event) {
      event.preventDefault();
      event.stopPropagation();
      navbarInner.classList.toggle("collapse-nav");
      navbarInner.classList.toggle("show-nav");
    });
  }

  // Login link from register
  const $loginLink = $(".login-link");
  if ($loginLink.length) {
    $loginLink.on("click", function () {
      if ($loginDropdown.hasClass("display-none")) {
        $registerDropdown.css('opacity', '0');
        setTimeout(() => $registerDropdown.addClass("display-none"), 100);
        $loginDropdown.removeClass("display-none");
        setTimeout(() => $loginDropdown.css('opacity', '1'), 10);
      }
    });
  }
});

// Code séparé pour la validation du login (inchangé)
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