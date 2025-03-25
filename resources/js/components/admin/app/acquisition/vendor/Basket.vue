<script setup>
import { onMounted, ref } from "vue";
import { RouterLink, useRoute } from "vue-router";
import { VendorService } from "@root/services/acquisition/VendorService";
import { formatCurrency, useMessage } from "@root/functions";
import Loading from "@admin/common/Loading.vue";
import Toast from "primevue/toast";
const message = useMessage();
const deleted = ref(false);
const vendor = ref(null);
const basket = ref(null);
const route = useRoute();
onMounted(() => {
    VendorService.getBasket(route.params.id, route.params.basketId, (res) => {
        vendor.value = res.data.vendor;
        basket.value = res.data.basket;
    });
});
function deleteBasket() {
    const yes = confirm("do you realy want to delete this basket ?");
    if (!yes) return;
    VendorService.deleteBasket(vendor.value.id, basket.value.id, () => {
        deleted.value = true;
    });
}
function closeBasket() {
    VendorService.updateBasket(
        vendor.value.id,
        basket.value.id,
        { values: { status: "closed" } },
        (res) => {
            basket.value.status = res.data.status;
        },
        { updateStatus: true }
    );
}
function reOpenBasket() {
    VendorService.updateBasket(
        vendor.value.id,
        basket.value.id,
        { values: { status: "spending" } },
        (res) => {
            basket.value.status = res.data.status;
        },
        { updateStatus: true }
    );
}
function cancelOrder(itemId) {
    const yes = confirm("do you realy want to delete this item ?");
    if (!yes) return;
    VendorService.deleteItem(
        vendor.value.id,
        basket.value.id,
        { item_id: itemId },
        (res) => {
            basket.value.items = basket.value.items.filter(
                (item) => item.id !== itemId
            );
            message(res.data.message);
        }
    );
}
</script>
<template>
    <Loading
        :visible="VendorService.loading.value || VendorService.submitting.value"
    />
    <Toast />
    <template v-if="vendor && basket">
        <template v-if="deleted">
            <div class="dialog message">
                <h3>Basket deleted</h3>
            </div>
            <RouterLink
                class="btn btn-default btn-sm vnh"
                :to="{ name: 'vendor', params: { id: vendor.id } }"
                >Show baskets for vendor "{{ vendor.name }}"</RouterLink
            >
        </template>
        <template v-else>
            <div id="toolbar" class="btn-toolbar">
                <template v-if="basket.finish == false">
                    <RouterLink
                        v-if="basket.status != 'closed'"
                        :to="`/admin/acquisition/vendor/${vendor.id}/basket/${basket.id}/neworder`"
                    >
                        <button class="btn btn-default">
                            <i class="fa fa-plus"></i> Add to basket
                        </button>
                    </RouterLink>
                    <RouterLink
                        :to="`/admin/acquisition/vendor/${vendor.id}/basket/${basket.id}/edit`"
                    >
                        <button class="btn btn-default">
                            <i class="fa fa-pencil"></i> Edit basket
                        </button>
                    </RouterLink>
                    <button @click="deleteBasket" class="btn btn-default">
                        <i class="fa fa-trash-o"></i> Delete basket
                    </button>
                    <button
                        v-if="basket.status == 'spending'"
                        @click="closeBasket"
                        class="btn btn-default"
                    >
                        <i class="fa fa-times-circle"></i> Close basket
                    </button>
                    <button
                        v-else-if="basket.status == 'closed'"
                        @click="reOpenBasket"
                        class="btn btn-default"
                    >
                        <i class="fa fa-arrow-circle-o-right"></i> Reopen basket
                    </button>
                </template>

                <button class="btn btn-default">
                    <i class="fa fa-download"></i> Export as CSV
                </button>
            </div>
            <h3 class="mb-3">
                Basket {{ basket.name }} ({{ basket.id }}) for
                <RouterLink
                    :to="{ name: 'vendor', params: { id: vendor.id } }"
                    >{{ vendor.name }}</RouterLink
                >
            </h3>
            <div class="row">
                <div class="col-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="patroninfo-heading">
                                <h4>General information</h4>
                            </div>
                            <div class="rows">
                                <ol>
                                    <li>
                                        <span class="label"
                                            >Delivery place:
                                        </span>
                                        <span>{{
                                            basket.billing_place.name
                                        }}</span>
                                    </li>
                                    <li>
                                        <span class="label"
                                            >Billing place:
                                        </span>
                                        <span>{{
                                            basket.billing_place.name
                                        }}</span>
                                    </li>
                                    <li>
                                        <span class="label">Created by: </span>
                                        <span>{{
                                            basket.created_by.name
                                        }}</span>
                                    </li>
                                    <li>
                                        <span class="label">Status: </span>
                                        <span class="fw-bold">{{
                                            basket.status
                                        }}</span>
                                    </li>
                                    <li>
                                        <span class="label">Opened on: </span>
                                        <span>{{ basket.opened_on }}</span>
                                    </li>
                                    <li v-if="basket.status == 'closed'">
                                        <span class="label">Closed on: </span>
                                        <span>{{ basket.closed_on }}</span>
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="patroninfo-heading">
                                <h4>Settings</h4>
                            </div>
                            <div class="rows">
                                <ol>
                                    <!-- <li>
                                    <span class="label">Card number: </span>
                                    <span>{{
                                        patron.user_meta.card_number
                                    }}</span>
                                </li> -->
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="patroninfo-heading">
                                <h4>Orders</h4>
                            </div>
                            <div class="table-responsive">
                                <table
                                    class="table table-bordered table-striped"
                                >
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Order</th>
                                            <th>Vendor price</th>
                                            <th>Discount</th>
                                            <th>Qty</th>
                                            <th>Actual cost</th>
                                            <th>Fund</th>
                                            <th v-if="basket.finish == false">
                                                Cancel order
                                            </th>
                                            <th v-else>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-center">
                                        <tr v-for="item in basket.items">
                                            <td>{{ item.id }}</td>
                                            <td>
                                                <RouterLink
                                                    :to="`/admin/cataloging/book/${item.book.id}`"
                                                >
                                                    {{
                                                        item.book.title
                                                    }}</RouterLink
                                                >
                                            </td>
                                            <td>
                                                {{
                                                    formatCurrency(
                                                        item.vendor_price
                                                    )
                                                }}
                                            </td>
                                            <td>{{ item.discount }}</td>
                                            <td>{{ item.quantity }}</td>
                                            <td>
                                                {{
                                                    formatCurrency(
                                                        item.vendor_price *
                                                            item.quantity
                                                    )
                                                }}
                                            </td>
                                            <td>{{ item.fund.name }}</td>
                                            <td v-if="basket.finish == false">
                                                <a
                                                    @click.prevent="
                                                        cancelOrder(item.id)
                                                    "
                                                    href=""
                                                    >Cancel order</a
                                                >
                                            </td>
                                            <td
                                                v-else-if="
                                                    item.status == 'received'
                                                "
                                            >
                                                Received
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </template>
</template>
