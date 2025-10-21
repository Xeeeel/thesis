<?php
session_start();
require_once __DIR__ . '/../config/db_config.php'; // Include your shared PDO connection
$pdo = db(); // Get PDO connection

// Ensure the user is logged in
if (empty($_SESSION['user_id'])) {
    header("Location: http://localhost/cartsy/login/login.php");
    exit();
}

$upload_success = false;
$upload_error = "";

// Handle file uploads
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {

    // Create uploads directory if it doesn’t exist
    $upload_dir = __DIR__ . '/uploads/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }

    // Initialize file paths
    $front_file_path = null;
    $back_file_path = null;

    // Upload front side
    if (!empty($_FILES['id_front']['name']) && $_FILES['id_front']['error'] === UPLOAD_ERR_OK) {
        $front_ext = pathinfo($_FILES['id_front']['name'], PATHINFO_EXTENSION);
        $front_new_name = uniqid('id_front_', true) . '.' . $front_ext;
        $front_target = $upload_dir . $front_new_name;

        if (move_uploaded_file($_FILES['id_front']['tmp_name'], $front_target)) {
            $front_file_path = 'uploads/' . $front_new_name;
        } else {
            $upload_error = "Error uploading front ID image.";
        }
    }

    // Upload back side
    if (!empty($_FILES['id_back']['name']) && $_FILES['id_back']['error'] === UPLOAD_ERR_OK) {
        $back_ext = pathinfo($_FILES['id_back']['name'], PATHINFO_EXTENSION);
        $back_new_name = uniqid('id_back_', true) . '.' . $back_ext;
        $back_target = $upload_dir . $back_new_name;

        if (move_uploaded_file($_FILES['id_back']['tmp_name'], $back_target)) {
            $back_file_path = 'uploads/' . $back_new_name;
        } else {
            $upload_error = "Error uploading back ID image.";
        }
    }

    // Save file paths in database if uploads succeeded
    if ($front_file_path && $back_file_path) {
        $user_id = $_SESSION['user_id'];

        try {
            $stmt = $pdo->prepare("UPDATE users SET id_front = :front, id_back = :back WHERE id = :id");
            $stmt->execute([
                ':front' => $front_file_path,
                ':back' => $back_file_path,
                ':id' => $user_id
            ]);

            // Redirect after successful upload
            header("Location: http://localhost/cartsy/index/test-9.php");
            exit();
        } catch (PDOException $e) {
            $upload_error = "Database error: " . $e->getMessage();
        }
    } elseif (empty($upload_error)) {
        $upload_error = "Please upload both front and back ID images.";
    }
}
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
    body { background-color: #140026; }
    .upload-box { flex: 1; }
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
    .upload-icon { font-size: 30px; color: #6c757d; }
    .upload-area img {
      max-width: 200px;
      max-height: 200px;
      width: 100%;
      height: auto;
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
    .nav-tabs .nav-link:hover { color: #ffc107; }
    .btn-warning { font-weight: 600; letter-spacing: 1px; }
    .badge { font-size: 0.75rem; }
    @media (max-width: 768px) {
      .d-flex.p-4 { flex-direction: column; }
      .pe-4.border-end { border: none !important; padding-right: 0 !important; margin-bottom: 2rem; }
      .ps-4 { padding-left: 0 !important; }
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
            For your protection and to prevent fraud, we need to verify your identity using an official government-issued document (such as an ID card, driver's license, or passport). Verifying your identity helps us keep your account secure and ensures any transactions or rewards go to the right person.
          </p>
          <div class="mt-4 d-flex align-items-center gap-2">
            <i class="bi bi-lock-fill text-secondary"></i>
            <small class="text-muted">All data is safely stored and encrypted</small>
          </div>
        </div>

        <!-- Right Panel -->
        <div class="ps-4" style="flex: 1;">
          <ul class="nav nav-tabs border-0 mb-3">
            <li class="nav-item"><a class="nav-link active" href="#">ID Card</a></li>
            <li class="nav-item"><a class="nav-link" href="#">Residence Permit</a></li>
            <li class="nav-item"><a class="nav-link" href="#">Passport</a></li>
            <li class="nav-item"><a class="nav-link" href="#">Driver's License</a></li>
          </ul>

          <p class="text-secondary">Take a photo of your ID card</p>

          <form method="POST" enctype="multipart/form-data">
            <div class="d-flex gap-4 flex-wrap">
              <!-- Front Upload -->
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
                  <input type="file" id="id_front" name="id_front" accept=".jpg,.jpeg,.png,.pdf" style="display:none;" onchange="previewImage(this, 'frontPreview');" />
                  <div id="frontPreview" class="mt-3" style="display: none;">
                    <img id="frontImage" src="#" alt="Front Image" />
                  </div>
                </div>
              </div>

              <!-- Back Upload -->
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
                  <input type="file" id="id_back" name="id_back" accept=".jpg,.jpeg,.png,.pdf" style="display:none;" onchange="previewImage(this, 'backPreview');" />
                  <div id="backPreview" class="mt-3" style="display: none;">
                    <img id="backImage" src="#" alt="Back Image" />
                  </div>
                </div>
              </div>
            </div>

            <?php if (!empty($upload_error)): ?>
              <div class="alert alert-danger mt-3"><?= htmlspecialchars($upload_error) ?></div>
            <?php endif; ?>

            <div class="text-center mt-4">
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
      const file = input.files[0];
      const reader = new FileReader();
      reader.onload = function(e) {
        const preview = document.getElementById(previewId);
        preview.innerHTML = `<img src="${e.target.result}" class="img-fluid rounded-3" />`;
        preview.style.display = 'block';
      };
      if (file) reader.readAsDataURL(file);
    }
  </script>
</body>
</html>
