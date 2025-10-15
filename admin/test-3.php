<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard Overview</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    .sidebar {
      min-height: 100vh;
      position: fixed;
      top: 0;
      left: 0;
      width: 250px;
      background-color: #343a40;
      padding-top: 20px;
    }
    .sidebar a {
      color: #ddd;
      text-decoration: none;
      font-size: 1.1rem;
      padding: 12px 16px;
      display: block;
      transition: background-color 0.3s;
    }
    .sidebar a:hover {
      background-color: #495057;
      color: #fff;
    }
    .sidebar .active {
      background-color: #007bff !important;
      color: white !important;
    }
    .main-content {
      margin-left: 250px;
      padding: 30px;
    }
    .card-stat {
      margin-bottom: 20px;
    }
  </style>
</head>
<body>

  <!-- Sidebar -->
  <div class="sidebar">
    <div class="container">
      <h4 class="text-white mb-4">Admin Panel</h4>
      <a href="#" class="active">Dashboard Overview</a>
      <a href="#">User Management</a>
      <a href="#">Product Management</a>
      <a href="#">Order Management</a>
      <a href="#">Reports</a>
      <a href="#">Settings</a>
      <a href="#">Logout</a>
    </div>
  </div>

  <!-- Main Content -->
  <div class="main-content">
    <div class="container-fluid">
      <h3 class="mb-4 text-primary">Admin Dashboard Overview</h3>

      <!-- Stats Overview -->
      <div class="row">
        <div class="col-lg-3 col-md-6">
          <div class="card card-stat text-center bg-primary text-white">
            <div class="card-body">
              <h5>Total Users</h5>
              <h2>1,230</h2>
            </div>
          </div>
        </div>
        <div class="col-lg-3 col-md-6">
          <div class="card card-stat text-center bg-success text-white">
            <div class="card-body">
              <h5>Total Products</h5>
              <h2>456</h2>
            </div>
          </div>
        </div>
        <div class="col-lg-3 col-md-6">
          <div class="card card-stat text-center bg-warning text-white">
            <div class="card-body">
              <h5>Total Orders</h5>
              <h2>1,800</h2>
            </div>
          </div>
        </div>
        <div class="col-lg-3 col-md-6">
          <div class="card card-stat text-center bg-danger text-white">
            <div class="card-body">
              <h5>Total Revenue</h5>
              <h2>$12,500</h2>
            </div>
          </div>
        </div>
      </div>

      <!-- Graph/Chart Overview -->
      <div class="row">
        <div class="col-lg-8 col-md-12">
          <div class="card">
            <div class="card-header">
              <h5>Monthly Sales Overview</h5>
            </div>
            <div class="card-body">
              <canvas id="salesChart"></canvas>
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-md-12">
          <div class="card">
            <div class="card-header">
              <h5>Latest Orders</h5>
            </div>
            <div class="card-body">
              <ul class="list-group">
                <li class="list-group-item">Order #12345 - $200 <span class="badge bg-success float-end">Completed</span></li>
                <li class="list-group-item">Order #12346 - $350 <span class="badge bg-warning float-end">Pending</span></li>
                <li class="list-group-item">Order #12347 - $100 <span class="badge bg-danger float-end">Cancelled</span></li>
                <li class="list-group-item">Order #12348 - $420 <span class="badge bg-success float-end">Completed</span></li>
              </ul>
            </div>
          </div>
        </div>
      </div>

      <!-- Product Table -->
      <div class="card mt-4">
        <div class="card-header">
          <h5>Recent Products</h5>
        </div>
        <div class="card-body">
          <table class="table table-striped">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">Product Name</th>
                <th scope="col">Category</th>
                <th scope="col">Price</th>
                <th scope="col">Status</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <th scope="row">1</th>
                <td>Smartphone X</td>
                <td>Electronics</td>
                <td>$599.99</td>
                <td><span class="badge bg-success">Active</span></td>
              </tr>
              <tr>
                <th scope="row">2</th>
                <td>Smartwatch Pro</td>
                <td>Wearables</td>
                <td>$199.99</td>
                <td><span class="badge bg-warning">Pending</span></td>
              </tr>
              <tr>
                <th scope="row">3</th>
                <td>Laptop 3000</td>
                <td>Electronics</td>
                <td>$899.99</td>
                <td><span class="badge bg-danger">Out of Stock</span></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <!-- Script for Chart.js -->
  <script>
    const ctx = document.getElementById('salesChart').getContext('2d');
    const salesChart = new Chart(ctx, {
      type: 'line',
      data: {
        labels: ['January', 'February', 'March', 'April', 'May', 'June'],
        datasets: [{
          label: 'Sales Revenue',
          data: [1500, 2000, 2500, 2200, 2700, 3000],
          borderColor: 'rgba(75, 192, 192, 1)',
          tension: 0.1
        }]
      }
    });
  </script>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
    