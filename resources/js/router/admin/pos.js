export default {
    path: "pos",
    component: () => import("../../components/admin/app/Pos.vue"),
    children: [
        {
            path: "",
            component: () => import("../../components/admin/app/pos/Pos.vue"),
        },
        {
            path: "debit-type",
            component: () =>
                import("../../components/admin/app/pos/DebitType.vue"),
        },
        {
            path: "cash-register",
            component: () =>
                import("../../components/admin/app/pos/CashRegister.vue"),
        },
        {
            path: "registers",
            component: () =>
                import("../../components/admin/app/pos/Registers.vue"),
        },
        {
            path: "register",
            component: () =>
                import("../../components/admin/app/pos/Register.vue"),
        },
    ],
};
