<script setup>
import { onMounted, ref } from "vue";
import { RouterLink } from "vue-router";
import { InvoiceService } from "@root/services/acquisition/InvoiceService";
import Loading from "@admin/common/Loading.vue";
const invoices = ref(null);
const openInvoices = ref([]);
const closedInvoices = ref([]);
onMounted(() => {
    InvoiceService.getList((res) => {
        invoices.value = res.data;
        openInvoices.value = invoices.value.filter(
            (item) => item.status === "open"
        );
        closedInvoices.value = invoices.value.filter(
            (item) => item.status === "closed"
        );
    });
});
</script>
<template>
    <Loading :visible="InvoiceService.loading.value" />
    <div v-if="invoices" class="row">
        <h3 class="mb-3">Invoices</h3>
        <div class="home-tab tabs">
            <div class="d-sm-flex align-items-center justify-content-between">
                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation">
                        <a
                            class="nav-link v-link active"
                            id="home-tab"
                            data-bs-toggle="tab"
                            href="#privatelists"
                            role="tab"
                            aria-selected="true"
                            >Open invoices</a
                        >
                    </li>
                    <li role="presentation">
                        <a
                            class="nav-link v-link"
                            id="profile-tab"
                            data-bs-toggle="tab"
                            href="#publiclists"
                            role="tab"
                            aria-selected="false"
                            tabindex="-1"
                            >Closed invoices</a
                        >
                    </li>
                </ul>
            </div>
            <div class="tab-content tab-content-basic mt-0">
                <div
                    class="tab-pane fade show active"
                    id="privatelists"
                    role="tabpanel"
                    aria-labelledby="privatelists"
                >
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Invoice number</th>
                                    <th>Vendor</th>
                                    <th>Shipment date</th>
                                    <th>Received items</th>
                                    <th>Status</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody class="text-center">
                                <tr v-for="item in openInvoices" :key="item.id">
                                    <td>
                                        <RouterLink
                                            :to="{
                                                name: 'invoice',
                                                params: { id: item.id },
                                            }"
                                        >
                                            #{{ item.invoice_number }}
                                        </RouterLink>
                                    </td>
                                    <td>
                                        <RouterLink
                                            :to="{
                                                name: 'vendor',
                                                params: { id: item.vendor.id },
                                            }"
                                        >
                                            {{ item.vendor.name }}
                                        </RouterLink>
                                    </td>
                                    <td>{{ item.shipping_date }}</td>
                                    <td>{{ item.received_items }}</td>
                                    <td>{{ item.status }}</td>
                                    <td style="text-align: right">
                                        <RouterLink
                                            :to="{
                                                name: 'invoice',
                                                params: { id: item.id },
                                            }"
                                        >
                                            <button
                                                type="button"
                                                class="btn btn-outline-light btn-fw action"
                                            >
                                                <i class="fa fa-search"></i>
                                                Details
                                            </button>
                                        </RouterLink>
                                        <button
                                            type="button"
                                            class="btn btn-outline-light btn-fw action"
                                        >
                                            <i class="fa fa-times-circle"></i>
                                            Close
                                        </button>
                                        <button
                                            type="button"
                                            class="btn btn-outline-light btn-fw action"
                                        >
                                            <i class="fa fa-trash-o"></i>
                                            Delete
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div
                    class="tab-pane fade show"
                    id="publiclists"
                    role="tabpanel"
                    aria-labelledby="publiclists"
                ></div>
            </div>
        </div>
    </div>
</template>
