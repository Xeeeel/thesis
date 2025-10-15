<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>My Profile</title>
    <style>
        body {
            background-color: #f8f9fa;
        }
        .profile-card {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-top: 50px;
        }
        .profile-image {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background-color: #ccc;
        }
        .btn-save {
            background-color: #FFC107;
            color: white;
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="profile-card">
            <h2 class="text-center">My Profile</h2>
            <div class="text-center mb-4">
                <div class="profile-image"></div>
                <h4>Trinity Adriano</h4>
            </div>
            <form>
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="email" class="form-control" id="username" value="Trinity@gmail.com">
                </div>
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control" id="name" value="Trinity Adriano">
                </div>
                <div class="mb-3">
                    <label class="form-label">Gender</label>
                    <div>
                        <input type="radio" id="female" name="gender" value="female">
                        <label for="female">Female</label>
                        <input type="radio" id="male" name="gender" value="male">
                        <label for="male">Male</label>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="dob" class="form-label">Date Of Birth</label>
                    <div class="row">
                        <div class="col">
                            <select class="form-select" id="month">
                                <option selected>May</option>
                                <!-- Add more months as options -->
                            </select>
                        </div>
                        <div class="col">
                            <select class="form-select" id="day">
                                <option selected>29</option>
                                <!-- Add more days as options -->
                            </select>
                        </div>
                        <div class="col">
                            <select class="form-select" id="year">
                                <option selected>2000</option>
                                <!-- Add more years as options -->
                            </select>
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="phone" class="form-label">Phone Number</label>
                    <input type="tel" class="form-control" id="phone" value="09955608023">
                </div>
                <div class="mb-3">
                    <label for="address" class="form-label">Address</label>
                    <input type="text" class="form-control" id="address" value="19 Mapagmahal St. Longos, Malolos, Bulacan">
                </div>
                <button type="submit" class="btn btn-save">Save</button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>