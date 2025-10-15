<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carts UI</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .navbar-brand {
            font-weight: bold;
        }

        .promo-banner {
            background: linear-gradient(to right, #000, rgba(0, 0, 0, 0.6)), url('https://via.placeholder.com/1920x400');
            background-size: cover;
            background-position: center;
            color: white;
            text-align: center;
            padding: 60px 20px;
        }

        .promo-banner h2 {
            font-size: 2rem;
            margin-bottom: 15px;
        }

        .promo-banner button {
            background-color: gold;
            border: none;
            font-weight: bold;
            padding: 10px 20px;
            border-radius: 25px;
        }

        .category-card img {
            border-radius: 10px;
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .category-title {
            text-align: center;
            font-weight: 600;
            margin-top: 10px;
        }

        .category-container {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
            gap: 20px;
            padding: 40px 20px;
        }

        .top-bar i {
            margin-left: 20px;
            font-size: 18px;
            cursor: pointer;
        }
    </style>
</head>

<body>

    <!-- Navbar -->
    <nav class="navbar navbar-light bg-light px-3">
        <div class="row">
            <div class="col-1">
                <a class="navbar-brand brand" href="#">Cartsy</a>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <input type="text" class="form-control d-inline-block" placeholder="Search" style="width: 300px;">
                <i class="fas fa-search"></i>
            </div>
        </div>
        
        <div>
            
        </div>
    </nav>

    <!-- Banner -->
    <div class="promo-banner">
        <h2>Upgrade your lifestyle with top-quality items at unbeatable prices.</h2>
        <p>From stylish footwear and fitness gear to essential home tools, we’ve got something for everyone.</p>
        <button>Shop now</button>
    </div>

    <!-- Featured Categories -->
    <section class="category-container">
        <div class="card category-card" style="width: 15rem;">
            <img src="https://via.placeholder.com/300x200" alt="Home & Living">
            <div class="category-title">Home & Living</div>
        </div>
        <div class="card category-card" style="width: 15rem;">
            <img src="https://via.placeholder.com/300x200" alt="Books & Magazines">
            <div class="category-title">Books & Magazines</div>
        </div>
        <div class="card category-card" style="width: 15rem;">
            <img src="https://via.placeholder.com/300x200" alt="Women's Apparel">
            <div class="category-title">Woman’s Apparel</div>
        </div>
        <div class="card category-card" style="width: 15rem;">
            <img src="https://via.placeholder.com/300x200" alt="Home Appliances">
            <div class="category-title">Home Appliances</div>
        </div>
        <div class="card category-card" style="width: 15rem;">
            <img src="https://via.placeholder.com/300x200" alt="Laptops & Computers">
            <div class="category-title">Laptops & Computers</div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
