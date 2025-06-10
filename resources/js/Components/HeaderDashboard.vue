<template>
    <div class="top-navbar flex-between gap-16">
        <div class="flex-align gap-16">
            <!-- Toggle Button Start -->
            <button
                type="button"
                class="toggle-btn d-xl-none d-flex text-26 text-gray-500 hover:text-main-600 transition-colors"
                @click="toggleSidebar"
            >
                <i class="ph ph-list"></i>
            </button>
            <!-- Toggle Button End -->

            <!-- Weather Widget Start -->
            <div class="weather-widget d-none d-md-flex">
                <div
                    class="weather-container bg-gradient-to-r from-blue-50 to-sky-50 rounded-xl p-3 border border-blue-100"
                >
                    <div v-if="weatherLoading" class="flex-align gap-2">
                        <div class="loading-spinner"></div>
                        <span class="text-sm text-gray-600"
                            >Loading weather...</span
                        >
                    </div>
                    <div v-else-if="weatherData" class="flex-align gap-3">
                        <div class="weather-icon">
                            <i
                                :class="getWeatherIcon(weatherData.weathercode)"
                                class="text-2xl text-blue-600"
                            ></i>
                        </div>
                        <div class="weather-info">
                            <div class="flex-align gap-2">
                                <span
                                    class="text-lg font-semibold text-gray-800"
                                    >{{
                                        Math.round(weatherData.temperature)
                                    }}°C</span
                                >
                                <div class="weather-location">
                                    <i
                                        class="ph ph-map-pin text-sm text-gray-500"
                                    ></i>
                                    <span class="text-sm text-gray-600"
                                        >Malang</span
                                    >
                                </div>
                            </div>
                            <div class="text-xs text-gray-500 mt-1">
                                Humidity: {{ weatherData.humidity }}% | Wind:
                                {{ Math.round(weatherData.windspeed) }} km/h
                            </div>
                        </div>
                    </div>
                    <div v-else class="text-sm text-red-500">
                        Weather unavailable
                    </div>
                </div>
            </div>
            <!-- Weather Widget End -->
        </div>

        <div class="flex-align gap-16">
            <!-- Mobile Clock Widget Start -->
            <div class="mobile-clock-widget d-md-none">
                <div class="mobile-clock-container">
                    <div class="mobile-time">{{ currentTime }}</div>
                    <div class="mobile-date">{{ currentDate }}</div>
                </div>
            </div>
            <!-- Mobile Clock Widget End -->

            <!-- Weather Widget Mobile Start -->
            <div class="weather-widget-mobile d-md-none">
                <div
                    class="weather-container-mobile bg-gradient-to-r from-blue-50 to-sky-50 rounded-lg p-2 border border-blue-100"
                >
                    <div v-if="weatherLoading" class="flex-center">
                        <div class="loading-spinner-sm"></div>
                    </div>
                    <div v-else-if="weatherData" class="flex-center gap-2">
                        <i
                            :class="getWeatherIcon(weatherData.weathercode)"
                            class="text-lg text-blue-600"
                        ></i>
                        <span class="text-sm font-semibold text-gray-800"
                            >{{ Math.round(weatherData.temperature) }}°C</span
                        >
                    </div>
                </div>
            </div>
            <!-- Weather Widget Mobile End -->

            <!-- User Profile Start -->
            <div class="dropdown">
                <button
                    class="users arrow-down-icon border border-gray-200 rounded-pill p-4 d-inline-block pe-40 position-relative"
                    type="button"
                    data-bs-toggle="dropdown"
                    aria-expanded="false"
                >
                    <span class="position-relative">
                        <img
                            src="/assets/images/user.png"
                            alt="Image"
                            class="h-40 w-40 rounded-circle"
                        />
                    </span>
                </button>
                <div
                    class="dropdown-menu dropdown-menu--lg border-0 bg-transparent p-0"
                >
                    <div
                        class="card border border-gray-100 rounded-12 box-shadow-custom"
                    >
                        <div class="card-body">
                            <div class="flex-align gap-8 mb-20 pb-20">
                                <img
                                    src="/assets/images/user.png"
                                    alt=""
                                    class="w-54 h-54 rounded-circle"
                                />
                                <div class="">
                                    <h4 class="mb-0">Michel John</h4>
                                    <p class="fw-medium text-13 text-gray-200">
                                        examplemail@mail.com
                                    </p>
                                </div>
                            </div>
                            <ul
                                class="max-h-270 overflow-y-auto scroll-sm pe-4"
                            >
                                <li class="pt-8 border-top border-gray-100">
                                    <a
                                        href="sign-in.html"
                                        class="py-12 text-15 px-20 hover-bg-danger-50 text-gray-300 hover-text-danger-600 rounded-8 flex-align gap-8 fw-medium text-15"
                                    >
                                        <span
                                            class="text-2xl text-danger-600 d-flex"
                                            ><i class="ph ph-sign-out"></i
                                        ></span>
                                        <span class="text">Log Out</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <!-- User Profile Start -->
        </div>
    </div>
