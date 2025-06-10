<template>
    <div class="auto-watering-container">
        <!-- Main Auto Watering Button -->
        <div class="watering-button-card">
            <div class="card-header">
                <div class="flex-between">
                    <h5 class="card-title">💧 Auto Watering System</h5>
                    <div class="system-status" :class="systemStatus.class">
                        <div class="status-indicator"></div>
                        <span class="status-text">{{ systemStatus.text }}</span>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <!-- Main Control Button -->
                <div class="main-button-container">
                    <button
                        @click="toggleWatering"
                        :disabled="isSystemMaintenance || isLowWater"
                        class="main-watering-btn"
                        :class="getButtonClass()"
                    >
                        <div class="button-content">
                            <div class="button-icon">
                                <i
                                    v-if="!isWatering && !isLoading"
                                    class="fas fa-play"
                                ></i>
                                <i
                                    v-else-if="isWatering"
                                    class="fas fa-pause"
                                ></i>
                                <div v-else class="loading-spinner"></div>
                            </div>
                            <div class="button-text">
                                <div class="main-text">
                                    {{ getButtonText() }}
                                </div>
                                <div class="sub-text">{{ getSubText() }}</div>
                            </div>
                        </div>

                        <!-- Water Flow Animation -->
                        <div v-if="isWatering" class="water-animation">
                            <div
                                class="water-drop"
                                v-for="n in 6"
                                :key="n"
                                :style="{ animationDelay: n * 0.2 + 's' }"
                            ></div>
                        </div>

                        <!-- Progress Ring -->
                        <div v-if="isWatering" class="progress-ring">
                            <svg
                                class="progress-ring-svg"
                                width="120"
                                height="120"
                            >
                                <circle
                                    class="progress-ring-circle-bg"
                                    stroke="#e5e7eb"
                                    stroke-width="4"
                                    fill="transparent"
                                    r="54"
                                    cx="60"
                                    cy="60"
                                />
                                <circle
                                    class="progress-ring-circle"
                                    :stroke="getProgressColor()"
                                    stroke-width="4"
                                    fill="transparent"
                                    r="54"
                                    cx="60"
                                    cy="60"
                                    :style="{
                                        strokeDasharray: circumference,
                                        strokeDashoffset: strokeDashoffset,
                                    }"
                                />
                            </svg>
                            <div class="progress-text">
                                <div class="progress-percentage">
                                    {{ wateringProgress }}%
                                </div>
                                <div class="progress-label">Complete</div>
                            </div>
                        </div>
                    </button>
                </div>

                <!-- Quick Actions -->
                <div class="quick-actions">
                    <div class="action-row">
                        <button
                            @click="startScheduledWatering"
                            :disabled="isWatering || isSystemMaintenance"
                            class="quick-btn schedule-btn"
                        >
                            <i class="fas fa-clock"></i>
                            <span>Schedule</span>
                        </button>

                        <button
                            @click="emergencyWatering"
                            :disabled="
                                isWatering || isSystemMaintenance || isLowWater
                            "
                            class="quick-btn emergency-btn"
                        >
                            <i class="fas fa-exclamation-triangle"></i>
                            <span>Emergency</span>
                        </button>

                        <button
                            @click="testSystem"
                            :disabled="isWatering || isSystemMaintenance"
                            class="quick-btn test-btn"
                        >
                            <i class="fas fa-flask"></i>
                            <span>Test</span>
                        </button>
                    </div>
                </div>

                <!-- System Info -->
                <div class="system-info">
                    <div class="info-grid">
                        <div class="info-item">
                            <div
                                class="info-icon water-level"
                                :class="getWaterLevelClass()"
                            >
                                <i class="fas fa-tint"></i>
                            </div>
                            <div class="info-content">
                                <div class="info-label">Water Tank</div>
                                <div class="info-value">{{ waterLevel }}%</div>
                            </div>
                        </div>

                        <div class="info-item">
                            <div
                                class="info-icon pressure"
                                :class="getPressureClass()"
                            >
                                <i class="fas fa-gauge-high"></i>
                            </div>
                            <div class="info-content">
                                <div class="info-label">Pressure</div>
                                <div class="info-value">{{ pressure }} PSI</div>
                            </div>
                        </div>

                        <div class="info-item">
                            <div class="info-icon flow-rate">
                                <i class="fas fa-water"></i>
                            </div>
                            <div class="info-content">
                                <div class="info-label">Flow Rate</div>
                                <div class="info-value">
                                    {{ flowRate }} L/min
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Schedule Modal -->
        <div
            v-if="showScheduleModal"
            class="modal-overlay"
            @click="closeScheduleModal"
        >
            <div class="schedule-modal" @click.stop>
                <div class="modal-header">
                    <h6>⏰ Schedule Watering</h6>
                    <button @click="closeScheduleModal" class="close-btn">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="schedule-form">
                        <div class="form-group">
                            <label>Duration (minutes)</label>
                            <input
                                v-model="scheduleDuration"
                                type="number"
                                min="1"
                                max="60"
                                class="form-input"
                            />
                        </div>
                        <div class="form-group">
                            <label>Start Time</label>
                            <input
                                v-model="scheduleTime"
                                type="time"
                                class="form-input"
                            />
                        </div>
                        <div class="form-group">
                            <label>Repeat</label>
                            <select v-model="scheduleRepeat" class="form-input">
                                <option value="once">Once</option>
                                <option value="daily">Daily</option>
                                <option value="weekly">Weekly</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button @click="closeScheduleModal" class="btn-secondary">
                        Cancel
                    </button>
                    <button @click="confirmSchedule" class="btn-primary">
                        Schedule
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "AutoWateringButton",
    data() {
        return {
            isWatering: false,
            isLoading: false,
            isSystemMaintenance: false,
            wateringProgress: 0,
            wateringDuration: 300, // 5 minutes in seconds
            currentWateringTime: 0,
            waterLevel: 85,
            pressure: 45,
            flowRate: 2.5,
            isLowWater: false,
            showScheduleModal: false,
            scheduleDuration: 10,
            scheduleTime: "",
            scheduleRepeat: "once",
            recentActivities: [
                {
                    id: 1,
                    type: "success",
                    icon: "fas fa-check-circle",
                    text: "Watering completed successfully",
                    time: "2 hours ago",
                },
                {
                    id: 2,
                    type: "warning",
                    icon: "fas fa-exclamation-triangle",
                    text: "Low water tank warning",
                    time: "1 day ago",
                },
                {
                    id: 3,
                    type: "info",
                    icon: "fas fa-cog",
                    text: "System maintenance completed",
                    time: "2 days ago",
                },
            ],
            wateringInterval: null,
            circumference: 2 * Math.PI * 54,
        };
    },
    computed: {
        systemStatus() {
            if (this.isSystemMaintenance) {
                return { class: "maintenance", text: "Maintenance" };
            } else if (this.isLowWater) {
                return { class: "warning", text: "Low Water" };
            } else if (this.isWatering) {
                return { class: "active", text: "Watering" };
            } else {
                return { class: "ready", text: "Ready" };
            }
        },
        strokeDashoffset() {
            const progress = this.wateringProgress / 100;
            return this.circumference - progress * this.circumference;
        },
    },
    methods: {
        toggleWatering() {
            if (this.isWatering) {
                this.stopWatering();
            } else {
                this.startWatering();
            }
        },

        startWatering() {
            this.isLoading = true;

            // Simulate connection delay
            setTimeout(() => {
                this.isLoading = false;
                this.isWatering = true;
                this.wateringProgress = 0;
                this.currentWateringTime = 0;

                // Add activity
                this.addActivity(
                    "info",
                    "fas fa-play-circle",
                    "Watering started"
                );

                // Start progress
                this.wateringInterval = setInterval(() => {
                    this.currentWateringTime += 1;
                    this.wateringProgress = Math.min(
                        100,
                        (this.currentWateringTime / this.wateringDuration) * 100
                    );

                    // Simulate water consumption
                    if (this.waterLevel > 0) {
                        this.waterLevel = Math.max(0, this.waterLevel - 0.05);
                    }

                    if (this.wateringProgress >= 100) {
                        this.completeWatering();
                    }
                }, 1000);
            }, 1500);
        },

        stopWatering() {
            if (this.wateringInterval) {
                clearInterval(this.wateringInterval);
                this.wateringInterval = null;
            }

            this.isWatering = false;
            this.addActivity(
                "warning",
                "fas fa-stop-circle",
                "Watering stopped manually"
            );
        },

        completeWatering() {
            if (this.wateringInterval) {
                clearInterval(this.wateringInterval);
                this.wateringInterval = null;
            }

            this.isWatering = false;
            this.wateringProgress = 100;
            this.addActivity(
                "success",
                "fas fa-check-circle",
                "Watering completed successfully"
            );

            // Reset progress after delay
            setTimeout(() => {
                this.wateringProgress = 0;
            }, 3000);
        },

        emergencyWatering() {
            this.wateringDuration = 60; // 1 minute for emergency
            this.startWatering();
            this.addActivity(
                "warning",
                "fas fa-exclamation-triangle",
                "Emergency watering activated"
            );
        },

        testSystem() {
            this.isLoading = true;

            setTimeout(() => {
                this.isLoading = false;
                this.addActivity(
                    "info",
                    "fas fa-flask",
                    "System test completed"
                );
            }, 2000);
        },

        startScheduledWatering() {
            this.showScheduleModal = true;
        },

        closeScheduleModal() {
            this.showScheduleModal = false;
        },

        confirmSchedule() {
            this.addActivity(
                "info",
                "fas fa-clock",
                `Watering scheduled for ${this.scheduleTime}`
            );
            this.closeScheduleModal();
        },

        addActivity(type, icon, text) {
            const newActivity = {
                id: Date.now(),
                type,
                icon,
                text,
                time: "Just now",
            };

            this.recentActivities.unshift(newActivity);
            if (this.recentActivities.length > 5) {
                this.recentActivities.pop();
            }
        },

        getButtonClass() {
            if (this.isSystemMaintenance) return "maintenance";
            if (this.isLowWater) return "disabled";
            if (this.isWatering) return "watering";
            if (this.isLoading) return "loading";
            return "ready";
        },

        getButtonText() {
            if (this.isLoading) return "Connecting...";
            if (this.isWatering) return "Stop Watering";
            if (this.isSystemMaintenance) return "Maintenance Mode";
            if (this.isLowWater) return "Low Water Tank";
            return "Start Watering";
        },

        getSubText() {
            if (this.isWatering) return `${this.wateringProgress}% Complete`;
            if (this.isSystemMaintenance) return "System unavailable";
            if (this.isLowWater) return "Refill required";
            return "Tap to activate";
        },

        getProgressColor() {
            if (this.wateringProgress < 30) return "#3b82f6";
            if (this.wateringProgress < 70) return "#10b981";
            return "#059669";
        },

        getWaterLevelClass() {
            if (this.waterLevel > 50) return "good";
            if (this.waterLevel > 20) return "warning";
            return "critical";
        },

        getPressureClass() {
            if (this.pressure > 30) return "good";
            if (this.pressure > 15) return "warning";
            return "critical";
        },
    },

    mounted() {
        // Simulate real-time data updates
        setInterval(() => {
            if (!this.isWatering) {
                this.pressure = Math.floor(Math.random() * 10) + 40; // 40-50 PSI
                this.flowRate = (Math.random() * 0.5 + 2.2).toFixed(1); // 2.2-2.7 L/min
            }

            // Check for low water
            this.isLowWater = this.waterLevel < 20;
        }, 5000);
    },

    beforeDestroy() {
        if (this.wateringInterval) {
            clearInterval(this.wateringInterval);
        }
    },
};
</script>

