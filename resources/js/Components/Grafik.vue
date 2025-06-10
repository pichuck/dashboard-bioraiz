<template>
    <!-- grafik track humidity, suhu ruangan, kelembapan tanah -->
    <div class="card mt-24">
        <div class="card-body">
            <div class="mb-20 flex-between flex-wrap gap-8">
                <h4 class="mb-0">Grafik Track</h4>
                <div class="flex-align gap-16 flex-wrap">
                    <select
                        class="form-select form-control text-13 px-8 pe-24 py-8 rounded-8 w-auto"
                        v-model="selectedPeriod"
                        @change="updateChart"
                    >
                        <option value="yearly">Yearly</option>
                        <option value="monthly">Monthly</option>
                        <option value="weekly">Weekly</option>
                        <option value="today">Today</option>
                    </select>

                    <!-- Filter untuk tracking data -->
                    <select
                        class="form-select form-control text-13 px-8 pe-24 py-8 rounded-8 w-auto"
                        v-model="selectedFilter"
                        @change="updateChartFilter"
                    >
                        <option value="all">Track Semua</option>
                        <option value="humidity">Humidity Saja</option>
                        <option value="temperature">Suhu Ruangan Saja</option>
                        <option value="soil">Kelembapan Tanah Saja</option>
                    </select>
                </div>
            </div>
            <div class="chart-container">
                <canvas ref="chartCanvas" id="environmentChart"></canvas>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, nextTick } from "vue";
import { Chart, registerables } from "chart.js";

// Register Chart.js components
Chart.register(...registerables);

// Reactive variables
const chartCanvas = ref(null);
const selectedPeriod = ref("monthly");
const selectedFilter = ref("all");
let chartInstance = null;

// Sample data untuk demonstrasi
const sampleData = {
    yearly: {
        labels: [
            "Jan",
            "Feb",
            "Mar",
            "Apr",
            "May",
            "Jun",
            "Jul",
            "Aug",
            "Sep",
            "Oct",
            "Nov",
            "Dec",
        ],
        humidity: [65, 68, 70, 72, 75, 78, 80, 82, 79, 76, 71, 67],
        temperature: [24, 25, 26, 28, 30, 32, 34, 33, 31, 29, 27, 25],
        soilMoisture: [45, 48, 52, 55, 58, 60, 62, 59, 56, 53, 50, 47],
    },
    monthly: {
        labels: ["Week 1", "Week 2", "Week 3", "Week 4"],
        humidity: [70, 72, 68, 75],
        temperature: [26, 28, 25, 29],
        soilMoisture: [52, 55, 50, 58],
    },
    weekly: {
        labels: ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"],
        humidity: [68, 70, 72, 71, 73, 69, 67],
        temperature: [25, 27, 28, 26, 29, 24, 23],
        soilMoisture: [50, 52, 54, 53, 55, 49, 48],
    },
    today: {
        labels: ["00:00", "04:00", "08:00", "12:00", "16:00", "20:00"],
        humidity: [65, 68, 72, 75, 73, 70],
        temperature: [22, 23, 28, 32, 30, 26],
        soilMoisture: [48, 50, 52, 51, 53, 49],
    },
};

