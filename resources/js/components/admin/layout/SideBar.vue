<script setup>
import { computed } from "vue";
import { useRouter } from "vue-router";
const router = useRouter();
const currentRoute = computed(() => {
    return router.currentRoute.value.path;
});
</script>
<template>
    <nav class="sidebar sidebar-offcanvas" id="sidebar">
        <ul class="nav">
            <li
                :class="{ active: currentRoute == '/admin' }"
                class="nav-item mt-0"
            >
                <RouterLink class="nav-link" to="/admin">
                    <i class="fa fa-fw fa-pie-chart fa-2x"></i>
                    <span class="menu-title ms-2">Report</span></RouterLink
                >
            </li>
            <li
                :class="{
                    active: currentRoute.includes('/admin/cataloging'),
                }"
                class="nav-item mt-0"
            >
                <RouterLink class="nav-link" to="/admin/cataloging">
                    <i class="fa fa-fw fa-tag fa-2x"></i>
                    <span class="menu-title ms-2">Cataloging</span>
                </RouterLink>
            </li>
            <li
                :class="{ active: currentRoute.includes('/admin/circulation') }"
                class="nav-item mt-0"
            >
                <RouterLink class="nav-link" to="/admin/circulation/checkout">
                    <i class="fa fa-fw fa-exchange fa-2x"></i>
                    <span class="menu-title ms-2">Circulation</span></RouterLink
                >
            </li>
            <li
                :class="{
                    active: currentRoute.includes('/admin/patron'),
                }"
                class="nav-item mt-0"
            >
                <RouterLink class="nav-link" to="/admin/patron">
                    <i class="fa fa-fw fa-id-card fa-2x"></i>
                    <span class="menu-title ms-2">Patrons</span></RouterLink
                >
            </li>
            <li
                :class="{
                    active: currentRoute.includes('/admin/administration'),
                }"
                class="nav-item mt-0"
            >
                <RouterLink class="nav-link" to="/admin/administration/library">
                    <i class="fa fa-fw fa-gears fa-2x"></i>
                    <span class="menu-title ms-2"
                        >Administration</span
                    ></RouterLink
                >
            </li>
            <li
                :class="{
                    active: currentRoute.indexOf('/admin/list') !== -1,
                }"
                class="nav-item mt-0"
            >
                <RouterLink class="nav-link" to="/admin/list">
                    <i class="fa fa-fw fa-list-alt fa-2x"></i>
                    <span class="menu-title ms-2">Lists</span></RouterLink
                >
            </li>
            <li
                :class="{
                    active: currentRoute.indexOf('/admin/acquisition') !== -1,
                }"
                class="nav-item mt-0"
            >
                <RouterLink class="nav-link" to="/admin/acquisition/vendor">
                    <i class="fa fa-fw fa-gift fa-2x"></i>
                    <span class="menu-title ms-2"
                        >Acquisitions</span
                    ></RouterLink
                >
            </li>
            <li
                :class="{
                    active: currentRoute.indexOf('/admin/serial') !== -1,
                }"
                class="nav-item mt-0"
            >
                <RouterLink class="nav-link" to="/admin">
                    <i class="fa fa-fw fa-newspaper fa-2x"></i>
                    <span class="menu-title ms-2">Serials</span></RouterLink
                >
            </li>
            <li
                :class="{
                    active: currentRoute.indexOf('/admin/pos') !== -1,
                }"
                class="nav-item mt-0"
            >
                <RouterLink class="nav-link" to="/admin/pos">
                    <i class="fa fa-fw fa-shopping-cart fa-2x"></i>
                    <span class="menu-title ms-2"
                        >Point of sale</span
                    ></RouterLink
                >
            </li>
            <li
                :class="{
                    active: currentRoute.indexOf('/admin/transfers') !== -1,
                }"
                class="nav-item mt-0"
            >
                <RouterLink class="nav-link" to="/admin/transfers">
                    <i class="fa fa-rotate-right fa-2x"></i>
                    <span class="menu-title ms-2">Transfer</span></RouterLink
                >
            </li>
            <li
                :class="{
                    active: currentRoute.indexOf('/admin/ill-requests') !== -1,
                }"
                class="nav-item mt-0"
            >
                <RouterLink class="nav-link" to="/admin/ill-requests">
                    <i class="fa fa-fw fa-retweet fa-2x"></i>
                    <span class="menu-title ms-2">ILL request</span></RouterLink
                >
            </li>
            <li
                :class="{
                    active:
                        currentRoute.indexOf('/admin/catalogue/itemsearch') !==
                        -1,
                }"
                class="nav-item mt-0"
            >
                <RouterLink class="nav-link" :to="{ name: 'itemsearch' }">
                    <i class="fa fa-search fa-2x"></i>
                    <span class="menu-title ms-2">Item search</span></RouterLink
                >
            </li>
        </ul>
    </nav>
</template>
<style scoped>
.sidebar {
    background: #e6e6e6;
}

.sidebar .nav:not(.sub-menu) > .nav-item.active .nav-link {
    border-radius: 0px 0px 0px 0px;
}
.sidebar .nav .nav-item.active > .nav-link {
    background: #f4f5f7;
    border-left: solid 5px #408540;
    display: flex;
    align-items: center;
}
.sidebar .nav .nav-item > .nav-link i {
    width: 50px !important;
    text-align: center;
}
.sidebar .nav .nav-item > .nav-link span {
    flex-grow: 1;
}
.sidebar .nav .nav-item.active > .nav-link .menu-title,
.sidebar .nav .nav-item:hover > .nav-link i,
.sidebar .nav .nav-item.active > .nav-link i {
    color: #006100;
}
.sidebar .nav .nav-item.active > .nav-link span {
    font-weight: bold;
}
.sidebar .nav:not(.sub-menu) > .nav-item:hover > .nav-link {
    border-left: solid 5px #6faf44;
    font-weight: normal;
}
.sidebar .nav .nav-item .nav-link {
    border-radius: 0px;
    font-weight: 500;
}
.sidebar .nav .nav-item:hover > .nav-link .menu-title {
    color: #006100;
}
.fa-newspaper:before {
    content: "\f1ea";
}
.sidebar .nav .nav-item .nav-link {
    padding: 10px 15px 10px 15px !important;
}
</style>
