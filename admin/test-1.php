<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Seller Account Approval</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    /* Optional custom styles */
    .table th, .table td {
      vertical-align: middle;
    }
    .badge-status {
      font-size: 0.875rem;
      padding: 0.4rem 0.75rem;
      border-radius: 0.25rem;
    }
  </style>
</head>
<body>
  <div class="container my-5">
    <h3 class="mb-4 text-primary">Seller Account Approval</h3>

    <!-- Filter Options -->
    <div class="mb-4">
      <label for="statusFilter" class="form-label">Filter by Status:</label>
      <select id="statusFilter" class="form-select">
        <option value="">All</option>
        <option value="pending">Pending Approval</option>
        <option value="approved">Approved</option>
        <option value="rejected">Rejected</option>
      </select>
    </div>

    <!-- Seller Account Approval Table -->
    <table class="table table-striped table-bordered">
      <thead>
        <tr>
          <th scope="col">#</th>
          <th scope="col">Seller Name</th>
          <th scope="col">Username</th>
          <th scope="col">Email</th>
          <th scope="col">Registration Date</th>
          <th scope="col">Verification Status</th>
          <th scope="col">Action</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <th scope="row">1</th>
          <td>Juan Dela Cruz</td>
          <td>@juandelacruz</td>
          <td>juan@email.com</td>
          <td>March 28, 2025</td>
          <td><span class="badge bg-warning badge-status">Pending</span></td>
          <td>
            <button class="btn btn-success btn-sm">Approve</button>
            <button class="btn btn-danger btn-sm">Reject</button>
            <button class="btn btn-info btn-sm">Request Info</button>
          </td>
        </tr>
        <tr>
          <th scope="row">2</th>
          <td>Maria Lopez</td>
          <td>@marialopez</td>
          <td>maria@email.com</td>
          <td>March 22, 2025</td>
          <td><span class="badge bg-success badge-status">Approved</span></td>
          <td>
            <button class="btn btn-primary btn-sm" disabled>Approved</button>
          </td>
        </tr>
        <tr>
          <th scope="row">3</th>
          <td>Aaron Reyes</td>
          <td>@aaronreyes</td>
          <td>aaron@email.com</td>
          <td>March 15, 2025</td>
          <td><span class="badge bg-danger badge-status">Rejected</span></td>
          <td>
            <button class="btn btn-warning btn-sm" disabled>Rejected</button>
          </td>
        </tr>
        <!-- Add more rows as needed -->
      </tbody>
    </table>
  </div>

  <script>
    // Optional JavaScript for filtering (filter the table based on status)
    const filter = document.getElementById('statusFilter');
    filter.addEventListener('change', function () {
      const rows = document.querySelectorAll('table tbody tr');
      rows.forEach(row => {
        const status = row.querySelector('td:nth-child(6)').innerText.toLowerCase();
        if (this.value === '' || status.includes(this.value)) {
          row.style.display = '';
        } else {
          row.style.display = 'none';
        }
      });
    });
  </script>
</body>
</html>