</template>

<style scoped>
/* Mobile Clock Widget Styles */
.mobile-clock-widget {
    margin-right: 8px;
}

.mobile-clock-container {
    background: linear-gradient(135deg, #45a538, #2d5016);
    border-radius: 12px;
    padding: 8px 12px;
    text-align: center;
    box-shadow: 0 4px 15px rgba(69, 165, 56, 0.25);
    border: 1px solid rgba(255, 255, 255, 0.2);
    min-width: 80px;
    position: relative;
    overflow: hidden;
}

.mobile-clock-container::before {
    content: "";
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(
        90deg,
        transparent,
        rgba(255, 255, 255, 0.2),
        transparent
    );
    transition: left 0.5s;
    animation: mobile-shimmer 3s infinite;
}

@keyframes mobile-shimmer {
    0% {
        left: -100%;
    }
    50% {
        left: 100%;
    }
    100% {
        left: -100%;
    }
}

.mobile-time {
    font-size: 14px;
    font-weight: 700;
    color: #ffffff;
    font-family: "Courier New", monospace;
    letter-spacing: 0.5px;
    text-shadow: 0 1px 2px rgba(0, 0, 0, 0.3);
    position: relative;
    z-index: 1;
}

.mobile-date {
    font-size: 10px;
    color: #aeff78;
    font-weight: 500;
    margin-top: 2px;
    text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
    position: relative;
    z-index: 1;
}

/* Weather Widget Styles */
.weather-widget {
    margin-left: 20px;
}

.weather-container {
    min-width: 280px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
}

.weather-container:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
}

.weather-container-mobile {
    min-width: 60px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.weather-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    background: rgba(59, 130, 246, 0.1);
    border-radius: 50%;
}

.weather-info {
    flex: 1;
}

.weather-location {
    display: flex;
    align-items: center;
    gap: 4px;
    margin-left: 8px;
}

/* Loading Spinner */
.loading-spinner {
    width: 20px;
    height: 20px;
    border: 2px solid #e5e7eb;
    border-top: 2px solid #3b82f6;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

.loading-spinner-sm {
    width: 16px;
    height: 16px;
    border: 2px solid #e5e7eb;
    border-top: 2px solid #3b82f6;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% {
        transform: rotate(0deg);
    }
    100% {
        transform: rotate(360deg);
    }
}