<style scoped>
.auto-watering-container {
    max-width: 400px;
    margin: 0 auto;
}

.watering-button-card {
    background: white;
    border-radius: 20px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    border: 1px solid #e5e7eb;
}

.card-header {
    padding: 20px;
    background: linear-gradient(135deg, #f0f9ff, #e0f2fe);
    border-bottom: 1px solid #e5e7eb;
}

.card-title {
    margin: 0;
    color: #1e40af;
    font-weight: 600;
}

.system-status {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 500;
}

.status-indicator {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    animation: pulse 2s infinite;
}

.system-status.ready {
    background: #f0fdf4;
    color: #166534;
}

.system-status.ready .status-indicator {
    background: #22c55e;
}

.system-status.active {
    background: #eff6ff;
    color: #1d4ed8;
}

.system-status.active .status-indicator {
    background: #3b82f6;
}

.system-status.warning {
    background: #fefce8;
    color: #a16207;
}

.system-status.warning .status-indicator {
    background: #eab308;
}

.system-status.maintenance {
    background: #fef2f2;
    color: #dc2626;
}

.system-status.maintenance .status-indicator {
    background: #ef4444;
}

.card-body {
    padding: 20px;
}

.main-button-container {
    text-align: center;
    margin-bottom: 24px;
    position: relative;
}

.main-watering-btn {
    width: 150px;
    height: 150px;
    border-radius: 50%;
    border: none;
    cursor: pointer;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.12);
}

