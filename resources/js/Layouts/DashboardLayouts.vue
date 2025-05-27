<template>
    <!-- Sidebar Dashboard -->
    <SidebarDashboard />

    <div class="dashboard-main-wrapper">
        <!-- Header Dashboard -->
        <HeaderDashboard />

        <div class="dashboard-body">
            <!-- Dynamic Content (will be replaced by router-view) -->
            <main class="dashboard-content">
                <slot></slot>
            </main>
        </div>

        <div class="dashboard-footer">
            <!-- Footer Dashboard -->
            <FooterDashboard />
        </div>
    </div>
</template>

<script>
import SidebarDashboard from "../Components/SidebarDashboard.vue";
import HeaderDashboard from "../Components/HeaderDashboard.vue";
import FooterDashboard from "../Components/FooterDashboard.vue";

export default {
    name: "DashboardLayout",
    components: {
        SidebarDashboard,
        HeaderDashboard,
        FooterDashboard,
    },
};
</script>

<style scoped>
.dashboard-main-wrapper {
    display: flex;
    min-height: 100vh;
}

.dashboard-body-wrapper {
    flex: 1;
    display: flex;
    flex-direction: column;
}

.dashboard-content {
    flex: 1;
    padding: 20px;
}
</style>
