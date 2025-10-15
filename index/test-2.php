<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Cartsy | Home</title>

    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
    />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Suranna&display=swap"
      rel="stylesheet"
    />

    <style>
      body {
        font-family: Arial, sans-serif;
      }
      .navbar-brand {
        font-family: "Suranna", serif;
        font-size: 30px;
      }
      .hero-banner {
        position: relative;
        text-align: center;
        padding: 50px;
        color: white;
        overflow: hidden; /* Keep content within bounds */
      }

      .hero-banner::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-image: url("./image/banner.webp");
        background-size: cover;
        background-position: center;
        filter: blur(4px); /* Blur the image */
        z-index: -1; /* Push background image behind the text */
      }

      .hero-banner h1,
      .hero-banner p,
      .hero-banner button {
        position: relative; /* Keeps the text above the blurred, darkened background */
        z-index: 1;
      }

      .featured-categories img {
        border-radius: 8px;
      }
      .card {
        border: 1px solid #ddd;
      }
      .card:hover {
        transform: scale(1.03);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
      }
      .about-section {
        padding: 30px;
        background-color: #f8f9fa;
        text-align: center;
        margin-top: 30px;
      }
      .about-section h4 {
        font-size: 1.5rem;
        margin-bottom: 10px;
      }
    </style>
  </head>
  <body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
      <div class="container">
        <a class="navbar-brand" href="#">Cartsy</a>
        <button
          class="navbar-toggler"
          type="button"
          data-bs-toggle="collapse"
          data-bs-target="#navbarContent"
          aria-controls="navbarContent"
          aria-expanded="false"
          aria-label="Toggle navigation"
        >
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarContent">
          <form class="d-flex w-100">
            <div class="input-group">
              <input
                type="search"
                class="form-control"
                placeholder="Search"
                aria-label="Search"
              />
              <button class="btn btn-dark" type="button">Search</button>
            </div>
          </form>
          <ul class="navbar-nav ms-3">
            <li class="nav-item">
              <a class="nav-link d-flex align-items-center" href="#!">Sell</a>
            </li>
            <li class="nav-item" style="width: 85px">
              <a class="nav-link d-flex align-items-center" href="#!"
                >Sign Up</a
              >
            </li>
            <li class="nav-item" style="width: 75px">
              <a
                class="nav-link d-flex align-items-center"
                href="http://localhost/cartsy/login/login.php"
                >Sign In</a
              >
            </li>
          </ul>
        </div>
      </div>
    </nav>

    <!-- Hero Section -->
    <div class="hero-banner container my-4">
      <h1>Upgrade Your Lifestyle</h1>
      <p>Explore our latest collection and grab the best deals today!</p>
      <button class="btn btn-warning">Shop Now</button>
    </div>

    <!-- Featured Categories -->
    <section class="container">
      <h3 class="mb-4">Featured Categories</h3>
      <div div class="row text-center">
        <div class="col-6 col-md-3 mb-4">
          <img
            src="https://via.placeholder.com/150"
            class="img-fluid"
            alt="Home & Living"
          />
          <p>Home & Living</p>
        </div>
        <div class="col-6 col-md-3 mb-4">
          <img
            src="https://via.placeholder.com/150"
            class="img-fluid"
            alt="Books & Magazines"
          />
          <p>Books & Magazines</p>
        </div>
        <div class="col-6 col-md-3 mb-4">
          <img
            src="https://via.placeholder.com/150"
            class="img-fluid"
            alt="Women's Apparel"
          />
          <p>Women's Apparel</p>
        </div>
        <div class="col-6 col-md-3 mb-4">
          <img
            src="https://via.placeholder.com/150"
            class="img-fluid"
            alt="Laptops & Computers"
          />
          <p>Laptops & Computers</p>
        </div>
      </div>
    </section>

    <!-- Product Grid -->
    <section class="container my-4">
      <h3>Featured Products</h3>
      <div class="row row-cols-1 row-cols-sm-2 row-cols-md-4 g-4">
        <div class="col">
          <div class="card">
            <img
              src="https://via.placeholder.com/200"
              class="card-img-top"
              alt="Product Image"
            />
            <div class="card-body">
              <h5 class="card-title">Unisex Fashion T-Shirt</h5>
              <p class="card-text">₱145</p>
            </div>
          </div>
        </div>
        <div class="col">
          <div class="card">
            <img
              src="https://via.placeholder.com/200"
              class="card-img-top"
              alt="Product Image"
            />
            <div class="card-body">
              <h5 class="card-title">18k Gold Plated Pendant</h5>
              <p class="card-text">₱188</p>
              <a href=""></a>
            </div>
          </div>
        </div>
        <div class="col">
          <div class="card">
            <img
              src="https://via.placeholder.com/200"
              class="card-img-top"
              alt="Product Image"
            />
            <div class="card-body">
              <h5 class="card-title">18k Gold Plated Pendant</h5>
              <p class="card-text">₱188</p>
              <a href=""></a>
            </div>
          </div>
        </div>
        <div class="col">
          <div class="card">
            <img
              src="https://via.placeholder.com/200"
              class="card-img-top"
              alt="Product Image"
            />
            <div class="card-body">
              <h5 class="card-title">18k Gold Plated Pendant</h5>
              <p class="card-text">₱188</p>
              <a href=""></a>
            </div>
          </div>
        </div>
        <div class="col">
          <div class="card">
            <img
              src="https://via.placeholder.com/200"
              class="card-img-top"
              alt="Product Image"
            />
            <div class="card-body">
              <h5 class="card-title">18k Gold Plated Pendant</h5>
              <p class="card-text">₱188</p>
              <a href=""></a>
            </div>
          </div>
        </div>
        <div class="col">
          <div class="card">
            <img
              src="https://via.placeholder.com/200"
              class="card-img-top"
              alt="Product Image"
            />
            <div class="card-body">
              <h5 class="card-title">18k Gold Plated Pendant</h5>
              <p class="card-text">₱188</p>
              <a href=""></a>
            </div>
          </div>
        </div>
        <div class="col">
          <div class="card">
            <img
              src="https://via.placeholder.com/200"
              class="card-img-top"
              alt="Product Image"
            />
            <div class="card-body">
              <h5 class="card-title">18k Gold Plated Pendant</h5>
              <p class="card-text">₱188</p>
              <a href=""></a>
            </div>
          </div>
        </div>
        <div class="col">
          <div class="card">
            <img
              src="https://via.placeholder.com/200"
              class="card-img-top"
              alt="Product Image"
            />
            <div class="card-body">
              <h5 class="card-title">18k Gold Plated Pendant</h5>
              <p class="card-text">₱188</p>
              <a href=""></a>
            </div>
          </div>
        </div>
        <div class="col">
          <div class="card">
            <img
              src="https://via.placeholder.com/200"
              class="card-img-top"
              alt="Product Image"
            />
            <div class="card-body">
              <h5 class="card-title">18k Gold Plated Pendant</h5>
              <p class="card-text">₱188</p>
              <a href=""></a>
            </div>
          </div>
        </div>
        <div class="col">
          <div class="card">
            <img
              src="https://via.placeholder.com/200"
              class="card-img-top"
              alt="Product Image"
            />
            <div class="card-body">
              <h5 class="card-title">18k Gold Plated Pendant</h5>
              <p class="card-text">₱188</p>
              <a href=""></a>
            </div>
          </div>
        </div>
        <div class="col">
          <div class="card">
            <img
              src="https://via.placeholder.com/200"
              class="card-img-top"
              alt="Product Image"
            />
            <div class="card-body">
              <h5 class="card-title">18k Gold Plated Pendant</h5>
              <p class="card-text">₱188</p>
              <a href=""></a>
            </div>
          </div>
        </div>
        <div class="col">
          <div class="card">
            <img
              src="https://via.placeholder.com/200"
              class="card-img-top"
              alt="Product Image"
            />
            <div class="card-body">
              <h5 class="card-title">18k Gold Plated Pendant</h5>
              <p class="card-text">₱188</p>
              <a href=""></a>
            </div>
          </div>
        </div>
        <!-- Repeat other product cards as needed -->
      </div>
    </section>

    <!-- About Us -->
    <div class="about-section">
      <h4>About Us</h4>
      <p>
        Welcome to Cartsy, the ultimate online marketplace that connects buyers
        and sellers seamlessly! We provide a hassle-free shopping experience
        with a wide range of items, from electronics to fashion and home decor.
        Explore our marketplace today!
      </p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>
