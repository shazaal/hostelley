// Buttons
const openBtn = document.getElementById("openPopup");     // Add Student
const openBtn1 = document.getElementById("openPopup1");   // Add Block

// Overlays
const overlay = document.getElementById("popupOverlay");
const overlay1 = document.getElementById("popupOverlay1");

// Close buttons
const closeBtnStudent = document.getElementById("closePopupStudent");
const closeBtnBlock = document.getElementById("closePopupBlock");

// Open popups
openBtn.onclick = function() {
  overlay.style.display = "block";
  closeOffcanvas(); // close sidebar
}

openBtn1.onclick = function() {
  overlay1.style.display = "block";
  closeOffcanvas(); // close sidebar
}

// Close popups
closeBtnStudent.onclick = function() {
  overlay.style.display = "none";
}

closeBtnBlock.onclick = function() {
  overlay1.style.display = "none";
}

// Click outside to close
window.onclick = function(event) {
  if (event.target === overlay) {
    overlay.style.display = "none";
  }
  if (event.target === overlay1) {
    overlay1.style.display = "none";
  }
}

// Helper → close offcanvas (sidebar)
function closeOffcanvas() {
  var offcanvasElement = document.getElementById("offcanvasExample");
  var offcanvasInstance = bootstrap.Offcanvas.getInstance(offcanvasElement);
  if (offcanvasInstance) {
    offcanvasInstance.hide();
  }
}
