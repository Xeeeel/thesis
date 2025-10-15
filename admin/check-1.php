<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Seller Product Approval</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="styles.css" />
  <style>
    body {
      background-color: #f9f9f9;
    }

    .card {
      border-radius: 1rem;
    }

    hr {
      border-top: 1px solid #dee2e6;
    }

    textarea {
      resize: none;
    }
  </style>
</head>
<body>
  <div class="container my-5">
    <div class="card shadow-lg p-4">
      <h3 class="mb-4 text-primary">🧾 Product Review</h3>

      <!-- Basic Info -->
      <div class="mb-4">
        <h5 class="text-secondary">Product Information</h5>
        <hr />
        <div class="row">
          <div class="col-md-6">
            <p><strong>Product Name:</strong> iPhone 13 Pro</p>
            <p><strong>Category:</strong> Mobile Phones</p>
            <p><strong>Price:</strong> ₱42,000</p>
          </div>
          <div class="col-md-6">
            <p><strong>Condition:</strong> New</p>
            <p><strong>Deal Option:</strong> Meetup</p>
            <p><strong>Location:</strong> Quezon City, Metro Manila</p>
          </div>
        </div>
      </div>

      <!-- Product Description -->
      <div class="mb-4">
        <h5 class="text-secondary">Product Description</h5>
        <hr />
        <p>
          The iPhone 13 Pro comes with a stunning 6.1-inch Super Retina XDR display, powered by the A15 Bionic chip for seamless performance. 
          It features a 12 MP triple-camera system for ultra-wide, wide, and telephoto shots, along with ProRAW for professional-level editing.
          With 5G capabilities, this phone ensures you stay connected at high speeds, and its ceramic shield provides enhanced durability. 
          A perfect choice for anyone who demands the best in performance and design.
        </p>
      </div>

      <!-- Product Images -->
      <div class="mb-4">
        <h5 class="text-secondary">Product Images</h5>
        <hr />
        <div class="row">
          <div class="col-md-4">
            <img src="1.png" class="img-fluid" alt="Product Image">
          </div>
          <div class="col-md-4">
            <img src="1.png" class="img-fluid" alt="Product Image">
          </div>
          <div class="col-md-4">
            <img src="1.png" class="img-fluid" alt="Product Image">
          </div>
        </div>
      </div>

      <!-- Actions -->
      <div class="d-flex justify-content-end gap-2">
        <button class="btn btn-success">✅ Approve</button>
        <button class="btn btn-danger">❌ Reject</button>
      </div>
    </div>
  </div>

  <!-- Bootstrap JS and Popper.js (required for Bootstrap components) -->
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>
