<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Saved Products - Cartsy</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
  <style>
    .product-card {
      border: 1px solid #e0e0e0;
      border-radius: 8px;
      padding: 15px;
      margin-bottom: 15px;
      transition: box-shadow 0.2s ease;
      display: flex;
      flex-wrap: wrap;
      align-items: center;
      justify-content: space-between;
    }

    .product-card:hover {
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .product-image {
      width: 100px;
      height: 100px;
      object-fit: cover;
      border-radius: 8px;
      margin-right: 15px;
    }

    .product-info {
      flex-grow: 1;
    }

    .product-title {
      font-weight: 600;
      font-size: 1.1rem;
    }

    .product-location {
      color: #6c757d;
    }

    .seller-name {
      font-size: 1.2rem;
      font-weight: bold;
      margin: 20px 0 10px 0;
    }

    .header-row {
      font-weight: bold;
      padding: 10px 15px;
      border-bottom: 2px solid #dee2e6;
    }

    .header-row > div {
      flex: 1;
      text-align: center;
    }

    .product-card > .price-col,
    .product-card > .action-col,
    .product-card > .contact-col {
      text-align: center;
    }

    .navbar {
      background-color: #ffffff;
      border-bottom: 1px solid #e0e0e0;
      padding: 1rem 2rem;
    }

    .navbar-brand {
      color: #343a40;
      font-family: "Suranna", serif;
      font-size: 30px;
    }

    .btn-dark:hover {
      background-color: #343a40;
    }

    .btn-outline-danger, .btn-outline-primary {
      border-radius: 50px;
    }
  </style>
</head>
<body>

<nav class="navbar sticky-top navbar-light">
  <div class="container-fluid">
    <a class="navbar-brand fs-3" href="#">Cartsy</a>

    <form class="d-flex flex-grow-1 mx-3" action="#" method="GET">
      <div class="input-group">
        <input class="form-control" type="search" name="query" placeholder="Search" required>
        <button class="btn btn-dark" type="submit">Search</button>
      </div>
    </form>

    <a href="#" class="btn btn-outline-dark me-3">Sell</a>

    <a href="#" class="btn btn-outline-danger me-3">
      <i class="bi bi-heart-fill"></i>
    </a>

    <div>
      <i class="bi bi-chat fs-4 me-3"></i>
      <i class="bi bi-person-circle fs-4"></i>
    </div>
  </div>
</nav>

<div class="container mt-4">
  <h5>Saved Products</h5>
  <!-- Header Row -->
  <div class="d-flex header-row bg-light text-dark">
    <div class="col-md-6 text-center">Product</div>
    <div class="col-md-2 text-center">Unit Price</div>
    <div class="col-md-2 text-center">Actions</div>
    <div class="col-md-2 text-center">Contact</div>
  </div>

  <!-- Seller 1 -->
  <div class="seller-name">Axel Delgado</div>
  <div class="product-card">
    <div class="col-md-6 d-flex align-items-center">
      <img src="https://via.placeholder.com/100" alt="Product" class="product-image">
      <div class="product-info">
        <div class="product-title">Corolla Sneakers</div>
        <div class="product-location">Bulacan</div>
      </div>
    </div>
    <div class="col-md-2 text-center">₱65</div>
    <div class="col-md-2 text-center">
      <button class="btn btn-outline-danger btn-sm">Delete</button>
    </div>
    <div class="col-md-2 text-center">
      <button class="btn btn-outline-primary btn-sm">Message</button>
    </div>
  </div>

  <!-- Seller 2 -->
  <div class="seller-name">Louis Litt</div>
  <div class="product-card">
    <div class="col-md-6 d-flex align-items-center">
      <img src="1.png" alt="Product" class="product-image">
      <div class="product-info">
        <div class="product-title">Corolla Sneakers</div>
        <div class="product-location">Calumpit, Bulacan</div>
      </div>
    </div>
    <div class="col-md-2 text-center">₱901</div>
    <div class="col-md-2 text-center">
      <button class="btn btn-outline-danger btn-sm">Delete</button>
    </div>
    <div class="col-md-2 text-center">
      <button class="btn btn-outline-primary btn-sm">Message</button>
    </div>
  </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
