<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cartsy - My Profile</title>
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

    <style>
        body {
            background-color: #e0e0e0;
        }
        .profile-container {
            max-width: 900px;
            margin: 50px auto;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            display: flex;
        }
        .sidebar {
            background: #f5f5f5;
            padding: 30px;
            width: 300px;
            text-align: center;
        }
        .sidebar img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: #d4af63;
            padding: 20px;
        }
        .profile-content {
            flex: 1;
            padding: 30px;
        }
        input, select {
            background-color: #e0e0e0;
        }
        .save-btn {
            background-color: #d4af63;
            color: white;
            border: none;
            padding: 10px 30px;
            border-radius: 5px;
            cursor: pointer;
        }
        .save-btn:hover {
            background-color: #b89250;
        }
        .logout-icon {
            position: absolute;
            bottom: 20px;
            left: 20px;
            font-size: 24px;
            cursor: pointer;
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-light bg-white p-3 border-bottom">
        <div class="container-fluid">
            <a class="navbar-brand fs-3" href="#">Cartsy</a>
            <div class="d-flex align-items-center">
                <i class="bi bi-chat fs-4 me-3"></i>
                <i class="bi bi-person-circle fs-4"></i>
            </div>
        </div>
    </nav>

    <!-- Profile Section -->
    <div class="profile-container shadow">
        <!-- Sidebar -->
        <div class="sidebar position-relative">
            <img src="https://via.placeholder.com/100" alt="Profile Picture">
            <h5 class="mt-3">Trinity Adriano</h5>
            <i class="bi bi-box-arrow-right logout-icon"></i>
        </div>

        <!-- Profile Content -->
        <div class="profile-content">
            <form>
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-control" value="Trinity@gmail.com" disabled>
                </div>

                <div class="mb-3">
                    <label class="form-label">Name</label>
                    <input type="text" class="form-control" value="Trinity Adriano" disabled>
                </div>

                <div class="mb-3">
                    <label class="form-label">Gender</label>
                    <div>
                        <input type="radio" name="gender" id="female" checked>
                        <label for="female">Female</label>
                        <input type="radio" name="gender" id="male" class="ms-3">
                        <label for="male">Male</label>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Date Of Birth</label>
                    <div class="d-flex">
                        <select class="form-select me-2">
                            <option selected>May</option>
                        </select>
                        <select class="form-select me-2">
                            <option selected>29</option>
                        </select>
                        <select class="form-select">
                            <option selected>2000</option>
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Phone Number</label>
                    <input type="text" class="form-control" value="09955608023" disabled>
                </div>

                <div class="mb-3">
                    <label class="form-label">Address</label>
                    <input type="text" class="form-control" value="19 Mapagmahal St. Longos, Malolos, Bulacan" disabled>
                </div>

                <button type="button" class="save-btn">Save</button>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
