<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Cartsy Product Page</title>
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="index.css" />

    <style>
      body {
        font-family: "Arial", sans-serif;
        background-color: #f5f5f5;
        margin: 0;
        padding: 0;
      }

      .navbar {
        background-color: white;
        border-bottom: 1px solid #ccc;
        padding: 1rem 2rem;
      }

      .navbar-brand {
        font-weight: bold;
      }

      .product-card {
        width: 90%; /* Occupy almost the full width */
        max-width: 1400px;
        background-color: white;
        padding: 50px 30px;
        margin: 20px auto; /* Center the card horizontally */
        border-radius: 10px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
      }

      .product-image {
        width: 100%;
        max-width: 400px;
        border-radius: 8px;
        object-fit: cover;
      }

      .product-title {
        font-weight: bold;
      }

      .product-price {
        color: #ff3333;
        font-size: 1.8rem;
        font-weight: bold;
      }

      .text-muted {
        font-size: 0.9rem;
      }

      .btn-warning {
        background-color: #d6a842;
        border-color: #d6a842;
      }

      .btn-warning:hover {
        background-color: #c0933a;
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

      .bookmark-button {
        background-color: transparent;
        border: none;
        font-size: 1.5rem;
      }

      .details-section {
        margin-top: 30px;
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

      .image-wrapper {
        background-color: #f5f5f5;
        padding: 10px;
        border-radius: 8px;
        display: flex;
        justify-content: center;
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

    <div class="container-fluid">
      <div class="row product-card align-items-center">
        <div class="col-md-6">
          <div class="image-wrapper">
            <img
              src="./image/Products/Mens_Apparel/rubber_shoes.png"
              alt="Product Image"
              class="product-image"
            />
          </div>
        </div>
        <div class="col-md-6">
          <div class="d-flex justify-content-between align-items-start mb-2">
            <h4 class="product-title">COROLLA Sneakers</h4>
            <button class="bookmark-button">
              <i class="bi bi-bookmark"></i>
            </button>
          </div>
          <p class="product-price">₱265</p>
          <p class="text-muted">Calumpit Bulacan</p>
          <div class="d-flex gap-2">
            <button class="btn btn-warning text-white flex-grow-1">
              Message
            </button>
            <button class="btn btn-dark">Report</button>
          </div>
          <div class="details-section">
            <h5>Details</h5>
            <p><strong>Condition:</strong> Brand New</p>
            <p><strong>Description:</strong> Comfortable, stylish sneakers</p>
            <p>
              <strong>Specs:</strong> Heel Height: 2cm | Colors: Beige/Black |
              Weight: 640g
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.js"></script>
  </body>
</html>
