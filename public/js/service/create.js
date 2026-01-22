/**
 * File: public/js/services/create.js
 */

function initServiceCreate(searchUrl) {
    const btnCheck = document.getElementById("btnCheck");
    const platInput = document.getElementById("license_plate");

    // Field Target
    const fieldId = document.getElementById("customer_id");
    const fieldName = document.getElementById("customer_name");
    const fieldPhone = document.getElementById("phone_number");
    const fieldVehicle = document.getElementById("vehicle");
    const fieldYear = document.getElementById("year");

    if (!btnCheck || !platInput) return; // Guard clause

    btnCheck.addEventListener("click", function (e) {
        e.preventDefault();

        let plat = platInput.value.trim();

        if (!plat) {
            alert("Silakan masukkan nomor polisi terlebih dahulu!");
            platInput.focus();
            return;
        }

        // UI Loading State
        let originalText = btnCheck.innerHTML;
        btnCheck.innerHTML =
            '<span class="spinner-border spinner-border-sm"></span> Cek...';
        btnCheck.disabled = true;

        // Gunakan URL yang dikirim dari parameter
        let url = `${searchUrl}?q=${encodeURIComponent(plat)}`;

        fetch(url, {
            method: "GET",
            headers: {
                "X-Requested-With": "XMLHttpRequest",
                Accept: "application/json",
            },
        })
            .then(async (response) => {
                let text = await response.text();
                try {
                    return JSON.parse(text);
                } catch {
                    console.error("Non-JSON Response:", text);
                    throw new Error("Respon server tidak valid.");
                }
            })
            .then((data) => {
                if (data.status === "found") {
                    // Isi Form
                    fieldId.value = data.data.customer_id;
                    fieldName.value = data.data.customer_name || "";
                    fieldPhone.value = data.data.phone_number || "";
                    fieldVehicle.value = data.data.vehicle || "";
                    fieldYear.value = data.data.year || "";

                    // Feedback Visual
                    platInput.classList.add("is-valid");
                    platInput.classList.remove("is-invalid");
                } else {
                    // Kosongkan Form jika tidak ketemu
                    resetFormFields();

                    // Feedback Visual
                    platInput.classList.add("is-invalid");
                    platInput.classList.remove("is-valid");

                    console.warn("Data tidak ditemukan, silakan input manual.");
                }
            })
            .catch((error) => {
                console.error("Error:", error);
                alert("Terjadi kesalahan saat mengecek data.");
            })
            .finally(() => {
                btnCheck.innerHTML = originalText;
                btnCheck.disabled = false;
            });
    });

    function resetFormFields() {
        fieldId.value = "";
        fieldName.value = "";
        fieldPhone.value = "";
        fieldVehicle.value = "";
        fieldYear.value = "";
    }
}
