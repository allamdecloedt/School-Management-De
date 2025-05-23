<!-- ========== MAIN ========== --> <?php
$slider = get_frontend_settings('slider_images');
$slider_images = json_decode($slider);
$upcoming_events = $this->frontend_model->get_frontend_upcoming_events();
?>  <!-- Ajout d'un style pour gérer le min-height sur les petits écrans -->
<style>
  .claim-free-trial {
    max-width: 100%; /* Ensures the image doesn't exceed its container */
    width: 600px; /* Default width for larger screens */
    height: auto; /* Maintains aspect ratio */
    z-index: 1; /* Keeps it above the background */
}

/* Responsive adjustments with media queries */
@media (max-width: 1200px) {
    .claim-free-trial {
        width: 50%; /* Scales down to 50% of the container width */
    }
}

@media (max-width: 1024px) and (max-height: 600px) {
    .claim-free-trial {
        width: 50%; /* Scales down to 50% of the container width */
    }
    .intro-container {
      min-height: 120vh !important;
    }
}


@media (max-width: 991px) {
    .claim-free-trial {
        width: 50%; /* Further reduction for medium screens */
    }
}

@media (max-width: 768px) {
    .claim-free-trial {
        width: 75%; /* Adjust for tablets */
    }
    .intro-container {
      min-height: 120vh !important;
    }
}

@media (max-width: 600px) {
    .claim-free-trial {
        width: 75%; /* Smaller size for mobile devices */
        right: 5px; /* Slight adjustment to avoid edge overlap */
        bottom: 5px;
    }
    .intro-container {
      min-height: 120vh !important;
    }
}

@media (max-width: 500px) {
    .claim-free-trial {
        width: 75%; /* Smaller size for mobile devices */
        right: 5px; /* Slight adjustment to avoid edge overlap */
        bottom: 5px;
    }
    .intro-container {
      min-height: 120vh !important;
    }
}

@media (max-width: 420px) {
    .claim-free-trial {
        width:  85%; /* Smaller size for mobile devices */
        right: 5px; /* Slight adjustment to avoid edge overlap */
        bottom: 5px;
    }
    .intro-container {
      min-height: 120vh !important;
    }
}

@media (max-width: 400px) {
    .claim-free-trial {
        width: 95%; /* Even smaller for very small screens */
    }
    .intro-container {
      min-height: 145vh !important;
    }
}
    @media (max-width: 402px) { 
      .btn-discov {
      padding-left: 12px !important;
  padding-right: 12px !important;
          }
    }
    .btn-discov {
      padding-left: 112px;
      padding-right: 112px;
          }
    @media (min-width: 1400px) {
      .container, .container-lg, .container-md, .container-sm, .container-xl, .container-xxl {
        max-width: 1520px;
    }
}
</style>
<main class="" id="content" role="main">
  <!-- Intro Section -->
  <div class="intro-section">
  <div id="intro-container" class="intro-container position-relative" style="min-height: 90vh;">
    <!-- Background -->
    <div class="position-absolute top-0 start-0 end-0 bottom-0 opacity-75" style="background-image: url('uploads/images/decloedt/home/main_bg.jpg'); background-size: cover; background-position: center; filter: brightness(40%); z-index: 0;"></div>
    <!-- Container for content -->
    <div class="container" style="padding-top: 13%;">
        <div class="row position-relative" style="z-index: 1;">
            <!-- Left Column -->
            <div class="col-lg-6 mb-5 mb-lg-0 d-flex align-items-center">
                <div class="px-md-4 w-100">
                    <div class="text-container">
                        <h1 class="text-white fs-2 display-2 fw-bold display-md-4 display-lg-3" style="letter-spacing: 2px;">YOUR MENTORSHIP THEIR SUCCESS</h1>
                        <p class="text-white fs-md-4 fs-lg-3" style="letter-spacing: 1px; font-size: 17px;">Strengthen loyalty, maintain engagement and drive growth</p>
                    </div>
                    <!-- Buttons -->
                    <div class="text-center ">
                        <div class="row justify-content-center g-3">
                            <!-- Student Admission Button -->
                            <div class="col-auto">
                                <a class="btn text-white border-3 shadow-sm rounded-3 w-100 w-md-auto px-4 py-2" style="background-color: rgba(210, 130, 45, 0.7); border-color: #A9A9A8; transition: background-color 0.3s ease;" onmouseover="this.style.backgroundColor='rgba(210, 130, 45, 0.55)'" onmouseout="this.style.backgroundColor='rgba(210, 130, 45, 0.7)'" href="<?php echo site_url('admission/online_admission_student'); ?>">Learner admission</a>
                            </div>
                            <!-- Mentor Admission Button -->
                            <div class="col-auto">
                                <a class="btn text-white border-3 shadow-sm rounded-3 w-100 w-md-auto px-4 py-2" style="background-color: rgba(210, 130, 45, 0.7); border-color: #A9A9A8; transition: background-color 0.3s ease;" onmouseover="this.style.backgroundColor='rgba(210, 130, 45, 0.55)'" onmouseout="this.style.backgroundColor='rgba(210, 130, 45, 0.7)'" href="<?php echo site_url('admission/online_admission'); ?>">Mentor admission</a>
                            </div>
                        </div>
                        <div class="row justify-content-center mt-3">
                            <!-- Discover Our Courses Button -->
                            <div class="col-auto">
                                <a class="btn text-white border-3 shadow-sm rounded-3 w-100 w-md-auto btn-discov py-2" style="background-color: rgba(210, 130, 45, 0.7); border-color: #A9A9A8; transition: background-color 0.3s ease;" onmouseover="this.style.backgroundColor='rgba(210, 130, 45, 0.55)'" onmouseout="this.style.backgroundColor='rgba(210, 130, 45, 0.7)'" href="<?php echo site_url('home/courses'); ?>">Discover our courses</a>
                            </div>
                        </div>
                    </div>
                    <!-- End Buttons -->
                </div>
            </div>
            <!-- Right Column -->
            <div class="col-lg-6 mt-3 mb-lg-0 d-flex align-items-center">
                <div class="text-container text-center text-lg-center w-100">
                    <p class="text-white fw-bold fs-4 fs-md-4">Enjoy 7 days free to explore Wayo Academy!</p>
                    <a class="btn text-white border-5 shadow-sm rounded-3 w-75 w-md-auto px-5 py-3 fw-bold " style="background-color: rgba(210, 130, 45, 0.7); border-color: #A9A9A8; transition: background-color 0.3s ease;" onmouseover="this.style.backgroundColor='rgba(210, 130, 45, 0.55)'" onmouseout="this.style.backgroundColor='rgba(210, 130, 45, 0.7)'" href="<?php echo site_url('home/courses'); ?>">START NOW</a>
                </div>
            </div>
        </div>
    </div>
