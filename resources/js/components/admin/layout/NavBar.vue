<script setup>
import { ref } from "vue";
import { useRouter } from "vue-router";
import { useUserStore } from "@root/stores/user";
import { useCartStore } from "@root/stores/cart";
import { StaffService } from "@root/services/user/StaffService";
import DataTable from "primevue/datatable";
import Column from "primevue/column";
import Loading from "@admin/common/Loading.vue";
import Dialog from "primevue/dialog";
const userStore = useUserStore();
const cartStore = useCartStore();
const router = useRouter();
const visible = ref(false);
function logout() {
    StaffService.logout(() => {
        userStore.setUser(null);
        router.push("/admin/login");
    });
}
</script>
<template>
    <Dialog v-model:visible="visible" modal :style="{ width: '60%' }">
        <template v-if="cartStore.items.length > 0">
            <div id="toolbar" class="btn-toolbar">
                <a href="basket.pl" class="btn btn-default showdetails showmore"
                    ><i class="fa fa-folder-open"></i> More details</a
                >
                <a class="btn btn-default" href="basket.pl" id="send_cart"
                    ><i class="fa fa-envelope"></i> Send</a
                >
                <a class="btn btn-default" href="basket.pl" id="print_cart"
                    ><i class="fa fa-print"></i> Print</a
                >
                <a class="btn btn-default" href="basket.pl" id="empty_cart"
                    ><i class="fa fa-trash-o"></i> Empty and close</a
                >
            </div>
            <DataTable :value="cartStore.items">
                <Column>
                    <template #body="{ data }">
                        <input type="checkbox" />
                    </template>
                </Column>
                <Column field="title" header="Title">
                    <template #body="{ data }">
                        <RouterLink
                            @click="visible = false"
                            :to="{ name: 'book', params: { id: data.id } }"
                            >{{ data.title }}</RouterLink
                        >
                        <p class="d-inline-block">
                            - {{ data.authors.join(",") }}
                        </p>
                        <p>
                            {{ data.publisher }}, {{ data.page_count }}p,
                            200x300 cm
                        </p>
                    </template>
                </Column>
                <Column header="Items">
                    <template #body="{ data }">
                        <p>
                            <span class="fw-bold">{{
                                data.items[0].current_location.name
                            }}</span>
                            -
                            {{ data.items[0].barcode }}
                        </p>
                    </template>
                </Column>
            </DataTable>
        </template>
        <template v-else> No items in your cart. </template>
    </Dialog>
    <nav
        class="navbar default-layout col-lg-12 col-12 p-0 fixed-top d-flex align-items-top flex-row"
    >
        <div
            class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-start"
        >
            <div class="me-3">
                <button
                    class="navbar-toggler navbar-toggler align-self-center"
                    type="button"
                    data-bs-toggle="minimize"
                >
                    <span class="icon-menu"></span>
                </button>
            </div>
            <div>
                <a class="navbar-brand brand-logo">
                    <img :src="'/admin_asset/images/logo.png'" alt="logo" />
                </a>
                <a class="navbar-brand brand-logo-mini" href="index.html">
                    <img
                        :src="'/admin_asset/images/logo-mini.svg'"
                        alt="logo"
                    />
                </a>
            </div>
        </div>
        <div class="navbar-menu-wrapper d-flex align-items-top">
            <ul class="navbar-nav gap-4 align-items-center">
                <li class="nav-item fw-semibold d-none d-lg-block ms-0">
                    <h1 class="welcome-text mb-0">
                        Good Morning,
                        <span class="text-black fw-bold">
                            {{ userStore.getUser.name }}
                        </span>
                    </h1>
                </li>
                <li>
                    <a
                        @click.prevent="visible = true"
                        href="#"
                        id="cartmenulink"
                    >
                        <i
                            style="font-size: 1.5rem"
                            class="fa fa-shopping-cart"
                        ></i>
                        <span
                            v-if="cartStore.items.length > 0"
                            id="basketcount"
                        >
                            <span>({{ cartStore.items.length }})</span></span
                        >
                    </a>
                </li>
            </ul>
            <ul class="navbar-nav ms-auto">
                <li class="nav-item dropdown d-none d-lg-block user-dropdown">
                    <a
                        class="nav-link"
                        id="UserDropdown"
                        href="#"
                        data-bs-toggle="dropdown"
                        aria-expanded="false"
                    >
                        <img
                            class="img-xs rounded-circle"
                            :src="'/admin_asset/images/faces/face8.jpg'"
                            alt="Profile image"
                        />
                    </a>
                    <div
                        class="dropdown-menu dropdown-menu-right navbar-dropdown"
                        aria-labelledby="UserDropdown"
                    >
                        <a @click.prevent="logout" class="dropdown-item"
                            ><i
                                class="dropdown-item-icon mdi mdi-power text-primary me-2"
                            ></i
                            >Sign Out</a
                        >
                    </div>
                </li>
            </ul>
            <button
                class="navbar-toggler navbar-toggler-right d-lg-none align-self-center"
                type="button"
                data-bs-toggle="offcanvas"
            >
                <span class="mdi mdi-menu"></span>
            </button>
        </div>
    </nav>
    <Loading :visible="StaffService.submitting.value" />
</template>
<script>
export default {
    name: "NavBar",
};
</script>
