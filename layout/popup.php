  <!-- Your Original Student Popup -->
    <div class="overlay w-100" id="popupOverlay" style="display:none; position: fixed; top: 0; left: 0; height: 100%; background: rgba(0,0,0,0.5); z-index: 1050; justify-content: center; align-items: center;">
        <div class="popup" style="background: white; padding: 25px; border-radius: 10px; width: 90%; max-width: 500px; position: relative;">
            <span class="close" id="closePopupStudent" style="position: absolute; top: 10px; right: 15px; font-size: 24px; cursor: pointer;">&times;</span>
            <h2>Add Student</h2>
            <form id="addStudentForm">
                <input class="form-control mb-2" type="text" name="student_name" placeholder="Student Name" required>
                <input class="form-control mb-2" type="text" name="contact_no" placeholder="Contact Number" required>
                <input class="form-control mb-2" type="email" name="email" placeholder="Email Address">
                <textarea class="form-control mb-2" name="address" placeholder="Address"></textarea>
                
                <label>Block</label>
                <select class="form-select mb-2" id="blockSelect" name="block_id" required>
                    <option value="">-- Select Block --</option>
                    <?php foreach($blocks_dropdown as $b): ?>
                        <option value="<?= $b['id'] ?>"><?= htmlspecialchars($b['bl_name']) ?></option>
                    <?php endforeach; ?>
                </select>
                
                <label>Room</label>
                <select class="form-select mb-2" id="roomSelect" name="room_id" required disabled>
                    <option value="">-- Select a block first --</option>
                </select>
                
                <label>Bed</label>
                <select class="form-select mb-2" id="bedSelect" name="bed_id" required disabled>
                    <option value="">-- Select a room first --</option>
                </select>
                
                <button type="submit" class="btn btn-success w-100" id="saveBtnStudent">Save</button>
            </form>
        </div>
    </div>

    <?php include("layout/script.php"); ?>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const popupOverlay = document.getElementById('popupOverlay');
        // IMPORTANT: Assuming your "Add Student" button in the sidebar has an ID of 'addStudentBtn'
        const addStudentBtn = document.getElementById('addStudentBtn'); 
        const closePopupBtn = document.getElementById('closePopupStudent');
        
        const blockSelect = document.getElementById('blockSelect');
        const roomSelect = document.getElementById('roomSelect');
        const bedSelect = document.getElementById('bedSelect');
        const addStudentForm = document.getElementById('addStudentForm');

        // Show popup when the sidebar button is clicked
        if (addStudentBtn) {
            addStudentBtn.addEventListener('click', (e) => {
                e.preventDefault(); // Prevent default link behavior
                popupOverlay.style.display = 'flex';
            });
        }

        // Hide popup with close button
        if (closePopupBtn) {
            closePopupBtn.addEventListener('click', () => {
                popupOverlay.style.display = 'none';
            });
        }
        
        // Hide popup if background is clicked
        popupOverlay.addEventListener('click', function(e) {
            if (e.target === popupOverlay) {
                popupOverlay.style.display = 'none';
            }
        });

        // --- Dynamic Dropdown Logic ---
        const populateSelect = (selectElement, items, defaultOption, valueKey, textKey) => {
            selectElement.innerHTML = `<option value="">${defaultOption}</option>`;
            items.forEach(item => {
                const option = document.createElement('option');
                option.value = item[valueKey];
                option.textContent = item[textKey];
                selectElement.appendChild(option);
            });
            selectElement.disabled = false;
        };

        blockSelect.addEventListener('change', function() {
            const blockId = this.value;
            roomSelect.innerHTML = '<option value="">Loading...</option>';
            bedSelect.innerHTML = '<option value="">-- Select a room first --</option>';
            roomSelect.disabled = true;
            bedSelect.disabled = true;

            if (blockId) {
                fetch(`assets/sqlConnector/getRooms.php?block_id=${blockId}`)
                    .then(response => response.json())
                    .then(data => {
                        populateSelect(roomSelect, data, '-- Select Room --', 'id', 'room_no');
                    });
            }
        });

        roomSelect.addEventListener('change', function() {
            const roomId = this.value;
            bedSelect.innerHTML = '<option value="">Loading...</option>';
            bedSelect.disabled = true;

            if (roomId) {
                fetch(`assets/sqlConnector/getBeds.php?room_id=${roomId}`)
                    .then(response => response.json())
                    .then(data => {
                        populateSelect(bedSelect, data, '-- Select Bed --', 'id', 'nu_bed');
                    });
            }
        });

        // --- Form Submission Logic ---
        addStudentForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            
            fetch('assets/sqlConnector/addStudent.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(message => {
                alert(message);
                if (!message.toLowerCase().startsWith('error')) {
                    location.reload();
                }
            });
        });
    });
    </script>