</div>


</div>
  <section class="container py-5">
    <div class="row align-items-center">
      <!-- Colonne de gauche : Texte -->
      <div class="col-md-6">
        <h1 class="text-white text-center" style="font-family: 'Playfair Display', serif; font-size: 34px;">Get to know us</h1>
        <p class="lead mt-3" style="color: #DDDBDF;"> Welcome to Wayo Academy, your partner in digital learning. Designed to meet the needs of mentors, educators, and professional coaches, our platform offers comprehensive tools to manage your online classes, organize educational routines, track learner progress, and more. Whether you're an experienced educator or new to digital teaching, </p>
        <p class="lead text-white" style="font-weight: 700;"> Wayo Academy is here to support you. Discover a new way to share your knowledge with simplicity and efficiency ! </p>
      </div>
      <!-- Colonne de droite : Vidéo -->
      <div class="col-md-6">
    <div class="border rounded">
        <div class="ratio ratio-16x9 rounded overflow-hidden">
            <iframe src="https://www.youtube.com/embed/viHILXVY_eU?si=r6tjXyeM_8hIEnL0" title="YouTube video player" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen class="border-0"></iframe>
        </div>
    </div>
</div>
</div>
    </div>
  </section>
  <section class="py-3 position-relative" style="min-height: 100vh;">
    <!-- Background Image -->
    <div class="position-absolute top-0 start-0 end-0 bottom-0" style="background-image: url('uploads/images/decloedt/home/bg_WhyChooseAcademy.jpg'); background-size: cover; background-position: center; filter: brightness(75%); z-index: -1;"></div>
    
    <div class="container position-relative" style="z-index: 1;">
        <!-- Title -->
        <h2 class="text-center text-white mb-5" style="font-family: 'Playfair Display', serif; font-size: 34px;">Why choose Wayo Academy as a...</h2>

        <!-- Mentor Section -->
        <div class="mb-5">
            <h3 class="text-center text-white mb-4" style="font-family: 'Playfair Display', serif; font-size: 34px;">Mentor</h3>
            <div class="row g-4">
                <!-- Mentor Item 1 -->
                <div class="col-xl-2 col-md-4 col-sm-6">
                    <div class="text-white p-4 rounded-3 h-100" style="background-color: rgba(255, 137, 3, 0.55);">
                        <p class="text-white text-center fs-5 mb-0" style="font-weight: bold;"><span class="text-white me-2">✔</span>Create and customize interactive courses in just a few clicks</p>
                    </div>
                </div>
                <!-- Mentor Item 2 -->
                <div class="col-xl-2 col-md-4 col-sm-6">
                    <div class="text-white p-4 rounded-3 h-100" style="background-color: rgba(255, 137, 3, 0.55);">
                        <p class="text-white text-center fs-5 mb-0" style="font-weight: bold;"><span class="text-white me-2">✔</span>Easily schedule online classes and track attendance</p>
                    </div>
                </div>
                <!-- Mentor Item 3 -->
                <div class="col-xl-2 col-md-4 col-sm-6">
                    <div class="text-white p-4 rounded-3 h-100" style="background-color: rgba(255, 137, 3, 0.55);">
                        <p class="text-white text-center fs-5 mb-0" style="font-weight: bold;"><span class="text-white me-2">✔</span>Access an intuitive calendar to organize your events</p>
                    </div>
                </div>
                <!-- Mentor Item 4 -->
                <div class="col-xl-2 col-md-4 col-sm-6">
                    <div class="text-white p-4 rounded-3 h-100" style="background-color: rgba(255, 137, 3, 0.55);">
                        <p class="text-white text-center fs-5 mb-0" style="font-weight: bold;"><span class="text-white me-2">✔</span>Evaluate learners with automated quizzes & reports</p>
                    </div>
                </div>
                <!-- Mentor Item 5 -->
                <div class="col-xl-2 col-md-4 col-sm-6">
                    <div class="text-white p-4 rounded-3 h-100" style="background-color: rgba(255, 137, 3, 0.55);">
                        <p class="text-white text-center fs-5 mb-0" style="font-weight: bold;"><span class="text-white me-2">✔</span>Analyze performance with detailed reports</p>
                    </div>
                </div>
                <!-- Mentor Item 6 -->
                <div class="col-xl-2 col-md-4 col-sm-6">
                    <div class="text-white p-4 rounded-3 h-100" style="background-color: rgba(255, 137, 3, 0.55);">
                        <p class="text-white text-center fs-5 mb-0" style="font-weight: bold;"><span class="text-white me-2">✔</span>Build a community around your expertise</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Learner Section -->
        <div>
            <h3 class="text-center text-white mb-4" style="font-family: 'Playfair Display', serif; font-size: 34px;">Learner</h3>
            <div class="row g-4">
                <!-- Learner Item 1 -->
                <div class="col-xl-2 col-md-4 col-sm-6">
                    <div class="text-white p-4 rounded-3 h-100" style="background-color: rgba(255, 137, 3, 0.55);">
                        <p class="text-white text-center fs-5 mb-0" style="font-weight: bold;"><span class="text-white me-2">✔</span>Learn at your own pace with online courses</p>
                    </div>
                </div>
                <!-- Learner Item 2 -->
                <div class="col-xl-2 col-md-4 col-sm-6">
                    <div class="text-white p-4 rounded-3 h-100" style="background-color: rgba(255, 137, 3, 0.55);">
                        <p class="text-white text-center fs-5 mb-0" style="font-weight: bold;"><span class="text-white me-2">✔</span>Follow a clear and motivating class routine</p>
                    </div>
                </div>
                <!-- Learner Item 3 -->
                <div class="col-xl-2 col-md-4 col-sm-6">
                    <div class="text-white p-4 rounded-3 h-100" style="background-color: rgba(255, 137, 3, 0.55);">
                        <p class="text-white text-center fs-5 mb-0" style="font-weight: bold;"><span class="text-white me-2">✔</span>Access a variety of multimedia resources (videos, quizzes, etc.)</p>
                    </div>
                </div>
                <!-- Learner Item 4 -->
                <div class="col-xl-2 col-md-4 col-sm-6">
                    <div class="text-white p-4 rounded-3 h-100" style="background-color: rgba(255, 137, 3, 0.55);">
                        <p class="text-white text-center fs-5 mb-0" style="font-weight: bold;"><span class="text-white me-2">✔</span>Stay organized with event reminders & deadlines</p>
                    </div>
                </div>
                <!-- Learner Item 5 -->
                <div class="col-xl-2 col-md-4 col-sm-6">
                    <div class="text-white p-4 rounded-3 h-100" style="background-color: rgba(255, 137, 3, 0.55);">
                        <p class="text-white text-center fs-5 mb-0" style="font-weight: bold;"><span class="text-white me-2">✔</span>Join interactive classes with your mentors</p>
                    </div>
                </div>
                <!-- Learner Item 6 -->
                <div class="col-xl-2 col-md-4 col-sm-6">
                    <div class="text-white p-4 rounded-3 h-100" style="background-color: rgba(255, 137, 3, 0.55);">
                        <p class="text-white text-center fs-5 mb-0" style="font-weight: bold;"><span class="text-white me-2">✔</span>Progress with instant feedback on your results</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
  <div id="mentors-section">
    <!-- Teacher Section -->
    <div class="section-height teacher-section">
      <!-- Title -->
      <h2 class="social-media-main-text" style="font-family: 'Playfair Display', serif; font-size: 34px;">Meet Your Mentors</h2>
      <!-- End Title -->
      <!-- Teacher Cards Carousel Start-->
      <!-- This is a place holder carousel -->
      <div class=" teacher-carousel-container">
        <div class="owl-carousel owl-theme  justify-content-center">
          <div class="teacher-card">
            <div class="teacher-card-img">
              <img src="uploads/images/decloedt/home/Mentor_01.jpg">
            </div>
            <div class="teacher-card-desc">
              <h6 class="teacher-card-primary-text">Fattah</h6>
              <h6 class="teacher-card-secondary-text">Full Stack Developer</h6>
            </div>
            <div class="teacher-card-details">
              <div class="">
                <a class="teacher-card-social-button" href="https://www.linkedin.com/company/decloedtcloud/mycompany/">
                  <svg xmlns="http://www.w3.org/2000/svg" class=" teacher-linkedin" width="20" height="20" fill="currentColor" class="bi bi-linkedin" viewBox="0 0 16 16">
                    <path d="M0 1.146C0 .513.526 0 1.175 0h13.65C15.474 0 16 .513 16 1.146v13.708c0 .633-.526 1.146-1.175 1.146H1.175C.526 16 0 15.487 0 14.854zm4.943 12.248V6.169H2.542v7.225zm-1.2-8.212c.837 0 1.358-.554 1.358-1.248-.015-.709-.52-1.248-1.342-1.248S2.4 3.226 2.4 3.934c0 .694.521 1.248 1.327 1.248zm4.908 8.212V9.359c0-.216.016-.432.08-.586.173-.431.568-.878 1.232-.878.869 0 1.216.662 1.216 1.634v3.865h2.401V9.25c0-2.22-1.184-3.252-2.764-3.252-1.274 0-1.845.7-2.165 1.193v.025h-.016l.016-.025V6.169h-2.4c.03.678 0 7.225 0 7.225z" />
                  </svg>
                </a>
              </div>
            </div>
          </div>
          <div class="teacher-card ">
            <div class="teacher-card-img">
              <img src="uploads/images/decloedt/home/Mentor_02.png">
            </div>
            <div class="teacher-card-desc">
              <h6 class="teacher-card-primary-text">Natalie</h6>
              <h6 class="teacher-card-secondary-text">Full Stack Developer</h6>
            </div>
            <div class="teacher-card-details">
              <div class="">
                <a class="teacher-card-social-button" href="https://www.linkedin.com/company/decloedtcloud/mycompany/">
                  <svg xmlns="http://www.w3.org/2000/svg" class=" teacher-linkedin" width="20" height="20" fill="currentColor" class="bi bi-linkedin" viewBox="0 0 16 16">
                    <path d="M0 1.146C0 .513.526 0 1.175 0h13.65C15.474 0 16 .513 16 1.146v13.708c0 .633-.526 1.146-1.175 1.146H1.175C.526 16 0 15.487 0 14.854zm4.943 12.248V6.169H2.542v7.225zm-1.2-8.212c.837 0 1.358-.554 1.358-1.248-.015-.709-.52-1.248-1.342-1.248S2.4 3.226 2.4 3.934c0 .694.521 1.248 1.327 1.248zm4.908 8.212V9.359c0-.216.016-.432.08-.586.173-.431.568-.878 1.232-.878.869 0 1.216.662 1.216 1.634v3.865h2.401V9.25c0-2.22-1.184-3.252-2.764-3.252-1.274 0-1.845.7-2.165 1.193v.025h-.016l.016-.025V6.169h-2.4c.03.678 0 7.225 0 7.225z" />
                  </svg>
                </a>
              </div>
            </div>
          </div>
          <div class="teacher-card">
            <div class="teacher-card-img">
              <img src="uploads/images/decloedt/home/Mentor_03.png">
            </div>
            <div class="teacher-card-desc">
              <h6 class="teacher-card-primary-text">Mohamed</h6>
              <h6 class="teacher-card-secondary-text">Full Stack Developer</h6>
            </div>
            <div class="teacher-card-details">
              <div class="">
                <a class="teacher-card-social-button" href="https://www.linkedin.com/company/decloedtcloud/mycompany/">
                  <svg xmlns="http://www.w3.org/2000/svg" class=" teacher-linkedin" width="20" height="20" fill="currentColor" class="bi bi-linkedin" viewBox="0 0 16 16">
                    <path d="M0 1.146C0 .513.526 0 1.175 0h13.65C15.474 0 16 .513 16 1.146v13.708c0 .633-.526 1.146-1.175 1.146H1.175C.526 16 0 15.487 0 14.854zm4.943 12.248V6.169H2.542v7.225zm-1.2-8.212c.837 0 1.358-.554 1.358-1.248-.015-.709-.52-1.248-1.342-1.248S2.4 3.226 2.4 3.934c0 .694.521 1.248 1.327 1.248zm4.908 8.212V9.359c0-.216.016-.432.08-.586.173-.431.568-.878 1.232-.878.869 0 1.216.662 1.216 1.634v3.865h2.401V9.25c0-2.22-1.184-3.252-2.764-3.252-1.274 0-1.845.7-2.165 1.193v.025h-.016l.016-.025V6.169h-2.4c.03.678 0 7.225 0 7.225z" />
                  </svg>
                </a>
              </div>
            </div>
          </div>
          <div class="teacher-card ">
            <div class="teacher-card-img">
              <img src="uploads/images/decloedt/home/Mentor_04.png">
            </div>
            <div class="teacher-card-desc">
              <h6 class="teacher-card-primary-text">Olivia</h6>
              <h6 class="teacher-card-secondary-text">Full Stack Developer</h6>
            </div>
            <div class="teacher-card-details">
              <div>
                <a class="teacher-card-social-button" href="https://www.linkedin.com/company/decloedtcloud/mycompany/">
                  <svg xmlns="http://www.w3.org/2000/svg" class=" teacher-linkedin" width="20" height="20" fill="currentColor" class="bi bi-linkedin" viewBox="0 0 16 16">
                    <path d="M0 1.146C0 .513.526 0 1.175 0h13.65C15.474 0 16 .513 16 1.146v13.708c0 .633-.526 1.146-1.175 1.146H1.175C.526 16 0 15.487 0 14.854zm4.943 12.248V6.169H2.542v7.225zm-1.2-8.212c.837 0 1.358-.554 1.358-1.248-.015-.709-.52-1.248-1.342-1.248S2.4 3.226 2.4 3.934c0 .694.521 1.248 1.327 1.248zm4.908 8.212V9.359c0-.216.016-.432.08-.586.173-.431.568-.878 1.232-.878.869 0 1.216.662 1.216 1.634v3.865h2.401V9.25c0-2.22-1.184-3.252-2.764-3.252-1.274 0-1.845.7-2.165 1.193v.025h-.016l.016-.025V6.169h-2.4c.03.678 0 7.225 0 7.225z" />
                  </svg>
                </a>
              </div>
            </div>
          </div>
          <div class="teacher-card ">
            <div class="teacher-card-img">
              <img src="https://images.pexels.com/photos/1759530/pexels-photo-1759530.jpeg?auto=compress&cs=tinysrgb&w=800">
            </div>
            <div class="teacher-card-desc">
              <h6 class="teacher-card-primary-text">Hamza</h6>
              <h6 class="teacher-card-secondary-text">Full Stack Developer</h6>
            </div>
            <div class="teacher-card-details">
              <div class="">
                <a class="teacher-card-social-button" href="https://www.linkedin.com/company/decloedtcloud/mycompany/">
                  <svg xmlns="http://www.w3.org/2000/svg" class=" teacher-linkedin" width="20" height="20" fill="currentColor" class="bi bi-linkedin" viewBox="0 0 16 16">
                    <path d="M0 1.146C0 .513.526 0 1.175 0h13.65C15.474 0 16 .513 16 1.146v13.708c0 .633-.526 1.146-1.175 1.146H1.175C.526 16 0 15.487 0 14.854zm4.943 12.248V6.169H2.542v7.225zm-1.2-8.212c.837 0 1.358-.554 1.358-1.248-.015-.709-.52-1.248-1.342-1.248S2.4 3.226 2.4 3.934c0 .694.521 1.248 1.327 1.248zm4.908 8.212V9.359c0-.216.016-.432.08-.586.173-.431.568-.878 1.232-.878.869 0 1.216.662 1.216 1.634v3.865h2.401V9.25c0-2.22-1.184-3.252-2.764-3.252-1.274 0-1.845.7-2.165 1.193v.025h-.016l.016-.025V6.169h-2.4c.03.678 0 7.225 0 7.225z" />
                  </svg>
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- Teacher Cards Carousel End-->
    </div>
    <!-- End Teacher Section -->
  </div> <?php
 $App_Google_play = base_url('uploads/images/decloedt/logo/Google_Play_Store_Bouton.svg');
 $App_store = base_url('uploads/images/decloedt/logo/App_Store_Bouton.svg');
 $telephone_wayo = base_url('uploads/images/decloedt/home/bg_download.png');

