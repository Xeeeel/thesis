<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Seller Product Approval</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    /* Optional custom styles */
    .table th, .table td {
      vertical-align: middle;
    }
    .badge-status {
      font-size: 0.875rem;
      padding: 0.4rem 0.75rem;
      border-radius: 0.25rem;
    }
    .search-bar {
      margin-bottom: 1rem;
    }
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
    .main-content {
      margin-left: 250px;
      padding: 30px;
    }
    .active {
      background-color: #007bff !important;
      color: white !important;
    }
  </style>
</head>
<body>
  <!-- Sidebar -->
  <div class="sidebar">
    <div class="container">
      <h4 class="text-white mb-4">Admin Panel</h4>
      <a href="#" class="active">Seller Account Approval</a>
      <a href="#">Product Approval</a>
      <a href="#">Reports</a>
      <a href="#">Settings</a>
      <a href="#">Logout</a>
    </div>
  </div>

  <!-- Main Content -->
  <div class="main-content">
    <div class="container my-5">
      <h3 class="mb-4 text-primary">Seller Product Approval</h3>

      <!-- Search Bar -->
      <div class="mb-4">
        <input type="text" id="searchBar" class="form-control search-bar" placeholder="Search for products, sellers, or categories..." />
      </div>

      <!-- Filter Options -->
      <div class="mb-4">
        <label for="statusFilter" class="form-label">Filter by Status:</label>
        <select id="statusFilter" class="form-select">
          <option value="">All</option>
          <option value="pending">Pending Approval</option>
          <option value="approved">Approved</option>
          <option value="rejected">Rejected</option>
        </select>
      </div>

      <!-- Seller Product Approval Table -->
      <table class="table table-striped table-bordered">
        <thead>
          <tr>
            <th scope="col">#</th>
            <th scope="col">Product Name</th>
            <th scope="col">Seller</th>
            <th scope="col">Category</th>
            <th scope="col">Price</th>
            <th scope="col">Status</th>
            <th scope="col">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <th scope="row">1</th>
            <td>Smartphone X</td>
            <td>Juan Dela Cruz</td>
            <td>Electronics</td>
            <td>$599.99</td>
            <td><span class="badge bg-warning badge-status">Pending</span></td>
            <td>
              <button class="btn btn-success btn-sm">Approve</button>
              <button class="btn btn-danger btn-sm">Reject</button>
            </td>
          </tr>
          <tr>
            <th scope="row">2</th>
            <td>Smartwatch Pro</td>
            <td>Maria Lopez</td>
            <td>Wearables</td>
            <td>$199.99</td>
            <td><span class="badge bg-success badge-status">Approved</span></td>
            <td>
              <button class="btn btn-primary btn-sm" disabled>Approved</button>
            </td>
          </tr>
          <tr>
            <th scope="row">3</th>
            <td>Laptop 3000</td>
            <td>Aaron Reyes</td>
            <td>Electronics</td>
            <td>$899.99</td>
            <td><span class="badge bg-danger badge-status">Rejected</span></td>
            <td>
              <button class="btn btn-warning btn-sm" disabled>Rejected</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>

  <script>
    // Optional JavaScript for filtering (filter the table based on status)
    const filter = document.getElementById('statusFilter');
    const searchBar = document.getElementById('searchBar');

    filter.addEventListener('change', function () {
      filterTable();
    });

    searchBar.addEventListener('input', function () {
      filterTable();
    });

    function filterTable() {
      const rows = document.querySelectorAll('table tbody tr');
      const searchValue = searchBar.value.toLowerCase();
      const filterValue = filter.value.toLowerCase();

      rows.forEach(row => {
        const productName = row.querySelector('td:nth-child(2)').innerText.toLowerCase();
        const sellerName = row.querySelector('td:nth-child(3)').innerText.toLowerCase();
        const category = row.querySelector('td:nth-child(4)').innerText.toLowerCase();
        const status = row.querySelector('td:nth-child(6)').innerText.toLowerCase();

        const matchesSearch = productName.includes(searchValue) || sellerName.includes(searchValue) || category.includes(searchValue);
        const matchesStatus = filterValue === '' || status.includes(filterValue);

        if (matchesSearch && matchesStatus) {
          row.style.display = '';
        } else {
          row.style.display = 'none';
        }
      });
    }
  </script>
</body>
</html>
