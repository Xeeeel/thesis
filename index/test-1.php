<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Carrey Store</title>
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
    />
    <style>
      .hero-banner {
        background-color: #f8f9fa;
        padding: 40px;
        text-align: center;
      }
      .featured-categories img {
        border-radius: 10px;
      }
      .card img {
        max-height: 200px;
        object-fit: cover;
      }
      .about-section {
        background-color: #f1f1f1;
        padding: 30px;
        text-align: center;
      }
    </style>
  </head>
  <body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
      <div class="container">
        <a class="navbar-brand" href="#">Carrey</a>
        <div class="collapse navbar-collapse">
          <form class="d-flex ms-auto">
            <input
              class="form-control me-2"
              type="search"
              placeholder="Search"
              aria-label="Search"
            />
            <button class="btn btn-outline-success" type="submit">
              Search
            </button>
          </form>
          <button class="btn btn-primary ms-3">Sell</button>
          <button class="btn btn-outline-primary ms-2">Login</button>
        </div>
      </div>
    </nav>

    <!-- Hero Section -->
    <div class="hero-banner container my-4 rounded bg-light">
      <h1>Upgrade Your Lifestyle</h1>
      <p>Explore our latest collection and grab the best deals today!</p>
      <button class="btn btn-warning">Shop now</button>
    </div>

    <!-- Featured Categories -->
    <section class="container">
      <h3 class="mb-4">Featured Categories</h3>
      <div class="row featured-categories">
        <div class="col-6 col-md-3 mb-3">
          <img
            src="https://via.placeholder.com/150"
            class="img-fluid"
            alt="Home & Living"
          />
          <p>Home & Living</p>
        </div>
        <div class="col-6 col-md-3 mb-3">
          <img
            src="https://via.placeholder.com/150"
            class="img-fluid"
            alt="Books & Magazines"
          />
          <p>Books & Magazines</p>
        </div>
        <div class="col-6 col-md-3 mb-3">
          <img
            src="https://via.placeholder.com/150"
            class="img-fluid"
            alt="Women's Apparel"
          />
          <p>Women's Apparel</p>
        </div>
        <div class="col-6 col-md-3 mb-3">
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
              <h5 class="card-title">Retro Cargo Pants</h5>
              <p class="card-text">₱175</p>
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
              <h5 class="card-title">Mens' Hiking Shoes</h5>
              <p class="card-text">₱3,150</p>
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
      </div>
    </section>

    <!-- About Us Section -->
    <div class="about-section">
      <h4>About Us</h4>
      <p>
        Welcome to Carrey, the ultimate online marketplace that connects buyers
        and sellers seamlessly! At Carrey, we provide a hassle-free shopping
        experience with a wide range of items, from electronics to fashion and
        home decor. Our mission is to simplify online shopping by providing
        quality products and excellent customer service.
      </p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>
