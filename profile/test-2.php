<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cartsy Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #e0e0e0;
        }
        .profile-container {
            max-width: 900px;
            background-color: white;
            margin: 50px auto;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .profile-sidebar {
            background-color: #f0f0f0;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
        }
        .profile-sidebar img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background-color: #d1ad51;
            padding: 20px;
        }
        .btn-save {
            background-color: #d1ad51;
            color: white;
            border: none;
        }
        .btn-save:hover {
            background-color: #b8953f;
        }
    </style>
</head>
<body>
    <div class="container profile-container">
        <div class="row">
            <div class="col-md-4 profile-sidebar">
                <img src="https://via.placeholder.com/100" alt="Profile Picture">
                <h5 class="mt-3">Trinity Adriano</h5>
            </div>
            <div class="col-md-8">
                <h4>My Profile</h4>
                <form>
                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input type="email" class="form-control" value="Trinity@gmail.com" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" class="form-control" value="Trinity Adriano" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Gender</label><br>
                        <input type="radio" name="gender" checked> Female
                        <input type="radio" name="gender" class="ms-3"> Male
                    </div>
                    <div class="mb-3 row">
                        <label class="form-label">Date of Birth</label>
                        <div class="col-md-4">
                            <select class="form-select">
                                <option selected>May</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <select class="form-select">
                                <option selected>29</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <select class="form-select">
                                <option selected>2000</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Phone Number</label>
                        <input type="text" class="form-control" value="09955608023" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Address</label>
                        <input type="text" class="form-control" value="19 Mapagmahal St. Longos, Malolos, Bulacan" readonly>
                    </div>
                    <button type="button" class="btn btn-save">Save</button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>