.main-watering-btn.ready {
    background: linear-gradient(135deg, #22c55e, #16a34a);
    color: white;
}

.main-watering-btn.ready:hover {
    transform: scale(1.05);
    box-shadow: 0 12px 40px rgba(34, 197, 94, 0.3);
}

.main-watering-btn.watering {
    background: linear-gradient(135deg, #ef4444, #dc2626);
    color: white;
}

.main-watering-btn.loading {
    background: linear-gradient(135deg, #6b7280, #4b5563);
    color: white;
    cursor: not-allowed;
}

.main-watering-btn.disabled {
    background: linear-gradient(135deg, #9ca3af, #6b7280);
    color: white;
    cursor: not-allowed;
    opacity: 0.7;
}

.button-content {
    position: relative;
    z-index: 2;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 100%;
}

.button-icon {
    font-size: 24px;
    margin-bottom: 8px;
}

.button-text {
    text-align: center;
}

.main-text {
    font-size: 12px;
}

.sub-text {
    font-size: 10px;
}

.loading-spinner {
    width: 24px;
    height: 24px;
    border: 3px solid rgba(255, 255, 255, 0.3);
    border-top: 3px solid white;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

.water-animation {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    pointer-events: none;
}

.water-drop {
    position: absolute;
    width: 4px;
    height: 4px;
    background: rgba(255, 255, 255, 0.8);
    border-radius: 50%;
    animation: waterDrop 2s infinite;
}

.water-drop:nth-child(1) {
    left: 30%;
}
.water-drop:nth-child(2) {
    left: 45%;
}
.water-drop:nth-child(3) {
    left: 60%;
}
.water-drop:nth-child(4) {
    left: 35%;
}
.water-drop:nth-child(5) {
    left: 50%;
}
.water-drop:nth-child(6) {
    left: 65%;
}

.progress-ring {
    position: absolute;
    top: -5px;
    left: -5px;
    width: 130px;
    height: 130px;
    pointer-events: none;
}

.progress-ring-svg {
    transform: rotate(-90deg);
}

.progress-ring-circle {
    transition: stroke-dashoffset 0.5s ease;
}

.progress-text {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    text-align: center;
    color: white;
    font-weight: 600;
}

.progress-percentage {
    font-size: 16px;
}

.progress-label {
    font-size: 10px;
    opacity: 0.8;
}

.quick-actions {
    margin-bottom: 24px;
}

.action-row {
    display: flex;
    gap: 12px;
    justify-content: center;
}

.quick-btn {
    flex: 1;
    padding: 12px 8px;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    background: white;
    cursor: pointer;
    transition: all 0.2s ease;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 4px;
    font-size: 12px;
}

.quick-btn:hover:not(:disabled) {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.quick-btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.schedule-btn {
    color: #3b82f6;
    border-color: #bfdbfe;
}

.emergency-btn {
    color: #ef4444;
    border-color: #fecaca;
}

.test-btn {
    color: #8b5cf6;
    border-color: #ddd6fe;
}

.system-info {
    background: #f8fafc;
    border-radius: 12px;
    padding: 16px;
    margin-bottom: 20px;
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 16px;
}

.info-item {
    display: flex;
    align-items: center;
    gap: 8px;
}

.info-icon {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    color: white;
}

.info-icon.good {
    background: #22c55e;
}

.info-icon.warning {
    background: #f59e0b;
}

.info-icon.critical {
    background: #ef4444;
}

.info-content {
    flex: 1;
}

.info-label {
    font-size: 11px;
    color: #6b7280;
    line-height: 1;
}

.info-value {
    font-size: 14px;
    font-weight: 600;
    color: #1f2937;
    line-height: 1;
    margin-top: 2px;
}

.last-watering-info {
    background: #f8fafc;
    border-radius: 12px;
    padding: 16px;
}

.info-header {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 12px;
    font-size: 13px;
    font-weight: 600;
    color: #374151;
}

.activity-list {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.activity-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 8px 0;
}

.activity-icon {
    width: 24px;
    height: 24px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 10px;
    color: white;
}

.activity-icon.success {
    background: #22c55e;
}

.activity-icon.warning {
    background: #f59e0b;
}

.activity-icon.info {
    background: #3b82f6;
}

.activity-content {
    flex: 1;
}

.activity-text {
    font-size: 12px;
    color: #374151;
    line-height: 1.2;
}

.activity-time {
    font-size: 10px;
    color: #9ca3af;
    margin-top: 2px;
}

.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1000;
}

.schedule-modal {
    background: white;
    border-radius: 16px;
    width: 90%;
    max-width: 400px;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
}

.modal-header {
    padding: 20px 20px 0;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modal-header h6 {
    margin: 0;
    color: #1f2937;
    font-weight: 600;
}

.close-btn {
    background: none;
    border: none;
    font-size: 16px;
    color: #6b7280;
    cursor: pointer;
    padding: 4px;
}

.modal-body {
    padding: 20px;
}

.schedule-form {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.form-group {
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.form-group label {
    font-size: 13px;
    font-weight: 500;
    color: #374151;
}

.form-input {
    padding: 10px 12px;
    border: 1px solid #d1d5db;
    border-radius: 8px;
    font-size: 14px;
    transition: border-color 0.2s ease;
}

.form-input:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.modal-footer {
    padding: 0 20px 20px;
    display: flex;
    gap: 12px;
    justify-content: flex-end;
}

.btn-secondary,
.btn-primary {
    padding: 10px 20px;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s ease;
}

.btn-secondary {
    background: #f3f4f6;
    border: 1px solid #d1d5db;
    color: #374151;
}

.btn-secondary:hover {
    background: #e5e7eb;
}

.btn-primary {
    background: #3b82f6;
    border: 1px solid #3b82f6;
    color: white;
}

.btn-primary:hover {
    background: #2563eb;
}

/* Utility classes */
.flex-between {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

/* Animations */
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
    from {
        transform: rotate(0deg);
    }
    to {
        transform: rotate(360deg);
    }
}

@keyframes waterDrop {
    0% {
        top: 20%;
        opacity: 0;
    }
    20% {
        opacity: 1;
    }
    80% {
        opacity: 1;
    }
    100% {
        top: 80%;
        opacity: 0;
    }
}

/* Responsive */
@media (max-width: 480px) {
    .info-grid {
        grid-template-columns: 1fr;
        gap: 12px;
    }

    .action-row {
        flex-direction: column;
    }

    .main-watering-btn {
        width: 120px;
        height: 120px;
    }

    .button-icon {
        font-size: 20px;
    }

    .main-text {
        font-size: 12px;
    }

    .sub-text {
        font-size: 10px;
    }

    .progress-ring {
        width: 130px;
        height: 130px;
        top: -5px;
        left: -5px;
    }
}

/* Extra Responsive for very small screens */
@media (max-width: 350px) {
    .main-watering-btn {
        width: 90px;
        height: 90px;
    }
    .progress-ring {
        width: 100px;
        height: 100px;
        top: 0;
        left: 0;
    }
    .progress-text {
        font-size: 12px;
    }
}
</style>
