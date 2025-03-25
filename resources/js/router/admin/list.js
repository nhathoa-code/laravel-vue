export default {
    path: "list",
    component: () => import("../../components/admin/app/List.vue"),
    children: [
        {
            path: "",
            name: "list",
            component: () => import("../../components/admin/app/list/List.vue"),
        },
        {
            path: "addnew",
            name: "list-new",
            component: () =>
                import("../../components/admin/app/list/AddNew.vue"),
        },
        {
            path: ":id",
            name: "list-content",
            component: () =>
                import("../../components/admin/app/list/ListContent.vue"),
        },
    ],
};
