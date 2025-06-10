<template>
    <div class="card mt-24 plant-care-card">
        <div
            class="card-header border-bottom border-gray-100 bg-gradient-to-r from-green-50 to-emerald-50"
        >
            <div class="flex-between">
                <h5 class="mb-0 text-green-800">🌱 Plant Care Center</h5>
                <div class="streak-badge">
                    <span class="text-xs text-green-600"
                        >{{ streakDays }} days streak!</span
                    >
                </div>
            </div>
        </div>
        <div class="card-body p-20">
            <!-- Next Watering Section -->
            <div
                class="next-watering-section mb-20 p-16 rounded-12 bg-blue-50 border border-blue-200"
            >
                <div class="flex-between mb-12">
                    <h6 class="text-blue-800 mb-0">💧 Next Watering</h6>
                    <span class="countdown-timer text-blue-600 font-semibold">{{
                        nextWateringCountdown
                    }}</span>
                </div>
                <div class="progress-bar-container mb-12">
                    <div
                        class="progress-bar bg-blue-100 h-6 rounded-full overflow-hidden"
                    >
                        <div
                            class="progress-fill bg-blue-500 h-full transition-all duration-500 ease-out"
                            :style="{ width: wateringProgress + '%' }"
                        ></div>
                    </div>
                </div>
                <button
                    @click="markAsWatered"
                    :disabled="isWatered"
                    class="btn-water w-100 py-8 rounded-8 text-white font-medium transition-all duration-300"
                    :class="
                        isWatered
                            ? 'bg-green-500'
                            : 'bg-blue-500 hover:bg-blue-600'
                    "
                >
                    {{ isWatered ? "✅ Watered Today!" : "💧 Mark as Watered" }}
                </button>
            </div>

            <!-- Today's Tasks -->
            <div class="tasks-section mb-20">
                <h6 class="text-gray-800 mb-12">📋 Today's Tasks</h6>
                <div class="task-list space-y-8">
                    <div
                        v-for="task in todayTasks"
                        :key="task.id"
                        class="task-item flex-between p-12 rounded-8 border transition-all duration-200"
                        :class="
                            task.completed
                                ? 'bg-green-50 border-green-200'
                                : 'bg-gray-50 border-gray-200 hover:bg-gray-100'
                        "
                    >
                        <div class="flex-align gap-8">
                            <button
                                @click="toggleTask(task.id)"
                                class="task-checkbox w-20 h-20 rounded-4 border-2 flex-center transition-all duration-200"
                                :class="
                                    task.completed
                                        ? 'bg-green-500 border-green-500'
                                        : 'border-gray-300 hover:border-green-400'
                                "
                            >
                                <i
                                    v-if="task.completed"
                                    class="fas fa-check text-white text-xs"
                                ></i>
                            </button>
                            <span
                                class="task-text text-14"
                                :class="
                                    task.completed
                                        ? 'text-green-700 line-through'
                                        : 'text-gray-700'
                                "
                            >
                                {{ task.text }}
                            </span>
                        </div>
                        <span
                            class="task-priority text-xs px-6 py-2 rounded-full"
                            :class="getPriorityClass(task.priority)"
                        >
                            {{ task.priority }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Plant Health Score -->
            <div
                class="health-section mb-20 p-16 rounded-12 bg-gradient-to-r from-emerald-50 to-green-50 border border-emerald-200"
            >
                <div class="flex-between mb-12">
                    <h6 class="text-emerald-800 mb-0">🌿 Plant Health Score</h6>
                    <span
                        class="health-score text-2xl font-bold text-emerald-600"
                        >{{ healthScore }}/100</span
                    >
                </div>
                <div class="health-indicators grid grid-cols-3 gap-8 mb-12">
                    <div class="indicator text-center">
                        <div class="indicator-icon text-2xl mb-4">💧</div>
                        <div class="indicator-value text-xs text-gray-600">
                            {{ soilMoisture }}%
                        </div>
                        <div class="indicator-label text-xs text-gray-500">
                            Moisture
                        </div>
                    </div>
                    <div class="indicator text-center">
                        <div class="indicator-icon text-2xl mb-4">🌡️</div>
                        <div class="indicator-value text-xs text-gray-600">
                            {{ temperature }}°C
                        </div>
                        <div class="indicator-label text-xs text-gray-500">
                            Temp
                        </div>
                    </div>
                    <div class="indicator text-center">
                        <div class="indicator-icon text-2xl mb-4">☀️</div>
                        <div class="indicator-value text-xs text-gray-600">
                            {{ lightLevel }}%
                        </div>
                        <div class="indicator-label text-xs text-gray-500">
                            Light
                        </div>
                    </div>
                </div>
                <div
                    class="health-tip p-10 bg-white rounded-8 border border-emerald-100"
                >
                    <p class="text-xs text-emerald-700 mb-0">
                        <strong>Tips of the Day:</strong>
                        {{ currentTip }}
                    </p>
                </div>
            </div>

            <!-- Achievement Section -->
        </div>
    </div>
</template>

<script>
export default {
    name: "PlantCareDashboard",
    data() {
        return {
            streakDays: 7,
            isWatered: false,
            wateringProgress: 75,
            nextWateringTime: new Date(Date.now() + 3 * 60 * 60 * 1000), // 3 hours from now
            healthScore: 87,
            soilMoisture: 65,
            temperature: 24,
            lightLevel: 78,
            totalAchievements: 12,
            todayTasks: [
                {
                    id: 1,
                    text: "Check soil moisture",
                    completed: true,
                    priority: "High",
                },
                {
                    id: 2,
                    text: "Apply fertilizer",
                    completed: false,
                    priority: "Medium",
                },
                {
                    id: 3,
                    text: "Remove dead leaves",
                    completed: false,
                    priority: "Low",
                },
                {
                    id: 4,
                    text: "Check for pests",
                    completed: true,
                    priority: "High",
                },
            ],
            recentAchievements: [
                { id: 1, name: "Green Thumb", icon: "🌱", earned: true },
                { id: 2, name: "Water Master", icon: "💧", earned: true },
                { id: 3, name: "Plant Whisperer", icon: "🗣️", earned: true },
                { id: 4, name: "Harvest King", icon: "👑", earned: false },
            ],
            tips: [
                "Water early morning for best absorption",
                "Check soil moisture before watering",
                "Ensure proper drainage to prevent root rot",
                "Monitor temperature changes regularly",
                "Provide adequate sunlight exposure",
            ],
        };
    },
    computed: {
        nextWateringCountdown() {
            const now = new Date();
            const timeDiff = this.nextWateringTime - now;

            if (timeDiff <= 0) {
                return "Time to water!";
            }

            const hours = Math.floor(timeDiff / (1000 * 60 * 60));
            const minutes = Math.floor(
                (timeDiff % (1000 * 60 * 60)) / (1000 * 60)
            );

            return `${hours}h ${minutes}m`;
        },
        currentTip() {
            const tipIndex =
                Math.floor(Date.now() / (1000 * 60 * 60 * 24)) %
                this.tips.length;
            return this.tips[tipIndex];
        },
    },
    methods: {
        markAsWatered() {
            if (!this.isWatered) {
                this.isWatered = true;
                this.streakDays++;
                this.wateringProgress = 100;
                this.nextWateringTime = new Date(
                    Date.now() + 24 * 60 * 60 * 1000
                ); // Next day

                // Reset after animation
                setTimeout(() => {
                    this.wateringProgress = 0;
                }, 2000);

                // Add achievement for consistency
                if (this.streakDays === 7) {
                    this.addAchievement("Consistent Caretaker", "📅");
                }
            }
        },
        toggleTask(taskId) {
            const task = this.todayTasks.find((t) => t.id === taskId);
            if (task) {
                task.completed = !task.completed;
                this.updateHealthScore();
            }
        },
        getPriorityClass(priority) {
            switch (priority) {
                case "High":
                    return "bg-red-100 text-red-700";
                case "Medium":
                    return "bg-yellow-100 text-yellow-700";
                case "Low":
                    return "bg-green-100 text-green-700";
                default:
                    return "bg-gray-100 text-gray-700";
            }
        },
        updateHealthScore() {
            const completedTasks = this.todayTasks.filter(
                (t) => t.completed
            ).length;
            const totalTasks = this.todayTasks.length;
            const taskScore = (completedTasks / totalTasks) * 40;

            // Base score from environmental factors
            const envScore =
                ((this.soilMoisture + this.temperature + this.lightLevel) / 3) *
                0.6;

            this.healthScore = Math.min(100, Math.round(taskScore + envScore));
        },
        addAchievement(name, icon) {
            // Logic to add new achievement
            console.log(`New achievement unlocked: ${name} ${icon}`);
        },
    },
    mounted() {
        // Update countdown every minute
        setInterval(() => {
            this.$forceUpdate();
        }, 60000);

        // Simulate real-time sensor data updates
        setInterval(() => {
            this.soilMoisture = Math.floor(Math.random() * 30) + 50; // 50-80%
            this.temperature = Math.floor(Math.random() * 10) + 20; // 20-30°C
            this.lightLevel = Math.floor(Math.random() * 40) + 60; // 60-100%
            this.updateHealthScore();
        }, 10000);
    },
};
</script>

<style scoped>
.plant-care-card {
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    border: none;
    border-radius: 16px;
    overflow: hidden;
}

.streak-badge {
    background: linear-gradient(135deg, #10b981, #059669);
    padding: 4px 12px;
    border-radius: 20px;
    color: white;
    font-size: 11px;
    font-weight: 600;
}

.progress-bar {
    position: relative;
    overflow: hidden;
}

.progress-fill {
    background: linear-gradient(90deg, #3b82f6, #1d4ed8);
    position: relative;
}

.progress-fill::after {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    bottom: 0;
    right: 0;
    background-image: linear-gradient(
        45deg,
        rgba(255, 255, 255, 0.2) 25%,
        transparent 25%,
        transparent 50%,
        rgba(255, 255, 255, 0.2) 50%,
        rgba(255, 255, 255, 0.2) 75%,
        transparent 75%,
        transparent
    );
    background-size: 20px 20px;
    animation: move 1s linear infinite;
}

@keyframes move {
    0% {
        background-position: 0 0;
    }
    100% {
        background-position: 20px 20px;
    }
}

.btn-water {
    position: relative;
    overflow: hidden;
    transform: translateY(0);
    box-shadow: 0 2px 8px rgba(59, 130, 246, 0.3);
}

.btn-water:hover:not(:disabled) {
    transform: translateY(-2px);
    box-shadow: 0 4px 16px rgba(59, 130, 246, 0.4);
}

.btn-water:disabled {
    box-shadow: 0 2px 8px rgba(34, 197, 94, 0.3);
}

.task-checkbox {
    position: relative;
    cursor: pointer;
}

.task-checkbox:hover {
    transform: scale(1.1);
}

.achievement-badge {
    cursor: pointer;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.achievement-badge:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.health-section {
    background: linear-gradient(135deg, #ecfdf5, #f0fdf4);
}

.indicator {
    transition: transform 0.3s ease;
}

.indicator:hover {
    transform: scale(1.05);
}

/* Utility classes for better compatibility */
.flex-between {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.flex-align {
    display: flex;
    align-items: center;
}

.flex-center {
    display: flex;
    align-items: center;
    justify-content: center;
}

.grid {
    display: grid;
}

.grid-cols-3 {
    grid-template-columns: repeat(3, 1fr);
}

.grid-cols-4 {
    grid-template-columns: repeat(4, 1fr);
}

.space-y-8 > * + * {
    margin-top: 8px;
}

.gap-8 {
    gap: 8px;
}

.gap-4 {
    gap: 4px;
}

/* Color utilities */
.text-green-800 {
    color: #166534;
}
.text-green-600 {
    color: #16a34a;
}
.text-green-700 {
    color: #15803d;
}
.text-blue-800 {
    color: #1e40af;
}
.text-blue-600 {
    color: #2563eb;
}
.text-emerald-800 {
    color: #065f46;
}
.text-emerald-600 {
    color: #059669;
}
.text-emerald-700 {
    color: #047857;
}
.text-yellow-500 {
    color: #eab308;
}
.text-yellow-700 {
    color: #a16207;
}
.text-red-700 {
    color: #b91c1c;
}

.bg-green-50 {
    background-color: #f0fdf4;
}
.bg-blue-50 {
    background-color: #eff6ff;
}
.bg-emerald-50 {
    background-color: #ecfdf5;
}
.bg-yellow-50 {
    background-color: #fefce8;
}
.bg-red-100 {
    background-color: #fee2e2;
}
.bg-yellow-100 {
    background-color: #fef3c7;
}
.bg-green-100 {
    background-color: #dcfce7;
}

.border-green-200 {
    border-color: #bbf7d0;
}
.border-blue-200 {
    border-color: #bfdbfe;
}
.border-emerald-200 {
    border-color: #a7f3d0;
}
.border-emerald-100 {
    border-color: #d1fae5;
}
.border-yellow-300 {
    border-color: #fde047;
}

.line-through {
    text-decoration: line-through;
}
</style>