?> <div class="section" style="min-height: 50vh;">
    <div class="image-container">
      <img src="<?php echo $telephone_wayo; ?>" class="hide-on-mobile" style="width: 100%;" alt="Image description">
    </div>
    <div class="text-container_wayo">
      <h1 class="text-black marg_wayo" style="font-family: 'Playfair Display', serif; font-size: 34px;">Easier, faster and more accessible mentoring at your fingertips !</h1>
      <div class="promo-buttons ">
        <a class="store-button">
          <img src="<?php echo $App_store; ?>" alt="Download on App Store">
        </a>
        <a class="store-button">
          <img src="<?php echo $App_Google_play; ?>" alt="Get it on Google Play">
        </a>
      </div>
      <h1 class="text-black marg_wayo text-center mt-5" style="font-family: 'Playfair Display', serif; font-size: 65px;">Coming soon!</h1>
    </div>
  </div>
  <style>
  @media (max-width: 768px) {
    .hide-on-mobile {
      display: none;
    }
    .section {
      height: 30vh !important;
    }
  }
</style>
<div class="bg-black text-white py-5">
    <h2 class="text-center fs-2 mb-4" style="font-family: 'Playfair Display', serif; font-size: 34px;">Follow us on :</h2>

    <!-- Section des icônes -->
    <div class="d-flex flex-column flex-md-row justify-content-center align-items-center gap-5 gap-md-5 gap-lg-5 gap-xl-5">
      <!-- Facebook -->
      <div class="d-flex align-items-center gap-3">
        <div class="bg-white text-black px-3 py-2 rounded">
          <i class="fab fa-facebook-f fs-4"></i>
        </div>
        <a href="https://www.facebook.com/people/Wayo-Academy/61572524656807/" target="_blank" class="fs-4 follow-us" style="font-family: 'Playfair Display', serif; font-size: 34px;">Wayo Academy</a>
      </div>

      <!-- LinkedIn -->
      <div class="d-flex align-items-center gap-3">
        <div class="bg-white text-black px-2 py-2 rounded">
          <i class="fab fa-linkedin-in fs-4"></i>
        </div>
        <a href="https://www.linkedin.com/company/wayoacademy/about/" target="_blank" class="fs-4 follow-us" style="font-family: 'Playfair Display', serif; font-size: 34px;">Wayo Academy</a>
      </div>

      <!-- Instagram -->
      <div class="d-flex align-items-center gap-3">
        <div class="bg-white text-black px-2 py-2 rounded">
          <i class="fab fa-instagram fs-3"></i>
        </div>
        <a href="https://www.instagram.com/wayo_academy/" target="_blank" class="fs-4 follow-us" style="font-family: 'Playfair Display', serif; font-size: 34px;">wayo_academy</a>
      </div>
    </div>
  </div>
  <div class="d-flex flex-column flex-md-row w-100 min-vh-50 min-vh-sm-75">
  <!-- Image occupant la moitié gauche -->
  <div class="w-100 w-2xl-66 w-md-50 h-300px h-sm-400px h-md-auto">
    <img
      src="uploads/images/decloedt/home/WhatOurMentorsSay.jpg"
      class="w-100 h-100 object-fit-cover"
      alt="Mentors background"
    />
  </div>

  <!-- Section témoignage occupant la moitié droite -->
  <div
    class="w-100 w-md-50 d-flex flex-column align-items-center justify-content-center px-4 px-sm-5 px-md-6 py-5 py-sm-6 card-mentors"
  >
    <!-- Titre -->
    <h2
      class="text-white mb-4 mb-sm-5 text-center" style="font-family: 'Playfair Display', serif; font-size: 40px;"
    >
      What our mentors say about us
    </h2>
    <!-- Carte Témoignage -->
    <div class="bg-white shadow-lg rounded-3 p-4 p-sm-5 w-100 max-w-xs max-w-2xl-2xl max-w-sm-sm max-w-xl-md mx-auto">
      <!-- Étoiles -->
      <div class="d-flex align-items-center mb-2 mb-sm-4 gap-1">
        <!-- Remplacez AiFillStar par des icônes Bootstrap ou un SVG personnalisé -->
        <svg class="text-warning" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
          <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
        </svg>
        <svg class="text-warning" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
          <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
        </svg>
        <svg class="text-warning" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
          <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
        </svg>
        <svg class="text-warning" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
          <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
        </svg>
        <svg class="text-warning" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
          <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
        </svg>
      </div>

      <!-- Témoignage -->
      <p class="text-black fs-5 fs-sm-5 fs-md-4 lh-base text-justify">
        "Wayo Academy has changed the way I connect with my students. The platform is 
        user-friendly and has everything I need to create a meaningful learning experience. 
        I appreciate how it helps me stay organized and engaged with my learners. 
        Highly recommend!"
      </p>

      <!-- Auteur -->
      <div class="d-flex align-items-center mt-3 mt-sm-4">
        <img
          src="uploads/images/decloedt/home/Mentor_03.png"
          alt="Théo James"
          class="rounded-circle me-2 me-sm-3" width="50" height="50"
        />
        <span class="fs-5 fs-sm-4 fw-bold text-gray-900">Théo James</span>
      </div>
    </div>
  </div>
