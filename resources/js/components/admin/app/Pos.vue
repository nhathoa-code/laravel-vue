<script setup>
import MenuItems from "../common/MenuItems.vue";
const items = [
    {
        name: "Point of sale",
        route: "/admin/pos",
        icon: '<i class="fa fa-shopping-cart"></i>',
    },
    {
        name: "Debit types",
        route: "/admin/pos/debit-type",
        icon: '<i class="fa fa-square"></i>',
    },
    {
        name: "Cash registers",
        route: "/admin/pos/cash-register",
        icon: '<i class="fa fa-suitcase"></i>',
    },
    {
        name: "Cash sumary",
        route: "/admin/pos/registers",
        icon: '<i class="fa fa-file-text"></i>',
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
