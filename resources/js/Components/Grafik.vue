<template>
    <!-- grafik track humidity, suhu ruangan, kelembapan tanah -->
    <div class="card mt-24">
        <div class="card-body">
            <div class="mb-20 flex-between flex-wrap gap-8">
                <div class="flex-align gap-16">
                    <h4 class="mb-0">Grafik Track</h4>
                    <div class="status-indicator">
                        <span
                            :class="[
                                'status-dot',
                                isRealTimeActive ? 'active' : 'inactive',
                            ]"
                        ></span>
                        <span class="status-text">{{
                            isRealTimeActive ? "Real-time ON" : "Real-time OFF"
                        }}</span>
                    </div>
                </div>
                <div class="flex-align gap-16 flex-wrap">
                    <select
                        class="form-select form-control text-13 px-8 pe-24 py-8 rounded-8 w-auto"
                        v-model="selectedPeriod"
                        @change="updateChart"
                    >
                        <option value="realtime">
                            Real-time (Last 20 points)
                        </option>
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

            <!-- Loading indicator -->
            <div v-if="isLoading" class="loading-indicator">
                <span class="loading-spinner"></span>
                <span>Loading sensor data...</span>
            </div>

            <div class="chart-container">
                <canvas ref="chartCanvas" id="environmentChart"></canvas>
            </div>

            <!-- Last update info -->
            <div class="last-update" v-if="lastUpdate">
                <span>Last update: {{ formatDateTime(lastUpdate) }}</span>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted, nextTick } from "vue";
import { Chart, registerables } from "chart.js";
import axios from "axios";

// Register Chart.js components
Chart.register(...registerables);

// Reactive variables
const chartCanvas = ref(null);
const selectedPeriod = ref("realtime");
const selectedFilter = ref("all");
const isRealTimeActive = ref(false);
const isLoading = ref(false);
const lastUpdate = ref(null);
let chartInstance = null;
let realTimeInterval = null;

// Real-time data storage
const realtimeData = ref({
    labels: [],
    humidity: [],
    temperature: [],
    soilMoisture: [],
});

// Maximum points to keep in real-time mode
const MAX_REALTIME_POINTS = 20;

// Historical data storage
const historicalData = ref({
    yearly: { labels: [], humidity: [], temperature: [], soilMoisture: [] },
    monthly: { labels: [], humidity: [], temperature: [], soilMoisture: [] },
    weekly: { labels: [], humidity: [], temperature: [], soilMoisture: [] },
    today: { labels: [], humidity: [], temperature: [], soilMoisture: [] },
});

// API Functions
const fetchLatestSensorData = async () => {
    try {
        const [suhuResponse, tanahResponse] = await Promise.all([
            axios.get("/api/Suhu/latest"),
            axios.get("/api/Tanah/latest"),
        ]);

        const suhuData = suhuResponse.data.data;
        const tanahData = tanahResponse.data.data;

        return {
            temperature: suhuData?.nilai_temperatur || 0,
            humidity: suhuData?.nilai_humidity || 0,
            soilMoisture: tanahData?.nilai_humidity || 0,
            timestamp: new Date(),
        };
    } catch (err) {
        console.error("Error fetching sensor data:", err);
        // Return fallback data instead of throwing error
        return {
            temperature: 0,
            humidity: 0,
            soilMoisture: 0,
            timestamp: new Date(),
        };
    }
};

// Fetch historical data from API
const fetchHistoricalData = async (period) => {
    try {
        isLoading.value = true;
        let startDate, endDate;
        const now = new Date();

        switch (period) {
            case "yearly":
                startDate = new Date(now.getFullYear(), 0, 1);
                endDate = now;
                break;
            case "monthly":
                startDate = new Date(now.getFullYear(), now.getMonth(), 1);
                endDate = now;
                break;
            case "weekly":
                startDate = new Date(now.getTime() - 7 * 24 * 60 * 60 * 1000);
                endDate = now;
                break;
            case "today":
                startDate = new Date(
                    now.getFullYear(),
                    now.getMonth(),
                    now.getDate()
                );
                endDate = now;
                break;
            default:
                throw new Error("Invalid period");
        }

        const [suhuResponse, tanahResponse] = await Promise.all([
            axios.post("/api/Suhu/date-range", {
                start_date: startDate.toISOString().split("T")[0],
                end_date: endDate.toISOString().split("T")[0],
            }),
            axios.post("/api/Tanah/date-range", {
                start_date: startDate.toISOString().split("T")[0],
                end_date: endDate.toISOString().split("T")[0],
            }),
        ]);

        const suhuData = suhuResponse.data.data || [];
        const tanahData = tanahResponse.data.data || [];

        // Process data based on period
        const processedData = processDataByPeriod(suhuData, tanahData, period);
        historicalData.value[period] = processedData;

        return processedData;
    } catch (err) {
        console.error(`Error fetching ${period} data:`, err);
        // Return empty data structure if API fails
        return {
            labels: [],
            humidity: [],
            temperature: [],
            soilMoisture: [],
        };
    } finally {
        isLoading.value = false;
    }
};

