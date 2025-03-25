export default {
    path: "circulation",
    component: () => import("../../components/admin/app/Circulation.vue"),
    children: [
        {
            path: "checkout",
            component: () =>
                import("../../components/admin/app/circulation/Checkout.vue"),
        },
        {
            path: "checkin",
            component: () =>
                import("../../components/admin/app/circulation/Checkin.vue"),
        },
        {
            path: "renew",
            component: () =>
                import("../../components/admin/app/circulation/Renew.vue"),
        },
        {
            path: "overdues",
            component: () =>
                import("../../components/admin/app/circulation/Overdues.vue"),
        },
    ],
};
