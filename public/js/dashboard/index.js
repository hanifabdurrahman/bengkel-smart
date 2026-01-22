document.addEventListener("DOMContentLoaded", () => {
    // === GLOBAL CHART DEFAULTS ===
    Chart.defaults.font.family = "'Inter', 'Segoe UI', sans-serif";
    Chart.defaults.color = "#9ca3af";
    Chart.defaults.scale.grid.color =
        DASHBOARD_DATA.theme === "dark"
            ? "rgba(255,255,255,0.05)"
            : "rgba(0,0,0,0.03)";

    // ============================
    // A. TRAFIK SERVIS (BAR)
    // ============================
    const ctxWeekly = document
        .getElementById("weeklyServiceChart")
        ?.getContext("2d");
    let weeklyChart;

    if (ctxWeekly) {
        weeklyChart = new Chart(ctxWeekly, {
            type: "bar",
            data: {
                labels: DASHBOARD_DATA.weeklyLabels,
                datasets: [
                    {
                        label: "Servis",
                        data: DASHBOARD_DATA.weeklyData,
                        backgroundColor: "#4f46e5",
                        hoverBackgroundColor: "#4338ca",
                        borderRadius: 6,
                        barThickness: 30,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            borderDash: [2, 4],
                            drawBorder: false,
                        },
                    },
                    x: { grid: { display: false } },
                },
            },
        });
    }

    // Filter Mingguan / Bulanan
    const trafficFilter = document.getElementById("trafficFilter");
    const trafficSubtext = document.getElementById("trafficSubtext");

    trafficFilter?.addEventListener("change", function () {
        const filter = this.value;
        this.disabled = true;

        fetch(`${DASHBOARD_DATA.trafficUrl}?filter=${filter}`)
            .then((res) => res.json())
            .then((res) => {
                weeklyChart.data.labels = res.labels;
                weeklyChart.data.datasets[0].data = res.data;
                weeklyChart.data.datasets[0].barThickness =
                    filter === "monthly" ? 10 : 30;

                trafficSubtext.innerText =
                    filter === "monthly"
                        ? "Overview 30 hari terakhir"
                        : "Overview minggu ini";

                weeklyChart.update();
                this.disabled = false;
            });
    });

    // ============================
    // B. SPAREPART (DOUGHNUT)
    // ============================
    const sparepartTotal =
        DASHBOARD_DATA.sparepartLabels[0] === "Belum ada data"
            ? 0
            : DASHBOARD_DATA.sparepartData.reduce((a, b) => a + Number(b), 0);

    const totalEl = document.getElementById("sparepartTotal");
    if (totalEl) totalEl.textContent = sparepartTotal;

    const sparepartCtx = document
        .getElementById("sparepartChart")
        ?.getContext("2d");
    if (sparepartCtx) {
        new Chart(sparepartCtx, {
            type: "doughnut",
            data: {
                labels: DASHBOARD_DATA.sparepartLabels,
                datasets: [
                    {
                        data: DASHBOARD_DATA.sparepartData,
                        backgroundColor: [
                            "#4f46e5",
                            "#818cf8",
                            "#c7d2fe",
                            "#f59e0b",
                            "#10b981",
                        ],
                        borderWidth: 0,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: "78%",
                plugins: {
                    legend: {
                        position: "bottom",
                        labels: { usePointStyle: true, padding: 15 },
                    },
                },
            },
        });
    }

    // ============================
    // C. PENDAPATAN TAHUNAN (AREA)
    // ============================
    const ctxAnnual = document
        .getElementById("annualIncomeChart")
        ?.getContext("2d");

    if (ctxAnnual) {
        const gradient = ctxAnnual.createLinearGradient(0, 0, 0, 300);
        gradient.addColorStop(0, "rgba(79,70,229,0.2)");
        gradient.addColorStop(1, "rgba(79,70,229,0)");

        new Chart(ctxAnnual, {
            type: "line",
            data: {
                labels: DASHBOARD_DATA.months,
                datasets: [
                    {
                        data: DASHBOARD_DATA.monthlyRevenue,
                        borderColor: "#4f46e5",
                        backgroundColor: gradient,
                        borderWidth: 3,
                        tension: 0.4,
                        fill: true,
                        pointBackgroundColor: "#fff",
                        pointBorderColor: "#4f46e5",
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        intersect: false,
                        backgroundColor: "#1f2937",
                        padding: 12,
                        displayColors: false,
                        callbacks: {
                            label: (ctx) =>
                                "Rp " + ctx.parsed.y.toLocaleString("id-ID"),
                        },
                    },
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { borderDash: [4, 4], drawBorder: false },
                        ticks: {
                            callback: (v) =>
                                v >= 1000
                                    ? "Rp " + v / 1000 + "k"
                                    : "Rp " + v.toLocaleString("id-ID"),
                        },
                    },
                    x: { grid: { display: false } },
                },
            },
        });
    }
});