// Process data based on selected period
const processDataByPeriod = (suhuData, tanahData, period) => {
    const labels = [];
    const humidity = [];
    const temperature = [];
    const soilMoisture = [];

    if (period === "yearly") {
        // Group by month
        const months = [
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
        ];
        const monthlyData = {};

        // Initialize monthly data
        months.forEach((month, index) => {
            monthlyData[index] = {
                humidity: [],
                temperature: [],
                soilMoisture: [],
            };
        });

        // Group suhu data by month
        suhuData.forEach((item) => {
            const month = new Date(item.created_at).getMonth();
            monthlyData[month].humidity.push(item.nilai_humidity);
            monthlyData[month].temperature.push(item.nilai_temperatur);
        });

        // Group tanah data by month
        tanahData.forEach((item) => {
            const month = new Date(item.created_at).getMonth();
            monthlyData[month].soilMoisture.push(item.nilai_humidity);
        });

        // Calculate averages
        months.forEach((month, index) => {
            labels.push(month);
            humidity.push(calculateAverage(monthlyData[index].humidity));
            temperature.push(calculateAverage(monthlyData[index].temperature));
            soilMoisture.push(
                calculateAverage(monthlyData[index].soilMoisture)
            );
        });
    } else if (period === "monthly") {
        // Group by week
        for (let i = 1; i <= 4; i++) {
            labels.push(`Week ${i}`);
            // You can implement more sophisticated week grouping here
            const weekHumidity = suhuData
                .filter(
                    (_, index) =>
                        Math.floor(index / (suhuData.length / 4)) === i - 1
                )
                .map((item) => item.nilai_humidity);
            const weekTemperature = suhuData
                .filter(
                    (_, index) =>
                        Math.floor(index / (suhuData.length / 4)) === i - 1
                )
                .map((item) => item.nilai_temperatur);
            const weekSoilMoisture = tanahData
                .filter(
                    (_, index) =>
                        Math.floor(index / (tanahData.length / 4)) === i - 1
                )
                .map((item) => item.nilai_humidity);

            humidity.push(calculateAverage(weekHumidity));
            temperature.push(calculateAverage(weekTemperature));
            soilMoisture.push(calculateAverage(weekSoilMoisture));
        }
    } else if (period === "weekly") {
        // Group by day
        const days = ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"];
        const dailyData = {};

        // Initialize daily data
        days.forEach((day, index) => {
            dailyData[index] = {
                humidity: [],
                temperature: [],
                soilMoisture: [],
            };
        });

        // Group suhu data by day
        suhuData.forEach((item) => {
            const dayOfWeek = (new Date(item.created_at).getDay() + 6) % 7; // Monday = 0
            dailyData[dayOfWeek].humidity.push(item.nilai_humidity);
            dailyData[dayOfWeek].temperature.push(item.nilai_temperatur);
        });

        // Group tanah data by day
        tanahData.forEach((item) => {
            const dayOfWeek = (new Date(item.created_at).getDay() + 6) % 7; // Monday = 0
            dailyData[dayOfWeek].soilMoisture.push(item.nilai_humidity);
        });

        // Calculate averages
        days.forEach((day, index) => {
            labels.push(day);
            humidity.push(calculateAverage(dailyData[index].humidity));
            temperature.push(calculateAverage(dailyData[index].temperature));
            soilMoisture.push(calculateAverage(dailyData[index].soilMoisture));
        });
    } else if (period === "today") {
        // Group by hour (4-hour intervals)
        const hours = ["00:00", "04:00", "08:00", "12:00", "16:00", "20:00"];
        const hourlyData = {};

        // Initialize hourly data
        hours.forEach((hour, index) => {
            hourlyData[index * 4] = {
                humidity: [],
                temperature: [],
                soilMoisture: [],
            };
        });

        // Group suhu data by hour
        suhuData.forEach((item) => {
            const hour = new Date(item.created_at).getHours();
            const interval = Math.floor(hour / 4) * 4;
            if (hourlyData[interval]) {
                hourlyData[interval].humidity.push(item.nilai_humidity);
                hourlyData[interval].temperature.push(item.nilai_temperatur);
            }
        });

        // Group tanah data by hour
        tanahData.forEach((item) => {
            const hour = new Date(item.created_at).getHours();
            const interval = Math.floor(hour / 4) * 4;
            if (hourlyData[interval]) {
                hourlyData[interval].soilMoisture.push(item.nilai_humidity);
            }
        });

        // Calculate averages
        hours.forEach((hour, index) => {
            labels.push(hour);
            const interval = index * 4;
            humidity.push(
                calculateAverage(hourlyData[interval]?.humidity || [])
            );
            temperature.push(
                calculateAverage(hourlyData[interval]?.temperature || [])
            );
            soilMoisture.push(
                calculateAverage(hourlyData[interval]?.soilMoisture || [])
            );
        });
    }

    return { labels, humidity, temperature, soilMoisture };
};

