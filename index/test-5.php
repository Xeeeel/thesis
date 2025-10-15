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

      .product-grid-section {
        background-color: white;
        padding: 100px 30px 30px 30px;
      }
      .card {
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        position: relative;
      }
      .card img {
        width: 100%;
        height: 200px;
        object-fit: cover;
        border-radius: 8px;
      }
      .card-body {
        padding: 10px;
        display: flex;
        flex-direction: column;
        justify-content: flex-start;
      }
      .card-title {
        font-size: 1rem;
        font-weight: bold;
      }
      .price-text {
        font-size: 1rem;
        font-weight: bold;
        color: #333;
        margin-top: auto;
      }
      .product-condition {
        font-size: 0.9rem;
        color: gray;
        margin-bottom: 10px;
      }
      .save-heart {
        position: absolute;
        top: 10px;
        right: 10px;
        background-color: rgba(255, 255, 255, 0);
        border: none;
        border-radius: 50%;
        padding: 5px;
        cursor: pointer;
      }
      .save-heart i {
        color: #ff5252;
        font-size: 18px;
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
    <nav class="navbar sticky-top navbar-expand-lg navbar-white bg-white">
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
              <a class="nav-link d-flex align-items-center" href="http://localhost/cartsy/sign-up/sign-up.php">Sell</a>
            </li>
            <li class="nav-item" style="width: 85px">
              <a class="nav-link d-flex align-items-center" href="http://localhost/cartsy/sign-up/sign-up.php"
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
          onclick="document.getElementById('products').scrollIntoView({ behavior: 'smooth' })"
          
        >
          Shop now
        </button>
      </div>
      <div class="banner-image">
        <img src="./image/banner.webp" alt="Banner Products" />
      </div>
    </div>

    <!-- Categories -->
    <section id="categories" class="container featured-categories">
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
    <section id="products" class="container-fluid product-grid-section">
      <div class="container">
        <h5 class="text-center" style="border-bottom: 5px solid #e3bf69; padding-bottom: 15px; margin-bottom: 30px;">
          DAILY DISCOVER
        </h5>

        <div class="row row-cols-2 row-cols-sm-3 row-cols-lg-4 row-cols-xl-5 g-4">
          <?php
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "cartsy";

            $conn = new mysqli($servername, $username, $password, $dbname);

            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            $sql = "SELECT product_id, name, price, image_path, condition_name FROM products";  // Use the actual primary key
            $result = $conn->query($sql);

            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<div class='col'>";
                    echo "  <div class='card'>";
                    echo "    <button class='save-heart'><i class='bi bi-heart-fill'></i></button>";
                    echo "<img src='/cartsy/" . $row['image_path'] . "' alt='" . $row['name'] . "' />";

                    echo "    <div class='card-body'>";
                    echo "      <p class='card-title'><a href='/cartsy/view-product/test-7.php?id=" . $row['product_id'] . "' class='text-dark text-decoration-none'>" . $row['name'] . "</a></p>";
                    echo "      <p class='product-condition'>" . $row['condition_name'] . "</p>";
                    echo "      <p class='price-text'>₱" . $row['price'] . "</p>";
                    echo "    </div>";
                    echo "  </div>";
                    echo "</div>";
                }
            } else {
                echo "<p class='text-center'>No products available yet.</p>";
            }
            $conn->close();
            ?>


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
