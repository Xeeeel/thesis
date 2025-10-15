<?php
session_start();

// Database connection
$servername = "localhost";
$username = "root";  // Replace with your DB username
$password = "";      // Replace with your DB password
$dbname = "cartsy";  // Database name

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables
$upload_success = false;
$upload_error = false;

// Check if the form is submitted
if (isset($_POST['submit'])) {
    // Handle front side file upload
    if (isset($_FILES['id_front']) && $_FILES['id_front']['error'] === 0) {
        $front_name = $_FILES['id_front']['name'];
        $front_tmp_name = $_FILES['id_front']['tmp_name'];
        $front_ext = pathinfo($front_name, PATHINFO_EXTENSION);
        $front_new_name = uniqid('id_front_', true) . '.' . $front_ext;
        $front_upload_dir = 'uploads/';  // Ensure this directory exists

        // Move the uploaded file to the uploads directory
        if (move_uploaded_file($front_tmp_name, $front_upload_dir . $front_new_name)) {
            $front_file_path = $front_upload_dir . $front_new_name;
        } else {
            $upload_error = "Error uploading front file.";
        }
    }

    // Handle back side file upload
    if (isset($_FILES['id_back']) && $_FILES['id_back']['error'] === 0) {
        $back_name = $_FILES['id_back']['name'];
        $back_tmp_name = $_FILES['id_back']['tmp_name'];
        $back_ext = pathinfo($back_name, PATHINFO_EXTENSION);
        $back_new_name = uniqid('id_back_', true) . '.' . $back_ext;
        $back_upload_dir = 'uploads/';  // Ensure this directory exists

        // Move the uploaded file to the uploads directory
        if (move_uploaded_file($back_tmp_name, $back_upload_dir . $back_new_name)) {
            $back_file_path = $back_upload_dir . $back_new_name;
        } else {
            $upload_error = "Error uploading back file.";
        }
    }

    // Assuming user is logged in, get user ID from session
    $user_id = $_SESSION['id'];  // Make sure user_id is stored in session after login

    // Save file paths in the database
    if (isset($front_file_path) && isset($back_file_path)) {
        $sql = "UPDATE users SET id_front = '$front_file_path', id_back = '$back_file_path' WHERE id = '$user_id'";

        if ($conn->query($sql) === TRUE) {
            // Redirect to the new page after successful upload
            header("Location: http://localhost/cartsy/index/test-9.php");
            exit();  // Always call exit() after header() to stop further script execution
        } else {
            $upload_error = "Error saving to database: " . $conn->error;
        }
    }

}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Verification - Cartsy</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
  <style>
    body {
      background-color: #140026;
    }

    .upload-box {
      flex: 1;
    }

    .upload-area {
      border: 2px dashed #ccc;
      border-radius: 10px;
      padding: 30px 20px;
      background-color: #f8f9fa;
      cursor: pointer;
      transition: border-color 0.3s, background-color 0.3s;
    }

    .upload-area:hover {
      border-color: #ffc107;
      background-color: #fffbe6;
    }

    .upload-icon {
      font-size: 30px;
      color: #6c757d;
    }

    /* Ensure consistent image size for uploaded previews */
.upload-area img {
  max-width: 200px;  /* Set the max width */
  max-height: 200px; /* Set the max height */
  width: 100%;       /* Ensure the width is 100% of the container */
  height: auto;      /* Maintain aspect ratio */
  border-radius: 8px;
}


    .nav-tabs .nav-link.active {
      border: none;
      border-bottom: 3px solid #ffc107;
      background-color: transparent;
      color: #000;
      font-weight: 600;
    }

    .nav-tabs .nav-link {
      border: none;
      color: #6c757d;
      font-weight: 500;
    }

    .nav-tabs .nav-link:hover {
      color: #ffc107;
    }

    .btn-warning {
      font-weight: 600;
      letter-spacing: 1px;
    }

    .badge {
      font-size: 0.75rem;
    }

    @media (max-width: 768px) {
      .d-flex.p-4 {
        flex-direction: column;
      }
      .pe-4.border-end {
        border: none !important;
        padding-right: 0 !important;
        margin-bottom: 2rem;
      }
      .ps-4 {
        padding-left: 0 !important;
      }
    }
  </style>