// Calculate average of array
const calculateAverage = (arr) => {
    if (!arr || arr.length === 0) return 0;
    return (
        Math.round(
            (arr.reduce((sum, val) => sum + val, 0) / arr.length) * 100
        ) / 100
    );
};

// Add new data point to real-time chart
// Perbaiki fungsi addRealtimeDataPoint untuk mencegah referensi siklik
const addRealtimeDataPoint = (newData) => {
    const timeLabel = new Date(newData.timestamp).toLocaleTimeString("id-ID", {
        hour: "2-digit",
        minute: "2-digit",
        second: "2-digit",
    });

    // PERBAIKAN: Clone data primitif untuk menghindari referensi reaktif siklik
    const temperature = Number(newData.temperature);
    const humidity = Number(newData.humidity);
    const soilMoisture = Number(newData.soilMoisture);

    // Add new data
    realtimeData.value.labels.push(timeLabel);
    realtimeData.value.temperature.push(temperature);
    realtimeData.value.humidity.push(humidity);
    realtimeData.value.soilMoisture.push(soilMoisture);

    // Keep only the last MAX_REALTIME_POINTS
    if (realtimeData.value.labels.length > MAX_REALTIME_POINTS) {
        realtimeData.value.labels.shift();
        realtimeData.value.temperature.shift();
        realtimeData.value.humidity.shift();
        realtimeData.value.soilMoisture.shift();
    }

    lastUpdate.value = new Date(newData.timestamp);
};

// Start real-time monitoring (auto-starts)
// Perbaiki fungsi startRealTime untuk menghindari referensi siklik dalam data reaktif
const startRealTime = async () => {
    if (isRealTimeActive.value) return;

    isRealTimeActive.value = true;

    // Set up interval for real-time updates
    realTimeInterval = setInterval(async () => {
        try {
            const newData = await fetchLatestSensorData();

            // PERBAIKAN: Pastikan data tidak siklik dan primitif
            const cleanData = {
                temperature: Number(newData.temperature),
                humidity: Number(newData.humidity),
                soilMoisture: Number(newData.soilMoisture),
                timestamp: new Date(),
            };

            addRealtimeDataPoint(cleanData);

            if (selectedPeriod.value === "realtime" && chartInstance) {
                // PERBAIKAN: Clone data chart dan update dengan data baru
                updateChartWithCleanData();
            }
        } catch (err) {
            console.error("Real-time update error:", err);
            // Continue running even if there's an error
        }
    }, 1000); // Update every 1 seconds for more real-time feel
};

// PERBAIKAN: Fungsi baru untuk update chart dengan data yang bersih
const updateChartWithCleanData = () => {
    if (!chartInstance) return;

    // Ambil konfigurasi chart dengan data terbaru
    const config = getChartConfig();

    // Update label dan dataset secara terpisah untuk menghindari referensi siklik
    chartInstance.data.labels = [...config.data.labels];

    // Update setiap dataset dengan nilai primitif yang baru
    config.data.datasets.forEach((dataset, i) => {
        // Pastikan dataset ada
        if (!chartInstance.data.datasets[i]) return;

        // Update dataset dengan nilai baru
        chartInstance.data.datasets[i].data = [...dataset.data];
    });

    // Update chart dengan animasi
    chartInstance.update("active");
};

