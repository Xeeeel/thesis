<?php
// Connect to database
$servername = "localhost";
$username = "root"; // Change if needed
$password = ""; // Change if needed
$dbname = "cartsy"; // Your database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check for errors
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get search query
$search_query = isset($_GET['query']) ? $_GET['query'] : '';

// Fetch products that match the search query
$sql = "SELECT * FROM products WHERE name LIKE ?";
$stmt = $conn->prepare($sql);
$search_term = "%" . $search_query . "%";
$stmt->bind_param("s", $search_term);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Cartsy - Product Listing</title>

    <!-- Bootstrap CSS -->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
    />
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css"
    />
    <link rel="stylesheet" href="styles.css" />
    <style>
      .card {
        transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
      }
      .card:hover {
        transform: translateY(-5px);
        box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.2);
      }

      .product-card {
    transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
    border-radius: 10px;
  }
  .product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.2);
  }
  .product-image {
    width: 100%;
    height: 150px; /* Reduced height for better readability */
    object-fit: contain; /* Ensures the image fits inside without cropping */
    border-top-left-radius: 10px;
    border-top-right-radius: 10px;
    background-color: #f8f8f8; /* Optional: Adds a subtle background for images with transparency */
    }

  .product-title {
    font-size: 1rem;
    font-weight: bold;
  }
  .product-price {
    font-size: 1.1rem;
    font-weight: bold;
    color: #d9534f;
  }
  .save-btn {
    width: 100%;
  }
      .navbar {
        background-color: #f8f9fa;
      }
      .btn-dark:hover {
        background-color: #343a40;
      }
      .footer-bg {
        background-color: #f8f9fa;
      }
    </style>
  </head>
  <body>
    <!-- Navbar -->
    <nav class="navbar sticky-top navbar-light shadow-sm p-3">
      <div class="container-fluid d-flex align-items-center">
        <a class="navbar-brand fs-3" href="http://localhost/cartsy/index/test-7.php">Cartsy</a>
        <form class="d-flex flex-grow-1 mx-3">
          <div class="input-group">
            <input class="form-control" type="search" placeholder="Search" />
            <button class="btn btn-dark" type="submit">Search</button>
          </div>
        </form>
        <button class="btn btn-outline-dark me-3">Sell</button>
        <a href="saved-products.php" class="btn btn-outline-danger me-3">
          <i class="bi bi-heart-fill"></i>
        </a>
        <div>
          <i class="bi bi-chat fs-4 me-3"></i>
          <i class="bi bi-person-circle fs-4"></i>
        </div>
      </div>
    </nav>

    <div class="container-fluid mt-3">
      <div class="row">
        <!-- Sidebar -->
        <aside class="col-md-3">
          <div class="p-3 border rounded bg-white">
            <h5 class="fw-bold">Filter</h5>
            <h6 class="mt-3">Category</h6>
            <ul class="list-unstyled">
              <li>
                <a href="#" class="text-dark text-decoration-none"
                  >Home & Living</a
                >
              </li>
              <li>
                <a href="#" class="text-dark text-decoration-none"
                  >Books & Magazines</a
                >
              </li>
              <li>
                <a href="#" class="text-dark text-decoration-none"
                  >School & Office Supplies</a
                >
              </li>
              <li>
                <a href="#" class="text-dark text-decoration-none"
                  >Men’s Apparel</a
                >
              </li>
              <li>
                <a href="#" class="text-dark text-decoration-none"
                  >Laptops & Computers</a
                >
              </li>
            </ul>

            <h6 class="mt-3">Price Range</h6>
            <div class="d-flex">
              <input type="text" class="form-control me-2" placeholder="min" />
              <input type="text" class="form-control" placeholder="max" />
            </div>
            <button class="btn btn-warning mt-2 w-100">Apply</button>

            <h6 class="mt-3">Location</h6>
            <div class="d-flex">
              <input
                type="text"
                class="form-control me-2"
                placeholder="Location"
              />
            </div>

            <h6 class="mt-3">Item Condition</h6>
            <ul class="list-unstyled">
              <li>
                <a href="#" class="text-dark text-decoration-none">New Item</a>
              </li>
              <li>
                <a href="#" class="text-dark text-decoration-none">Used Item</a>
              </li>
            </ul>
          </div>
        </aside>

        <div class="col-md-9">
          <h3 class="mb-3">Search Results for "<?php echo htmlspecialchars($search_query); ?>"</h3>
          <div class="row">
            <?php
              if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                  echo '<div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                    <div class="card shadow-sm border-0">
                      <img src="/cartsy/' . htmlspecialchars($row["image_path"]) . '" class="product-image" alt="Product Image">
                      <div class="card-body">
                        <h6 class="card-title">' . htmlspecialchars($row["name"]) . '</h6>
                        <p class="text-muted small">Condition: ' . htmlspecialchars($row["condition_name"]) . '</p>
                        <p class="text-danger fw-bold">₱' . htmlspecialchars($row["price"]) . '</p>
                        <button class="btn btn-outline-dark save-btn"><i class="bi bi-heart"></i> Save</button>
                      </div>
                    </div>
                  </div>';
                }
              } else {
                echo '<p class="text-muted">No products found.</p>';
              }
            ?>
          </div>
        </div>
      </div>
    </div>

    <footer class="footer-bg text-center text-lg-start mt-5">
      <div class="container p-4">
        <div class="row">
          <div class="col-lg-6 col-md-12 mb-4">
            <h4>About Us</h4>
            <p>Welcome to Cartsy, the ultimate online marketplace that connects buyers and sellers seamlessly!</p>
          </div>
          <div class="col-lg-3 col-md-6 mb-4">
            <h5>Follow Us</h5>
            <a href="#" class="text-dark me-3"><i class="bi bi-facebook"></i></a>
            <a href="#" class="text-dark me-3"><i class="bi bi-twitter"></i></a>
            <a href="#" class="text-dark me-3"><i class="bi bi-instagram"></i></a>
          </div>
        </div>
      </div>
      <div class="text-center p-3 bg-dark text-white">© 2025 Cartsy. All Rights Reserved.</div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
      document.querySelectorAll(".save-btn").forEach((btn) => {
        btn.addEventListener("click", function () {
          this.classList.toggle("active");
          let icon = this.querySelector("i");
          icon.classList.toggle("bi-heart");
          icon.classList.toggle("bi-heart-fill");
        });
      });
    </script>
  </body>
</html>
