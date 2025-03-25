import { createRouter, createWebHistory } from "vue-router";
import { useUserStore } from "@root/stores/user";
import { UserService } from "@root/services/user/UserService";

const routes = [
    {
        path: "/",
        name: "main",
        component: () => import("../../components/public/Main.vue"),
    },
    {
        path: "/opac-search",
        name: "opac-search",
        component: () => import("../../components/public/Search.vue"),
    },
    {
        path: "/opac-user/login",
        name: "opac-user-login",
        component: () => import("../../components/public/UserLogin.vue"),
    },
    {
        path: "/opac-user",
        name: "opac-user",
        component: () => import("../../components/public/opac-user/Main.vue"),
        beforeEnter: (to) => {
            return guard(to);
        },
        children: [
            {
                path: "summary",
                name: "opac-user-summary",
                component: () =>
                    import("../../components/public/opac-user/Summary.vue"),
            },
            {
                path: "charges",
                name: "opac-user-charges",
                component: () =>
                    import("../../components/public/opac-user/Charges.vue"),
            },
        ],
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

async function guard(to) {
    const userStore = useUserStore();
    if (userStore.user) return true;
    try {
        const res = await UserService.getUser();
        userStore.setUser(res.data);
        if (to.name === "opac-user-login") return false;
    } catch (error) {
        if (error.response.status === 401) {
            if (to.name !== "opac-user-login") {
                return { name: "opac-user-login" };
            }
        }
    } finally {
        UserService.loading.value = false;
    }
}

export default router;
