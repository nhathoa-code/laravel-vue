<script setup>
import MenuItems from "../common/MenuItems.vue";
const items = [
    {
        name: "New subscription",
        route: "/admin/serial/subscription/add-new",
        icon: '<i class="fa fa-plus"></i>',
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
