<template>
    <DashboardLayout>
        <div class="row gy-4">
            <div class="col-lg-9">
                <div
                    class="grettings-box position-relative rounded-16 bg-main-600 overflow-hidden gap-16 flex-wrap z-1"
                >
                    <img
                        src="assets/images/bg/grettings-pattern.png"
                        alt=""
                        class="position-absolute inset-block-start-0 inset-inline-start-0 z-n1 w-100 h-100 opacity-6"
                    />
                    <div class="row gy-4">
                        <div class="col-sm-7">
                            <div class="grettings-box__content py-xl-4">
                                <h2 class="text-white mb-0">Hello, Mohib!</h2>
                                <p class="text-15 fw-light mt-4 text-white">
                                    Let’s learning something today
                                </p>
                                <p class="text-lg fw-light mt-24 text-white">
                                    Set your study plan and growth with
                                    community
                                </p>
                            </div>
                        </div>
                        <div class="col-sm-5 d-sm-block d-none">
                            <div
                                class="text-center h-100 d-flex justify-content-center align-items-end"
                            >
                                <img
                                    src="assets/images/thumbs/gretting-img.png"
                                    alt=""
                                />
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Widgets Start -->
                <div class="row gy-4 mt-10">
                    <div class="col-xxl-3 col-sm-6">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="mb-2">155+</h4>
                                <span class="text-gray-600"
                                    >Completed Courses</span
                                >
                                <div class="flex-between gap-8 mt-16">
                                    <span
                                        class="flex-shrink-0 w-48 h-48 flex-center rounded-circle bg-main-600 text-white text-2xl"
                                        ><i class="ph-fill ph-book-open"></i
                                    ></span>
                                    <div
                                        id="complete-course"
                                        class="remove-tooltip-title rounded-tooltip-value"
                                    ></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xxl-3 col-sm-6">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="mb-2">39+</h4>
                                <span class="text-gray-600"
                                    >Earned Certificate</span
                                >
                                <div class="flex-between gap-8 mt-16">
                                    <span
                                        class="flex-shrink-0 w-48 h-48 flex-center rounded-circle bg-main-two-600 text-white text-2xl"
                                        ><i class="ph-fill ph-certificate"></i
                                    ></span>
                                    <div
                                        id="earned-certificate"
                                        class="remove-tooltip-title rounded-tooltip-value"
                                    ></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xxl-3 col-sm-6">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="mb-2">25+</h4>
                                <span class="text-gray-600"
                                    >Course in Progress</span
                                >
                                <div class="flex-between gap-8 mt-16">
                                    <span
                                        class="flex-shrink-0 w-48 h-48 flex-center rounded-circle bg-purple-600 text-white text-2xl"
                                    >
                                        <i class="ph-fill ph-graduation-cap"></i
                                    ></span>
                                    <div
                                        id="course-progress"
                                        class="remove-tooltip-title rounded-tooltip-value"
                                    ></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xxl-3 col-sm-6">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="mb-2">18k+</h4>
                                <span class="text-gray-600"
                                    >Community Support</span
                                >
                                <div class="flex-between gap-8 mt-16">
                                    <span
                                        class="flex-shrink-0 w-48 h-48 flex-center rounded-circle bg-warning-600 text-white text-2xl"
                                        ><i class="ph-fill ph-users-three"></i
                                    ></span>
                                    <div
                                        id="community-support"
                                        class="remove-tooltip-title rounded-tooltip-value"
                                    ></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Widgets End -->

                <!-- Top Course Start -->
                <div class="card mt-24">
                    <div class="card-body">
                        <div class="mb-20 flex-between flex-wrap gap-8">
                            <h4 class="mb-0">Study Statistics</h4>
                            <div class="flex-align gap-16 flex-wrap">
                                <div class="flex-align flex-wrap gap-16">
                                    <div class="flex-align flex-wrap gap-8">
                                        <span
                                            class="w-8 h-8 rounded-circle bg-main-600"
                                        ></span>
                                        <span class="text-13 text-gray-600"
                                            >Study</span
                                        >
                                    </div>
                                    <div class="flex-align flex-wrap gap-8">
                                        <span
                                            class="w-8 h-8 rounded-circle bg-main-two-600"
                                        ></span>
                                        <span class="text-13 text-gray-600"
                                            >Test</span
                                        >
                                    </div>
                                </div>
                                <select
                                    class="form-select form-control text-13 px-8 pe-24 py-8 rounded-8 w-auto"
                                >
                                    <option value="1">Yearly</option>
                                    <option value="1">Monthly</option>
                                    <option value="1">Weekly</option>
                                    <option value="1">Today</option>
                                </select>
                            </div>
                        </div>

                        <div
                            id="doubleLineChart"
                            class="tooltip-style y-value-left"
                        ></div>
                    </div>
                </div>
                <!-- Top Course End -->
            </div>

            <div class="col-lg-3">
                <!-- Calendar Start -->
                <div class="card">
                    <div class="card-body">
                        <div class="calendar">
                            <div class="calendar__header">
                                <button
                                    type="button"
                                    class="calendar__arrow left"
                                >
                                    <i class="ph ph-caret-left"></i>
                                </button>
                                <p class="display h6 mb-0">""</p>
                                <button
                                    type="button"
                                    class="calendar__arrow right"
                                >
                                    <i class="ph ph-caret-right"></i>
                                </button>
                            </div>

                            <div class="calendar__week week">
                                <div class="calendar__week-text">Su</div>
                                <div class="calendar__week-text">Mo</div>
                                <div class="calendar__week-text">Tu</div>
                                <div class="calendar__week-text">We</div>
                                <div class="calendar__week-text">Th</div>
                                <div class="calendar__week-text">Fr</div>
                                <div class="calendar__week-text">Sa</div>
                            </div>
                            <div class="days"></div>
                        </div>
                    </div>
                </div>
                <!-- Calendar End -->

                <!-- Progress Bar Start -->
                <div class="card mt-24">
                    <div class="card-header border-bottom border-gray-100">
                        <h5 class="mb-0">My Progress</h5>
                    </div>
                    <div class="card-body">
                        <div id="radialMultipleBar"></div>

                        <div class="">
                            <h6 class="text-lg mb-16 text-center">
                                <span class="text-gray-400">Total hour:</span>
                                6h 32 min
                            </h6>
                            <div class="flex-between gap-8 flex-wrap">
                                <div class="flex-align flex-column">
                                    <h6 class="mb-6">60/60</h6>
                                    <span
                                        class="w-30 h-3 rounded-pill bg-main-600"
                                    ></span>
                                    <span class="text-13 mt-6 text-gray-600"
                                        >Completed</span
                                    >
                                </div>
                                <div class="flex-align flex-column">
                                    <h6 class="mb-6">60/60</h6>
                                    <span
                                        class="w-30 h-3 rounded-pill bg-main-two-600"
                                    ></span>
                                    <span class="text-13 mt-6 text-gray-600"
                                        >Completed</span
                                    >
                                </div>
                                <div class="flex-align flex-column">
                                    <h6 class="mb-6">60/60</h6>
                                    <span
                                        class="w-30 h-3 rounded-pill bg-gray-500"
                                    ></span>
                                    <span class="text-13 mt-6 text-gray-600"
                                        >Completed</span
                                    >
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Progress bar end -->
            </div>
        </div>
    </DashboardLayout>
</template>

<script>
import DashboardLayout from "../../Layouts/DashboardLayouts.vue";

export default {
    name: "DashboardPage",
    components: {
        DashboardLayout,
    },
};
</script>
