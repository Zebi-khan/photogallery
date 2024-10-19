<?php require_once 'header.php'; ?>

<body>

  <!-- Slider -->
  <div class="slider">
    <div id="carouselExampleControls" class="carousel slide" data-bs-ride="false">
      <div class="carousel-inner">
        <div class="carousel-item active">
          <img src="/assets/images/Natural1.png" alt="png">
        </div>
        <div class="carousel-item">
          <img src="/assets/images/Natural2.png" alt="png">
        </div>
        <div class="carousel-item">
          <img src="/assets/images/Natural3.png" alt="png">
        </div>
        <div class="carousel-item">
          <img src="/assets/images/Natural5.png" alt="png">
        </div>
        <div class="carousel-item">
          <img src="/assets/images/Natural6.png" alt="png">
        </div>
        <div class="carousel-item">
          <img src="/assets/images/Natural7.png" alt="png">
        </div>
        <div class="carousel-item">
          <img src="/assets/images/Natural9.png" alt="png">
        </div>
      </div>
      <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="prev">
        <span class="position-relative custom-s carousel-control-prev-icon" aria-hidden="true"></span>
      </button>
      <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="next">
        <span class="position-relative custom-spn carousel-control-next-icon" aria-hidden="true"></span>
      </button>
    </div>
  </div>

  <!-- About Us -->
  <div class="about py-5">
    <div class="container">
      <div class="row">
        <div class="col-lg-6">
          <h2 class="mb-3"><b>About Us</b></h2>
          <p class="mt-3 fs-5">Lorem ipsum dolor sit amet consectetur adipisicing elit. Rem fugiat placeat id accusamus nostrum unde amet nesciunt. Sapiente totam, doloremque commodi ex amet neque in excepturi est rerum, error eveniet. Lorem ipsum dolor sit amet consectetur adipisicing elit. Rem fugiat placeat id accusamus nostrum unde amet nesciunt. Sapiente totam, doloremque commodi ex amet neque in excepturi est rerum, error eveniet. Lorem ipsum dolor sit amet consectetur adipisicing elit. Rem fugiat placeat id accusamus nostrum unde amet nesciunt. Sapiente totam, doloremque commodi ex amet neque in excepturi est rerum, error eveniet.</p>
        </div>
        <div class="col-lg-6 ps-5">
          <div class="image">
            <img src="/assets/images/about.png" alt="png" width="500">
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Albums -->
  <div class="album py-5">
    <h2 class="text-center mb-4"><b>Albums</b></h2>
    <div id="carouselAlbum" class="carousel slide" data-bs-ride="false">
      <div class="carousel-inner">
        <div class="carousel-item active">
          <div class="row">
            <div class="col-lg-4">
              <img src="/assets/images/1.png" height="324" width="410" alt="image">
            </div>
            <div class="col-lg-4">
              <img src="/assets/images/A2.png" height="324" width="410" alt="image">
            </div>
            <div class="col-lg-4">
              <img src="/assets/images/A3.png" height="324" width="410" alt="image">
            </div>
          </div>
        </div>
        <div class="carousel-item">
          <div class="row">
            <div class="col-lg-4">
              <img src="/assets/images/A4.png" height="324" width="410" alt="image">
            </div>
            <div class="col-lg-4">
              <img src="/assets/images/A5.png" height="324" width="410" alt="image">
            </div>
            <div class="col-lg-4">
              <img src="/assets/images/A6.png" height="324" width="410" alt="image">
            </div>
          </div>
        </div>
        <div class="carousel-item">
          <div class="row">
            <div class="col-lg-4">
              <img src="/assets/images/A7.png" height="324" width="410" alt="image">
            </div>
            <div class="col-lg-4">
              <img src="/assets/images/A8.png" height="324" width="410" alt="image">
            </div>
            <div class="col-lg-4">
              <img src="/assets/images/A9.png" height="324" width="410" alt="image">
            </div>
          </div>
        </div>
      </div>
      <button class="carousel-control-prev" type="button" data-bs-target="#carouselAlbum" data-bs-slide="prev">
        <span class="carousel-control-prev-icon position-relative custom-s" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
      </button>
      <button class="carousel-control-next" type="button" data-bs-target="#carouselAlbum" data-bs-slide="next">
        <span class="carousel-control-next-icon position-relative custom-spn" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
      </button>
    </div>
  </div>

  <!-- Filter -->
  <div class="filter py-5 text-center">
    <div class="container">
      <!-- Filter Buttons -->
      <div class="btn-group">
        <div class="custom-button">
          <a href="#" class="btn filter-btn active" data-filter="all">All</a>
          <a href="#" class="btn filter-btn" data-filter="beauty">Beauty</a>
          <a href="#" class="btn filter-btn" data-filter="natural">Natural</a>
          <a href="#" class="btn filter-btn" data-filter="birds">Birds</a>
        </div>
      </div>

      <!-- Gallery Images -->
      <div class="row d-flex mt-5">
        <div class="col-lg-2" data-category="birds">
          <img src="assets/images/b1.png" alt="Bird image" class="img-fluid equal-height">
        </div>
        <div class="col-lg-2" data-category="beauty">
          <img src="assets/images/A2.png" alt="Beauty image" class="img-fluid equal-height">
        </div>
        <div class="col-lg-2" data-category="birds">
          <img src="assets/images/b6.png" alt="Bird image" class="img-fluid equal-height">
        </div>
        <div class="col-lg-2" data-category="birds">
          <img src="assets/images/b2.png" alt="Bird image" class="img-fluid equal-height">
        </div>
        <div class="col-lg-2" data-category="beauty">
          <img src="assets/images/b4.png" alt="Beauty image" class="img-fluid equal-height">
        </div>
        <div class="col-lg-2" data-category="natural">
          <img src="assets/images/Natural3.png" alt="Natural image" class="img-fluid equal-height">
        </div>
      </div>

      <div class="row d-flex mt-5">
        <div class="col-lg-2" data-category="beauty">
          <img src="assets/images/b5.png" alt="Beauty image" class="img-fluid equal-height">
        </div>
        <div class="col-lg-2" data-category="birds">
          <img src="assets/images/b3.png" alt="Bird image" class="img-fluid equal-height">
        </div>
        <div class="col-lg-2" data-category="beauty">
          <img src="assets/images/A5.png" alt="Beauty image" class="img-fluid equal-height">
        </div>
        <div class="col-lg-2" data-category="natural">
          <img src="assets/images/Natural5.png" alt="Natural image" class="img-fluid equal-height">
        </div>
        <div class="col-lg-2" data-category="beauty">
          <img src="assets/images/A9.png" alt="Beauty image" class="img-fluid equal-height">
        </div>
        <div class="col-lg-2" data-category="beauty">
          <img src="assets/images/A6.png" alt="Beauty image" class="img-fluid equal-height">
        </div>
      </div>
    </div>
  </div>

  <!-- Footer -->
  <div class="custom-footer py-5">
    <div class="container">
      <div class="row">
        <div class="col-lg-12">
          <img src="assets/images/logo.png" alt="image" height="70">
        </div>
      </div>
      <div class="row">
        <div class="col-lg-4 mt-5">
          <div class="email d-flex align-items-center me-4 py-3">
            <i class="me-2">
              <img src="assets/images/mail" alt="Email" width="32">
            </i>
            <a href="email:photogallery@gmail.com" class="text-decoration-none text-dark">photogallery@gmail.com</a>
          </div>
          <div class="phone d-flex align-items-center me-4 py-3">
            <i class="me-2">
              <img src="assets/images/call.png" alt="Phone No" width="32">
            </i>
            <a href="tel:+92(123) 456-7890" class="text-decoration-none text-dark">+92(123) 456-7890</a>
          </div>
          <div class="address d-flex align-items-center me-4 py-2">
            <i class="me-2">
              <img src="assets/images/map.png" alt="Location" width="32">
            </i>
            <p class="m-0">111 high street Sahiwal</p>
          </div>
        </div>
        <div class="col-lg-4 mt-5">
          <div class="links ms-5">
            <ul class="list-unstyled">
              <li class="mb-3"><a href="#" class="text-decoration-none text-dark">Home</a></li>
              <li class="mb-3"><a href="#" class="text-decoration-none text-dark">About</a></li>
              <li class="mb-3"><a href="#" class="text-decoration-none text-dark">Albums</a></li>
              <li class="mb-3"><a href="#" class="text-decoration-none text-dark">Gallery</a></li>
              <li><a href="#" class="text-decoration-none text-dark">Contact Us</a></li>
            </ul>
          </div>
        </div>
        <div class="col-lg-4 mt-5">
          <ul class="list-unstyled">
            <li class="mb-3"><a href="#" class="text-decoration-none text-dark">Login</a></li>
            <li class="mb-3"><a href="#" class="text-decoration-none text-dark">Register</a></li>
          </ul>
        </div>
      </div>
    </div>
  </div>

  <?php require_once 'footer.php'; ?>

  <!-- JavaScript -->
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const filterButtons = document.querySelectorAll('.filter-btn');
    const galleryItems = document.querySelectorAll('[data-category]');

    filterButtons.forEach(button => {
      button.addEventListener('click', function(e) {
        e.preventDefault();

        // Remove active class from all buttons
        filterButtons.forEach(btn => btn.classList.remove('active'));
        this.classList.add('active');

        const filterValue = this.getAttribute('data-filter');

        // Show or hide images based on the selected filter
        galleryItems.forEach(item => {
          if (filterValue === 'all' || item.getAttribute('data-category') === filterValue) {
            item.style.display = 'block';
          } else {
            item.style.display = 'none';
          }
        });
      });
    });
  });
</script>