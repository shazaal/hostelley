<?php
include('layout/head.php');
include('layout/header.php');
include("assets/sqlConnector/sqlConnection.php");

// Fetch all data in a structured way
$hostel_data = [];
$block_res = $con->query("SELECT * FROM tb_block ORDER BY bl_name ASC");
while ($block = $block_res->fetch_assoc()) {
    $block_id = $block['id'];
    $block['rooms'] = [];

    $room_res = $con->query("SELECT * FROM tb_rooms WHERE block_id = $block_id ORDER BY id ASC");
    while ($room = $room_res->fetch_assoc()) {
        $room_id = $room['id'];
        $room['beds'] = [];

        $bed_res = $con->query("SELECT * FROM tb_bed WHERE room_id = $room_id ORDER BY id ASC");
        while ($bed = $bed_res->fetch_assoc()) {
            $room['beds'][] = $bed;
        }
        $block['rooms'][] = $room;
    }
    $hostel_data[] = $block;
}

// Fetch data for dropdowns
$blocks_dropdown = [];
$block_res_dropdown = $con->query("SELECT id, bl_name FROM tb_block ORDER BY bl_name ASC");
while ($row = $block_res_dropdown->fetch_assoc()) {
    $blocks_dropdown[] = $row;
}

$rooms_dropdown = [];
$room_res_dropdown = $con->query("SELECT r.id, r.room_no, b.bl_name FROM tb_rooms r JOIN tb_block b ON r.block_id = b.id ORDER BY b.bl_name, r.id ASC");
while ($row = $room_res_dropdown->fetch_assoc()) {
    $rooms_dropdown[] = $row;
}
?>

<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
    /* Custom styles to complement Bootstrap */
    body {
        background-color: #f8f9fa;
    }

    .accordion-button:not(.collapsed) {
        color: #0c63e4;
        background-color: #e7f1ff;
    }

    .delete-btn {
        background: none;
        border: none;
        cursor: pointer;
        padding: 5px;
    }

    .delete-btn svg {
        width: 20px;
        height: 20px;
        fill: #dc3545;
        transition: transform 0.2s;
    }

    .delete-btn:hover svg {
        transform: scale(1.1);
    }

    /* --- SCROLLING FIX --- */
    /* This makes each column scroll independently if its content is too long */
    .col-lg-4,
    .col-lg-8 {
        max-height: 58vh;
        overflow-y: auto;
    }
