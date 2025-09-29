<script>
    document.querySelectorAll(".filter-bar select").forEach((select) => {
        select.addEventListener("change", () => {
            console.log("Filter applied:", {
                block: document.getElementById("blockFilter").value,
                payment: document.getElementById("paymentFilter").value,
                occupancy: document.getElementById("occupancyFilter").value,
            });
        });
    });

    function toggleDarkMode() {
        document.body.classList.toggle("dark-mode");
    }

    $(document).ready(function () {
        $(".cols-header").click(function () {
            // Close all other sections
            $(".cols-col").not($(this).next()).slideUp(200);
            $(".cols-header .bi").not($(this).find(".bi"))
                .removeClass("bi-chevron-up")
                .addClass("bi-chevron-down");

            // Toggle clicked section
            $(this).next(".cols-col").slideToggle(200);
            $(this).find(".bi").toggleClass("bi-chevron-down bi-chevron-up");
        });
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/style/popup.js"></script>