<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Cartsy Product Page</title>

    <!-- Bootstrap CSS -->
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="index.css" />
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css"
    />

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Suranna&display=swap"
      rel="stylesheet"
    />

    <style>
       body {
    font-family: "Poppins", sans-serif;
    background-color: #f8f9fa;
  }

  .navbar {
    background-color: #ffffff;
    border-bottom: 1px solid #e0e0e0;
  }

  .navbar-brand {
    color: #343a40;
    font-family: "Suranna", serif;
    font-size: 30px;
  }

  .product-card {
    background-color: white;
    border-radius: 10px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    padding: 40px;
    max-width: 1200px;
    margin: 30px auto;
  }

  .image-wrapper {
  padding: 10px;
  background-color: #f5f5f5;
  border-radius: 8px;
  text-align: center;
  height: 500px;  /* Increased height for a larger image */
  display: flex;
  justify-content: center;
  align-items: center;
  overflow: hidden;
}

.product-image {
  width: 100%;
  height: 100%;  /* Use full height for better scaling */
  object-fit: contain;  /* Ensure the image maintains its aspect ratio */
  border-radius: 8px;
}

  .product-title {
    font-weight: 700;
    font-size: 1.8rem;
    color: #333;
  }

  .product-price {
    color: #ff5555;
    font-weight: bold;
    font-size: 1.6rem;
    margin-top: 5px;
  }

  .product-location {
    color: #666;
    font-size: 1rem;
    margin-bottom: 15px;
  }

  .btn-warning {
    background-color: #d6a842;
    border-color: #d6a842;
  }

  .btn-warning:hover {
    background-color: #c0933a;
  }

  .details-section {
    margin-top: 30px;
  }

  .details-section h5 {
    font-size: 1.3rem;
    color: #343a40;
    font-weight: bold;
  }

  .seller-info {
    background-color: #fff3cd;
    padding: 20px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
  }

  .seller-icon {
    background-color: #fbc02d;
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 2rem;
    margin-right: 15px;
  }

  .save-heart {
    border: none;
    background: none;
    color: #ff5555;
    font-size: 1.8rem;
    padding: 5px;
    transition: transform 0.3s ease-in-out;
  }

  .save-heart:hover {
    transform: scale(1.3);
  }

  .save-heart .bi-heart-fill {
    display: none;
  }

  .save-heart.active .bi-heart {
    display: none;
  }

  .save-heart.active .bi-heart-fill {
    display: inline;
  }
    </style>
  </head>
  <body>
    <nav class="navbar navbar-light">
      <div class="container-fluid">
        <!-- Brand -->
        <a class="navbar-brand fs-3" href="#">Cartsy</a>

        <!-- Search Bar with Button Inside -->
        <form class="d-flex flex-grow-1 mx-3">
          <div class="input-group">
            <input
              class="form-control"
              type="search"
              placeholder="Search"
              aria-label="Search"
            />
            <button class="btn btn-dark" type="submit">Search</button>
          </div>
        </form>

        <!-- Sell Button -->
        <button class="btn btn-outline-dark me-3">Sell</button>

        <!-- Chat and Profile Icons -->
        <div>
          <i class="bi bi-chat fs-4 me-3"></i>
          <i class="bi bi-person-circle fs-4"></i>
        </div>
      </div>
    </nav>

    <div class="container">
      <div class="product-card">
      <?php
$servername = 'localhost';
$username = 'root';
$password = '';
$dbname = 'cartsy';

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $product_id = intval($_GET['id']);

    // Correct the column name if it's 'product_id' instead of 'id'
    $stmt = $conn->prepare("SELECT * FROM products WHERE product_id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo "
            <div class='row g-4 align-items-center'>
              <div class='col-md-6'>
                <div class='image-wrapper'>
                  <img src='" . htmlspecialchars($row['image_path']) . "' alt='Product Image' class='product-image' />
                </div>
              </div>
              <div class='col-md-6'>
                <div class='d-flex justify-content-between align-items-start'>
                  <h2 class='product-title'>" . htmlspecialchars($row['name']) . "</h2>
                  <button class='save-heart'>
                    <i class='bi bi-heart'></i><i class='bi bi-heart-fill'></i>
                  </button>
                </div>
                <p class='product-price'>₱" . htmlspecialchars($row['price']) . "</p>
                <p class='product-location'><i class='bi bi-geo-alt'></i> " . htmlspecialchars($row['location']) . "</p>

                <div class='d-flex gap-2 mb-3'>
                  <button class='btn btn-warning text-white flex-grow-1'>Message</button>
                  <button class='btn btn-dark'>Report</button>
                </div>

                <div class='details-section'>
                  <h5>Details</h5>
                  <p><strong>Condition:</strong> " . htmlspecialchars($row['condition_name']) . "</p>
                  <p><strong>Description:</strong> " . htmlspecialchars($row['description']) . "</p>
                </div>

                <div class='details-section mt-4'>
                  <h5>Seller Information</h5>
                  <div class='seller-info'>
                    <div class='seller-icon'>
                      <i class='bi bi-person'></i>
                    </div>
                    <div>
                      <span class='fw-bold'>" . htmlspecialchars($row['seller_name']) . "</span>
                      <p class='text-muted mb-0'>Active Seller</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>";
    } else {
        echo "<p>Product not found.</p>";
    }
    $stmt->close();
} else {
    echo "<p>Invalid product ID.</p>";
}

$conn->close();
?>




      </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.js"></script>

    <script>
      // JavaScript to toggle heart fill
      document
        .querySelector(".save-heart")
        .addEventListener("click", function () {
          this.classList.toggle("active");
        });
    </script>
  </body>
</html>