/* Toggle Button Enhancement */
.toggle-btn {
    border: none;
    background: none;
    padding: 8px;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.toggle-btn:hover {
    background-color: rgba(69, 165, 56, 0.1);
    transform: scale(1.1);
}

/* Responsive Design */
@media (max-width: 768px) {
    .weather-widget {
        margin-left: 10px;
    }

    .top-navbar {
        padding: 10px 15px;
    }

    .mobile-clock-container {
        padding: 6px 10px;
        min-width: 70px;
    }

    .mobile-time {
        font-size: 12px;
    }

    .mobile-date {
        font-size: 9px;
    }
}

/* Extra small devices */
@media (max-width: 480px) {
    .mobile-clock-container {
        padding: 5px 8px;
        min-width: 65px;
    }

    .mobile-time {
        font-size: 11px;
        letter-spacing: 0.3px;
    }

    .mobile-date {
        font-size: 8px;
    }

    .flex-align.gap-16 {
        gap: 8px;
    }
}

/* Utility Classes */
.flex-align {
    display: flex;
    align-items: center;
}

.flex-center {
    display: flex;
    align-items: center;
    justify-content: center;
}

.flex-between {
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.text-sm {
    font-size: 0.875rem;
}

.text-xs {
    font-size: 0.75rem;
}

.text-lg {
    font-size: 1.125rem;
}

.text-2xl {
    font-size: 1.5rem;
}

.font-semibold {
    font-weight: 600;
}

.text-gray-500 {
    color: #6b7280;
}

.text-gray-600 {
    color: #4b5563;
}

.text-gray-800 {
    color: #1f2937;
}

.text-blue-600 {
    color: #2563eb;
}

.text-red-500 {
    color: #ef4444;
}

.bg-gradient-to-r {
    background-image: linear-gradient(to right, var(--tw-gradient-stops));
}

.from-blue-50 {
    --tw-gradient-from: #eff6ff;
    --tw-gradient-stops: var(--tw-gradient-from),
        var(--tw-gradient-to, rgba(239, 246, 255, 0));
}

.to-sky-50 {
    --tw-gradient-to: #f0f9ff;
}

.rounded-xl {
    border-radius: 0.75rem;
}

.rounded-lg {
    border-radius: 0.5rem;
}

.border-blue-100 {
    border-color: #dbeafe;
}

.gap-2 {
    gap: 0.5rem;
}

.gap-3 {
    gap: 0.75rem;
}

.p-2 {
    padding: 0.5rem;
}

.p-3 {
    padding: 0.75rem;
}

.mt-1 {
    margin-top: 0.25rem;
}

/* Hide mobile clock on desktop */
@media (min-width: 768px) {
    .mobile-clock-widget {
        display: none !important;
    }
}
</style>

<script>
export default {
    name: "HeaderDashboard",
    emits: ["toggle-sidebar"],
    data() {
        return {
            weatherData: null,
            weatherLoading: true,
            weatherError: null,
            currentTime: "",
            currentDate: "",
            timeInterval: null,
        };
    },
    methods: {
        toggleSidebar() {
            this.$emit("toggle-sidebar");
        },
        updateTime() {
            const now = new Date();

            // Format time (24-hour format for mobile - shorter)
            const hours = now.getHours().toString().padStart(2, "0");
            const minutes = now.getMinutes().toString().padStart(2, "0");
            this.currentTime = `${hours}:${minutes}`;

            // Format date (shorter for mobile)
            const day = now.getDate().toString().padStart(2, "0");
            const month = (now.getMonth() + 1).toString().padStart(2, "0");
            this.currentDate = `${day}/${month}`;
        },
        async fetchWeatherData() {
            try {
                this.weatherLoading = true;

                // Koordinat Malang, Indonesia
                const latitude = -7.9666;
                const longitude = 112.6326;

                const response = await fetch(
                    `https://api.open-meteo.com/v1/forecast?latitude=${latitude}&longitude=${longitude}&current_weather=true&hourly=temperature_2m,relativehumidity_2m,windspeed_10m&timezone=Asia%2FJakarta`
                );

                if (!response.ok) {
                    throw new Error("Weather data not available");
                }

                const data = await response.json();

                this.weatherData = {
                    temperature: data.current_weather.temperature,
                    windspeed: data.current_weather.windspeed,
                    weathercode: data.current_weather.weathercode,
                    humidity: data.hourly.relativehumidity_2m[0] || 0,
                };
            } catch (error) {
                console.error("Error fetching weather data:", error);
                this.weatherError = error.message;
            } finally {
                this.weatherLoading = false;
            }
        },
        getWeatherIcon(weatherCode) {
            // Weather code mapping untuk Open-Meteo API
            const weatherIcons = {
                0: "ph ph-sun", // Clear sky
                1: "ph ph-sun-dim", // Mainly clear
                2: "ph ph-cloud-sun", // Partly cloudy
                3: "ph ph-cloud", // Overcast
                45: "ph ph-cloud-fog", // Fog
                48: "ph ph-cloud-fog", // Depositing rime fog
                51: "ph ph-cloud-drizzle", // Light drizzle
                53: "ph ph-cloud-drizzle", // Moderate drizzle
                55: "ph ph-cloud-drizzle", // Dense drizzle
                61: "ph ph-cloud-rain", // Slight rain
                63: "ph ph-cloud-rain", // Moderate rain
                65: "ph ph-cloud-rain", // Heavy rain
                71: "ph ph-cloud-snow", // Slight snow
                73: "ph ph-cloud-snow", // Moderate snow
                75: "ph ph-cloud-snow", // Heavy snow
                95: "ph ph-cloud-lightning", // Thunderstorm
                96: "ph ph-cloud-lightning", // Thunderstorm with hail
                99: "ph ph-cloud-lightning", // Thunderstorm with heavy hail
            };

            return weatherIcons[weatherCode] || "ph ph-cloud";
        },
    },
    mounted() {
        // Initialize clock
        this.updateTime();
        this.timeInterval = setInterval(this.updateTime, 1000);

        // Initialize weather
        this.fetchWeatherData();

        // Update weather data setiap 10 menit
        setInterval(() => {
            this.fetchWeatherData();
        }, 600000); // 10 minutes
    },
    beforeDestroy() {
        // Clear interval when component is destroyed
        if (this.timeInterval) {
            clearInterval(this.timeInterval);
        }
    },
};
</script>
