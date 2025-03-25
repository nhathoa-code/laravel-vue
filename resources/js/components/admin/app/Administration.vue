<script setup>
import MenuItems from "../common/MenuItems.vue";
const items = [
    {
        name: "Collection",
        route: "/admin/administration/collection",
        icon: '<i class="fa fa-upload"></i>',
    },
    {
        name: "Library",
        route: "/admin/administration/library",
        icon: '<i class="fa fa-home"></i>',
    },
    {
        name: "Shelve Location",
        route: "/admin/administration/shelve-location",
    },
    {
        name: "Rules",
        route: "/admin/administration/rules",
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
