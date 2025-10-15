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

      .seller-icon {
        width: 50px;
        height: 50px;
        background-color: #f3d35e;
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
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
        margin-bottom: 10px;
        font-weight: bold;
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
            <p>Condition:</p>
            <p>Description</p>
            <p>Heel Height : 2cm Colors: Beige/Black Weight: 640g</p>
          </div>
          <div class="details-section">
            <h5>Seller Information</h5>
            <div class="d-flex align-items-center">
              <div class="seller-icon me-2">
                <i class="bi bi-person"></i>
              </div>
              <span>John Doe</span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.js"></script>
  </body>
</html>
