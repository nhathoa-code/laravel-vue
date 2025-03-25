export default {
    path: "acquisition",
    component: () => import("../../components/admin/app/Acquisition.vue"),
    children: [
        {
            path: "vendor",
            children: [
                {
                    path: "",
                    component: () =>
                        import(
                            "../../components/admin/app/acquisition/vendor/VendorList.vue"
                        ),
                },
                {
                    path: ":id",
                    name: "vendor",
                    component: () =>
                        import(
                            "../../components/admin/app/acquisition/vendor/Vendor.vue"
                        ),
                },
                {
                    path: ":id/edit",
                    name: "edit-vendor",
                    component: () =>
                        import(
                            "../../components/admin/app/acquisition/vendor/EditVendor.vue"
                        ),
                },
                {
                    path: "addnew",
                    name: "new-vendor",
                    component: () =>
                        import(
                            "../../components/admin/app/acquisition/vendor/AddVendor.vue"
                        ),
                },
                {
                    path: ":id/basket",
                    name: "new-basket",
                    component: () =>
                        import(
                            "../../components/admin/app/acquisition/vendor/AddBasket.vue"
                        ),
                },
                {
                    path: ":id/basket/:basketId",
                    name: "basket",
                    component: () =>
                        import(
                            "../../components/admin/app/acquisition/vendor/Basket.vue"
                        ),
                },
                {
                    path: ":id/basket/:basketId/edit",
                    name: "edit-basket",
                    component: () =>
                        import(
                            "../../components/admin/app/acquisition/vendor/EditBasket.vue"
                        ),
                },
                {
                    path: ":id/basket/:basketId/neworder",
                    name: "neworder",
                    component: () =>
                        import(
                            "../../components/admin/app/acquisition/vendor/NewOrder.vue"
                        ),
                },
            ],
        },
        {
            path: "budget",
            children: [
                {
                    path: "",
                    name: "budget-list",
                    component: () =>
                        import(
                            "../../components/admin/app/acquisition/budget/BudgetList.vue"
                        ),
                },
                {
                    path: ":id",
                    name: "budget",
                    component: () =>
                        import(
                            "../../components/admin/app/acquisition/budget/Budget.vue"
                        ),
                },
                {
                    path: ":id/edit",
                    name: "budget-edit",
                    component: () =>
                        import(
                            "../../components/admin/app/acquisition/budget/Edit.vue"
                        ),
                },
                {
                    path: ":id/addfund",
                    name: "fund-add",
                    component: () =>
                        import(
                            "../../components/admin/app/acquisition/budget/AddFund.vue"
                        ),
                },
                {
                    path: ":id/fund/:fundId/edit",
                    name: "fund-edit",
                    component: () =>
                        import(
                            "../../components/admin/app/acquisition/budget/EditFund.vue"
                        ),
                },
            ],
        },
        {
            path: "funds",
            component: () =>
                import(
                    "../../components/admin/app/acquisition/budget/Funds.vue"
                ),
        },
        {
            path: "parcels",
            name: "vendor-parcel",
            component: () =>
                import("../../components/admin/app/acquisition/Parcels.vue"),
        },
        {
            path: "orderreceive",
            name: "receive-shipment",
            component: () =>
                import(
                    "../../components/admin/app/acquisition/OrderReceive.vue"
                ),
        },
        {
            path: "invoices",
            component: () =>
                import("../../components/admin/app/acquisition/Invoices.vue"),
        },
        {
            path: "invoice/:id",
            name: "invoice",
            component: () =>
                import("../../components/admin/app/acquisition/Invoice.vue"),
        },
    ],
};