</div>

<div class="container-faq faq-section">
    <div class="faq-left">
        <h1 class="faq-title" style="font-family: 'Playfair Display', serif; font-size: 50px;">FAQ</h1>
         <div class="question-marks">
            <span class="question-mark1"><img src="uploads/images/decloedt/home/FAQ.png" width="90" height="120" class="mt-3"></span>
            <span class="question-mark2"><img src="uploads/images/decloedt/home/FAQ.png" width="100" height="120" class="mt-10"></span>
        </div>
    </div>
    <div class="faq-right">
        <div class="faq-item">
            <div class="faq-question" onclick="toggleAnswer(this)">
                "What are the main features offered by Wayo Academy?"
                <span class="toggle-icon"></span>
            </div>
            <div class="faq-answer">
                Wayo Academy offers a variety of features including interactive online classes, quizzes, progress tracking, and a user-friendly interface for both learners and mentors.
            </div>
        </div>
        <div class="faq-item">
            <div class="faq-question" onclick="toggleAnswer(this)">
                "Can I organize online classes and quizzes on the platform?"
                <span class="toggle-icon"></span>
            </div>
            <div class="faq-answer">
                Yes, Wayo Academy allows you to organize and schedule online classes as well as create quizzes to assess learner progress.
            </div>
        </div>
        <div class="faq-item">
            <div class="faq-question" onclick="toggleAnswer(this)">
                "How do I create an account on Wayo Academy?"
                <span class="toggle-icon"></span>
            </div>
            <div class="faq-answer">
                To create an account, click on the "register" button, and choose between learner and mentor, and fill in the required details.
            </div>
        </div>
        <div class="faq-item">
            <div class="faq-question" onclick="toggleAnswer(this)">
                "Is Wayo Academy available on both mobile and desktop?"
                <span class="toggle-icon"></span>
            </div>
            <div class="faq-answer">
                Yes, Wayo Academy is accessible on both mobile devices and desktops.
            </div>
        </div>
        <div class="faq-item">
            <div class="faq-question" onclick="toggleAnswer(this)">
                "What subscription plans are available?"
                <span class="toggle-icon"></span>
            </div>
            <div class="faq-answer">
                Wayo Academy offers a free trial, and have access to all courses by purchasing a class.
            </div>
        </div>
        <div class="faq-item">
            <div class="faq-question" onclick="toggleAnswer(this)">
                "How can I contact technical support if I encounter an issue?"
                <span class="toggle-icon"></span>
            </div>
            <div class="faq-answer">
                You can contact technical support via email at info@wayo.cloud or through the contact form.
            </div>
        </div>
        <div class="faq-item">
            <div class="faq-question" onclick="toggleAnswer(this)">
                "Is my personal data secure on Wayo Academy?"
                <span class="toggle-icon"></span>
            </div>
            <div class="faq-answer">
                Yes, Wayo Academy uses advanced encryption and security protocols to ensure that your personal data is protected.
            </div>
        </div>
    </div>
