<template>
    <DashboardLayout>
        <div class="row gy-4">
            <div class="col-lg-9">
                <div
                    class="grettings-box position-relative rounded-16 overflow-hidden gap-16 flex-wrap z-1 bg-[#45a538]"
                >
                    <img
                        src="/assets/images/grettings-pattern.png"
                        alt=""
                        class="position-absolute inset-block-start-0 inset-inline-start-0 z-n1 w-100 h-100 opacity-6"
                    />
                    <div class="row gy-4">
                        <div class="col-sm-7">
                            <div class="grettings-box__content py-xl-4">
                                <h1 class="text-white mb-0 text-5xl">
                                    Hello, Kadavi!
                                </h1>
                                <p class="text-15 fw-light mt-4 text-white">
                                    Siram tanamanmu setiap hari
                                </p>
                            </div>
                        </div>
                        <div class="col-sm-5 d-sm-block d-none">
                            <div
                                class="text-center h-100 d-flex justify-content-center align-items-end"
                            >
                                <img
                                    src="/assets/images/petani-user.png"
                                    alt=""
                                />
                            </div>
                        </div>
                    </div>
                </div>

                <!-- bagian widget monitoring -->
                <MonitoringTrack />

                <!-- bagian grafik -->
                <Grafik />
            </div>

            <div class="col-lg-3">
                <!-- bagian button watering -->
                <ButtonWatering />
                <!-- slot untuk calender -->

                <!-- bagian untuk plant care -->
                <PlantCare />
            </div>
        </div>
    </DashboardLayout>
</template>

<script>
import DashboardLayout from "../../Layouts/DashboardLayouts.vue";
import Grafik from "../../Components/Grafik.vue";
import MonitoringTrack from "../../Components/MonitoringTrack.vue";
import PlantCare from "../../Components/PlantCare.vue";
import ButtonWatering from "../../Components/ButtonWatering.vue";

export default {
    name: "DashboardPage",
    components: {
        DashboardLayout,
        Grafik,
        MonitoringTrack,
        PlantCare,
        ButtonWatering,
    },
};
</script>
