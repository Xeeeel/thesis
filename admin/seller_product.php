<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Seller Account Approval</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f8f9fa;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .sidebar {
      height: 100vh;
      background-color: #ffffff;
      border-right: 1px solid #dee2e6;
      padding-top: 1rem;
      position: fixed;
      width: 260px;
      transition: all 0.3s ease-in-out;
    }

    .sidebar a {
      display: flex;
      align-items: center;
      padding: 12px 20px;
      color: #495057;
      text-decoration: none;
      font-weight: 500;
      border-radius: 10px;
      margin: 5px 10px;
      transition: background-color 0.2s ease-in-out;
    }

    .sidebar a:hover,
    .sidebar a.active {
      background-color: #e9ecef;
      color: #0d6efd;
    }

    .main-content {
      margin-left: 260px;
      padding: 2rem;
    }

    .header h3 {
      color: #0d6efd;
      font-weight: 600;
    }

    .card {
      border-radius: 10px;
      box-shadow: 0 2px 12px rgba(0, 0, 0, 0.05);
      border: none;
    }

    .badge-pending {
      background-color: #fff3cd;
      color: #856404;
    }

    .badge-approved {
      background-color: #d1ecf1;
      color: #0c5460;
    }

    .badge-rejected {
      background-color: #f8d7da;
      color: #721c24;
    }

    .table th {
      background-color: #f1f1f1;
      color: #333;
    }

    .table td,
    .table th {
      vertical-align: middle;
    }

    .navbar {
      background-color: #ffffff;
      border-bottom: 1px solid #dee2e6;
      padding: 1rem 2rem;
    }

    .navbar-brand {
      color: #343a40;
      font-family: "Suranna", serif;
      font-size: 30px;
    }
  </style>
</head>
<body>
  <!-- Navbar -->
  <nav class="navbar sticky-top navbar-light">
    <div class="container-fluid">
      <a class="navbar-brand fs-3" href="#">Cartsy</a>
    </div>
  </nav>

  <!-- Sidebar -->
  <div class="sidebar d-flex flex-column">
    <h4 class="sidebar-title px-4 mb-3 fs-4 text-dark">
    Admin Dashboard
    </h4>

    <a href="#">
      🛍️ Sellers Product Approval
    </a>
    <a href="#" class="active">
      👤 Sellers Account Approval
    </a>
    <!-- Logout link at the bottom -->
    <a href="#" class="logout-icon d-flex align-items-center text-danger">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-box-arrow-left me-2" viewBox="0 0 16 16">
        <path fill-rule="evenodd" d="M10 15a1 1 0 0 0 1-1v-2h-1v2H2V2h8v2h1V2a1 1 0 0 0-1-1H2a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h8z"/>
        <path fill-rule="evenodd" d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 1 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3z"/>
        </svg>
        Logout
    </a>
  </div>

  <!-- Main Content -->
  <!-- Main Content -->
<div class="main-content">
  <div class="header mb-4">
    <h3>Sellers Product Approval</h3>
  </div>

  <div class="card p-4">
    <label for="statusFilter" class="form-label">Filter by Status:</label>
    <select id="statusFilter" class="form-select mb-3" style="max-width: 200px;">
      <option value="">All</option>
      <option value="pending">Pending</option>
      <option value="approved">Approved</option>
      <option value="rejected">Rejected</option>
    </select>

    <div class="table-responsive">
      <table class="table table-hover table-bordered bg-white">
        <thead class="table-light">
          <tr>
            <th>#</th>
            <th>Product Name</th>
            <th>Seller</th>
            <th>Category</th>
            <th>Price</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>1</td>
            <td>Smartphone X</td>
            <td>Juan Dela Cruz</td>
            <td>Electronics</td>
            <td>$599.99</td>
            <td><span class="badge badge-pending">Pending</span></td>
            <td><a href="#" class="btn btn-sm btn-outline-primary">View</a></td>
          </tr>
          <tr>
            <td>2</td>
            <td>Smartwatch Pro</td>
            <td>Maria Lopez</td>
            <td>Wearables</td>
            <td>$199.99</td>
            <td><span class="badge badge-approved">Approved</span></td>
            <td><a href="#" class="btn btn-sm btn-outline-primary">View</a></td>
          </tr>
          <tr>
            <td>3</td>
            <td>Laptop 3000</td>
            <td>Aaron Reyes</td>
            <td>Electronics</td>
            <td>$899.99</td>
            <td><span class="badge badge-rejected">Rejected</span></td>
            <td><a href="#" class="btn btn-sm btn-outline-primary">View</a></td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>

</body>
</html>
