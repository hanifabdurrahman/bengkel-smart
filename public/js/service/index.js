/**
 * File: public/js/services/index.js
 */

function initServicePage(routeUrl) {
    const searchInput = document.getElementById("searchInput");
    const loader = document.getElementById("loadingSpinner");
    const innerContent = document.getElementById("innerTableContent");

    let timer;
    const delay = 400;

    // Fungsi utama untuk mengambil data
    function fetchServices(query = "", page = 1) {
        // Tampilkan loading & beri efek transparan pada konten lama
        loader.classList.remove("d-none");
        innerContent.style.opacity = "0.5";

        let url = `${routeUrl}?search=${encodeURIComponent(
            query
        )}&page=${page}`;

        fetch(url, {
            headers: {
                "X-Requested-With": "XMLHttpRequest",
            },
        })
            .then((res) => res.text())
            .then((html) => {
                // Sembunyikan loading
                loader.classList.add("d-none");
                innerContent.style.opacity = "1";

                // Update konten tabel saja (tanpa menghapus elemen loader dari DOM)
                innerContent.innerHTML = html;

                // Pasang ulang listener pagination
                attachPagination();
            })
            .catch((err) => {
                console.error("Error fetching data:", err);
                loader.classList.add("d-none");
                innerContent.style.opacity = "1";
            });
    }

    // Fungsi untuk menangani klik pagination
    function attachPagination() {
        const paginationLinks = document.querySelectorAll(".pagination a");

        paginationLinks.forEach((link) => {
            link.addEventListener("click", (e) => {
                e.preventDefault();

                // Ambil halaman dari URL href
                const url = new URL(link.href);
                const page = url.searchParams.get("page");

                fetchServices(searchInput.value, page);
            });
        });
    }

    // Event Listener untuk Search dengan Debounce
    if (searchInput) {
        searchInput.addEventListener("keyup", () => {
            clearTimeout(timer);
            timer = setTimeout(() => {
                fetchServices(searchInput.value);
            }, delay);
        });
    }

    // Inisialisasi awal pagination
    attachPagination();
}
