<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Cartsy Product Page</title>

    <!-- Bootstrap CSS -->
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="index.css" />
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css"
    />

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Suranna&display=swap"
      rel="stylesheet"
    />

    <style>
      body {
        font-family: "Poppins", sans-serif;
        background-color: #f8f9fa;
      }

      .navbar {
        background-color: #ffffff;
        border-bottom: 1px solid #e0e0e0;
      }

      .navbar-brand {
        color: #343a40;
        font-family: "Suranna", serif;
        font-size: 30px;
      }

      .product-card {
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        padding: 40px;
        max-width: 1200px;
        margin: 30px auto;
      }

      .product-image {
        max-width: 100%;
        border-radius: 8px;
      }

      .image-wrapper {
        padding: 10px;
        background-color: #f5f5f5;
        border-radius: 8px;
        text-align: center;
      }

      .product-title {
        font-weight: 700;
        font-size: 1.8rem;
      }

      .product-price {
        color: #ff5555;
        font-weight: bold;
        font-size: 1.7rem;
      }

      .btn-warning {
        background-color: #d6a842;
        border-color: #d6a842;
      }

      .btn-warning:hover {
        background-color: #c0933a;
      }

      .details-section h5 {
        font-size: 1.1rem;
        margin-top: 30px;
        color: #343a40;
        font-weight: bold;
      }

      .details-section p {
        margin: 0;
      }

      .seller-info {
        background-color: #fff3cd;
        padding: 15px;
        border-radius: 8px;
        display: flex;
        align-items: center;
      }

      .seller-icon {
        background-color: #fbc02d;
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.8rem;
      }

      /* Heart Button */
      .save-heart {
        border: none;
        background: none;
        color: #ff5555;
        font-size: 1.5rem;
        padding: 5px;
      }

      .save-heart:hover {
        transform: scale(1.2);
        transition: transform 0.2s ease-in-out;
      }

      .save-heart .bi-heart-fill {
        display: none;
      }

      .save-heart.active .bi-heart {
        display: none;
      }

      .save-heart.active .bi-heart-fill {
        display: inline;
      }
    </style>
  </head>
  <body>
    <nav class="navbar navbar-light">
      <div class="container-fluid">
        <!-- Brand -->
        <a class="navbar-brand fs-3" href="#">Cartsy</a>

        <!-- Search Bar with Button Inside -->
        <form class="d-flex flex-grow-1 mx-3">
          <div class="input-group">
            <input
              class="form-control"
              type="search"
              placeholder="Search"
              aria-label="Search"
            />
            <button class="btn btn-dark" type="submit">Search</button>
          </div>
        </form>

        <!-- Sell Button -->
        <button class="btn btn-outline-dark me-3">Sell</button>

        <!-- Chat and Profile Icons -->
        <div>
          <i class="bi bi-chat fs-4 me-3"></i>
          <i class="bi bi-person-circle fs-4"></i>
        </div>
      </div>
    </nav>

    <div class="container">
      <div class="product-card">
        <div class="row align-items-center">
          <!-- Product Image Section -->
          <div class="col-md-6">
            <div class="image-wrapper">
              <img
                src="./image/Products/Mens_Apparel/rubber_shoes.png"
                alt="Product Image"
                class="product-image"
              />
            </div>
          </div>

          <!-- Product Details Section -->
          <div class="col-md-6">
            <div class="d-flex justify-content-between align-items-start">
              <h2 class="product-title">COROLLA Sneakers</h2>
              <button class="save-heart">
                <i class="bi bi-heart"></i>
                <i class="bi bi-heart-fill"></i>
              </button>
            </div>

            <p class="product-price">₱265</p>
            <p class="location">Calumpit, Bulacan</p>

            <div class="d-flex gap-2">
              <button class="btn btn-warning text-white flex-grow-1">
                Message
              </button>
              <button class="btn btn-dark">Report</button>
            </div>

            <div class="details-section">
              <h5>Details</h5>
              <p><strong>Condition:</strong> Brand New</p>
              <p>
                <strong>Description:</strong> Heel Height: 2cm | Colors:
                Beige/Black | Weight: 640g
              </p>
            </div>

            <div class="details-section">
              <h5>Seller Information</h5>
              <div class="seller-info">
                <div class="seller-icon me-3">
                  <i class="bi bi-person"></i>
                </div>
                <div>
                  <span class="fw-bold">Axel Delgado</span>
                  <p class="text-muted mb-0">Trusted Seller</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.js"></script>

    <script>
      // JavaScript to toggle heart fill
      document
        .querySelector(".save-heart")
        .addEventListener("click", function () {
          this.classList.toggle("active");
        });
    </script>
  </body>
</html>
