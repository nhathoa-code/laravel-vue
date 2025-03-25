<script setup>
import { onMounted, ref } from "vue";
import { RouterLink, useRoute, useRouter } from "vue-router";
import { VendorService } from "@root/services/acquisition/VendorService";
import { formatCurrency } from "@root/functions";
import Spinner from "@admin/common/Spinner.vue";
import Loading from "@admin/common/Loading.vue";
const route = useRoute();
const router = useRouter();
const orders = route.query.orders;
const invoiceNumber = route.query.invoice;
const shippingDate = route.query.shipping_date;
const library = route.query.library;
const invoiceConfirm = ref(null);
const vendor = ref(null);
const items = ref(null);
onMounted(() => {
    VendorService.getSpendingOrders(
        route.query.booksellerid,
        (res) => {
            console.log(res);
            vendor.value = res.data.vendor;
            items.value = res.data.items;
        },
        { orders: orders }
    );
});
function confirmReceive(confirm = true) {
    const data = {
        invoice_number: invoiceNumber,
        shipping_date: shippingDate,
        billing_place: library,
        items: items.value.map((item) => {
            return {
                basket_item: item.id,
                received: item.received,
            };
        }),
    };
    VendorService.invoice(
        vendor.value.id,
        data,
        (res) => {
            if (confirm) {
                invoiceConfirm.value = res.data;
            } else {
                router.push({
                    name: "invoice",
                    params: { id: res.data.invoice.id },
                });
            }
        },
        { confirm_invoice: confirm }
    );
}
</script>
<template>
    <Loading :visible="VendorService.loading.value" />
    <template v-if="items && items.length > 0">
        <template v-if="!invoiceConfirm">
            <h3 class="mb-3">
                Receive items from : {{ vendor.name }} [{{ vendor.id }}] (order
                #{{ orders }})
            </h3>
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Order</th>
                            <th>Title</th>
                            <th>Author</th>
                            <th>ISBN</th>
                            <th>Date received</th>
                            <th>Fund</th>
                            <th>Quantity</th>
                            <th>Receive</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="item in items" :key="item.id">
                            <td>
                                {{ item.id }}
                            </td>
                            <td>
                                {{ item.book.title }}
                            </td>
                            <td>
                                {{ item.book.authors.join(",") }}
                            </td>
                            <td>
                                {{ item.book.isbn }}
                            </td>
                            <td>
                                {{ new Date().toLocaleDateString("vi-VN") }}
                            </td>
                            <td>
                                {{ item.fund.name }}
                            </td>
                            <td>
                                Receiving {{ item.received }} out of
                                {{ item.quantity }}
                            </td>
                            <td>
                                <div
                                    class="d-flex align-items-center justify-content-center gap-2"
                                >
                                    <input
                                        v-model="item.received"
                                        style="width: 50px; text-align: center"
                                        type="number"
                                        min="1"
                                    />
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <fieldset class="action mt-3">
                <button
                    :disabled="VendorService.submitting.value"
                    @click="confirmReceive()"
                    class="save btn btn-secondary"
                >
                    Confirm
                </button>
                <a :disabled="VendorService.submitting.value" class="btn"
                    >Cancel</a
                >
                <spinner :visible="VendorService.submitting.value"></spinner>
            </fieldset>
        </template>
        <template v-if="invoiceConfirm">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th
                                class="sorting sorting_asc"
                                tabindex="0"
                                aria-controls="receivedt"
                                rowspan="1"
                                colspan="1"
                                aria-sort="ascending"
                                aria-label="Basket: activate to sort column descending"
                                style="width: 56.3125px"
                            >
                                Basket
                            </th>
                            <th
                                class="sorting"
                                tabindex="0"
                                aria-controls="receivedt"
                                rowspan="1"
                                colspan="1"
                                aria-label="Order line: activate to sort column ascending"
                                style="width: 45.625px"
                            >
                                Order line
                            </th>
                            <th
                                title="Item holds / Total holds"
                                class="sorting"
                                tabindex="0"
                                aria-controls="receivedt"
                                rowspan="1"
                                colspan="1"
                                aria-label="Holds: activate to sort column ascending"
                                style="width: 35.375px"
                            >
                                Holds
                            </th>
                            <th
                                class="sorting"
                                tabindex="0"
                                aria-controls="receivedt"
                                rowspan="1"
                                colspan="1"
                                aria-label="Summary: activate to sort column ascending"
                                style="width: 137.219px"
                            >
                                Summary
                            </th>
                            <th
                                class="sorting"
                                tabindex="0"
                                aria-controls="receivedt"
                                rowspan="1"
                                colspan="1"
                                aria-label="Replacement price: activate to sort column ascending"
                                style="width: 92.6875px"
                            >
                                Seller price
                            </th>
                            <th
                                class="sorting"
                                tabindex="0"
                                aria-controls="receivedt"
                                rowspan="1"
                                colspan="1"
                                aria-label="Quantity: activate to sort column ascending"
                                style="width: 53.3906px"
                            >
                                Quantity
                            </th>
                            <th
                                class="sorting"
                                tabindex="0"
                                aria-controls="receivedt"
                                rowspan="1"
                                colspan="1"
                                aria-label="Fund: activate to sort column ascending"
                                style="width: 30.5469px"
                            >
                                Fund
                            </th>
                            <th
                                class="sorting"
                                tabindex="0"
                                aria-controls="receivedt"
                                rowspan="1"
                                colspan="1"
                                aria-label="Est cost: activate to sort column ascending"
                                style="width: 33.5px"
                            >
                                Est cost
                            </th>
                            <th
                                class="sorting"
                                tabindex="0"
                                aria-controls="receivedt"
                                rowspan="1"
                                colspan="1"
                                aria-label="Actual cost: activate to sort column ascending"
                                style="width: 50.4844px"
                            >
                                Actual cost
                            </th>
                            <th
                                class="sorting"
                                tabindex="0"
                                aria-controls="receivedt"
                                rowspan="1"
                                colspan="1"
                                aria-label="TOTAL: activate to sort column ascending"
                                style="width: 38.9688px"
                            >
                                TOTAL
                            </th>
                        </tr>
                    </thead>
                    <tbody class="filterclass">
                        <tr v-for="item in invoiceConfirm" class="odd">
                            <td class="sorting_1">
                                <RouterLink
                                    :to="{
                                        name: 'basket',
                                        params: {
                                            id: item.basket.vendor_id,
                                            basketId: item.basket.id,
                                        },
                                    }"
                                >
                                    {{ item.basket.name }} ({{
                                        item.basket.id
                                    }})
                                </RouterLink>
                            </td>
                            <td>
                                {{ item.id }}
                            </td>
                            <td>0</td>
                            <td>
                                <RouterLink
                                    :to="{
                                        name: 'book',
                                        params: { id: item.book.id },
                                    }"
                                >
                                    <span class="biblio-title"
                                        >{{ item.book.title }}
                                    </span>
                                </RouterLink>

                                - {{ item.book.isbn }}
                            </td>
                            <td>{{ formatCurrency(item.vendor_price) }}</td>
                            <td>{{ item.received }}</td>
                            <td>{{ item.fund.name }}</td>
                            <td>{{ formatCurrency(item.vendor_price) }}</td>
                            <td>{{ formatCurrency(item.vendor_price) }}</td>
                            <td>
                                {{
                                    formatCurrency(
                                        item.vendor_price * item.quantity
                                    )
                                }}
                            </td>
                        </tr>
                    </tbody>
                    <tfoot style="font-size: 0.85rem">
                        <tr>
                            <td colspan="5" class="total" rowspan="1">
                                (Tax exc.)
                            </td>
                            <td colspan="3" rowspan="1">
                                <em>Subtotal for</em> Books
                            </td>
                            <td rowspan="1" colspan="1">10.00</td>
                            <td rowspan="1" colspan="1">10.00</td>
                        </tr>
                        <tr>
                            <th colspan="8" class="total" rowspan="1">
                                Total tax exc.
                            </th>
                            <th rowspan="1" colspan="1">
                                {{
                                    formatCurrency(
                                        invoiceConfirm.reduce((prev, item) => {
                                            return (
                                                prev +
                                                item.vendor_price *
                                                    item.quantity
                                            );
                                        }, 0)
                                    )
                                }}
                            </th>
                            <th rowspan="1" colspan="1"></th>
                        </tr>
                        <tr>
                            <th colspan="8" rowspan="1">Total (GST 0%)</th>
                            <th rowspan="1" colspan="1">0.00</th>
                            <th rowspan="1" colspan="1"></th>
                        </tr>
                        <tr>
                            <th colspan="8" class="total" rowspan="1">
                                Total tax inc.
                            </th>
                            <th rowspan="1" colspan="1">
                                {{
                                    formatCurrency(
                                        invoiceConfirm.reduce((prev, item) => {
                                            return (
                                                prev +
                                                item.vendor_price *
                                                    item.quantity
                                            );
                                        }, 0)
                                    )
                                }}
                            </th>
                            <th rowspan="1" colspan="1"></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div class="d-flex align-items-center">
                <button
                    :disabled="VendorService.submitting.value"
                    @click="confirmReceive(false)"
                    class="save btn btn-secondary mt-3"
                >
                    Finish receiving
                </button>
                <a @click="invoiceConfirm = null" class="cancel"
                    >Cancel receipt</a
                >
            </div>
        </template>
    </template>
</template>
<style scoped></style>
