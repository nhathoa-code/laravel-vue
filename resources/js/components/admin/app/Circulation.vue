<script setup>
import MenuItems from "../common/MenuItems.vue";
const items = [
    {
        name: "Check out",
        route: "/admin/circulation/checkout",
        icon: '<i class="fa fa-upload"></i>',
    },
    {
        name: "Check in",
        route: "/admin/circulation/checkin",
        icon: '<i class="fa fa-download"></i>',
    },
    {
        name: "Renew",
        route: "/admin/circulation/renew",
        icon: '<i class="fa fa-retweet"></i>',
    },
    {
        name: "Overdues",
        route: "/admin/circulation/overdues",
        icon: '<i class="fa fa-clock-o"></i>',
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
