<script setup>
import { onMounted, ref } from "vue";
import { UserService } from "@root/services/user/UserService";
import { useUserStore } from "@root/stores/user";
import Loading from "@admin/common/Loading.vue";
import Header from "./layout/Header.vue";
import Search from "./layout/Search.vue";
const checkLogin = ref(false);
const userStore = useUserStore();
onMounted(async () => {
    try {
        const res = await UserService.getUser();
        console.log(res.data);
        userStore.setUser(res.data);
    } finally {
        UserService.loading.value = false;
        checkLogin.value = true;
    }
});
</script>
<template>
    <Loading :visible="UserService.loading.value" />
    <div v-if="checkLogin" id="wrapper">
        <Header />
        <div class="container-fluid">
            <Search />
        </div>
        <div class="main">
            <router-view></router-view>
        </div>
    </div>
</template>
