/**
 * File: public/js/report/finance-chart.js
 */

function initFinanceChart(data) {
    // Ambil element canvas
    const chartCanvas = document.getElementById("financeChart");
    if (!chartCanvas) return; // Guard clause jika element tidak ada

    const ctx = chartCanvas.getContext("2d");

    // Destructure data dari parameter agar lebih mudah dibaca
    const { labels, revenueValues, profitValues, isPremium } = data;

    // Konfigurasi Dataset
    const datasets = [
        {
            label: "Total Omset",
            data: revenueValues,
            borderColor: "#4f46e5",
            backgroundColor: "rgba(79, 70, 229, 0.1)",
            borderWidth: 2,
            pointBackgroundColor: "#fff",
            pointBorderColor: "#4f46e5",
            fill: true,
            tension: 0.4,
        },
    ];

    // Jika Premium, tambahkan dataset Laba Bersih
    if (isPremium) {
        datasets.push({
            label: "Laba Bersih",
            data: profitValues,
            borderColor: "#10b981",
            backgroundColor: "rgba(16, 185, 129, 0.05)",
            borderWidth: 2,
            borderDash: [5, 5],
            pointBackgroundColor: "#fff",
            pointBorderColor: "#10b981",
            fill: true,
            tension: 0.4,
        });
    }

    // Render Chart
    new Chart(ctx, {
        type: "line",
        data: {
            labels: labels,
            datasets: datasets,
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                mode: "index",
                intersect: false,
            },
            plugins: {
                legend: {
                    display: true,
                    position: "top",
                    align: "end",
                    labels: {
                        usePointStyle: true,
                        boxWidth: 8,
                    },
                },
                tooltip: {
                    backgroundColor: "rgba(255, 255, 255, 0.9)",
                    titleColor: "#1f2937",
                    bodyColor: "#4b5563",
                    borderColor: "#e5e7eb",
                    borderWidth: 1,
                    padding: 10,
                    callbacks: {
                        label: function (context) {
                            let label = context.dataset.label || "";
                            if (label) label += ": ";
                            if (context.parsed.y !== null) {
                                label += new Intl.NumberFormat("id-ID", {
                                    style: "currency",
                                    currency: "IDR",
                                    maximumFractionDigits: 0,
                                }).format(context.parsed.y);
                            }
                            return label;
                        },
                    },
                },
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: "#f3f4f6" },
                    ticks: { font: { size: 11 } },
                },
                x: {
                    grid: { display: false },
                    ticks: { font: { size: 11 } },
                },
            },
        },
    });
}