// Format date time for display
const formatDateTime = (date) => {
    return new Date(date).toLocaleString("id-ID", {
        day: "2-digit",
        month: "2-digit",
        year: "numeric",
        hour: "2-digit",
        minute: "2-digit",
        second: "2-digit",
    });
};

// Chart configuration
// Perbaiki fungsi getChartConfig untuk memastikan data tidak siklik
const getChartConfig = () => {
    let data;

    if (selectedPeriod.value === "realtime") {
        // PERBAIKAN: Clone data untuk menghindari referensi siklik
        data = {
            labels: [...realtimeData.value.labels],
            humidity: [...realtimeData.value.humidity],
            temperature: [...realtimeData.value.temperature],
            soilMoisture: [...realtimeData.value.soilMoisture],
        };
    } else {
        // PERBAIKAN: Clone data untuk historical data juga
        const histData = historicalData.value[selectedPeriod.value] || {
            labels: [],
            humidity: [],
            temperature: [],
            soilMoisture: [],
        };

        data = {
            labels: [...histData.labels],
            humidity: [...histData.humidity],
            temperature: [...histData.temperature],
            soilMoisture: [...histData.soilMoisture],
        };
    }

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
            animation: {
                duration: selectedPeriod.value === "realtime" ? 750 : 1000,
            },
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
                        maxTicksLimit:
                            selectedPeriod.value === "realtime"
                                ? 10
                                : undefined,
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
const updateChart = async () => {
    if (selectedPeriod.value !== "realtime") {
        // Fetch historical data for non-realtime periods
        await fetchHistoricalData(selectedPeriod.value);
    }

    if (chartInstance) {
        // PERBAIKAN: Gunakan updateChartWithCleanData untuk menghindari referensi siklik
        updateChartWithCleanData();
    }
};

// Update chart when filter changes
const updateChartFilter = () => {
    if (chartInstance) {
        // PERBAIKAN: Gunakan updateChartWithCleanData untuk menghindari referensi siklik
        updateChartWithCleanData();
    }
};

// Mount lifecycle
onMounted(async () => {
    await nextTick();
    initChart();

    // Auto-start real-time monitoring
    startRealTime();

    // Load initial historical data
    if (selectedPeriod.value !== "realtime") {
        await fetchHistoricalData(selectedPeriod.value);
        updateChart();
    }
});

// Cleanup on unmount
onUnmounted(() => {
    stopRealTime();
    if (chartInstance) {
        chartInstance.destroy();
    }
});

// Simple watcher replacement for period changes
let previousPeriod = selectedPeriod.value;
const checkPeriodChange = () => {
    if (selectedPeriod.value !== previousPeriod) {
        updateChart();
        previousPeriod = selectedPeriod.value;
    }
    requestAnimationFrame(checkPeriodChange);
};

onMounted(() => {
    checkPeriodChange();
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

/* Real-time specific styles */
.status-indicator {
    display: flex;
    align-items: center;
    gap: 8px;
}

.status-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    transition: all 0.3s ease;
}

.status-dot.active {
    background-color: #10b981;
    box-shadow: 0 0 8px rgba(16, 185, 129, 0.5);
    animation: pulse 2s infinite;
}

.status-dot.inactive {
    background-color: #6b7280;
}

.status-text {
    font-size: 12px;
    font-weight: 500;
    color: #6b7280;
}

.loading-indicator {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 16px;
    background-color: #f3f4f6;
    border-radius: 8px;
    margin-bottom: 16px;
    color: #6b7280;
    font-size: 14px;
}

.loading-spinner {
    width: 16px;
    height: 16px;
    border: 2px solid #e5e7eb;
    border-top: 2px solid #3b82f6;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

.last-update {
    text-align: center;
    margin-top: 16px;
    font-size: 12px;
    color: #6b7280;
}

@keyframes pulse {
    0%,
    100% {
        opacity: 1;
    }
    50% {
        opacity: 0.5;
    }
}

@keyframes spin {
    0% {
        transform: rotate(0deg);
    }
    100% {
        transform: rotate(360deg);
    }
}
</style>