</head>
<body>
  <nav class="navbar bg-white px-4 py-2">
    <span class="navbar-brand mb-0 h1 fw-semibold">Cartsy</span>
  </nav>

  <div class="container-fluid py-5 px-4">
    <div class="d-flex justify-content-center">
      <div class="bg-light rounded-3 d-flex flex-wrap p-4" style="width: 100%; max-width: 1000px;">
        
        <!-- Left Panel -->
        <div class="pe-4 border-end" style="width: 100%; max-width: 400px;">
          <h4 class="fw-bold">Verification</h4>
          <p class="mt-3 text-secondary" style="font-size: 0.95rem;">
            For your protection and to prevent fraud, we need to verify your identity using an official government-issued document (such as an ID card, driver's license, or passport). Verifying your identity helps us keep your account secure, ensures you can recover it if necessary, and makes certain that any gifts or rewards reach the correct address.
          </p>
          <div class="mt-4 d-flex align-items-center gap-2">
            <i class="bi bi-lock-fill text-secondary"></i>
            <small class="text-muted">All data is safely stored and encrypted</small>
          </div>
        </div>

        <!-- Right Panel -->
        <div class="ps-4" style="flex: 1;">
          <!-- Tabs -->
          <ul class="nav nav-tabs border-0 mb-3">
            <li class="nav-item">
              <a class="nav-link active" data-bs-toggle="tab" href="#">ID card</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" data-bs-toggle="tab" href="#">Residence permit</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" data-bs-toggle="tab" href="#">Passport</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" data-bs-toggle="tab" href="#">Driver's License</a>
            </li>
          </ul>

          <p class="text-secondary">Take a photo of your ID card</p>

          <form action="" method="post" enctype="multipart/form-data">
            <div class="d-flex gap-4 flex-wrap">
              <!-- Front Side Upload -->
              <div class="upload-box text-center">
                <p class="fw-semibold">Front Side</p>
                <div class="upload-area" onclick="document.getElementById('id_front').click();">
                  <div class="upload-icon"><i class="bi bi-paperclip"></i></div>
                  <p class="small text-muted mb-1">Drop file here or Upload</p>
                  <div class="file-types d-flex justify-content-center gap-2">
                    <span class="badge bg-light text-dark">PNG</span>
                    <span class="badge bg-light text-dark">JPG</span>
                    <span class="badge bg-light text-dark">PDF</span>
                  </div>
                  <!-- Hidden File Input -->
                  <input type="file" id="id_front" name="id_front" accept=".jpg,.jpeg,.png,.pdf" style="display:none;" onchange="previewImage(this, 'frontPreview');" />
                  <!-- Image Preview -->
                  <div id="frontPreview" class="mt-3" style="display: none;">
                    <img id="frontImage" src="#" alt="Front Image" class="img-fluid" style="max-width: 100%; border-radius: 8px;" />
                  </div>
                </div>
              </div>

              <!-- Back Side Upload -->
              <div class="upload-box text-center">
                <p class="fw-semibold">Back Side</p>
                <div class="upload-area" onclick="document.getElementById('id_back').click();">
                  <div class="upload-icon"><i class="bi bi-paperclip"></i></div>
                  <p class="small text-muted mb-1">Drop file here or Upload</p>
                  <div class="file-types d-flex justify-content-center gap-2">
                    <span class="badge bg-light text-dark">PNG</span>
                    <span class="badge bg-light text-dark">JPG</span>
                    <span class="badge bg-light text-dark">PDF</span>
                  </div>
                  <!-- Hidden File Input -->
                  <input type="file" id="id_back" name="id_back" accept=".jpg,.jpeg,.png,.pdf" style="display:none;" onchange="previewImage(this, 'backPreview');" />
                  <!-- Image Preview -->
                  <div id="backPreview" class="mt-3" style="display: none;">
                    <img id="backImage" src="#" alt="Back Image" class="img-fluid" style="max-width: 100%; border-radius: 8px;" />
                  </div>
                </div>
              </div>
            </div>

            <?php if ($upload_success): ?>
              <div class="alert alert-success mt-3">Files uploaded successfully!</div>
            <?php elseif ($upload_error): ?>
              <div class="alert alert-danger mt-3"><?php echo $upload_error; ?></div>
            <?php endif; ?>

            <div class="text-center mt-4">
              <!-- Submit button inside the form -->
              <button class="btn btn-warning px-5 py-2 shadow" type="submit" name="submit">SUBMIT</button>
            </div>
          </form>
        </div>

      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    function previewImage(input, previewId) {
      var file = input.files[0];
      var reader = new FileReader();

      reader.onload = function(e) {
        var preview = document.getElementById(previewId);
        var img = document.createElement('img');
        img.src = e.target.result;

        // Apply CSS class that controls image size
        img.classList.add('img-fluid');

        // Show the preview
        preview.innerHTML = '';  // Clear previous preview
        preview.appendChild(img);
        preview.style.display = 'block';
      }

      if (file) {
        reader.readAsDataURL(file);
      }
    }
  </script>
</body>
</html>
