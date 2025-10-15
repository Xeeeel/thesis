<?php
// Start session
session_start();

// Database connection
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'cartsy';

$conn = new mysqli($host, $user, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function getProductImage($conn, $product_id) {
  $imgQuery = "SELECT image_path FROM product_images WHERE product_id = '$product_id' LIMIT 1";
  $imgResult = mysqli_query($conn, $imgQuery);
  if ($img = mysqli_fetch_assoc($imgResult)) {
    return $img['image_path'];
  }
  return 'default-image.png'; // fallback image
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Seller Product Approval</title>
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
    .product-img {
      width: 40px;
      height: 40px;
      object-fit: cover;
      border-radius: 5px;
      margin-right: 10px;
    }
  </style>
</head>
<body>
  <nav class="navbar sticky-top navbar-light">
    <div class="container-fluid">
      <a class="navbar-brand fs-3" href="#">Cartsy</a>
    </div>
  </nav>

  <div class="sidebar d-flex flex-column">
    <h4 class="sidebar-title px-4 mb-3 fs-4 text-dark">
      Admin Dashboard
    </h4>
    <a href="admin.php">🛍️ Sellers Account Approval</a>
    <a href="#"  class="active">👤 Sellers Product Approval</a>
    <a href="report.php">
      ❗ Customer Report
    </a>
    <a href="login.php" class="logout-icon d-flex align-items-center text-danger">
      <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-box-arrow-left me-2" viewBox="0 0 16 16">
        <path fill-rule="evenodd" d="M10 15a1 1 0 0 0 1-1v-2h-1v2H2V2h8v2h1V2a1 1 0 0 0-1-1H2a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h8z"/>
        <path fill-rule="evenodd" d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 1 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3z"/>
      </svg>
      Logout
    </a>
    
  </div>

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

      <label for="searchBar" class="form-label">Search Products:</label>
      <input type="text" id="searchBar" class="form-control mb-3" placeholder="Search by Product Name, Seller, or Category">

      <div class="table-responsive">
        <table class="table table-hover table-bordered bg-white">
          <thead class="table-light">
            <tr>
              <th>#</th>
              <th>Product</th>
              <th>Seller</th>
              <th>Category</th>
              <th>Price</th>
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody id="productTable">
            <?php
              $query = "SELECT p.*, u.name FROM products p 
                        JOIN users u ON p.seller_id = u.id 
                        ORDER BY p.product_id DESC";
              $result = mysqli_query($conn, $query);
              $count = 1;

              while ($row = mysqli_fetch_assoc($result)) {
                $image = getProductImage($conn, $row['product_id']);
                $status = strtolower($row['product_status']);
                $badgeClass = match ($status) {
                  'approved' => 'badge-approved',
                  'rejected' => 'badge-rejected',
                  default => 'badge-pending',
                };
                echo "<tr>
                        <td>{$count}</td>
                        <td><img src='/cartsy/seller/{$image}' alt='' class='product-img'> {$row['product_name']}</td>
                        <td>{$row['name']}</td>
                        <td>{$row['category']}</td>
                        <td>₱{$row['price']}</td>
                        <td><span class='badge {$badgeClass}'>" . ucfirst($status) . "</span></td>
                        <td><a href='product_review.php?id={$row['product_id']}' class='btn btn-sm btn-outline-primary'>View</a></td>
                      </tr>";
                $count++;
              }
            ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <script>
    const searchInput = document.getElementById('searchBar');
    const statusFilter = document.getElementById('statusFilter');
    const tableRows = document.querySelectorAll('#productTable tr');

    function filterTable() {
      const searchTerm = searchInput.value.toLowerCase();
      const status = statusFilter.value;

      tableRows.forEach(row => {
        const productText = row.cells[1].textContent.toLowerCase();
        const sellerText = row.cells[2].textContent.toLowerCase();
        const categoryText = row.cells[3].textContent.toLowerCase();
        const statusText = row.cells[5].textContent.toLowerCase();

        const matchesSearch = productText.includes(searchTerm) || sellerText.includes(searchTerm) || categoryText.includes(searchTerm);
        const matchesStatus = !status || statusText.includes(status);

        row.style.display = matchesSearch && matchesStatus ? '' : 'none';
      });
    }

    searchInput.addEventListener('input', filterTable);
    statusFilter.addEventListener('change', filterTable);
  </script>
</body>
</html>
