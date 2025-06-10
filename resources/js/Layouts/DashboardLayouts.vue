<!-- DashboardLayouts.vue -->
<template>
    <div class="dashboard-layout">
        <!-- Overlay untuk mobile -->
        <div
            v-if="sidebarOpen"
            class="sidebar-overlay d-xl-none"
            @click="closeSidebar"
        ></div>

        <!-- Sidebar Component -->
        <div class="sidebar-wrapper" :class="{ 'sidebar-active': sidebarOpen }">
            <SidebarDashboard @close-sidebar="closeSidebar" />
        </div>

        <!-- Main Content Area -->
        <div class="main-content">
            <!-- Header Component -->
            <HeaderDashboard @toggle-sidebar="toggleSidebar" />

            <!-- Page Content -->
            <div class="content-area">
                <slot></slot>
            </div>
        </div>
    </div>
</template>

<script>
import SidebarDashboard from "../Components/SidebarDashboard.vue";
import HeaderDashboard from "../Components/HeaderDashboard.vue";

export default {
    name: "DashboardLayout",
    components: {
        SidebarDashboard,
        HeaderDashboard,
    },
    data() {
        return {
            sidebarOpen: false,
        };
    },
    methods: {
        toggleSidebar() {
            this.sidebarOpen = !this.sidebarOpen;
        },
        closeSidebar() {
            this.sidebarOpen = false;
        },
        handleResize() {
            if (window.innerWidth >= 1200) {
                this.sidebarOpen = false;
            }
        },
        handleKeydown(e) {
            if (e.key === "Escape" && this.sidebarOpen) {
                this.closeSidebar();
            }
        },
    },
    mounted() {
        window.addEventListener("resize", this.handleResize);
        document.addEventListener("keydown", this.handleKeydown);
    },
    beforeUnmount() {
        // Vue 3
        window.removeEventListener("resize", this.handleResize);
        document.removeEventListener("keydown", this.handleKeydown);
    },
    beforeDestroy() {
        // Vue 2
        window.removeEventListener("resize", this.handleResize);
        document.removeEventListener("keydown", this.handleKeydown);
    },
};
</script>

<style scoped>
.dashboard-layout {
    position: relative;
    min-height: 100vh;
    display: flex;
}

/* Sidebar Wrapper - Tidak diperlukan karena sidebar sudah fixed */
.sidebar-wrapper {
    /* Tidak ada positioning di sini karena SidebarDashboard menangani sendiri */
}

/* Main Content - Berikan margin left untuk desktop agar tidak tertutup sidebar */
.main-content {
    flex: 1;
    min-width: 0;
    display: flex;
    flex-direction: column;
    /* Berikan margin left di desktop untuk memberikan ruang sidebar */
    margin-left: 0;
}

@media (min-width: 1200px) {
    .main-content {
        margin-left: 280px; /* Sesuai dengan lebar sidebar */
    }
}

/* Overlay untuk mobile */
.sidebar-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    z-index: 999;
    animation: fadeIn 0.3s ease;
}

@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

/* Content Area */
.content-area {
    flex: 1;
    padding: 20px;
    overflow-y: auto;
}

@media (max-width: 768px) {
    .content-area {
        padding: 15px;
    }
}

/* Header styles */
.top-navbar {
    padding: 15px 20px;
    background: #fff;
    border-bottom: 1px solid #e5e7eb;
    flex-shrink: 0;
}

@media (max-width: 768px) {
    .top-navbar {
        padding: 10px 15px;
    }
}
</style>
