<!DOCTYPE html>
<html lang="en">
<?php
include("layout/head.php");
?>

<body>
    <?php
    include("layout/header.php"); ?>


    <main class="container-fluid">
        <!-- Results Section -->
        <h4 class="mb-4 px-3">Results</h4>
        <div id="results" class="row g-3 px-3"></div>
    </main>

    <div class="container-fluid px-3" id="studentsList">
        <div class="row g-3">
            <div class="col-12 col-md-4">
                <div class="card student-card p-3" data-block="A" data-status="due" data-name="John Doe">
                    <div class="d-flex align-items-center mb-3 justify-content-center">
                        <img src="assets/images/person.webp" alt="Peter Parker"
                            class="img-fluid rounded-circle me-3" style="width: 150px; height: 150px;">
                    </div>
                    <h5>Peter parker</h5>
                    <p>Block A, Room 101</p>
                    <p>Status: <span class="text-danger">Due</span></p>

                    <button class="btn btn-sm btn-primary view-details">View Details</button>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="card student-card p-3" data-block="B" data-status="paid" data-name="Jane Smith">
                    <div class="d-flex align-items-center mb-3 justify-content-center">
                        <img src="assets/images/person.webp" alt="Peter Parker" class="img-fluid rounded-circle me-3"
                            style="width: 150px; height: 150px;">
                    </div>
                    <h5>Davincy</h5>
                    <p>Block B, Room 202</p>
                    <p>Status: <span class="text-success">Paid</span></p>
                    <button class="btn btn-sm btn-primary view-details">View Details</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Student Popup -->
    <div class="overlay w-100" id="popupOverlay">
        <div class="popup">
            <span class="close" id="closePopupStudent">&times;</span>
            <h2>Add Student</h2>
            <form>
                <input type="text" placeholder="Student Name" required>
                <input type="number" placeholder="Age" required>
                <select required>
                    <option value="">Select Block</option>
                    <option value="A">Block A</option>
                    <option value="B">Block B</option>
                    <option value="C">Block C</option>
                </select>
                <label>Room</label>
                <select id="roomSelect">
                    <option value="">-- Select Room --</option>
                </select>
                <label>Bed</label>
                <select id="bedSelect">
                    <option value="">-- Select Bed --</option>
                </select>
                <label>Payment </label>
                <select id="paymentStatus">
                    <option value="">-- Select --</option>
                    <option value="cash">Cash</option>
                    <option value="online">G Pay</option>
                </select>
                <button id="saveBtnStudent">Save</button>
            </form>
        </div>
    </div>

    <?php include("layout/script.php"); ?>
</body>

</html>