</div>

<style>
    .container-faq {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        padding: 50px 30px;
        width: 100%;
        max-width: 1200px; /* Adjust as needed */
        margin: 0 auto;
    }
    .faq-left {
        flex: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    .question-marks {
        display: flex;
        justify-content: center;
        margin-bottom: 20px;
    }
    .question-mark1 {
      transform: rotate(-15deg);
    }
    .question-mark2 {
      transform: rotate(20deg);
}
    .faq-title {
        color: #fff;
        text-align: center;
    }
    .faq-right {
        flex: 2;
    }
    .faq-question {
        color: white;
        font-size: 1.7rem;
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 5px 0;
        border-bottom: 5px solid #fff;
    }
    .faq-question.active {
        color: orange !important; /* Couleur orange pour la question sélectionnée */
    }
    .faq-answer {
        color: white !important;
        font-size: 1.2rem;
        padding: 0;
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.5s ease-in-out, padding 0.5s ease-in-out;
    }
    .faq-answer.active {
        max-height: 200px;
        padding: 10px 0;
        color: white;
    }
    .toggle-icon::after {
        content: "+";
        font-size: 2rem;
        color: white;
        transition: transform 0.3s ease;
    }
    .toggle-icon.active::after {
        content: "-";
        transform: rotate(180deg);
    }
    .follow-us {
      text-decoration: none !important;
      color: #ffffff;
    }
    .follow-us:hover {
      text-decoration: none !important;
      color: rgba(210, 130, 45, 0.937);
    }
    @media (max-width: 1024px) {
        .question-marks {
            display: none; /* Masque les images */
        }
        .container-faq {
            flex-direction: column; /* Passe en disposition verticale */
            align-items: center; /* Centre les éléments */
        }
        .faq-left {
            flex: none; /* Supprime la flexibilité pour éviter l'étirement */
            margin-bottom: 20px; /* Ajoute un espace sous le titre */
        }
        .faq-right {
            flex: none; /* Supprime la flexibilité */
            width: 100%; /* Prend toute la largeur disponible */
            max-width: 800px; /* Limite la largeur des questions pour lisibilité */
        }
        .faq-question {
            font-size: 1.3rem;
        }
    }
</style>
<script>
    function toggleAnswer(element) {
        const answer = element.nextElementSibling;
        const icon = element.querySelector('.toggle-icon');
        const allQuestions = document.querySelectorAll('.faq-question');
        const allAnswers = document.querySelectorAll('.faq-answer');
        const allIcons = document.querySelectorAll('.toggle-icon');

        // Si la réponse cliquée est déjà active, on la ferme
        if (answer.classList.contains('active')) {
            answer.classList.remove('active');
            icon.classList.remove('active');
            element.classList.remove('active');
        } else {
            // Ferme toutes les autres réponses actives
            allAnswers.forEach((ans, index) => {
                if (ans !== answer) {
                    ans.classList.remove('active');
                    allIcons[index].classList.remove('active');
                    allQuestions[index].classList.remove('active');
                }
            });

            // Ouvre la nouvelle réponse
            answer.classList.add('active');
            icon.classList.add('active');
            element.classList.add('active');
        }
    }
</script>
  <!-- Title -->
  <div class="event-title-section">
    <div class=" text-center event-title-section-text">
      <h2 style="font-family: 'Playfair Display', serif; font-size: 45px;"> <?php echo get_phrase('Upcoming Events'); ?> </h2>
      <div class="content">
        <svg id="more-arrows">
          <polygon class="arrow-top" points="37.6,27.9 1.8,1.3 3.3,0 37.6,25.3 71.9,0 73.7,1.3 " />
          <polygon class="arrow-middle" points="37.6,45.8 0.8,18.7 4.4,16.4 37.6,41.2 71.2,16.4 74.5,18.7 " />
          <polygon class="arrow-bottom" points="37.6,64 0,36.1 5.1,32.8 37.6,56.8 70.4,32.8 75.5,36.1 " />
        </svg>
      </div>
    </div>
  </div>
  <!-- End Title -->
  <section class="container-fluid">
    <div class="row">
        <!-- Sidebar avec les phrases -->
        <div class="col-md-4 sidebar">
            <p class="clickable active" data-target="video" style="font-family: 'Playfair Display', serif; font-size: 30px;">Exciting News Ahead! Watch Our Teaser</p>
        </div>
        <!-- Zone de contenu à droite -->
        <div class="col-md-8 content-area">
            <!-- Vidéo YouTube -->
            <iframe id="video" 
                    class="active" 
                    width="1260" 
                    src="https://www.youtube.com/embed/viHILXVY_eU?si=r6tjXyeM_8hIEnL0" 
                    title="YouTube video player" 
                    frameborder="0" 
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                    allowfullscreen></iframe>
        </div>
    </div>
</section>

<!-- CSS -->
<style>
    .sidebar {
        background-color: #000;
        color: #fff;
        padding: 20px;
        top: 50px;
    }
    .sidebar p {
        color: #fff;
        margin-top: 20px;
        padding: 5px 0;
        justify-content: space-between;
        align-items: center !important;
        border-bottom: 5px solid #fff;
        cursor: pointer;
    }
    .sidebar .active {
        color: #FD9830 !important;/* Optionnel : change la couleur du texte actif */
    }
    .content-area {
        padding: 20px;
        height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
    }
    .content-area iframe, .content-area img {
        display: none;
        max-width: 100%;
        height: 80%;
    }
    .content-area iframe.active {
        display: block;
        border: 1px solid white;
        border-radius: 10px;
    }
    .content-area img.active {
        display: block;
    }
</style>

<!-- JavaScript pour gérer les clics -->
<script>
    document.querySelectorAll('.clickable').forEach(item => {
        item.addEventListener('click', function() {
            // Récupérer la cible (video ou image)
            const target = this.getAttribute('data-target');
            
            // Supprimer la classe active de tous les éléments
            document.querySelectorAll('.clickable').forEach(el => {
                el.classList.remove('active');
            });
            document.querySelectorAll('.content-area > *').forEach(el => {
                el.classList.remove('active');
            });

            // Ajouter la classe active à l'élément cliqué et au contenu correspondant
            this.classList.add('active');
            document.getElementById(target).classList.add('active');
        });
    });
</script>
<section class="pricing-section">
        <h1 style="font-family: 'Playfair Display', serif; font-size: 40px;">Pricing</h1>
        <h2 style="">Flexible Plans for Every Mentor</h2>
        <div class="container">
            <div class="row g-4"> <!-- g-4 for gutter spacing -->
                <!-- Free Trial Card -->
                <div class="col-md-4">
                    <div class="pricing-card">
                        <h3>Free Trial:</h3>
                        <p>Explore Wayo Academy for <span style="color: #FD9830;">free</span>  for 30 days!</p>
                    </div>
                </div>
                <!-- Monthly Plan Card -->
                <div class="col-md-4">
                    <div class="pricing-card">
                        <h3>Monthly Plan:</h3>
                        <p>Start at just <span style="color: #FD9830;">...AED/month</span> for full access to all features</p>
                    </div>
                </div>
                <!-- Yearly Plan Card -->
                <div class="col-md-4">
                    <div class="pricing-card">
                        <h3>Yearly Plan:</h3>
                        <p>Save ...% with an annual subscription at <span style="color: #FD9830;">...AED/year</span> for full access to all features</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <style>

@media (min-width: 1100px) {
  .pricing-section h2 {
            text-align: center;
            font-size: 1.5rem;
            margin-bottom: 40px;
            color: #fff;
            border-bottom: 2px solid #fff;
            display: inline-block;
            margin-left: 10% !important;
            font-weight: bold; 
            font-size: 30px;
        }
}
        .pricing-section {
            padding: 50px 0;
        }
        .pricing-section h1 {
            color: white;
            text-align: center;
            font-size: 2.0rem;
            margin-bottom: 55px;
        }
        .pricing-section h2 {
            text-align: center;
            font-size: 1.5rem;
            margin-bottom: 40px;
            color: #fff;
            border-bottom: 2px solid #fff;
            display: inline-block;
            margin-left: 2%;
            font-weight: bold; 
        }
        .pricing-card {
            background-color: #CCCCCC;
            color: #000;
            border: none;
            padding: 40px;
            text-align: center;
            height: 100%;
        }
        .pricing-card h3 {
            color: #FD9830; /* Orange color for the plan titles */
            font-size: 1.5rem;
            margin-bottom: 10px;
            font-weight: bold;
        }
        .pricing-card p {
            color: white;
            font-size: 1.3rem;
            margin: 0;
            font-weight: bold;
        }
    </style>
  <div class="carousel-container">
    <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel" data-bs-interval="5000">
        <!-- Indicateurs verticaux -->
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2" aria-label="Slide 3"></button>
        </div>

        <!-- Slides -->
        <div class="carousel-inner">
            <!-- Slide 1 -->
            <div class="carousel-item active" style="background-image: url('uploads/images/decloedt/placeholders/Image_01.png');">
                <div class="carousel-caption">
                    <h5 style="font-family: 'Playfair Display', serif; font-size: 30px;">Work Smarter, Not Harder Online Course</h5>
                    <!-- <a href="#" class="btn">Read More</a> -->
                </div>
            </div>
            <!-- Slide 2 -->
            <div class="carousel-item" style="background-image: url('uploads/images/decloedt/placeholders/Image_02.png');">
                <div class="carousel-caption">
                    <h5 style="font-family: 'Playfair Display', serif; font-size: 30px;">From Awkward To Awesome: Secrets To Success</h5>
                    <!-- <a href="#" class="btn">Read More</a> -->
                </div>
            </div>
            <!-- Slide 3 -->
            <div class="carousel-item" style="background-image: url('uploads/images/decloedt/placeholders/Image_03.png');">
                <div class="carousel-caption">
                    <h5 style="font-family: 'Playfair Display', serif; font-size: 30px;">Virtual Learning In Modern Scrum Environments</h5>
                    <!-- <a href="#" class="btn">Read More</a> -->
                </div>
            </div>
        </div>
    </div>
</div>
<style>
        /* Style personnalisé pour le carrousel */
        .carousel-container {
            position: relative;
            width: 100%;
            height: 600px; /* Ajustez selon vos besoins */
            overflow: hidden;
        }

        .carousel-item {
        position: relative;
        height: 1000px; /* Ajustez selon vos besoins */
        transition: transform 0.5s linear; /* Transition fluide */
    }

    /* Utilisation de ::before pour l'image de fond avec filtre brightness */
    .carousel-item::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-size: cover;
        background-position: center;
        background-image: inherit; /* Hérite de l'image définie dans le style inline */
        filter: brightness(65%); /* Ajustez la valeur selon vos besoins (0% = noir, 100% = normal, >100% = plus lumineux) */
        z-index: 1; /* Place l'image derrière le contenu */
       
    }

        /* Style pour les points de navigation verticaux */
        .carousel-indicators {
            position: absolute;
            left: 20px;
            top: 30%;
            transform: translateY(-50%);
            display: flex;
            flex-direction: column;
            gap: 20px;
            margin: 0;
        }

        .carousel-indicators [data-bs-target] {
            width: 6px;
            height: 60px;
            border-radius: 10%;
            background-color: white !important;
            opacity: 0.5;
            border: none;
        }

        .carousel-indicators .active {
            background-color: #000;
            opacity: 1;
        }

        /* Style pour le texte et le bouton */
        .carousel-caption {
            position: absolute;
            top: 32%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
            color: #fff;
            width: 80%;
        }

        .carousel-caption a {
            font-size: 1.4rem;
            font-weight: bold;
        }

        .carousel-caption .btn {
            margin-top: 25rem;
            font-weight: bold;
            color: white;
        }
    </style>
    <!------------------------------------------------------------------------------------------------>
</main>
<!-- ========== END MAIN ========== -->