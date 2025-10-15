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
        font-weight: 600;
        color: #343a40;
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
    </style>
  </head>
  <body>
    <nav class="navbar navbar-light">
      <div class="container-fluid">
        <a class="navbar-brand fs-3" href="#">Cartsy</a>
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
                <i class="bi bi-heart-fill"></i>
              </button>
            </div>

            <p class="product-price">₱265</p>
            <p class="text-muted">Calumpit, Bulacan</p>

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
                  <span class="fw-bold">John Doe</span>
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
  </body>
</html>
