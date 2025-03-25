<script setup>
import { onMounted, ref } from "vue";
import { RouterLink, useRoute } from "vue-router";
import { InvoiceService } from "@root/services/acquisition/InvoiceService";
import { formatCurrency } from "@root/functions";
import Loading from "@admin/common/Loading.vue";
const route = useRoute();
const invoice = ref(null);
onMounted(() => {
    InvoiceService.getById(route.params.id, (res) => {
        console.log(res);
        invoice.value = res.data;
    });
});
</script>
<template>
    <Loading :visible="InvoiceService.loading.value" />
    <template v-if="invoice">
        <div class="mb-3">
            <h3>Invoice: {{ invoice.invoice_number }}</h3>
            <p>
                <span class="fw-bold">Vendor:</span>
                <RouterLink>{{ invoice.vendor.name }}</RouterLink>
                &nbsp;
                <span class="fw-bold">Shipping date:</span>
                {{ invoice.shipping_date }}
                &nbsp;
                <span class="fw-bold">Billing place:</span>
                {{ invoice.billing_place.name }}
            </p>
        </div>
        <div class="row">
            <div class="grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h2 class="card-title">Invoice details</h2>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Summary</th>
                                        <th>Library</th>
                                        <th>Actual cost tax inc.</th>
                                        <th>Qty.</th>
                                        <th>Total tax inc.</th>
                                        <th>Fund</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr
                                        v-for="item in invoice.details"
                                        :key="item.id"
                                    >
                                        <td>
                                            <RouterLink
                                                :to="{
                                                    name: 'book',
                                                    params: {
                                                        id: item.book.id,
                                                    },
                                                }"
                                            >
                                                {{ item.book.title }}
                                            </RouterLink>
                                            - {{ item.book.isbn }} <br />{{
                                                item.book.authors.join(",")
                                            }}
                                        </td>
                                        <td class="text-center">
                                            <RouterLink
                                                :to="`/admin/lirary/${invoice.billing_place.id}`"
                                            >
                                                {{ invoice.billing_place.name }}
                                            </RouterLink>
                                        </td>
                                        <td class="text-center">
                                            {{ formatCurrency(item.cost) }}
                                        </td>
                                        <td class="text-center">
                                            {{ item.quantity }}
                                        </td>
                                        <td class="text-center">
                                            {{
                                                formatCurrency(
                                                    item.quantity * item.cost
                                                )
                                            }}
                                        </td>
                                        <td class="text-center">
                                            <RouterLink
                                                :to="`/admin/fund/${item.fund.id}`"
                                            >
                                                {{ item.fund.name }}
                                            </RouterLink>
                                        </td>
                                    </tr>
                                </tbody>
                                <tfoot style="font-size: 0.85rem">
                                    <tr>
                                        <th colspan="2" rowspan="1">
                                            Total (GST 0 %)
                                        </th>
                                        <th
                                            class="tax_included"
                                            rowspan="1"
                                            colspan="1"
                                            style="display: none"
                                        ></th>
                                        <th
                                            class="replacementprice"
                                            rowspan="1"
                                            colspan="1"
                                        ></th>
                                        <th
                                            class="text-center"
                                            rowspan="1"
                                            colspan="1"
                                        >
                                            {{
                                                invoice.details.reduce(
                                                    (prev, item) => {
                                                        return (
                                                            prev + item.quantity
                                                        );
                                                    },
                                                    0
                                                )
                                            }}
                                        </th>
                                        <th
                                            class="text-center"
                                            rowspan="1"
                                            colspan="1"
                                        >
                                            {{
                                                formatCurrency(
                                                    invoice.details.reduce(
                                                        (prev, item) => {
                                                            return (
                                                                prev +
                                                                item.quantity *
                                                                    item.cost
                                                            );
                                                        },
                                                        0
                                                    )
                                                )
                                            }}
                                        </th>
                                        <th rowspan="1" colspan="1">&nbsp;</th>
                                    </tr>

                                    <tr>
                                        <th colspan="2" rowspan="1">
                                            Total (Rs)
                                        </th>
                                        <th
                                            class="tax_included"
                                            rowspan="1"
                                            colspan="1"
                                            style="display: none"
                                        ></th>
                                        <th
                                            class="replacementprice"
                                            rowspan="1"
                                            colspan="1"
                                        ></th>
                                        <th
                                            class="text-center"
                                            rowspan="1"
                                            colspan="1"
                                        >
                                            {{
                                                invoice.details.reduce(
                                                    (prev, item) => {
                                                        return (
                                                            prev + item.quantity
                                                        );
                                                    },
                                                    0
                                                )
                                            }}
                                        </th>
                                        <th
                                            class="text-center"
                                            rowspan="1"
                                            colspan="1"
                                        >
                                            {{
                                                formatCurrency(
                                                    invoice.details.reduce(
                                                        (prev, item) => {
                                                            return (
                                                                prev +
                                                                item.quantity *
                                                                    item.cost
                                                            );
                                                        },
                                                        0
                                                    )
                                                )
                                            }}
                                        </th>
                                        <th rowspan="1" colspan="1">&nbsp;</th>
                                    </tr>
                                    <tr>
                                        <th colspan="2" rowspan="1">
                                            Total + adjustments + shipment cost
                                            (Rs)
                                        </th>
                                        <th
                                            class="tax_included"
                                            rowspan="1"
                                            colspan="1"
                                            style="display: none"
                                        ></th>
                                        <th
                                            class="replacementprice"
                                            rowspan="1"
                                            colspan="1"
                                        ></th>
                                        <th
                                            class="text-center"
                                            rowspan="1"
                                            colspan="1"
                                        >
                                            {{
                                                invoice.details.reduce(
                                                    (prev, item) => {
                                                        return (
                                                            prev + item.quantity
                                                        );
                                                    },
                                                    0
                                                )
                                            }}
                                        </th>
                                        <th
                                            class="text-center"
                                            rowspan="1"
                                            colspan="1"
                                        >
                                            {{
                                                formatCurrency(
                                                    invoice.details.reduce(
                                                        (prev, item) => {
                                                            return (
                                                                prev +
                                                                item.quantity *
                                                                    item.cost
                                                            );
                                                        },
                                                        0
                                                    )
                                                )
                                            }}
                                        </th>
                                        <th rowspan="1" colspan="1">&nbsp;</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </template>
</template>
