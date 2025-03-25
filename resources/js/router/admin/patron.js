export default {
    path: "patron",
    component: () => import("../../components/admin/app/Patron.vue"),
    children: [
        {
            path: "",
            name: "patrons",
            component: () =>
                import("../../components/admin/app/patron/List.vue"),
        },
        {
            path: "add-new",
            name: "patron-new",
            component: () =>
                import("../../components/admin/app/patron/AddNew.vue"),
        },
        {
            path: ":id",
            name: "patron",
            component: () =>
                import("../../components/admin/app/patron/Patron.vue"),
        },
        {
            path: ":id/edit",
            name: "patron-edit",
            component: () =>
                import("../../components/admin/app/patron/Edit.vue"),
        },
    ],
};
