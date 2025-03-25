<script setup>
import MenuItems from "../common/MenuItems.vue";
const items = [
    {
        name: "Transfer",
        route: "/admin/transfers",
    },
    {
        name: "Transfers to send",
        route: "/admin/transfers/send",
    },
    {
        name: "Transfers to receive",
        route: "/admin/transfers/receive",
    },
];
</script>
<template>
    <div class="row">
        <div class="col-sm-12">
            <div class="home-tab">
                <div
                    class="d-sm-flex align-items-center justify-content-between"
                >
                    <menu-items :items="items" />
                </div>
                <div class="tab-content tab-content-basic">
                    <router-view></router-view>
                </div>
            </div>
        </div>
    </div>
</template>
