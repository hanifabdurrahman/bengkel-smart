document.addEventListener("DOMContentLoaded", function () {
    const searchInput = document.getElementById("searchInput");
    const tableContainer = document.getElementById("tableContainer");
    const loadingSpinner = document.getElementById("loadingSpinner");
    let innerContent = document.getElementById("innerTableContent"); // Menggunakan let agar bisa di-update

    let typingTimer;
    const doneTypingInterval = 400;

    // Ambil URL Route dari atribut data-route pada tableContainer
    const baseUrl = tableContainer.getAttribute("data-route");

    function fetchTable(query = "", page = 1) {
        // Tampilkan Loading
        loadingSpinner.classList.remove("d-none");

        // Ambil ulang elemen innerContent jika DOM berubah
        innerContent = document.getElementById("innerTableContent");
        if (innerContent) innerContent.style.opacity = "0.3";

        // Ambil filter dari URL browser saat ini (jika ada low_stock, dll)
        const urlParams = new URLSearchParams(window.location.search);
        const filter = urlParams.get("filter") || "";

        // Susun URL Fetch
        let url = `${baseUrl}?search=${encodeURIComponent(
            query
        )}&page=${page}&filter=${filter}`;

        fetch(url, {
            headers: {
                "X-Requested-With": "XMLHttpRequest",
            },
        })
            .then((response) => response.text())
            .then((html) => {
                loadingSpinner.classList.add("d-none");

                // Parsing HTML string menjadi DOM
                // Catatan: Cara ini aman, tapi pastikan loadingSpinner ada di luar area yang di-replace
                // atau simpan referensinya seperti kode asli

                const newContent = `<div id="innerTableContent" class="fade-in">${html}</div>`;
                const loaderHTML = loadingSpinner.outerHTML; // Simpan spinner agar tidak hilang

                tableContainer.innerHTML = loaderHTML + newContent;

                // Re-select element spinner karena DOM baru saja di-overwrite
                const newLoader = document.getElementById("loadingSpinner");

                attachPaginationListeners();
            })
            .catch((error) => {
                console.error("Error:", error);
                loadingSpinner.classList.add("d-none");
                if (innerContent) innerContent.style.opacity = "1";
            });
    }

    searchInput.addEventListener("keyup", function () {
        clearTimeout(typingTimer);
        if (searchInput.value) {
            typingTimer = setTimeout(() => {
                fetchTable(searchInput.value);
            }, doneTypingInterval);
        } else {
            fetchTable();
        }
    });

    function attachPaginationListeners() {
        const paginationLinks = document.querySelectorAll(".pagination a");
        paginationLinks.forEach((link) => {
            link.addEventListener("click", function (e) {
                e.preventDefault();
                let url = new URL(this.href);
                let page = url.searchParams.get("page");
                let query = searchInput.value;
                fetchTable(query, page);
            });
        });
    }

    // Jalankan listener pagination saat pertama kali load
    attachPaginationListeners();
});
