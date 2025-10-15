<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cartsy - Product Listing</title>
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<!-- Navbar -->
<nav class="navbar sticky-top navbar-light bg-white shadow-sm p-3">
    <div class="container-fluid">
        <!-- Brand -->
        <a class="navbar-brand fs-3" href="http://localhost/cartsy/index/test-5.php">Cartsy</a>

        <!-- Search Bar -->
        <form class="d-flex flex-grow-1 mx-3">
            <div class="input-group">
                <input class="form-control" type="search" placeholder="Search" aria-label="Search">
                <button class="btn btn-dark" type="submit">Search</button>
            </div>
        </form>

        <!-- Sell Button -->
        <button class="btn btn-outline-dark me-3">Sell</button>

        <!-- Saved Products Button -->
        <a href="http://localhost/cartsy/saved-products.php" class="btn btn-outline-danger me-3">
            <i class="bi bi-heart-fill"></i>
        </a>

        <!-- Chat & Profile -->
        <div>
            <i class="bi bi-chat fs-4 me-3"></i>
            <i class="bi bi-person-circle fs-4"></i>
        </div>
    </div>
</nav>

<!-- Page Layout -->
<div class="container-fluid mt-3">
    <div class="row">
        <!-- Sidebar -->
        <aside class="col-md-3">
            <div class="p-3 border rounded bg-white">
                <h5 class="fw-bold">Filter</h5>
                <h6 class="mt-3">Category</h6>
                <ul class="list-unstyled">
                    <li><a href="#" class="text-dark text-decoration-none">Home & Living</a></li>
                    <li><a href="#" class="text-dark text-decoration-none">Books & Magazines</a></li>
                    <li><a href="#" class="text-dark text-decoration-none">School & Office Supplies</a></li>
                    <li><a href="#" class="text-dark text-decoration-none">Men’s Apparel</a></li>
                    <li><a href="#" class="text-dark text-decoration-none">Laptops & Computers</a></li>
                </ul>

                <h6 class="mt-3">Price Range</h6>
                <div class="d-flex">
                    <input type="text" class="form-control me-2" placeholder="min">
                    <input type="text" class="form-control" placeholder="max">
                </div>
                <button class="btn btn-warning mt-2 w-100">Apply</button>

                <h6 class="mt-3">Location</h6>
                <div class="d-flex">
                    <input type="text" class="form-control me-2" placeholder="Location">
                </div>

                <h6 class="mt-3">Item Condition</h6>
                <ul class="list-unstyled">
                    <li><a href="#" class="text-dark text-decoration-none">New Item</a></li>
                    <li><a href="#" class="text-dark text-decoration-none">Used Item</a></li>
                </ul>
            </div>
        </aside>

        <!-- Product Grid -->
        <section class="col-md-9">
            <div class="row">
                <div class="col-md-4 col-sm-6 mb-4">
                    <div class="card product-card">
                        <img src="https://via.placeholder.com/200" class="card-img-top" alt="Product">
                        <div class="card-body">
                            <h6 class="card-title">Brown Leather Shoes</h6>
                            <p class="text-danger fw-bold">₱590</p>
                        </div>
                        <button class="save-btn"><i class="bi bi-heart"></i></button>
                    </div>
                </div>

                <div class="col-md-4 col-sm-6 mb-4">
                    <div class="card product-card">
                        <img src="https://via.placeholder.com/200" class="card-img-top" alt="Product">
                        <div class="card-body">
                            <h6 class="card-title">White Sneakers</h6>
                            <p class="text-danger fw-bold">₱260</p>
                        </div>
                        <button class="save-btn"><i class="bi bi-heart"></i></button>
                    </div>
                </div>

                <div class="col-md-4 col-sm-6 mb-4">
                    <div class="card product-card">
                        <img src="https://via.placeholder.com/200" class="card-img-top" alt="Product">
                        <div class="card-body">
                            <h6 class="card-title">Red Boat Shoes</h6>
                            <p class="text-danger fw-bold">₱769</p>
                        </div>
                        <button class="save-btn"><i class="bi bi-heart"></i></button>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

<!-- Footer -->
<footer class="bg-light text-center text-lg-start mt-5">
    <div class="container p-4">
        <div class="row">
            <!-- About Us -->
            <div class="col-lg-6 col-md-12 mb-4">
                <h4 style="text-align: center;">About Us</h4>
                <p style="text-align: justify">
                    Welcome to Cartsy, the ultimate online marketplace that connects buyers
                    and sellers seamlessly! At Cartsy, we provide a hassle-free platform
                    where sellers can list their products easily, and customers can reach
                    out to them directly. Our user-friendly interface ensures a smooth
                    browsing experience, allowing for secure and efficient transactions
                    without unnecessary fees or middlemen. Whether you're on the hunt for
                    the best deals or looking for a hassle-free way to sell your products,
                    Cartsy provides a trusted and convenient marketplace tailored to your
                    needs.
                </p>
            </div>

            <!-- Follow Us -->
            <div class="col-lg-3 col-md-6 mb-4">
                <h5 class="text-uppercase">Follow Us</h5>
                <a href="#" class="text-dark me-3"><i class="bi bi-facebook"></i></a>
                <a href="#" class="text-dark me-3"><i class="bi bi-twitter"></i></a>
                <a href="#" class="text-dark me-3"><i class="bi bi-instagram"></i></a>
            </div>
        </div>
    </div>
    
    <!-- Copyright -->
    <div class="text-center p-3 bg-dark text-white">
        © 2025 Cartsy. All Rights Reserved.
    </div>
</footer>


<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Toggle Save Button (Heart)
    document.querySelectorAll(".save-btn").forEach(btn => {
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
