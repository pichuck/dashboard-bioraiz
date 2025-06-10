<template>
    <!-- ============================ Sidebar Start ============================ -->
    <aside class="sidebar" ref="sidebarElement">
        <!-- sidebar close btn -->
        <button
            type="button"
            class="sidebar-close-btn text-gray-500 hover-text-white hover-bg-main-600 text-md w-24 h-24 border border-gray-100 hover-border-main-600 d-xl-none d-flex flex-center rounded-circle position-absolute"
            @click="closeSidebar"
        >
            <i class="ph ph-x"></i>
        </button>
        <!-- sidebar close btn -->

        <!-- Logo Section with Enhanced Container -->
        <a
            href="index.html"
            class="sidebar__logo text-center p-20 position-sticky inset-block-start-0 bg-white w-100 z-1 pb-10"
        >
            <div class="logo-container">
                <div class="logo-content">
                    <i class="ph ph-leaf logo-icon"></i>
                    <span class="logo-text">Bioraiz</span>
                </div>
            </div>
        </a>

        <div class="sidebar-menu-wrapper overflow-y-auto scroll-sm">
            <div class="p-20 pt-10">
                <ul class="sidebar-menu">
                    <li class="sidebar-menu__item">
                        <a href="javascript:void(0)" class="sidebar-menu__link">
                            <span class="icon">
                                <i class="ph ph-squares-four"></i>
                            </span>
                            <span class="text">Dashboard</span>
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Clock Widget at Bottom -->
            <div class="clock-widget">
                <div class="clock-container">
                    <div class="clock-header">
                        <i class="ph ph-clock clock-header-icon"></i>
                        <span class="clock-title">Current Time</span>
                    </div>
                    <div class="clock-display">
                        <div class="time-display">{{ currentTime }}</div>
                        <div class="date-display">{{ currentDate }}</div>
                    </div>
                    <div class="clock-decoration">
                        <div class="clock-pulse"></div>
                    </div>
                </div>
            </div>
        </div>
    </aside>
    <!-- ============================ Sidebar End  ============================ -->
</template>

<style scoped>
/* Enhanced Logo Container Styles */
.logo-container {
    border-radius: 16px;
    padding: 20px 24px;
    margin: 0 auto;
    display: inline-block;
    backdrop-filter: blur(10px);
    box-shadow: 0 4px 20px rgba(69, 165, 56, 0.15);
    border: 1px solid rgba(69, 165, 56, 0.2);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
}

.logo-container::before {
    content: "";
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(
        90deg,
        transparent,
        rgba(255, 255, 255, 0.4),
        transparent
    );
    transition: left 0.5s;
}

.logo-container:hover::before {
    left: 100%;
}

.logo-container:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 30px rgba(69, 165, 56, 0.25);
    background-color: rgba(207, 255, 200, 0.6);
}

.logo-content {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 12px;
    position: relative;
    z-index: 1;
}

