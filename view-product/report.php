<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Report Seller</title>
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
    />
    <style>
      body {
        font-family: Arial, sans-serif;
        background-color: #f8f9fa;
      }
      .container {
        margin-top: 50px;
      }
      .form-container {
        background-color: white;
        border-radius: 8px;
        padding: 30px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      }
    </style>
  </head>
  <body>
    <!-- Report Form Modal (Bootstrap) -->
<div class="modal fade" id="reportModal" tabindex="-1" aria-labelledby="reportModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="reportModalLabel">Report Product</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="reportForm" action="submit_report.php" method="POST">
          
          <!-- Product Information -->
          <div class="mb-3">
            <label for="product_name" class="form-label">Product</label>
            <input type="text" class="form-control" id="product_name" name="product_name" value="<?php echo htmlspecialchars($product['product_name']); ?>" readonly>
          </div>

          <!-- Reason for Reporting -->
          <div class="mb-3">
            <label class="form-label">Reason for Reporting</label>
            <div class="form-check">
              <input class="form-check-input" type="radio" name="report_reason" value="Fraud" required>
              <label class="form-check-label">Fraud/Scam</label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="radio" name="report_reason" value="Item Not Received">
              <label class="form-check-label">Item Not Received</label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="radio" name="report_reason" value="Item Not As Described">
              <label class="form-check-label">Item Not As Described</label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="radio" name="report_reason" value="Poor Communication">
              <label class="form-check-label">Poor Communication</label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="radio" name="report_reason" value="Other" id="otherReason" onchange="toggleOtherReasonField()">
              <label class="form-check-label">Other</label>
            </div>
          </div>

          <!-- Additional Description (only shown if "Other" is selected) -->
          <div id="otherReasonField" class="mb-3" style="display: none;">
            <label for="other_description" class="form-label">Describe the issue</label>
            <textarea class="form-control" id="other_description" name="other_description" rows="3" placeholder="Provide details about the issue"></textarea>
          </div>

          <!-- Buyer Information (Hidden or pre-filled) -->
          <input type="hidden" id="buyer_id" name="buyer_id" value="1"> <!-- Replace with dynamic Buyer ID -->

          <!-- Product ID (Hidden) -->
          <input type="hidden" id="product_id" name="product_id" value="<?php echo $product['product_id']; ?>">

          <!-- Submit Button -->
          <div class="d-flex justify-content-end mt-3">
            <button type="submit" class="btn btn-danger">Submit Report</button>
          </div>

        </form>
      </div>
    </div>
  </div>
</div>

<!-- Button to Trigger Report Form -->
<button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#reportModal">Report Product</button>

<!-- Bootstrap JS & jQuery -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
  // Toggle the "Other" reason description field
  function toggleOtherReasonField() {
    var otherReasonField = document.getElementById("otherReasonField");
    if (document.getElementById("otherReason").checked) {
      otherReasonField.style.display = "block";
    } else {
      otherReasonField.style.display = "none";
    }
  }

  // If the page reloads with "Other" already selected, show the additional description field
  window.onload = function() {
    if (document.querySelector('input[name="report_reason"]:checked')?.value === 'Other') {
      document.getElementById("otherReasonField").style.display = "block";
    }
  }
</script>

  </body>
</html>
