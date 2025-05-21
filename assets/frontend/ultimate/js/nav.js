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

    // Configurer Toastr
    toastr.options = {
        closeButton: true,
        progressBar: true,
        positionClass: 'toast-top-right',
        timeOut: 1500,
        showMethod: 'fadeIn',
        hideMethod: 'fadeOut',
        onHidden: function() {} // Sera remplacé dynamiquement pour certaines actions
    };

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
            $loginDropdown.removeClass("has-error"); // Reset error class
            const errorDiv = document.getElementById("loginError");
            if (errorDiv) {
                errorDiv.textContent = ""; // Clear error message
                errorDiv.classList.add("display-none"); // Hide error message
            }
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

        // Validate required fields
        inputs.each(function () {
            if (!this.checkValidity()) {
                valid = false;
                $(this).addClass('invalid');
            } else {
                $(this).removeClass('invalid');
            }
        });

        const maxSteps = formId === 'learner-form' ? 4 : 5;

        // If form inputs are valid, proceed with additional checks
        if (valid && currentStep < maxSteps) {
            // Learner form: Check email in Step 1
            if (formId === 'learner-form' && currentStep === 1) {
                const email = $form.find('input[name="student_email"]').val();
                checkEmailExists(email).then((response) => {
                    const $emailInput = $form.find('input[name="student_email"]');
                    const $errorSpan = $emailInput.next('.email-error');
                    
                    // Remove any existing error message
                    if ($errorSpan.length) $errorSpan.remove();
                    
                    if (response.exists) {
                        // Display error message below email field
                        $emailInput.after('<span class="email-error text-danger">Email is already in use.</span>');
                        $emailInput.addClass('invalid');
                    } else {
                        // Proceed to next step
                        updateStep(currentStep + 1, formId);
                    }
                    
                    // Adjust dropdown height
                    adjustLoginDropdownHeight($form);
                });
            }
            // Mentor form: Check school name in Step 1
            else if (formId === 'mentor-form' && currentStep === 1) {
                const schoolName = $form.find('input[name="school_name"]').val();
                checkSchoolNameExists(schoolName).then((response) => {
                    const $schoolInput = $form.find('input[name="school_name"]');
                    const $errorSpan = $schoolInput.next('.school-error');
                    
                    // Remove any existing error message
                    if ($errorSpan.length) $errorSpan.remove();
                    
                    if (response.exists) {
                        // Display error message below school name field
                        $schoolInput.after('<span class="school-error text-danger">School name is already in use.</span>');
                        $schoolInput.addClass('invalid');
                    } else {
                        // Proceed to next step
                        updateStep(currentStep + 1, formId);
                    }
                    
                    // Adjust dropdown height
                    adjustLoginDropdownHeight($form);
                });
            }
            // Mentor form: Check email in Step 4
            else if (formId === 'mentor-form' && currentStep === 4) {
                const email = $form.find('input[name="email"]').val();
                checkEmailExists(email).then((response) => {
                    const $emailInput = $form.find('input[name="email"]');
                    const $errorSpan = $emailInput.next('.email-error');
                    
                    // Remove any existing error message
                    if ($errorSpan.length) $errorSpan.remove();
                    
                    if (response.exists) {
                        // Display error message below email field
                        $emailInput.after('<span class="email-error text-danger">Email is already in use.</span>');
                        $emailInput.addClass('invalid');
                    } else {
                        // Proceed to next step
                        updateStep(currentStep + 1, formId);
                    }
                    
                    // Adjust dropdown height
                    adjustLoginDropdownHeight($form);
                });
            }
            // Mentor form: Check password match in Step 5
            else if (formId === 'mentor-form' && currentStep === 5) {
                const password = $('#password-mentor').val();
                const repeatPassword = $('#repeat-password-mentor').val();
                if (password !== repeatPassword) {
                    $('#errorMessageMentor').removeClass('display-none');
                    // Adjust dropdown height (in case other errors are present)
                    adjustLoginDropdownHeight($form);
                    return;
                } else {
                    $('#errorMessageMentor').addClass('display-none');
                    updateStep(currentStep + 1, formId);
                    // Adjust dropdown height
                    adjustLoginDropdownHeight($form);
                }
            }
            // Proceed to next step for other cases
            else {
                updateStep(currentStep + 1, formId);
                // Adjust dropdown height (in case errors were removed)
                adjustLoginDropdownHeight($form);
            }
        }
    });

    // Back button logic
    $('.back-btn').on('click', function (e) {
        e.preventDefault();
        const $form = $(this).closest('form');
        const formId = $form.attr('id');
        const currentStep = $form.data('currentStep') || 1;

        if (currentStep > 1) {
            updateStep(currentStep - 1, formId);
        } else {
            const $formContainer = formId === 'learner-form' ? $('.learner-form-container') : $('.mentor-form-container');
            const $registerChoice = $('.register-choice');

            $formContainer.css('opacity', '0');
            setTimeout(() => {
                $formContainer.addClass('display-none');
                $formContainer.css('opacity', '');
                $registerChoice.removeClass('display-none');
                setTimeout(() => $registerChoice.css('opacity', '1'), 10);
            }, 100);
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

    // Gestion du formulaire learner
    const learnerForm = document.getElementById("learner-form");
    if (learnerForm) {
        const registerBtn = document.querySelector('#learner-form .register-btn');
        if (registerBtn) {
            registerBtn.addEventListener('click', function (event) {
                event.preventDefault();
                if (learnerForm.checkValidity()) {
                    const password = document.getElementById('password-student')?.value;
                    const repeatPassword = document.getElementById('repeat-password-student')?.value;
                    if (password !== repeatPassword) {
                        const errorMessage = document.getElementById('errorMessage');
                        if (errorMessage) errorMessage.classList.remove('display-none');
                        return;
                    } else {
                        const errorMessage = document.getElementById('errorMessage');
                        if (errorMessage) errorMessage.classList.add('display-none');
                    }

                    const email = document.querySelector('#learner-form input[name="student_email"]')?.value;
                    if (!email) {
                        toastr.error('Adresse e-mail manquante.', 'Erreur', { timeOut: 3000 });
                        return;
                    }

                    // Show loading spinner and disable button
                    const $spinner = $('.register-dropdown .loading-spinner');
                    if ($spinner.length) $spinner.removeClass('display-none');
                    registerBtn.disabled = true;

                    // Fetch fresh CSRF token
                    $.ajax({
                        type: "GET",
                        url: window.baseUrl + 'login/get_csrf_token',
                        dataType: 'json',
                        success: function (response) {
                            if (!response.csrfName || !response.csrfHash) {
                                if ($spinner.length) $spinner.addClass('display-none');
                                registerBtn.disabled = false;
                                registerBtn.innerHTML = 'S\'inscrire';
                                toastr.error('Jeton CSRF invalide.', 'Erreur', { timeOut: 3000 });
                                return;
                            }

                            // Update CSRF token in form
                            $('input[name="' + response.csrfName + '"]').val(response.csrfHash);

                            // Submit form via AJAX
                            $.ajax({
                                type: "POST",
                                url: learnerForm.action,
                                data: new FormData(learnerForm),
                                contentType: false,
                                processData: false,
                                dataType: 'json',
                                success: function (response) {
                                    if (response.status) {
                                        // Show toast immediately with reduced duration
                                        toastr.success(response.message || 'Votre inscription a été effectuée avec succès.', 'Inscription réussie !', { timeOut: 1500 });

                                        // Perform auto-login immediately
                                        $.ajax({
                                            type: "POST",
                                            url: window.baseUrl + 'login/validate_login_frontend',
                                            data: {
                                                login_email: email,
                                                login_password: password,
                                                [response.csrf.csrfName]: response.csrf.csrfHash
                                            },
                                            dataType: 'json',
                                            success: function (loginResponse) {
                                                // Update CSRF token
                                                const newCsrfName = loginResponse.csrf?.csrfName;
                                                const newCsrfHash = loginResponse.csrf?.csrfHash;
                                                if (newCsrfName && newCsrfHash) {
                                                    $('input[name="' + newCsrfName + '"]').val(newCsrfHash);
                                                }

                                                // Hide spinner and re-enable button
                                                if ($spinner.length) $spinner.addClass('display-none');
                                                registerBtn.disabled = false;
                                                registerBtn.innerHTML = 'S\'inscrire';

                                                if (loginResponse.status) {
                                                    // Reset form and hide dropdown
                                                    learnerForm.reset();
                                                    $('.register-dropdown').css('opacity', '0');
                                                    setTimeout(() => {
                                                        $('.register-dropdown').addClass("display-none");
                                                        // Redirect to dashboard with delay
                                                        setTimeout(() => {
                                                            console.log("Learner redirect triggered");
                                                            window.location.href = window.baseUrl + '/';
                                                        }, 1500); // Delay redirect by 1.5s
                                                    }, 100); // Dropdown animation
                                                } else {
                                                    toastr.error(loginResponse.message || 'Échec de la connexion automatique.', 'Erreur', { timeOut: 3000 });
                                                    window.location.href = window.baseUrl + 'login';
                                                }
                                            },
                                            error: function (error) {
                                                console.error("Error during auto-login:", error);
                                                if ($spinner.length) $spinner.addClass('display-none');
                                                registerBtn.disabled = false;
                                                registerBtn.innerHTML = 'S\'inscrire';
                                                toastr.error('Une erreur serveur s\'est produite lors de la connexion automatique.', 'Erreur', { timeOut: 3000 });
                                                window.location.href = window.baseUrl + 'login';
                                            }
                                        });
                                    } else {
                                        if ($spinner.length) $spinner.addClass('display-none');
                                        registerBtn.disabled = false;
                                        registerBtn.innerHTML = 'S\'inscrire';
                                        toastr.error(response.message || 'Une erreur s\'est produite lors de l\'inscription.', 'Erreur', { timeOut: 3000 });
                                    }
                                    // Update CSRF token
                                    const newCsrfName = response.csrf?.csrfName;
                                    const newCsrfHash = response.csrf?.csrfHash;
                                    if (newCsrfName && newCsrfHash) {
                                        $('input[name="' + newCsrfName + '"]').val(newCsrfHash);
                                    }
                                },
                                error: function (error) {
                                    console.error("Error:", error);
                                    if ($spinner.length) $spinner.addClass('display-none');
                                    registerBtn.disabled = false;
                                    registerBtn.innerHTML = 'S\'inscrire';
                                    toastr.error('Une erreur serveur s\'est produite. Vérifiez reCAPTCHA ou contactez l\'administrateur.', 'Erreur', { timeOut: 3000 });
                                }
                            });
                        },
                        error: function (error) {
                            console.error("Error fetching CSRF token:", error);
                            if ($spinner.length) $spinner.addClass('display-none');
                            registerBtn.disabled = false;
                            registerBtn.innerHTML = 'S\'inscrire';
                            toastr.error('Impossible de récupérer le jeton CSRF.', 'Erreur', { timeOut: 3000 });
                        }
                    });
                } else {
                    learnerForm.reportValidity();
                }
            });
        }
    }

    // Gestion du formulaire mentor
const mentorForm = document.getElementById("mentor-form");
if (mentorForm) {
    const registerBtn = document.querySelector('#mentor-form .register-btn');
    if (registerBtn) {
        registerBtn.addEventListener('click', function (event) {
            event.preventDefault();
            if (mentorForm.checkValidity()) {
                const password = document.getElementById('password-mentor')?.value;
                const repeatPassword = document.getElementById('repeat-password-mentor')?.value;
                if (password !== repeatPassword) {
                    const errorMessage = document.getElementById('errorMessageMentor');
                    if (errorMessage) errorMessage.classList.remove('display-none');
                    return;
                } else {
                    const errorMessage = document.getElementById('errorMessageMentor');
                    if (errorMessage) errorMessage.classList.add('display-none');
                }

                const email = document.querySelector('#mentor-form input[name="email"]')?.value;
                if (!email) {
                    toastr.error('Adresse e-mail manquante.', 'Erreur', { timeOut: 3000 });
                    return;
                }

                // Show loading spinner and disable button
                const $spinner = $('.register-dropdown .loading-spinner');
                if ($spinner.length) $spinner.removeClass('display-none');
                registerBtn.disabled = true;

                // Fetch fresh CSRF token
                $.ajax({
                    type: "GET",
                    url: window.baseUrl + 'login/get_csrf_token',
                    dataType: 'json',
                    success: function (response) {
                        if (!response.csrfName || !response.csrfHash) {
                            if ($spinner.length) $spinner.addClass('display-none');
                            registerBtn.disabled = false;
                            registerBtn.innerHTML = 'S\'inscrire';
                            toastr.error('Jeton CSRF invalide.', 'Erreur', { timeOut: 3000 });
                            return;
                        }

                        // Update CSRF token in form
                        $('input[name="' + response.csrfName + '"]').val(response.csrfHash);

                        // Submit form via AJAX
                        $.ajax({
                            type: "POST",
                            url: mentorForm.action,
                            data: new FormData(mentorForm),
                            contentType: false,
                            processData: false,
                            dataType: 'json',
                            success: function (response) {
                                // Hide spinner and re-enable button
                                if ($spinner.length) $spinner.addClass('display-none');
                                registerBtn.disabled = false;
                                registerBtn.innerHTML = 'S\'inscrire';

                                if (response.status) {
                                    // Show success message
                                    toastr.success(response.message || 'Votre inscription a été effectuée avec succès.', 'Inscription réussie !', { timeOut: 1500 });

                                    // Reset form and hide dropdown
                                    mentorForm.reset();
                                    $('.register-dropdown').css('opacity', '0');
                                    setTimeout(() => {
                                        $('.register-dropdown').addClass("display-none");
                                        // Redirect to login page
                                        setTimeout(() => {
                                            console.log("Mentor redirect to login triggered");
                                            window.location.href = window.baseUrl + '';
                                        }, 1500); // Delay redirect by 1.5s
                                    }, 100); // Dropdown animation
                                } else {
                                    toastr.error(response.message || 'Une erreur s\'est produite lors de l\'inscription.', 'Erreur', { timeOut: 3000 });
                                }

                                // Update CSRF token
                                const newCsrfName = response.csrf?.csrfName;
                                const newCsrfHash = response.csrf?.csrfHash;
                                if (newCsrfName && newCsrfHash) {
                                    $('input[name="' + newCsrfName + '"]').val(newCsrfHash);
                                }
                            },
                            error: function (error) {
                                console.error("Error:", error);
                                if ($spinner.length) $spinner.addClass('display-none');
                                registerBtn.disabled = false;
                                registerBtn.innerHTML = 'S\'inscrire';
                                toastr.error('Une erreur serveur s\'est produite. Vérifiez reCAPTCHA ou contactez l\'administrateur.', 'Erreur', { timeOut: 3000 });
                            }
                        });
                    },
                    error: function (error) {
                        console.error("Error fetching CSRF token:", error);
                        if ($spinner.length) $spinner.addClass('display-none');
                        registerBtn.disabled = false;
                        registerBtn.innerHTML = 'S\'inscrire';
                        toastr.error('Impossible de récupérer le jeton CSRF.', 'Erreur', { timeOut: 3000 });
                    }
                });
            } else {
                mentorForm.reportValidity();
            }
        });
    }
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

    // Input validation
    const inputs = document.querySelectorAll('.information');
    const passwordInputs = document.querySelectorAll('.password');
    const selects = document.getElementsByTagName('select');

    inputs.forEach(input => {
        input.addEventListener('input', function () {
            if (this.value !== "") {
                this.classList.remove("invalid");
            }
        });
    });

    passwordInputs.forEach(input => {
        input.addEventListener('input', function () {
            if (this.value !== "" && checkPassword()) {
                this.classList.remove("invalid");
            }
        });
    });

    Array.from(selects).forEach(select => {
        select.addEventListener('change', function () {
            if (this.value !== "") {
                this.classList.remove("invalid");
            } else {
                this.classList.add("invalid");
            }
        });
    });

    // Login submit button logic
    const loginSubmitButton = document.getElementById("loginSubmit");
    if (loginSubmitButton) {
        loginSubmitButton.addEventListener("click", function (event) {
            if (!loginSubmit()) {
                event.preventDefault();
            }
        });
    }
});

function check_email(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

function checkPassword() {
    // Placeholder for password validation logic, if needed
    return true;
}

function loginSubmit() {
    const emailInput = document.getElementById("loginEmail");
    const passwordInput = document.getElementById("loginPassword");
    const errorDiv = document.getElementById("loginError");
    const loginDropdown = document.querySelector(".login-dropdown");

    if (!emailInput || !passwordInput || !errorDiv || !loginDropdown) {
        console.warn("Login form elements not found");
        return false;
    }

    const email = emailInput.value;
    const password = passwordInput.value;

    errorDiv.classList.add("display-none");
    errorDiv.textContent = "";
    loginDropdown.classList.remove("has-error");

    if (!check_email(email)) {
        errorDiv.textContent = 'Invalid email format';
        errorDiv.classList.remove("display-none");
        loginDropdown.classList.add("has-error");
        return false;
    }

    validateCredentials(email, password).then((response) => {
        if (response.status) {
            document.getElementById("login-form").submit();
        } else {
            errorDiv.textContent = response.message || 'Invalid email or password';
            errorDiv.classList.remove("display-none");
            loginDropdown.classList.add("has-error");
        }
    });

    return false;
}

function validateCredentials(email, password) {
    return new Promise((resolve) => {
        const csrfName = $('input[name="' + csrfTokenName + '"]').attr('name');
        const csrfHash = $('input[name="' + csrfTokenName + '"]').val();

        $.ajax({
            type: "POST",
            url: loginValidateUrl,
            data: { email: email, password: password, [csrfName]: csrfHash },
            dataType: 'json',
            success: function (response) {
                const newCsrfName = response.csrf.csrfName;
                const newCsrfHash = response.csrf.csrfHash;
                $('input[name="' + newCsrfName + '"]').val(newCsrfHash);

                resolve({
                    status: response.status,
                    message: response.message || 'Invalid email or password'
                });
            },
            error: function (error) {
                console.error("Error:", error);
                resolve({ status: false, message: 'Server error occurred' });
            }
        });
    });
}

// Function to adjust login-dropdown height based on error messages
function adjustLoginDropdownHeight($form) {
    const $loginDropdown = $form.closest('.login-dropdown');
    const $errorMessage = $form.find('.email-error, .school-error');
    
    if ($errorMessage.length > 0) {
        // Error message is present, increase height
        $loginDropdown.css('height', '310px');
    } else {
        // No error message, revert to default height
        $loginDropdown.css('height', '270px');
    }
}

function checkEmailExists(email) {
    return new Promise((resolve) => {
        const csrfName = $('input[name="' + csrfTokenName + '"]').attr('name');
        const csrfHash = $('input[name="' + csrfTokenName + '"]').val();

        $.ajax({
            type: "POST",
            url: checkEmailExistsUrl,
            data: { email: email, [csrfName]: csrfHash },
            dataType: 'json',
            success: function (response) {
                const newCsrfName = response.csrf.csrfName;
                const newCsrfHash = response.csrf.csrfHash;
                $('input[name="' + newCsrfName + '"]').val(newCsrfHash);
                resolve({ exists: response.exists });
            },
            error: function (error) {
                console.error("Error:", error);
                resolve({ exists: false }); // Assume email doesn't exist on error
            }
        });
    });
}

function checkSchoolNameExists(schoolName) {
    return new Promise((resolve) => {
        const csrfName = $('input[name="' + csrfTokenName + '"]').attr('name');
        const csrfHash = $('input[name="' + csrfTokenName + '"]').val();

        $.ajax({
            type: "POST",
            url: checkSchoolNameExistsUrl,
            data: { school_name: schoolName, [csrfName]: csrfHash },
            dataType: 'json',
            success: function (response) {
                const newCsrfName = response.csrf.csrfName;
                const newCsrfHash = response.csrf.csrfHash;
                $('input[name="' + newCsrfName + '"]').val(newCsrfHash);
                resolve({ exists: response.exists });
            },
            error: function (error) {
                console.error("Error:", error);
                resolve({ exists: false }); // Assume school name doesn't exist on error
            }
        });
    });
}