</style>

    
<div class="container-fluid mt-4">
    <h2>Room Settings</h2>

    <div class="row">
        <!-- Left Column for Adding Items -->
        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h4>Add New Block</h4>
                </div>
                <div class="card-body">
                    <form id="addBlockForm" method="post">
                        <div class="mb-3">
                            <label for="blockName" class="form-label">Block Name:</label>
                            <input type="text" class="form-control" name="blockName" id="blockName" required>
                        </div>
                        <button type="submit" class="btn btn-success w-100">Save Block</button>
                    </form>
                </div>
            </div>
            <div class="card mb-4">
                <div class="card-header">
                    <h4>Add Rooms to Block</h4>
                </div>
                <div class="card-body">
                    <form id="addRoomForm" method="post">
                        <div class="mb-3">
                            <label for="block_id" class="form-label">Select Block:</label>
                            <select name="block_id" id="block_id" class="form-select" required>
                                <option value="">Choose...</option>
                                <?php foreach ($blocks_dropdown as $b): ?>
                                    <option value="<?= $b['id'] ?>"><?= htmlspecialchars($b['bl_name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="room_count" class="form-label">Number of Rooms:</label>
                            <input type="number" class="form-control" name="room_count" id="room_count" min="1"
                                placeholder="Number of Rooms" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Add Rooms</button>
                    </form>
                </div>
            </div>
            <div class="card mb-4">
                <div class="card-header">
                    <h4>Add Beds to Room</h4>
                </div>
                <div class="card-body">
                    <form id="addBedForm" method="post">
                        <div class="mb-3">
                            <label for="room_id" class="form-label">Select Room:</label>
                            <select name="room_id" id="room_id" class="form-select" required>
                                <option value="">Choose...</option>
                                <?php foreach ($rooms_dropdown as $r): ?>
                                    <option value='<?= $r['id'] ?>'>Block <?= htmlspecialchars($r['bl_name']) ?> - Room
                                        <?= htmlspecialchars($r['room_no']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
       
            
                        <label for="bed_count" class="form-label">Number of Beds:</label>
                            <input type="number" class="form-control" name="bed_count" id="bed_count" min="1"
                                placeholder="Number of Beds" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Add Beds</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Right Column for Managing Hierarchy -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h4>Manage Hostel Structure</h4>
                </div>
                <div class="card-body">
                    <div class="accordion" id="hostelAccordion">
                        <?php foreach ($hostel_data as $block): ?>
                            <div class="accordion-item block-item">
                                <!-- CORRECTED HEADER STRUCTURE -->
                                <h2 class="accordion-header d-flex align-items-center"
                                    id="heading-block-<?= $block['id'] ?>">
                                    <button class="accordion-button collapsed flex-grow-1" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#collapse-block-<?= $block['id'] ?>">
                                        <strong>Block <?= htmlspecialchars($block['bl_name']) ?></strong>
                                    </button>
                                    <button class="delete-btn p-3" data-type="block" data-id="<?= $block['id'] ?>"
                                        title="Delete Block"><svg viewBox="0 0 24 24">
                                            <path
                                                d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z" />
                                        </svg></button>
                                </h2>
                                <div id="collapse-block-<?= $block['id'] ?>" class="accordion-collapse collapse"
                                    data-bs-parent="#hostelAccordion">
                                    <div class="accordion-body">
                                        <?php foreach ($block['rooms'] as $room): ?>
                                            <div class="accordion-item room-item mb-2">
                                                <!-- CORRECTED HEADER STRUCTURE -->
                                                <h2 class="accordion-header d-flex align-items-center"
                                                    id="heading-room-<?= $room['id'] ?>">
                                                    <button class="accordion-button collapsed flex-grow-1" type="button"
                                                        data-bs-toggle="collapse"
                                                        data-bs-target="#collapse-room-<?= $room['id'] ?>">
                                                        Room <?= htmlspecialchars($room['room_no']) ?>
                                                    </button>
                                                    <button class="delete-btn p-3" data-type="room" data-id="<?= $room['id'] ?>"
                                                        title="Delete Room"><svg viewBox="0 0 24 24">
                                                            <path
                                                                d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z" />
                                                        </svg></button>
                                                </h2>
                                                <div id="collapse-room-<?= $room['id'] ?>" class="accordion-collapse collapse">
                                                    <div class="accordion-body">
                                                        <table class="table table-sm table-hover">
                                                            <thead>
                                                                <tr>
                                                                    <th>Bed No.</th>
                                                                    <th>Student ID</th>
                                                                    <th>Status</th>
                                                                    <th>Action</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php foreach ($room['beds'] as $bed): ?>
                                                                    <tr>
                                                                        <td><?= htmlspecialchars($bed['nu_bed']) ?></td>
                                                                        <td><?= htmlspecialchars($bed['student_id'] ?? 'N/A') ?>
                                                                        </td>
                                                                        <td><span
                                                                                class="badge bg-success"><?= htmlspecialchars($bed['status']) ?></span>
                                                                        </td>
                                                                        <td><button class="delete-btn" data-type="bed"
                                                                                data-id="<?= $bed['id'] ?>" title="Delete Bed"><svg
                                                                                    viewBox="0 0 24 24">
                                                                                    <path
                                                                                        d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z" />
                                                                                </svg></button></td>
                                                                    </tr>
                                                                <?php endforeach; ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<div class="container-fluid mt-4">
    <h2>Room Settings</h2>

    <div class="row">
        <!-- Left Column for Adding Items -->
        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h4>Add New Block</h4>
                </div>
                <div class="card-body">
                    <form id="addBlockForm" method="post">
                        <div class="mb-3">
                            <label for="blockName" class="form-label">Block Name:</label>
                            <input type="text" class="form-control" name="blockName" id="blockName" required>
                        </div>
                        <button type="submit" class="btn btn-success w-100">Save Block</button>
                    </form>
                </div>
            </div>
            <div class="card mb-4">
                <div class="card-header">
                    <h4>Add Rooms to Block</h4>
                </div>
                <div class="card-body">
                    <form id="addRoomForm" method="post">
                        <div class="mb-3">
                            <label for="block_id" class="form-label">Select Block:</label>
                            <select name="block_id" id="block_id" class="form-select" required>
                                <option value="">Choose...</option>
                                <?php foreach ($blocks_dropdown as $b): ?>
                                    <option value="<?= $b['id'] ?>"><?= htmlspecialchars($b['bl_name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="room_count" class="form-label">Number of Rooms:</label>
                            <input type="number" class="form-control" name="room_count" id="room_count" min="1"
                                placeholder="Number of Rooms" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Add Rooms</button>
                    </form>
                </div>
            </div>
            <div class="card mb-4">
                <div class="card-header">
                    <h4>Add Beds to Room</h4>
                </div>
                <div class="card-body">
                    <form id="addBedForm" method="post">
                        <div class="mb-3">
                            <label for="room_id" class="form-label">Select Room:</label>
                            <select name="room_id" id="room_id" class="form-select" required>
                                <option value="">Choose...</option>
                                <?php foreach ($rooms_dropdown as $r): ?>
                                    <option value='<?= $r['id'] ?>'>Block <?= htmlspecialchars($r['bl_name']) ?> - Room
                                        <?= htmlspecialchars($r['room_no']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
       
            
                        <label for="bed_count" class="form-label">Number of Beds:</label>
                            <input type="number" class="form-control" name="bed_count" id="bed_count" min="1"
                                placeholder="Number of Beds" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Add Beds</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Right Column for Managing Hierarchy -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h4>Manage Hostel Structure</h4>
                </div>
                <div class="card-body">
                    <div class="accordion" id="hostelAccordion">
                        <?php foreach ($hostel_data as $block): ?>
                            <div class="accordion-item block-item">
                                <!-- CORRECTED HEADER STRUCTURE -->
                                <h2 class="accordion-header d-flex align-items-center"
                                    id="heading-block-<?= $block['id'] ?>">
                                    <button class="accordion-button collapsed flex-grow-1" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#collapse-block-<?= $block['id'] ?>">
                                        <strong>Block <?= htmlspecialchars($block['bl_name']) ?></strong>
                                    </button>
                                    <button class="delete-btn p-3" data-type="block" data-id="<?= $block['id'] ?>"
                                        title="Delete Block"><svg viewBox="0 0 24 24">
                                            <path
                                                d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z" />
                                        </svg></button>
                                </h2>
                                <div id="collapse-block-<?= $block['id'] ?>" class="accordion-collapse collapse"
                                    data-bs-parent="#hostelAccordion">
                                    <div class="accordion-body">
                                        <?php foreach ($block['rooms'] as $room): ?>
                                            <div class="accordion-item room-item mb-2">
                                                <!-- CORRECTED HEADER STRUCTURE -->
                                                <h2 class="accordion-header d-flex align-items-center"
                                                    id="heading-room-<?= $room['id'] ?>">
                                                    <button class="accordion-button collapsed flex-grow-1" type="button"
                                                        data-bs-toggle="collapse"
                                                        data-bs-target="#collapse-room-<?= $room['id'] ?>">
                                                        Room <?= htmlspecialchars($room['room_no']) ?>
                                                    </button>
                                                    <button class="delete-btn p-3" data-type="room" data-id="<?= $room['id'] ?>"
                                                        title="Delete Room"><svg viewBox="0 0 24 24">
                                                            <path
                                                                d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z" />
                                                        </svg></button>
                                                </h2>
                                                <div id="collapse-room-<?= $room['id'] ?>" class="accordion-collapse collapse">
                                                    <div class="accordion-body">
                                                        <table class="table table-sm table-hover">
                                                            <thead>
                                                                <tr>
                                                                    <th>Bed No.</th>
                                                                    <th>Student ID</th>
                                                                    <th>Status</th>
                                                                    <th>Action</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php foreach ($room['beds'] as $bed): ?>
                                                                    <tr>
                                                                        <td><?= htmlspecialchars($bed['nu_bed']) ?></td>
                                                                        <td><?= htmlspecialchars($bed['student_id'] ?? 'N/A') ?>
                                                                        </td>
                                                                        <td><span
                                                                                class="badge bg-success"><?= htmlspecialchars($bed['status']) ?></span>
                                                                        </td>
                                                                        <td><button class="delete-btn" data-type="bed"
                                                                                data-id="<?= $bed['id'] ?>" title="Delete Bed"><svg
                                                                                    viewBox="0 0 24 24">
                                                                                    <path
                                                                                        d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z" />
                                                                                </svg></button></td>
                                                                    </tr>
                                                                <?php endforeach; ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>    <?php include("layout/script.php"); ?>
    document.addEventListener('DOMContentLoaded', function () {
        // --- Logic for Adding Items ---
        const handleAdd = (formId, phpFile) => {
            document.getElementById(formId).addEventListener("submit", function (e) {
                e.preventDefault();
                fetch(`assets/sqlConnector/${phpFile}`, { method: 'POST', body: new FormData(this) })
                    .then(res => res.text()).then(msg => {
                        alert(msg);
                        if (!msg.toLowerCase().startsWith('error') && !msg.toLowerCase().startsWith('exists')) {
                            location.reload();
                        }
                    });
            });
        };

        handleAdd('addBlockForm', 'addBlock.php');
        handleAdd('addRoomForm', 'addRoom.php');
        handleAdd('addBedForm', 'addBed.php');

        // --- Unified Delete Logic (Now includes beds) ---
        document.getElementById('hostelAccordion').addEventListener('click', function (e) {
            const button = e.target.closest('.delete-btn');
            if (button) {
                e.stopPropagation(); // Prevents the accordion from toggling
                const type = button.dataset.type; // 'block', 'room', or 'bed'
                const id = button.dataset.id;
                const phpFile = `delete${type.charAt(0).toUpperCase() + type.slice(1)}.php`;

                if (!confirm(`Are you sure you want to delete this ${type}?`)) return;

                const formData = new FormData();
                formData.append(`${type}_id`, id);

                fetch(`assets/sqlConnector/${phpFile}`, { method: 'POST', body: formData })
                    .then(res => res.text())
                    .then(msg => {
                        alert(msg);
                        if (!msg.startsWith('Error')) {
                            if (type === 'bed') {
                                button.closest('tr').remove();
                            } else {
                                // For block or room, we need to reload to reflect count changes
                                location.reload();
                            }
                        }
                    });
            }
        });
    });
</script>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        // --- Logic for Adding Items ---
        const handleAdd = (formId, phpFile) => {
            document.getElementById(formId).addEventListener("submit", function (e) {
                e.preventDefault();
                fetch(`assets/sqlConnector/${phpFile}`, { method: 'POST', body: new FormData(this) })
                    .then(res => res.text()).then(msg => {
                        alert(msg);
                        if (!msg.toLowerCase().startsWith('error') && !msg.toLowerCase().startsWith('exists')) {
                            location.reload();
                        }
                    });
            });
        };

        handleAdd('addBlockForm', 'addBlock.php');
        handleAdd('addRoomForm', 'addRoom.php');
        handleAdd('addBedForm', 'addBed.php');

        // --- Unified Delete Logic (Now includes beds) ---
        document.getElementById('hostelAccordion').addEventListener('click', function (e) {
            const button = e.target.closest('.delete-btn');
            if (button) {
                e.stopPropagation(); // Prevents the accordion from toggling
                const type = button.dataset.type; // 'block', 'room', or 'bed'
                const id = button.dataset.id;
                const phpFile = `delete${type.charAt(0).toUpperCase() + type.slice(1)}.php`;

                if (!confirm(`Are you sure you want to delete this ${type}?`)) return;

                const formData = new FormData();
                formData.append(`${type}_id`, id);

                fetch(`assets/sqlConnector/${phpFile}`, { method: 'POST', body: formData })
                    .then(res => res.text())
                    .then(msg => {
                        alert(msg);
                        if (!msg.startsWith('Error')) {
                            if (type === 'bed') {
                                button.closest('tr').remove();
                            } else {
                                // For block or room, we need to reload to reflect count changes
                                location.reload();
                            }
                        }
                    });
            }
        });
    });
    
</script>
   

