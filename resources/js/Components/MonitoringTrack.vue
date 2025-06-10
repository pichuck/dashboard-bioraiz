<template>
    <!-- Widgets Start -->
    <div class="row gy-4 mt-10">
        <!-- Humidity Card -->
        <div class="col-xxl-4 col-sm-6">
            <div class="monitoring-card">
                <div class="card-body">
                    <div class="monitoring-header">
                        <span class="monitoring-icon humidity-icon">
                            <i class="ph-fill ph-drop"></i>
                        </span>
                        <h5 class="monitoring-title">Humidity</h5>
                    </div>
                    <div class="monitoring-value-container">
                        <h1 class="monitoring-value">
                            68<span class="unit">%</span>
                        </h1>
                        <div class="monitoring-status normal">Normal</div>
                    </div>
                    <div class="monitoring-footer">
                        <div class="trend-indicator up">
                            <i class="ph-fill ph-arrow-up"></i>
                            <span>2.5%</span>
                        </div>
                        <span class="last-update">Last updated: 2 min ago</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Temperature Card -->
        <div class="col-xxl-4 col-sm-6">
            <div class="monitoring-card">
                <div class="card-body">
                    <div class="monitoring-header">
                        <span class="monitoring-icon temperature-icon">
                            <i class="ph-fill ph-thermometer"></i>
                        </span>
                        <h5 class="monitoring-title">Temperature</h5>
                    </div>
                    <div class="monitoring-value-container">
                        <h1 class="monitoring-value">
                            24<span class="unit">°C</span>
                        </h1>
                        <div class="monitoring-status optimal">Optimal</div>
                    </div>
                    <div class="monitoring-footer">
                        <div class="trend-indicator down">
                            <i class="ph-fill ph-arrow-down"></i>
                            <span>1.2°C</span>
                        </div>
                        <span class="last-update">Last updated: 1 min ago</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Soil Moisture Card -->
        <div class="col-xxl-4 col-sm-6">
            <div class="monitoring-card">
                <div class="card-body">
                    <div class="monitoring-header">
                        <span class="monitoring-icon soil-icon">
                            <i class="ph-fill ph-plant"></i>
                        </span>
                        <h5 class="monitoring-title">Soil Moisture</h5>
                    </div>
                    <div class="monitoring-value-container">
                        <h1 class="monitoring-value">
                            45<span class="unit">%</span>
                        </h1>
                        <div class="monitoring-status warning">Low</div>
                    </div>
                    <div class="monitoring-footer">
                        <div class="trend-indicator down">
                            <i class="ph-fill ph-arrow-down"></i>
                            <span>5.3%</span>
                        </div>
                        <span class="last-update">Last updated: 3 min ago</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Widgets End -->
</template>

<script>
export default {
    name: "MonitoringTrack",
    data() {
        return {
            // Data monitoring bisa ditambahkan di sini
        };
    },
};
</script>

<style scoped>
.monitoring-card {
    background: #ffffff;
    border-radius: 20px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    border: none;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.monitoring-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
}

.monitoring-card::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, #48bb78 0%, #38a169 100%);
}

.card-body {
    padding: 30px;
    position: relative;
}

.monitoring-header {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 25px;
}

.monitoring-icon {
    width: 50px;
    height: 50px;
    border-radius: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    color: white;
    position: relative;
}

.humidity-icon {
    background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
    box-shadow: 0 8px 20px rgba(72, 187, 120, 0.3);
}

.temperature-icon {
    background: linear-gradient(135deg, #68d391 0%, #48bb78 100%);
    box-shadow: 0 8px 20px rgba(104, 211, 145, 0.3);
}

.soil-icon {
    background: linear-gradient(135deg, #81c784 0%, #66bb6a 100%);
    box-shadow: 0 8px 20px rgba(129, 199, 132, 0.3);
}

.monitoring-title {
    font-size: 16px;
    font-weight: 600;
    color: #2d3748;
    margin: 0;
}

.monitoring-value-container {
    text-align: center;
    margin: 30px 0;
}

.monitoring-value {
    font-size: 3.5rem;
    font-weight: 700;
    color: #1a202c;
    margin: 0;
    line-height: 1;
    background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.unit {
    font-size: 2rem;
    font-weight: 500;
    opacity: 0.8;
    background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    color: transparent;
}

.monitoring-status {
    display: inline-block;
    padding: 6px 16px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-top: 10px;
}

.monitoring-status.normal {
    background: linear-gradient(135deg, #81c784 0%, #4caf50 100%);
    color: white;
}

.monitoring-status.optimal {
    background: linear-gradient(135deg, #4fc3f7 0%, #29b6f6 100%);
    color: white;
}

.monitoring-status.warning {
    background: linear-gradient(135deg, #ffb74d 0%, #ff9800 100%);
    color: white;
}

.monitoring-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 20px;
    padding-top: 20px;
    border-top: 1px solid #e2e8f0;
}

.trend-indicator {
    display: flex;
    align-items: center;
    gap: 4px;
    font-size: 12px;
    font-weight: 600;
}

.trend-indicator.up {
    color: #48bb78;
}

.trend-indicator.down {
    color: #ed8936;
}

.trend-indicator i {
    font-size: 14px;
}

.last-update {
    font-size: 11px;
    color: #718096;
    font-weight: 500;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .monitoring-value {
        font-size: 2.8rem;
    }

    .unit {
        font-size: 1.6rem;
    }

    .card-body {
        padding: 20px;
    }
}

/* Animation for value changes */
@keyframes valueChange {
    0% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.05);
    }
    100% {
        transform: scale(1);
    }
}

.monitoring-value.updating {
    animation: valueChange 0.5s ease-in-out;
}
</style>
