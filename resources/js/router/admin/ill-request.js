export default {
    path: "ill-requests",
    component: () => import("../../components/admin/app/Illrequest.vue"),
    children: [
        {
            path: "",
            component: () =>
                import("../../components/admin/app/illrequest/List.vue"),
        },
        {
            path: "addnew",
            component: () =>
                import("../../components/admin/app/illrequest/Add.vue"),
        },
        {
            path: ":id",
            component: () =>
                import("../../components/admin/app/illrequest/Request.vue"),
        },
    ],
};
