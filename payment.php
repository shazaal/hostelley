<!DOCTYPE html>
<html lang="en">
<?php
include("layout/head.php");
?>
  <body>
    <?php
    include("layout/header.php"); ?> 
<div class="container-fluid mt-4 px-3">
  <!-- Header -->
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="fw-bold text-primary"><i class="bi bi-receipt"></i> Payment History</h2>
    <a href="admin.php" class="btn btn-primary btn-sm"><i class="bi bi-arrow-left"></i> Back</a>
  </div>

  <!-- Search & Filter -->
  <div class="row g-2 mb-3">
    <div class="col-12 col-md-8">
      <input type="text" class="form-control" placeholder="Search student...">
    </div>
    <div class="col-12 col-md-4">
      <input type="date" class="form-control">
    </div>
  </div>

  <!-- Payment History Table -->
  <div class="table-responsive shadow-sm rounded-3">
    <table class="table table-hover align-middle mb-0">
      <thead class="bg-primary text-white">
        <tr>
          <th>Student</th>
          <th>Block & Room</th>
          <th>Method</th>
          <th>Amount</th>
          <th>Date</th>
          <th>Receipt</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>binu kuttan</td>
          <td>Block A, Room 101</td>
          <td><span class="badge bg-primary">GPay</span></td>
          <td class="fw-bold">₹5000</td>
          <td>18 Aug 2025</td>
          <td><a href="#" class="btn btn-sm btn-primary"><i class="bi bi-eye"></i> View</a></td>
        </tr>
        <tr>
          <td>appu kuttan</td>
          <td>Block B, Room 203</td>
          <td><span class="badge bg-secondary">Cash</span></td>
          <td class="fw-bold">₹5000</td>
          <td>15 Aug 2025</td>
          <td><a href="#" class="btn btn-sm btn-primary"><i class="bi bi-eye"></i> View</a></td>
        </tr>
      </tbody>
    </table>
  </div>
</div>

    <?php include("layout/script.php"); ?>
  <?php include("layout/popup.php"); ?>

</body>
</html>