// Chart configuration
const getChartConfig = () => {
    const data = sampleData[selectedPeriod.value];
    const datasets = [];

    // Dataset untuk Humidity
    if (selectedFilter.value === "all" || selectedFilter.value === "humidity") {
        datasets.push({
            label: "Humidity (%)",
            data: data.humidity,
            borderColor: "#3b82f6",
            backgroundColor: "rgba(59, 130, 246, 0.1)",
            borderWidth: 2,
            fill: false,
            tension: 0.4,
            pointBackgroundColor: "#3b82f6",
            pointBorderColor: "#ffffff",
            pointBorderWidth: 2,
            pointRadius: 4,
            pointHoverRadius: 6,
        });
    }

    // Dataset untuk Temperature
    if (
        selectedFilter.value === "all" ||
        selectedFilter.value === "temperature"
    ) {
        datasets.push({
            label: "Suhu Ruangan (°C)",
            data: data.temperature,
            borderColor: "#ef4444",
            backgroundColor: "rgba(239, 68, 68, 0.1)",
            borderWidth: 2,
            fill: false,
            tension: 0.4,
            pointBackgroundColor: "#ef4444",
            pointBorderColor: "#ffffff",
            pointBorderWidth: 2,
            pointRadius: 4,
            pointHoverRadius: 6,
        });
    }

    // Dataset untuk Soil Moisture
    if (selectedFilter.value === "all" || selectedFilter.value === "soil") {
        datasets.push({
            label: "Kelembapan Tanah (%)",
            data: data.soilMoisture,
            borderColor: "#10b981",
            backgroundColor: "rgba(16, 185, 129, 0.1)",
            borderWidth: 2,
            fill: false,
            tension: 0.4,
            pointBackgroundColor: "#10b981",
            pointBorderColor: "#ffffff",
            pointBorderWidth: 2,
            pointRadius: 4,
            pointHoverRadius: 6,
        });
    }

    return {
        type: "line",
        data: {
            labels: data.labels,
            datasets: datasets,
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: "top",
                    labels: {
                        usePointStyle: true,
                        padding: 20,
                        font: {
                            size: 12,
                            family: "Inter, sans-serif",
                        },
                    },
                },
                tooltip: {
                    mode: "index",
                    intersect: false,
                    backgroundColor: "rgba(0, 0, 0, 0.8)",
                    titleColor: "#ffffff",
                    bodyColor: "#ffffff",
                    borderColor: "#e5e7eb",
                    borderWidth: 1,
                    cornerRadius: 8,
                    displayColors: true,
                    callbacks: {
                        title: function (context) {
                            return `${data.labels[context[0].dataIndex]}`;
                        },
                        label: function (context) {
                            let label = context.dataset.label || "";
                            if (label) {
                                label += ": ";
                            }
                            label += context.parsed.y;
                            if (
                                context.dataset.label.includes("Humidity") ||
                                context.dataset.label.includes("Kelembapan")
                            ) {
                                label += "%";
                            } else if (context.dataset.label.includes("Suhu")) {
                                label += "°C";
                            }
                            return label;
                        },
                    },
                },
            },
            interaction: {
                mode: "nearest",
                axis: "x",
                intersect: false,
            },
            scales: {
                x: {
                    display: true,
                    grid: {
                        display: true,
                        color: "rgba(0, 0, 0, 0.05)",
                    },
                    ticks: {
                        font: {
                            size: 11,
                            family: "Inter, sans-serif",
                        },
                        color: "#6b7280",
                    },
                },
                y: {
                    display: true,
                    grid: {
                        display: true,
                        color: "rgba(0, 0, 0, 0.05)",
                    },
                    ticks: {
                        font: {
                            size: 11,
                            family: "Inter, sans-serif",
                        },
                        color: "#6b7280",
                        callback: function (value) {
                            return value;
                        },
                    },
                    beginAtZero: false,
                },
            },
        },
    };
};

// Initialize chart
const initChart = () => {
    if (chartInstance) {
        chartInstance.destroy();
    }

    const ctx = chartCanvas.value.getContext("2d");
    chartInstance = new Chart(ctx, getChartConfig());
};

// Update chart when period changes
const updateChart = () => {
    if (chartInstance) {
        const config = getChartConfig();
        chartInstance.data = config.data;
        chartInstance.update("active");
    }
};

// Update chart when filter changes
const updateChartFilter = () => {
    if (chartInstance) {
        const config = getChartConfig();
        chartInstance.data = config.data;
        chartInstance.update("active");
    }
};

// Mount lifecycle
onMounted(async () => {
    await nextTick();
    initChart();
});
</script>

<style scoped>
.chart-container {
    position: relative;
    height: 400px;
    width: 100%;
}

.flex-between {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.flex-align {
    display: flex;
    align-items: center;
}

.flex-wrap {
    flex-wrap: wrap;
}

.gap-8 {
    gap: 8px;
}

.gap-16 {
    gap: 16px;
}

.mb-20 {
    margin-bottom: 20px;
}

.mb-0 {
    margin-bottom: 0;
}

.mt-24 {
    margin-top: 24px;
}

.text-13 {
    font-size: 13px;
}

.px-8 {
    padding-left: 8px;
    padding-right: 8px;
}

.pe-24 {
    padding-right: 24px;
}

.py-8 {
    padding-top: 8px;
    padding-bottom: 8px;
}

.rounded-8 {
    border-radius: 8px;
}

.w-auto {
    width: auto;
}

.card {
    background: #ffffff;
    border-radius: 12px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    border: 1px solid #e5e7eb;
}

.card-body {
    padding: 24px;
}

.form-select {
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
    background-position: right 8px center;
    background-repeat: no-repeat;
    background-size: 16px 12px;
    padding-right: 32px;
}

.form-control {
    border: 1px solid #d1d5db;
    background-color: #ffffff;
    color: #374151;
    transition: all 0.15s ease-in-out;
}

.form-control:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

h4 {
    font-size: 20px;
    font-weight: 600;
    color: #1f2937;
    font-family: "Inter", sans-serif;
}
</style>
