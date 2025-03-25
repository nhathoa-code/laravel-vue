import { createRouter, createWebHistory } from "vue-router";
import { useUserStore } from "@root/stores/user";
import { UserService } from "@root/services/user/UserService";
import administration_routes from "./administration.js";
import cataloging_routes from "./cataloging.js";
import patron_routes from "./patron.js";
import circulation_routes from "./circulation.js";
import list_routes from "./list.js";
import acquisition_routes from "./acquisition.js";
import pos_routes from "./pos.js";
import transfer_routes from "./transfer.js";
import ill_request_routes from "./ill-request.js";

const routes = [
    {
        path: "/admin",
        component: () => import("../../components/admin/layout/Main.vue"),
        meta: { layout: true },
        children: [
            {
                path: "",
                component: () => import("../../components/admin/Dashboard.vue"),
            },
            administration_routes,
            cataloging_routes,
            patron_routes,
            circulation_routes,
            list_routes,
            acquisition_routes,
            pos_routes,
            transfer_routes,
            ill_request_routes,
            {
                path: "catalogue/itemsearch",
                name: "itemsearch",
                component: () =>
                    import("../../components/admin/app/search/ItemSearch.vue"),
            },
            {
                path: "login",
                name: "login",
                component: () =>
                    import("../../components/admin/app/staff/Login.vue"),
                meta: { layout: false },
            },
        ],
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

router.beforeEach(async (to, from) => {
    const userStore = useUserStore();
    if (
        userStore.user &&
        (user.Store.user.role === "admin" || user.Store.user.role === "staff")
    )
        return true;
    try {
        const res = await UserService.getUser();
        userStore.setUser(res.data);
        if (to.name === "login") return false;
    } catch (error) {
        if (error.response.status === 401) {
            if (to.name !== "login") {
                return { name: "login" };
            }
        }
    }
});

export default router;
