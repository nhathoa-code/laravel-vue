<script setup>
import { onMounted, reactive, ref, watch } from "vue";
import { RouterLink, useRoute, useRouter } from "vue-router";
import { Field, Form, ErrorMessage } from "vee-validate";
import { VendorService } from "@root/services/acquisition/VendorService";
import Loading from "@admin/common/Loading.vue";
const router = useRouter();
const route = useRoute();
const vendor = ref(null);
const invoice = reactive({
    invoice_number: "",
    shipping_date: new Date().toISOString().split("T")[0],
});
const currentStep = ref("invoice");
const spendingOrders = ref([]);
const receiveSelected = ref([]);
onMounted(() => {
    VendorService.getById(route.query.booksellerid, (res) => {
        vendor.value = res.data;
    });
});
watch(currentStep, async (newCurrentStep) => {
    if (newCurrentStep === "receive orders") {
        VendorService.getSpendingOrders(route.query.booksellerid, (res) => {
            spendingOrders.value = res.data;
        });
    }
});
function receiveOrders() {
    if (receiveSelected.value.length === 0) return;
    router.push(
        `/admin/acquisition/orderreceive?booksellerid=${vendor.value.id}&orders=` +
            receiveSelected.value.join(",") +
            "&invoice=" +
            invoice.invoice_number +
            "&shipping_date=" +
            invoice.shipping_date +
            "&library=" +
            spendingOrders.value[0].billing_place
    );
}
</script>
<template>
    <Loading :visible="VendorService.loading.value" />
    <template v-if="vendor">
        <template v-if="currentStep === 'invoice'">
            <h3 class="mb-3">
                Receive shipment from vendor
                <RouterLink to="''">{{ vendor.name }}</RouterLink>
            </h3>
            <div class="row">
                <div class="col-md-8 grid-margin stretch-card">
                    <div class="card">
                        <h2 class="ms-3 mt-3">Receive a new shipment</h2>
                        <div class="card-body row">
                            <Form>
                                <div class="form-group row">
                                    <label
                                        for="exampleInputUsername2"
                                        class="col-sm-3 col-form-label required"
                                        >Vendor invoice:</label
                                    >
                                    <div class="col-sm-9">
                                        <Field
                                            type="text"
                                            name="invoice_number"
                                            class="form-control"
                                            v-model="invoice.invoice_number"
                                        />
                                        <ErrorMessage
                                            name="invoice_number"
                                            as="p"
                                            class="text-danger"
                                        />
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label
                                        for="exampleInputUsername2"
                                        class="col-sm-3 col-form-label"
                                        >Shipment date:</label
                                    >
                                    <div class="col-sm-9">
                                        <Field
                                            type="date"
                                            name="shipping_date"
                                            class="form-control"
                                            v-model="invoice.shipping_date"
                                        />
                                        <ErrorMessage
                                            name="shipping_date"
                                            as="p"
                                            class="text-danger"
                                        />
                                    </div>
                                </div>
                                <div class="d-flex align-items-center gap-3">
                                    <button
                                        @click="currentStep = 'receive orders'"
                                        class="btn btn-secondary m-0"
                                    >
                                        next
                                    </button>
                                </div>
                            </Form>
                        </div>
                    </div>
                </div>
            </div>
        </template>
        <template v-if="currentStep === 'receive orders'">
            <h3>
                <span>Receive orders from {{ vendor.name }}</span>
            </h3>
            <div id="acqui_receive_summary">
                <p>
                    <strong>Invoice number:</strong>
                    {{ invoice.invoice_number }}
                    <strong class="ms-3">Shipment date:</strong>
                    {{ invoice.shipping_date }}
                </p>
            </div>
            <div class="row">
                <div class="grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h2 class="card-title">Pending orders</h2>
                            <div class="table-responsive">
                                <table
                                    class="table table-bordered table-striped"
                                >
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>Basket</th>
                                            <th>Order line</th>
                                            <th>Summary</th>
                                            <th>Quantity</th>
                                            <th>Unit cost</th>
                                            <th>Order cost</th>
                                            <th>Fund</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <template
                                            v-for="basket in spendingOrders"
                                        >
                                            <tr
                                                v-for="item in basket.items"
                                                :key="item.id"
                                            >
                                                <td>
                                                    <input
                                                        v-model="
                                                            receiveSelected
                                                        "
                                                        :value="item.id"
                                                        type="checkbox"
                                                    />
                                                </td>
                                                <td>
                                                    <RouterLink :to="`/admin`">
                                                        {{ basket.name }} ({{
                                                            basket.id
                                                        }})
                                                    </RouterLink>
                                                </td>
                                                <td>
                                                    <RouterLink :to="`/admin`">
                                                        {{ item.id }}
                                                    </RouterLink>
                                                </td>
                                                <td>
                                                    <RouterLink :to="`/admin`">
                                                        {{ item.book.title }}
                                                    </RouterLink>
                                                </td>
                                                <td>{{ item.quantity }}</td>
                                                <td>
                                                    {{
                                                        new Intl.NumberFormat({
                                                            style: "currency",
                                                        }).format(
                                                            item.vendor_price
                                                        ) + " đ"
                                                    }}
                                                </td>
                                                <td>
                                                    {{
                                                        new Intl.NumberFormat({
                                                            style: "currency",
                                                        }).format(
                                                            item.vendor_price
                                                        ) + " đ"
                                                    }}
                                                </td>
                                                <td>{{ item.fund.name }}</td>
                                                <td>
                                                    <a href="">cancel order</a>
                                                </td>
                                            </tr>
                                        </template>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <button @click="receiveOrders" class="btn btn-secondary">
                Receive selected ({{ receiveSelected.length }})
            </button>
        </template>
    </template>
</template>
