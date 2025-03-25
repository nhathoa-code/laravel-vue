export default {
    path: "transfers",
    component: () => import("../../components/admin/app/Transfer.vue"),
    children: [
        {
            path: "",
            component: () =>
                import("../../components/admin/app/transfer/Transfer.vue"),
        },
        {
            path: "send",
            component: () =>
                import("../../components/admin/app/transfer/Send.vue"),
        },
        {
            path: "receive",
            component: () =>
                import("../../components/admin/app/transfer/Receive.vue"),
        },
    ],
};
