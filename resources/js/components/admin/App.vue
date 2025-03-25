<script setup>
import { useRoute } from "vue-router";
import { useUserStore } from "@root/stores/user";
import NavBar from "./layout/NavBar.vue";
import SideBar from "./layout/SideBar.vue";
import Footer from "./layout/Footer.vue";
import Login from "./app/staff/Login.vue";
const route = useRoute();
const userStore = useUserStore();
</script>
<template>
    <Login v-if="route.meta.layout === false && !userStore.user" />
    <template v-if="route.meta.layout === true && userStore.user">
        <NavBar />
        <div class="container-fluid page-body-wrapper">
            <SideBar />
            <div class="main-panel">
                <router-view></router-view>
                <Footer />
            </div>
        </div>
    </template>
</template>
