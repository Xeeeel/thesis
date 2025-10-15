<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Top Categories Display</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f8f9fa;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .main-content {
      padding: 2rem;
    }

    .header h3 {
      color: #0d6efd;
      font-weight: 600;
      text-align: center;
      margin-bottom: 3rem;
    }

    .card {
      border-radius: 15px;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
      border: none;
      margin-bottom: 1.5rem;
    }

    .card-body {
      padding: 1.5rem;
      text-align: center;
    }

    .card-title {
      color: #0d6efd;
      font-weight: 700;
      font-size: 1.3rem;
    }

    .card-text {
      font-size: 1.1rem;
      color: #555;
    }

    .row {
      display: flex;
      justify-content: center;
      gap: 1.5rem;
      flex-wrap: wrap;
    }

    .card-footer {
      background-color: #f1f1f1;
      border-radius: 0 0 15px 15px;
      padding: 10px;
    }

    .badge {
      font-size: 1rem;
      padding: 0.5rem;
      color: white;
      border-radius: 5px;
    }

    .badge-primary {
      background-color: #0d6efd;
    }

    .badge-secondary {
      background-color: #6c757d;
    }

    .badge-success {
      background-color: #28a745;
    }

    .badge-danger {
      background-color: #dc3545;
    }

  </style>
</head>
<body>
  <!-- Main Content -->
  <div class="main-content">
    <div class="header mb-4">
        <h3>Top Categories</h3>
    </div>

    <!-- Displaying Top Categories in Cards -->
    <div class="row">
      <div class="col-md-4 col-sm-6 mb-4">
        <div class="card shadow-lg">
          <div class="card-body">
            <h5 class="card-title">Books & Magazines</h5>
            <p class="card-text">Number of Products: 120</p>
          </div>
          <div class="card-footer text-muted">
            <span class="badge badge-primary">Top 1</span>
          </div>
        </div>
      </div>

      <div class="col-md-4 col-sm-6 mb-4">
        <div class="card shadow-lg">
          <div class="card-body">
            <h5 class="card-title">Electronics</h5>
            <p class="card-text">Number of Products: 120</p>
          </div>
          <div class="card-footer text-muted">
            <span class="badge badge-secondary">Top 2</span>
          </div>
        </div>
      </div>

      <div class="col-md-4 col-sm-6 mb-4">
        <div class="card shadow-lg">
          <div class="card-body">
            <h5 class="card-title">Men's Apparel</h5>
            <p class="card-text">Number of Products: 120</p>
          </div>
          <div class="card-footer text-muted">
            <span class="badge badge-success">Top 3</span>
          </div>
        </div>
      </div>

      <div class="col-md-4 col-sm-6 mb-4">
        <div class="card shadow-lg">
          <div class="card-body">
            <h5 class="card-title">Home Appliances</h5>
            <p class="card-text">Number of Products: 120</p>
          </div>
          <div class="card-footer text-muted">
            <span class="badge badge-danger">Top 4</span>
          </div>
        </div>
      </div>

      <div class="col-md-4 col-sm-6 mb-4">
        <div class="card shadow-lg">
          <div class="card-body">
            <h5 class="card-title">Health & Personal Care</h5>
            <p class="card-text">Number of Products: 120</p>
          </div>
          <div class="card-footer text-muted">
            <span class="badge badge-primary">Top 5</span>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap JS (optional for responsive navbar, modal, etc.) -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