.logo-icon {
    font-size: 32px;
    color: #2d5016;
    background: linear-gradient(135deg, #45a538, #2d5016);
    -webkit-background-clip: text;
    background-clip: text;
    -webkit-text-fill-color: transparent;
    filter: drop-shadow(0 2px 4px rgba(45, 80, 22, 0.3));
    transition: all 0.3s ease;
}

.logo-container:hover .logo-icon {
    transform: rotate(10deg) scale(1.1);
    filter: drop-shadow(0 4px 8px rgba(174, 255, 120, 0.4));
}

.logo-text {
    font-size: 28px;
    font-weight: 700;
    color: #2d5016;
    letter-spacing: 1px;
    text-shadow: 0 2px 4px rgba(45, 80, 22, 0.1);
    background: linear-gradient(135deg, #2d5016, #45a538);
    -webkit-background-clip: text;
    background-clip: text;
    -webkit-text-fill-color: transparent;
    transition: all 0.3s ease;
}

.logo-container:hover .logo-text {
    transform: scale(1.05);
    text-shadow: 0 4px 8px rgba(45, 80, 22, 0.2);
}

/* Enhanced Sidebar Styles - FIXED POSITIONING */
.sidebar {
    background: linear-gradient(180deg, #ffffff 0%, #fafafa 100%);
    border-right: 1px solid rgba(69, 165, 56, 0.1);
    box-shadow: 2px 0 20px rgba(0, 0, 0, 0.08);
    display: flex;
    flex-direction: column;
    height: 100vh;
    width: 280px;
    position: fixed; /* Selalu fixed di semua breakpoint */
    top: 0;
    left: 0;
    z-index: 1000;
    transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Desktop - sidebar visible dan tetap fixed */
@media (min-width: 1200px) {
    .sidebar {
        transform: translateX(0);
    }
}

/* Mobile - sidebar hidden by default */
@media (max-width: 1199px) {
    .sidebar {
        transform: translateX(-100%);
    }

    /* Show sidebar when parent has sidebar-active class */
    .sidebar-active .sidebar {
        transform: translateX(0);
    }
}

.sidebar__logo {
    background: linear-gradient(180deg, #ffffff 0%, #f9f9f9 100%);
    border-bottom: 1px solid rgba(69, 165, 56, 0.1);
    flex-shrink: 0;
}

.sidebar-menu-wrapper {
    flex: 1;
    display: flex;
    flex-direction: column;
}

/* Enhanced Menu Styles */
.sidebar-menu__link {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    border-radius: 12px;
    margin-bottom: 4px;
    position: relative;
    overflow: hidden;
}

.sidebar-menu__link::before {
    content: "";
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(
        90deg,
        transparent,
        rgba(69, 165, 56, 0.1),
        transparent
    );
    transition: left 0.3s;
}

.sidebar-menu__link:hover::before {
    left: 100%;
}

.sidebar-menu__link:hover {
    background-color: rgba(69, 165, 56, 0.08);
    color: #45a538;
    transform: translateX(4px);
    box-shadow: 0 2px 8px rgba(69, 165, 56, 0.1);
}

.sidebar-menu__link:hover .icon {
    transform: scale(1.1);
}

.sidebar-menu__link .icon {
    transition: transform 0.3s ease;
}

/* Clock Widget Styles */
.clock-widget {
    margin-top: auto;
    padding: 20px;
    flex-shrink: 0;
}

.clock-container {
    background: linear-gradient(135deg, #45a538, #2d5016);
    border-radius: 20px;
    padding: 20px;
    position: relative;
    overflow: hidden;
    box-shadow: 0 8px 32px rgba(69, 165, 56, 0.3);
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.clock-container::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(
        45deg,
        transparent 30%,
        rgba(255, 255, 255, 0.1) 50%,
        transparent 70%
    );
    transform: translateX(-100%);
    animation: shimmer 3s infinite;
}

@keyframes shimmer {
    0% {
        transform: translateX(-100%);
    }
    100% {
        transform: translateX(100%);
    }
}

.clock-header {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 16px;
    color: rgba(255, 255, 255, 0.9);
}

.clock-header-icon {
    font-size: 18px;
    color: #aeff78;
}

.clock-title {
    font-size: 14px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.clock-display {
    text-align: center;
    position: relative;
    z-index: 1;
}

.time-display {
    font-size: 24px;
    font-weight: 700;
    color: #ffffff;
    margin-bottom: 8px;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
    font-family: "Courier New", monospace;
    letter-spacing: 1px;
}

.date-display {
    font-size: 14px;
    color: #aeff78;
    font-weight: 500;
    text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
}

.clock-decoration {
    position: absolute;
    top: 50%;
    right: 20px;
    transform: translateY(-50%);
}

.clock-pulse {
    width: 12px;
    height: 12px;
    background: #aeff78;
    border-radius: 50%;
    position: relative;
    animation: pulse 2s infinite;
}

.clock-pulse::before {
    content: "";
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 100%;
    height: 100%;
    background: #aeff78;
    border-radius: 50%;
    animation: pulse-ring 2s infinite;
}

@keyframes pulse {
    0%,
    100% {
        opacity: 1;
        transform: scale(1);
    }
    50% {
        opacity: 0.8;
        transform: scale(1.1);
    }
}

@keyframes pulse-ring {
    0% {
        transform: translate(-50%, -50%) scale(1);
        opacity: 1;
    }
    100% {
        transform: translate(-50%, -50%) scale(2);
        opacity: 0;
    }
}

/* Enhanced Close Button */
.sidebar-close-btn {
    top: 20px;
    right: 20px;
    z-index: 1001;
}

.sidebar-close-btn:hover {
    background-color: #45a538 !important;
    border-color: #45a538 !important;
    transform: rotate(90deg);
}

/* Scrollbar Enhancement */
.sidebar-menu-wrapper::-webkit-scrollbar {
    width: 6px;
}

.sidebar-menu-wrapper::-webkit-scrollbar-track {
    background: rgba(69, 165, 56, 0.05);
    border-radius: 3px;
}

.sidebar-menu-wrapper::-webkit-scrollbar-thumb {
    background: linear-gradient(180deg, #45a538, #2d5016);
    border-radius: 3px;
}

.sidebar-menu-wrapper::-webkit-scrollbar-thumb:hover {
    background: linear-gradient(180deg, #2d5016, #45a538);
}

/* Responsive Enhancements */
@media (max-width: 1200px) {
    .logo-container {
        padding: 16px 20px;
    }

    .logo-text {
        font-size: 24px;
    }

    .logo-icon {
        font-size: 28px;
    }

    .time-display {
        font-size: 20px;
    }

    .clock-container {
        padding: 16px;
    }
}

/* Animation Keyframes */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.sidebar-menu__item {
    animation: fadeInUp 0.5s ease forwards;
}

.sidebar-menu__item:nth-child(1) {
    animation-delay: 0.1s;
}
.sidebar-menu__item:nth-child(2) {
    animation-delay: 0.2s;
}
.sidebar-menu__item:nth-child(3) {
    animation-delay: 0.3s;
}
.sidebar-menu__item:nth-child(4) {
    animation-delay: 0.4s;
}
.sidebar-menu__item:nth-child(5) {
    animation-delay: 0.5s;
}

.clock-widget {
    animation: fadeInUp 0.5s ease forwards;
    animation-delay: 0.6s;
    opacity: 0;
}

/* Utility classes */
.flex-center {
    display: flex;
    align-items: center;
    justify-content: center;
}

.position-absolute {
    position: absolute;
}

.position-sticky {
    position: sticky;
}

.position-relative {
    position: relative;
}

.d-flex {
    display: flex;
}

.d-xl-none {
    display: flex;
}

@media (min-width: 1200px) {
    .d-xl-none {
        display: none !important;
    }
}

.text-center {
    text-align: center;
}

.w-100 {
    width: 100%;
}

.h-24 {
    height: 24px;
}

.w-24 {
    width: 24px;
}

.z-1 {
    z-index: 1;
}

.p-20 {
    padding: 20px;
}

.pt-10 {
    padding-top: 10px;
}

.pb-10 {
    padding-bottom: 10px;
}

.mb-4 {
    margin-bottom: 4px;
}

.overflow-y-auto {
    overflow-y: auto;
}

.rounded-circle {
    border-radius: 50%;
}

.border {
    border: 1px solid;
}

.border-gray-100 {
    border-color: #f3f4f6;
}

.text-gray-500 {
    color: #6b7280;
}

.hover-text-white:hover {
    color: white !important;
}

.hover-bg-main-600:hover {
    background-color: #45a538 !important;
}

.hover-border-main-600:hover {
    border-color: #45a538 !important;
}

.text-md {
    font-size: 1rem;
}

.inset-block-start-0 {
    top: 0;
}

.bg-white {
    background-color: white;
}
</style>

<script>
export default {
    name: "Sidebar",
    emits: ["close-sidebar"],
    data() {
        return {
            currentTime: "",
            currentDate: "",
            timeInterval: null,
        };
    },
    mounted() {
        // Initialize clock
        this.updateTime();
        this.timeInterval = setInterval(this.updateTime, 1000);

        // Safe animation using ref instead of $el
        this.$nextTick(() => {
            if (this.$refs.sidebarElement) {
                // Animation sudah dihandle oleh CSS, tidak perlu JavaScript
                console.log("Sidebar mounted successfully");
            }
        });
    },
    beforeDestroy() {
        // Clear interval when component is destroyed
        if (this.timeInterval) {
            clearInterval(this.timeInterval);
        }
    },
    methods: {
        updateTime() {
            const now = new Date();

            // Format time (24-hour format)
            const hours = now.getHours().toString().padStart(2, "0");
            const minutes = now.getMinutes().toString().padStart(2, "0");
            const seconds = now.getSeconds().toString().padStart(2, "0");
            this.currentTime = `${hours}:${minutes}:${seconds}`;

            // Format date
            const options = {
                weekday: "short",
                year: "numeric",
                month: "short",
                day: "numeric",
            };
            this.currentDate = now.toLocaleDateString("en-US", options);
        },
        closeSidebar() {
            this.$emit("close-sidebar");
        },
    },
};
</script>
