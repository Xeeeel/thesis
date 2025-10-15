<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cartsy - Login</title>
    <link rel="stylesheet" href="login.css">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body class="body">
    <div class="container-fluid login-page">
        <div class="row">
            <div class="col-12">
                <nav class="navbar bg-white p-3 shadow">
                    <div class="container">
                      <a class="navbar-brand fs-3" href="#">Cartsy</a>
                    </div>
                </nav>
            </div>
        </div>

        <div class="row justify-content-center align-items-center vh-100">
            <div class="col-lg-4 col-md-6 col-sm-8">
                <div class="login-box p-4 shadow-lg rounded-3">
                    <h3 class="text-center mb-4">Login</h3>

                    <form>
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" id="username" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" id="password" class="form-control">
                        </div>
                        <button type="submit" class="btn btn-dark w-100">LOGIN</button>
                    </form>

                    <p class="text-center my-3">OR</p>

                    <p class="text-center mt-3">
                        No Account? <a href="#" class="text-danger">Sign Up Here</a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
