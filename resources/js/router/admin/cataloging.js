export default {
    path: "cataloging",
    component: () => import("../../components/admin/app/Cataloging.vue"),
    children: [
        {
            path: "",
            name: "books",
            component: () =>
                import("../../components/admin/app/cataloging/List.vue"),
        },
        {
            path: "addnew",
            name: "book-new",
            component: () =>
                import("../../components/admin/app/cataloging/AddNew.vue"),
        },
        {
            path: "book/:id/additems",
            name: "book-items",
            component: () =>
                import("../../components/admin/app/cataloging/AddItems.vue"),
        },
        {
            path: "book/:id",
            name: "book",
            component: () =>
                import("../../components/admin/app/cataloging/Book.vue"),
        },
        {
            path: ":id/edit",
            name: "book-edit",
            component: () =>
                import("../../components/admin/app/cataloging/Edit.vue"),
        },
        {
            path: "reserve",
            name: "reserve",
            component: () =>
                import("../../components/admin/app/cataloging/Reserve.vue"),
        },
    ],
};
