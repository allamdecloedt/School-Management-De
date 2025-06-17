<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400..700&display=swap" rel="stylesheet">

<div class=" container-about">
  <div class="general-container ">
    <div class="general-header align-items-center">
      <h1 class='col-6 display-4 text_fade text-uppercase text-center  text-sm-break'> <?php echo get_phrase('About_us'); ?> </h1>
      <!-- Div Section For Header Background Fade In-Out Animation-->
      <div></div>
      <div></div>
      <div></div>
      <!-- End Div Section-->
    </div>
    <img class="ct-img rellax " data-rellax-speed="1.5" src="
			<?php echo base_url('assets/frontend/ultimate/img/online admission/oa-img-top.jpg') ?>" alt="">
    <div class="general-container-ol"></div>
  </div>
  <div class="container row">
    <!-- Text Section -->
    <div class="col-md-6 content-section">
      <h1>Welcome to Wayo Academy</h1>
      <p> Where learning adapts to you at WAYO Academy, we are dedicated to delivering a personalized, dynamic educational experience that integrates effortlessly into your life. Our platform offers a diverse selection of expert-led courses and practical resources to help you develop the skills that matter most, for today and the future. </p>
      <p> Whether you are beginning a new career, advancing in your field or exploring a passion, we will support you at every stage. Join us in a journey of growth, discovery and achievement. </p>
      <p style="font-family: 'Dancing Script', cursive; font-optical-sizing: auto; font-weight: bold; font-style: normal; font-size: 1.9rem;">Because learning should evolve with you !</p>
    </div>
    <!-- Image Section -->
    <div class="col-md-6 image-section">
      <img alt="About Us" src="
					<?php echo base_url('uploads/images/about_us/gros-plan-personnes-travaillant-au-bureau-min.jpg') ?>">
    </div>
  </div>
</div>
<div class="general-container g-0 container-fluid ">
        <img id="img-bot" class="ct-img rellax " data-rellax-speed="1.5"
            src="<?php echo base_url('assets/frontend/ultimate/img/online admission/oa-img-bot.jpg') ?>" alt="">
        <div class="general-container-ol-bot"></div>

    </div>
    <style>
/* OR add padding-top to the text/image container */
.container.row {
  padding-top: 80px; /* Adjust this value as needed */
}

/* Existing styles remain unchanged */
.container-about {
  display: flex;
  flex-direction: column;
  align-items: center;
}

.content-section {
  padding: 40px;
}

.content-section h1 {
  color: white;
  font-size: 2.5rem;
  font-weight: bold;
  margin-bottom: 20px;
}

.content-section p {
  color: white;
  font-size: 1.1rem;
  line-height: 1.6;
  margin-bottom: 20px;
}

.image-section img {
  width: 90%;
  height: 65%;
  border-radius: 15px;
}

@media (max-width: 1399px) { /* Tablets and smaller */
  .container.row {
    padding-top: 40px; /* Reduce padding for smaller screens */
  }

  .content-section {
    padding: 20px; /* Reduce padding */
  }

  .content-section h1 {
    font-size: 2rem; /* Smaller heading */
  }

  .content-section p {
    font-size: 1rem; /* Slightly smaller text */
  }

  .image-section img {
    width: 100%; /* Full width for images */
    height: 65%; /* Maintain aspect ratio */
    margin-top: 20px; /* Add spacing between text and image */
  }
}

@media (max-width: 991px) { /* Tablets and smaller */
  .container.row {
    padding-top: 40px; /* Reduce padding for smaller screens */
  }

  .content-section {
    padding: 20px; /* Reduce padding */
  }

  .content-section h1 {
    font-size: 1.7rem; /* Smaller heading */
    text-align: center;
  }

  .content-section p {
    font-size: 0.9rem; /* Slightly smaller text */
    text-align: center;
  }

  .image-section img {
    width: 110%; /* Full width for images */
    height: 75%; /* Maintain aspect ratio */
    margin-top: 20px; /* Add spacing between text and image */
  }
}

@media (max-width: 767px) { /* Tablets and smaller */
  .container.row {
    padding-top: 40px; /* Reduce padding for smaller screens */
  }

  .content-section {
    padding: 20px; /* Reduce padding */
  }

  .content-section h1 {
    font-size: 2rem; /* Smaller heading */
    text-align: center;
  }

  .content-section p {
    font-size: 1rem; /* Slightly smaller text */
    text-align: center;
  }

  .image-section img {
    width: 100%; /* Full width for images */
    height: auto; /* Maintain aspect ratio */
    margin-top: 20px; /* Add spacing between text and image */
  }
}

@media (max-width: 859px) { /* Tablets and smaller */
  .container.row {
    padding-top: 40px; /* Reduce padding for smaller screens */
  }

  .content-section {
    padding: 20px; /* Reduce padding */
  }

  .content-section h1 {
    font-size: 1.7rem; /* Smaller heading */
    text-align: center;
  }

  .content-section p {
    font-size: 0.9rem; /* Slightly smaller text */
    text-align: center;
  }

  .image-section img {
    width: 100%; /* Full width for images */
    height: 75%; /* Maintain aspect ratio */
    margin-top: 20px; /* Add spacing between text and image */
  }
}

@media (max-width: 633px) { /* Tablets and smaller */
  .container.row {
    padding-top: 40px; /* Reduce padding for smaller screens */
  }

  .content-section {
    padding: 20px; /* Reduce padding */
  }

  .content-section h1 {
    font-size: 1.7rem; /* Smaller heading */
    text-align: center;
  }

  .content-section p {
    font-size: 0.9rem; /* Slightly smaller text */
    text-align: center;
  }

  .image-section img {
    width: 100%; /* Full width for images */
    height: 60%; /* Maintain aspect ratio */
    margin-top: 20px; /* Add spacing between text and image */
  }
}
</style>