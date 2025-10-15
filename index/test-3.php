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

    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css"
    />

    <style>
      body {
        font-family: Arial, sans-serif;
      }
      .navbar-brand {
        font-family: "Suranna", serif;
        font-size: 30px;
      }
      .banner {
        display: flex;
        margin: 40px auto 60px auto;
        width: 90%;
        overflow: hidden;
        border-radius: 20px;
        background-color: #2f3b14;
      }
      /* Image Section (Left Half) */
      .banner-image {
        flex: 1;
        border-top-left-radius: 250px;
        border-bottom-left-radius: 250px;
        overflow: hidden; /* Ensures image follows rounded corners */
      }

      .banner-image img {
        width: 100%;
        height: 100%;
        object-fit: cover; /* Ensures image fills the space nicely */
      }

      /* Text Section (Right Half) */
      .banner-text {
        flex: 1;
        color: white;
        padding: 40px 0 40px 40px;
        display: flex;
        flex-direction: column;
        justify-content: center;
      }

      .banner-text h2 {
        font-size: 1.8rem;
        margin-bottom: 15px;
      }

      .banner-text p {
        margin-bottom: 20px;
      }

      .featured-categories img {
        width: 100%; /* Responsive width */
        height: 200px; /* Set fixed height */
        object-fit: cover; /* Ensures the image fills the frame without distortion */
        border-radius: 8px; /* Keeps the rounded corners */
      }

      .featured-categories .col-6 {
        padding: 0 5px; /* Adds even spacing between images */
      }

      .featured-categories p {
        margin-top: 15px;
      }

      /* Fix product card image to have uniform dimensions */
      .card img {
        width: 100%; /* Responsive width */
        height: 200px; /* Fixed height for uniform image size */
        object-fit: cover; /* Ensures consistent image scaling */
        border-radius: 8px;
      }

      /* Add equal heights to the card body, including title and price */
      .card-body {
        padding: 10px;
        display: flex;
        flex-direction: column;
        justify-content: flex-start; /* Align text to top */
      }

      .card-title {
        font-size: 1rem;
        font-weight: bold;
        margin-bottom: 5px;
      }

      .product-condition {
        font-size: 0.9rem;
        color: gray;
        margin-bottom: 10px;
      }

      .price-text {
        font-size: 1rem;
        font-weight: bold;
        color: #333;
        margin-top: auto; /* Push the price to the bottom */
      }

      .card {
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        position: relative;
      }

      .save-heart {
        position: absolute;
        top: 10px;
        right: 10px;
        background-color: rgba(255, 255, 255, 0.8);
        border: none;
        border-radius: 50%;
        padding: 5px;
        cursor: pointer;
      }

      .save-heart i {
        color: #ff5252;
        font-size: 18px;
      }

      .product-grid-section {
        background-color: white; /* bg-light effect */
        padding: 30px;
      }

      .product-grid h3 {
        text-align: center;
        margin-bottom: 30px;
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
  <body class="bg-light">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-white bg-white">
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
    <div class="banner">
      <div class="banner-text">
        <h2>
          Upgrade your lifestyle with top-quality items at unbeatable prices.
        </h2>
        <p>
          From stylish footwear and fitness gear to essential home tools, we’ve
          got something for everyone. Browse our latest collections and grab the
          best deals today!
        </p>
        <button
          class="btn btn-warning"
          style="width: 50%; margin: auto; border-radius: 20px; font-size: 500"
        >
          Shop now
        </button>
      </div>
      <div class="banner-image">
        <img src="./image/banner.webp" alt="Banner Products" />
      </div>
    </div>

    <!-- Categories -->
    <section class="container featured-categories">
      <h3 class="mb-4">Categories</h3>
      <div class="row text-center">
        <div class="col-6 col-md-3 mb-4">
          <img
            src="./image/categories/Categories/Womans_Apparel.webp"
            class="img-fluid"
            alt="Women's Apparel"
          />
          <p>Women's Apparel</p>
        </div>
        <div class="col-6 col-md-3 mb-4">
          <img
            src="./image/categories/Categories/Mens_Apparel.jpg"
            class="img-fluid"
            alt="Men's Apparel"
          />
          <p>Men's Apparel</p>
        </div>
        <div class="col-6 col-md-3 mb-4">
          <img
            src="./image/categories/Categories/Laptopss_&_Computers.jpg"
            class="img-fluid"
            alt="Laptops & Computers"
          />
          <p>Laptops & Computers</p>
        </div>
        <div class="col-6 col-md-3 mb-4">
          <img
            src="./image/categories/Categories/Schools_&_Office_Supplies.jpg"
            class="img-fluid"
            alt="School & Office Supplies"
          />
          <p>School & Office Supplies</p>
        </div>
        <div class="col-6 col-md-3 mb-4">
          <img
            src="./image/categories/Categories/Home_&_Living.jpg"
            class="img-fluid"
            alt="Home & Living"
          />
          <p>Home & Living</p>
        </div>
        <div class="col-6 col-md-3 mb-4">
          <img
            src="./image/categories/Categories/Home_Appliances.jpg"
            class="img-fluid"
            alt="Home Appliances"
          />
          <p>Home Appliances</p>
        </div>
        <div class="col-6 col-md-3 mb-4">
          <img
            src="./image/categories/Categories/Healths_&_Personal_Care.jpg"
            class="img-fluid"
            alt="Health & Personal Care"
          />
          <p>Health & Personal Care</p>
        </div>
        <div class="col-6 col-md-3 mb-4">
          <img
            src="./image/categories/Categories/Books_&_Magazines.jpg"
            class="img-fluid"
            alt="Books & Magazines"
          />
          <p>Books & Magazines</p>
        </div>
      </div>
    </section>

    <!-- Product Grid -->
    <section class="container-fluid product-grid-section">
      <div class="container">
        <h5
          class="text-center"
          style="
            border-bottom: 5px solid #e3bf69;
            padding-bottom: 15px;
            margin-bottom: 30px;
          "
        >
          DAILY DISCOVER
        </h5>

        <div
          class="row row-cols-2 row-cols-sm-3 row-cols-lg-4 row-cols-xl-5 g-4"
        >
          <div class="col">
            <div class="card">
              <button class="save-heart">
                <i class="bi bi-heart-fill"></i>
              </button>
              <img
                src="./image/Products/Mens_Apparel/formal_attire.png"
                class="card-img-top"
                alt="Product Image"
              />
              <div class="card-body">
                <p class="card-title">Formal Attire</p>
                <p class="product-condition">New</p>
                <p class="price-text">₱345</p>
              </div>
            </div>
          </div>
          <div class="col">
            <div class="card">
              <button class="save-heart">
                <i class="bi bi-heart-fill"></i>
              </button>
              <img
                src="./image/Products/School_&_Office_Supplies/desk.png"
                class="card-img-top"
                alt="Product Image"
              />
              <div class="card-body">
                <p class="card-title">Desk</p>
                <p class="price-text">₱2312</p>
              </div>
            </div>
          </div>
          <div class="col">
            <div class="card">
              <button class="save-heart">
                <i class="bi bi-heart-fill"></i>
              </button>
              <img
                src="./image/Products/Books_&_Magazines/10.png"
                class="card-img-top"
                alt="Product Image"
              />
              <div class="card-body">
                <p class="card-title">Lazy Book</p>
                <p class="price-text">₱145</p>
              </div>
            </div>
          </div>
          <div class="col">
            <div class="card">
              <button class="save-heart">
                <i class="bi bi-heart-fill"></i>
              </button>
              <img
                src="./image/Products/Health_&_Personal_Care/11.png"
                class="card-img-top"
                alt="Product Image"
              />
              <div class="card-body">
                <p class="card-title">Foldable Walker</p>
                <p class="price-text">₱823</p>
              </div>
            </div>
          </div>
          <div class="col">
            <div class="card">
              <button class="save-heart">
                <i class="bi bi-heart-fill"></i>
              </button>
              <img
                src="./image/Products/Electronic_Devices/3.png"
                class="card-img-top"
                alt="Product Image"
              />
              <div class="card-body">
                <p class="card-title">Gaming Laptop</p>
                <p class="price-text">₱14,225</p>
              </div>
            </div>
          </div>
          <div class="col">
            <div class="card">
              <button class="save-heart">
                <i class="bi bi-heart-fill"></i>
              </button>
              <img
                src="./image/Products/Home_&_Living/12.png"
                class="card-img-top"
                alt="Product Image"
              />
              <div class="card-body">
                <p class="card-title">Bed</p>
                <p class="price-text">₱14,212</p>
              </div>
            </div>
          </div>
          <div class="col">
            <div class="card">
              <button class="save-heart">
                <i class="bi bi-heart-fill"></i>
              </button>
              <img src="./image/Products/Home_&_Living/14.png" alt="Drawer" />
              <div class="card-body">
                <p class="card-title">Drawer</p>
                <p class="product-condition">Used - Like New</p>
                <p class="price-text">₱12,231</p>
              </div>
            </div>
          </div>
          <div class="col">
            <div class="card">
              <button class="save-heart">
                <i class="bi bi-heart-fill"></i>
              </button>
              <img
                src="./image/Products/Home_&_Living/19.png"
                class="card-img-top"
                alt="Product Image"
              />
              <div class="card-body">
                <p class="card-title">Couch</p>
                <p class="price-text">₱122</p>
              </div>
            </div>
          </div>
          <div class="col">
            <div class="card">
              <button class="save-heart">
                <i class="bi bi-heart-fill"></i>
              </button>
              <img
                src="./image/Products/School_&_Office_Supplies/organizer.png"
                class="card-img-top"
                alt="Product Image"
              />
              <div class="card-body">
                <p class="card-title">Organizer</p>
                <p class="price-text">₱23</p>
              </div>
            </div>
          </div>
          <div class="col">
            <div class="card">
              <button class="save-heart">
                <i class="bi bi-heart-fill"></i>
              </button>
              <img
                src="./image/Products/Electronic_Devices/12.png"
                class="card-img-top"
                alt="Product Image"
              />
              <div class="card-body">
                <p class="card-title">Printer</p>
                <p class="price-text">₱11,212</p>
              </div>
            </div>
          </div>
          <div class="col">
            <div class="card">
              <button class="save-heart">
                <i class="bi bi-heart-fill"></i>
              </button>
              <img
                src="./image/Products/Womans_Apparel/blouse.png"
                class="card-img-top"
                alt="Product Image"
              />
              <div class="card-body">
                <p class="card-title">Blouse</p>
                <p class="price-text">₱123</p>
              </div>
            </div>
          </div>
          <div class="col">
            <div class="card">
              <button class="save-heart">
                <i class="bi bi-heart-fill"></i>
              </button>
              <img
                src="./image/Products/Womans_Apparel/cargo_shorts.png"
                class="card-img-top"
                alt="Product Image"
              />
              <div class="card-body">
                <p class="card-title">Cargo Shorts</p>
                <p class="price-text">₱99</p>
              </div>
            </div>
          </div>
          <div class="col">
            <div class="card">
              <button class="save-heart">
                <i class="bi bi-heart-fill"></i>
              </button>
              <img
                src="./image/Products/Mens_Apparel/casio.png"
                class="card-img-top"
                alt="Product Image"
              />
              <div class="card-body">
                <p class="card-title">Casio</p>
                <p class="price-text">₱5,212</p>
              </div>
            </div>
          </div>
          <div class="col">
            <div class="card">
              <button class="save-heart">
                <i class="bi bi-heart-fill"></i>
              </button>
              <img
                src="./image/Products/Home_Appliances/21.png"
                class="card-img-top"
                alt="Product Image"
              />
              <div class="card-body">
                <p class="card-title">Oven</p>
                <p class="price-text">₱14,999</p>
              </div>
            </div>
          </div>
          <div class="col">
            <div class="card">
              <button class="save-heart">
                <i class="bi bi-heart-fill"></i>
              </button>
              <img
                src="./image/Products/Health_&_Personal_Care/5.png"
                class="card-img-top"
                alt="Product Image"
              />
              <div class="card-body">
                <p class="card-title">Cetaphil Cleanser</p>
                <p class="price-text">₱249</p>
              </div>
            </div>
          </div>

          <!-- More product cards can be added similarly -->
        </div>
      </div>
    </section>

    <!-- About Us -->
    <div class="about-section">
      <h4>About Us</h4>
      <p style="text-align: justify">
        Welcome to Cartsy, the ultimate online marketplace that connects buyers
        and sellers seamlessly! At Cartsy, we provide a hassle-free platform
        where sellers can list their products easily, and customers can reach
        out to them directly. Our user-friendly interface ensures a smooth
        browsing experience, allowing for secure and efficient transactions
        without unnecessary fees or middlemen. Whether you're on the hunt for
        the best deals or looking for a hassle-free way to sell your products,
        Cartsy provides a trusted and convenient marketplace tailored to your
        needs.
      </p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>
