export default {
    path: "administration",
    component: () => import("../../components/admin/app/Administration.vue"),
    children: [
        {
            path: "collection",
            component: () =>
                import(
                    "../../components/admin/app/administration/Collection.vue"
                ),
        },
        {
            path: "library",
            component: () =>
                import("../../components/admin/app/administration/Library.vue"),
        },
        {
            path: "shelve-location",
            component: () =>
                import(
                    "../../components/admin/app/administration/ShelveLocation.vue"
                ),
        },
        {
            path: "rules",
            component: () =>
                import("../../components/admin/app/administration/Rules.vue"),
        },
    ],
};
