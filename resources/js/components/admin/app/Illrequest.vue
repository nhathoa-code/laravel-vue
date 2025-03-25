<script setup>
import MenuItems from "../common/MenuItems.vue";
const items = [
    {
        name: "New ILL Request",
        route: "/admin/ill-requests/addnew",
        icon: '<i class="fa fa-plus"></i>',
    },
    {
        name: "ILL requests",
        route: "/admin/ill-requests",
        icon: '<i class="fa fa-list-ul"></i>',
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
