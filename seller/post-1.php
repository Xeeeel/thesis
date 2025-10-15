<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cartsy - Item for Sale</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Suranna&display=swap" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .navbar {
            background-color: #ffffff;
            border-bottom: 1px solid #e0e0e0;
            padding: 1rem 2rem;
        }
        .navbar-brand {
            font-family: "Suranna", serif;
            font-size: 30px;
            color: #343a40;
        }
        .sidebar, .preview-box {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .btn-custom {
            background-color: #d4af37;
            border: none;
            transition: 0.3s;
        }
        .btn-custom:hover { background-color: #b8962e; }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg sticky-top navbar-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Cartsy</a>
            <form class="d-flex flex-grow-1 mx-3" action="#" method="GET">
                <div class="input-group">
                    <input class="form-control" type="search" name="query" placeholder="Search" required>
                    <button class="btn btn-dark" type="submit">Search</button>
                </div>
            </form>
            <button class="btn btn-outline-dark me-3">Sell</button>
            <a href="#" class="btn btn-outline-danger me-3"><i class="bi bi-heart-fill"></i></a>
            <i class="bi bi-chat fs-4 me-3"></i>
            <i class="bi bi-person-circle fs-4"></i>
        </div>
    </nav>
    <div class="container mt-4">
        <div class="row g-4">
            <div class="col-lg-4">
                <div class="sidebar">
                    <h4>Item for sale</h4>
                    <div class="d-flex align-items-center mb-3">
                        <img src="https://via.placeholder.com/50" class="rounded-circle me-2" alt="User">
                        <div><strong>John Doe</strong><br><small>Listing to Cartsy</small></div>
                    </div>
                    <div class="mb-3 border p-4 text-center bg-light rounded">
                        <p class="mb-0">Add Photos</p>
                    </div>
                    <form>
                        <input type="text" class="form-control mb-3" placeholder="Title">
                        <input type="number" class="form-control mb-3" placeholder="Price">
                        <select class="form-select mb-3"><option>Category</option></select>
                        <select class="form-select mb-3"><option>Condition</option></select>
                        <input type="text" class="form-control mb-3" placeholder="Location">
                        <select class="form-select mb-3"><option>Deal Option</option></select>
                        <textarea class="form-control mb-3" placeholder="Enter product description" rows="4"></textarea>
                    </form>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="preview-box">
                    <h5>Preview</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <img src="https://via.placeholder.com/300" class="img-fluid rounded" alt="Product">
                        </div>
                        <div class="col-md-6">
                            <h4>COROLLA Sneakers</h4>
                            <h5 class="text-danger">₱265</h5>
                            <p>No local taxes included</p>
                            <button class="btn btn-warning btn-custom mb-2"><i class="bi bi-chat-dots"></i> Message</button>
                            <button class="btn btn-dark mb-2"><i class="bi bi-flag"></i> Report</button>
                            <h6 class="mt-3">Product Description</h6>
                            <p>Heel Height: 2cm | Colors: Beige/Black | Weight: 640g</p>
                            <h6>Seller Information</h6>
                            <div class="d-flex align-items-center">
                                <img src="https://via.placeholder.com/40" class="rounded-circle me-2" alt="User">
                                <strong>John Doe</strong>
                            </div>
                        </div>
                    </div>
                    <button class="btn btn-warning btn-custom mt-3 w-100">Publish</button>
                </div>
            </div>
        </div>
    </div>
</body>
</html>