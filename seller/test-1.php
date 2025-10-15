<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cartsy Selling Page</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

    <style>
        body {
    font-family: "Poppins", sans-serif;
    background-color: #f5f5f5;
    margin: 0;
}

.navbar { background-color: #ffffff; border-bottom: 1px solid #e0e0e0; padding: 1rem 2rem; }
        .navbar-brand { color: #343a40; font-family: "Suranna", serif; font-size: 30px; }

.sidebar {
    width: 250px;
    background: white;
    padding: 20px;
    height: 100vh;
    border-right: 1px solid #ddd;
}

.sidebar .btn {
    padding: 10px;
    text-align: left;
}

.filters label {
    font-size: 14px;
    color: #444;
}

.main-content {
    flex-grow: 1;
    display: flex;
    justify-content: center;
    align-items: center;
    background: #dcdcdc;
    height: 100vh;
}

.plane-icon {
    width: 100px;
}

.profile-icon {
    color: goldenrod;
}

    </style>
</head>
<body>

    <nav class="navbar sticky-top navbar-light">
        <div class="container-fluid">
            <!-- Brand -->
            <a class="navbar-brand fs-3" href="http://localhost/cartsy/index/test-9.php">Cartsy</a>

            <!-- Search Bar with Button Inside -->
            <form class="d-flex flex-grow-1 mx-3" action="http://localhost/cartsy/search-product/test-4.php" method="GET">
                <div class="input-group">
                    <input class="form-control" type="search" name="query" placeholder="Search" required>
                    <button class="btn btn-dark" type="submit">Search</button>
                </div>
            </form>

            <!-- Sell Button -->
            <a href="http://localhost/cartsy/seller/test-1.php" class="btn btn-outline-dark me-3">Sell</a>

            <!-- Saved Products Button -->
            <a href="http://localhost/cartsy/saved/test-6.php" class="btn btn-outline-danger me-3">
                <i class="bi bi-heart-fill"></i>
            </a>

            <!-- Chat and Profile Icons -->
            <div>
                <a href="http://localhost/cartsy/chat/conversation.php">
                    <i class="bi bi-chat fs-4 me-3"></i>
                </a>
                <a href="http://localhost/cartsy/profile/index-7.php">
                    <i class="bi bi-person-circle fs-4"></i>
                </a>
            </div>
        </div>
    </nav>

    <div class="d-flex">
        <!-- Sidebar -->
        <aside class="sidebar">
            <button class="btn btn-light w-100 text-danger fw-bold" onclick="window.location.href='redirect.php'">+ Create new listing</button>


            <button class="btn btn-secondary w-100 mt-3"> 
                <i class="bi bi-bag"></i> Your Listing
            </button>

            <div class="filters mt-3">
                <p class="text-muted d-flex justify-content-between">Filters <span class="text-danger">Clear</span></p>

                <p class="mb-1">Sort by <i class="bi bi-chevron-down"></i></p>
                <p id="status-toggle" style="cursor: pointer;">
    Status <i class="bi bi-chevron-up"></i>
</p>

<div id="status-options" style="display: block;">
    <label><input type="radio" name="status" value="all" checked> All</label><br>
    <label><input type="radio" name="status" value="available"> Available & in stock</label><br>
    <label><input type="radio" name="status" value="sold"> Sold & out of stock</label><br>
    <label><input type="radio" name="status" value="draft"> Draft</label>
</div>

<script>
    document.getElementById("status-toggle").addEventListener("click", function() {
        let statusOptions = document.getElementById("status-options");
        let arrowIcon = this.querySelector("i");

        // Toggle visibility
        if (statusOptions.style.display === "none") {
            statusOptions.style.display = "block";
            arrowIcon.classList.remove("bi-chevron-down");
            arrowIcon.classList.add("bi-chevron-up");
        } else {
            statusOptions.style.display = "none";
            arrowIcon.classList.remove("bi-chevron-up");
            arrowIcon.classList.add("bi-chevron-down");
        }
    });
</script>

            </div>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <div class="text-center">
                <img src="paper.png" alt="Paper Plane" class="plane-icon">
                <p class="text-muted">When you start selling, your listing will appear here.</p>
                <p class="text-danger fw-bold"><!-- Button to Create New Listing -->
                <a href="redirect.php" class="btn btn-primary">Create New Listing</a>

            </div>
        </main>
    </div>

</body>
</html>
