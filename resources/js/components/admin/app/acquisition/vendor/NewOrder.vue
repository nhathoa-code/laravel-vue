<script setup>
import { onMounted, ref } from "vue";
import { useRoute, useRouter } from "vue-router";
import { Field, Form, ErrorMessage } from "vee-validate";
import { VendorService } from "@root/services/acquisition/VendorService";
import { FundService } from "@root/services/acquisition/FundService";
import { BookService } from "@root/services/book/BookService";
import { useMessage } from "@root/functions";
import Loading from "@admin/common/Loading.vue";
import Spinner from "@admin/common/Spinner.vue";
import Toast from "primevue/toast";
const message = useMessage();
const route = useRoute();
const router = useRouter();
const basket = ref(null);
const vendor = ref(null);
const funds = ref([]);
const selectedFund = ref(null);
const basketItem = ref(null);
const vendorPrice = ref(0);
const quantity = ref(0);
const discount = ref(0);
const total = ref(0);
const searchResults = ref(null);
const q = ref("");
onMounted(() => {
    VendorService.getBasket(route.params.id, route.params.basketId, (res) => {
        basket.value = res.data.basket;
        vendor.value = res.data.vendor;
    });
    FundService.getList(
        (res) => {
            funds.value = res.data;
        },
        { getAvailableAmount: true }
    );
});
function searchCatalog() {
    BookService.search(
        (res) => {
            searchResults.value = res.data;
        },
        { q: q.value }
    );
}
function handleBlur() {
    total.value =
        quantity.value * vendorPrice.value -
        quantity.value * vendorPrice.value * (discount.value / 100);
}
function addItem(values) {
    VendorService.setError((error) => {
        if (error.status === 400) {
            message(error.response.data.message, "error");
        }
    });
    if (
        values.vendor_price * values.quantity >
        selectedFund.value.availableAmount
    ) {
        if (
            !confirm(
                "Warning! Order total amount exceeds allowed budget. Do you want to confirm this order?"
            )
        ) {
            return;
        }
    }
    values.fund_id = selectedFund.value.id;
    VendorService.addItem(vendor.value.id, basket.value.id, { values }, () => {
        router.push({
            name: "basket",
            params: { id: vendor.value.id, basketId: basket.value.id },
        });
    });
}
</script>
<template>
    <Loading :visible="VendorService.loading.value" />
    <Toast />
    <template v-if="basket && !basketItem">
        <h3 class="mb-3">New order for basket "{{ basket.name }}"</h3>
        <div class="row">
            <div class="col-md-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <Form @submit="searchCatalog">
                            <div class="row">
                                <div class="form-group col-8 mb-0">
                                    <Field
                                        type="text"
                                        name="q"
                                        v-model="q"
                                        class="form-control p-1"
                                        placeholder="Enter book's title or author"
                                        style="height: auto !important"
                                    />
                                    <ErrorMessage
                                        name="address"
                                        as="q"
                                        class="text-danger"
                                    />
                                </div>
                                <div
                                    class="d-flex align-items-center gap-3 col-4"
                                >
                                    <button
                                        :disabled="BookService.loading.value"
                                        type="submit"
                                        style="color: white"
                                        class="btn btn-secondary m-0"
                                    >
                                        Search
                                    </button>
                                    <spinner
                                        :visible="BookService.loading.value"
                                    ></spinner>
                                </div>
                            </div>
                        </Form>
                    </div>
                </div>
            </div>
        </div>
        <template v-if="searchResults">
            <div v-if="searchResults.length > 0" class="row">
                <div class="table-responsive col-6">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Title</th>
                                <th>Author</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="item in searchResults" :key="item.id">
                                <td style="text-align: center">
                                    <img
                                        style="width: 40px; height: auto"
                                        :src="item.cover_image"
                                        alt=""
                                    />
                                </td>
                                <td>
                                    <a href="">{{ item.title }}</a>
                                </td>
                                <td>
                                    <a href="">{{ item.authors.join(",") }}</a>
                                </td>
                                <td>
                                    <button
                                        @click="basketItem = item"
                                        class="btn btn-outline-light btn-fw action"
                                    >
                                        <i class="fa fa-shopping-basket"></i>
                                        Add order
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div v-else>
                <h3>No results found</h3>
                <p>
                    No results match your search for
                    <span style="font-weight: bold">
                        <span class="term">"{{ q }}"</span>
                    </span>
                </p>
            </div>
        </template>
    </template>
    <template v-if="basketItem">
        <h3 class="mb-3">New order for basket "{{ basket.name }}"</h3>
        <div class="row">
            <div class="col-md-6 grid-margin stretch-card">
                <div class="card p-2">
                    <fieldset class="rows">
                        <legend>Basket details</legend>
                        <ol>
                            <li>
                                <span class="label">Created by:</span>
                                {{ basket.created_by.name }}
                            </li>
                            <li>
                                <span class="label">Open on:</span>
                                {{ basket.opened_on }}
                            </li>
                        </ol>
                    </fieldset>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 grid-margin stretch-card">
                <div class="card p-2">
                    <fieldset class="rows">
                        <legend>Catalog details</legend>
                        <ol>
                            <li>
                                <span class="label">Title:</span>
                                {{ basketItem.title }}
                            </li>
                            <li>
                                <span class="label">Author:</span>
                                {{ basketItem.authors.join(",") }}
                            </li>
                        </ol>
                    </fieldset>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 grid-margin stretch-card">
                <div class="card p-2">
                    <fieldset class="rows">
                        <legend class="mb-3">Accounting details</legend>
                        <Form @submit="addItem">
                            <div class="form-group row">
                                <label
                                    for="exampleInputUsername2"
                                    class="col-sm-3 col-form-label required"
                                    >Quantity:</label
                                >
                                <div class="col-sm-9">
                                    <Field
                                        type="number"
                                        name="quantity"
                                        class="form-control"
                                        v-model="quantity"
                                    />
                                    <ErrorMessage
                                        name="code"
                                        as="p"
                                        class="text-danger"
                                    />
                                </div>
                            </div>
                            <div class="form-group row">
                                <label
                                    for="exampleInputUsername2"
                                    class="col-sm-3 col-form-label"
                                    >Fund:</label
                                >
                                <div class="col-sm-9">
                                    <Field
                                        name="fund_id"
                                        v-model="selectedFund"
                                        as="select"
                                        class="form-control"
                                    >
                                        <option
                                            :value="item"
                                            v-for="item in funds"
                                        >
                                            {{ item.name }}
                                        </option>
                                    </Field>
                                    <ErrorMessage
                                        name="fund_id"
                                        as="p"
                                        class="text-danger"
                                    />
                                </div>
                            </div>
                            <div class="form-group row">
                                <label
                                    for="exampleInputUsername2"
                                    class="col-sm-3 col-form-label"
                                    >Vendor price:</label
                                >
                                <div class="col-sm-9">
                                    <Field
                                        type="number"
                                        name="vendor_price"
                                        class="form-control"
                                        v-model="vendorPrice"
                                        @blur="handleBlur"
                                    />
                                    <ErrorMessage
                                        name="vendor_price"
                                        as="p"
                                        class="text-danger"
                                    />
                                </div>
                            </div>
                            <div class="form-group row">
                                <label
                                    for="exampleInputUsername2"
                                    class="col-sm-3 col-form-label"
                                    >Discount(%):</label
                                >
                                <div class="col-sm-9">
                                    <Field
                                        type="number"
                                        name="discount"
                                        class="form-control"
                                        v-model="discount"
                                        @blur="handleBlur"
                                    />
                                    <ErrorMessage
                                        name="discount"
                                        as="p"
                                        class="text-danger"
                                    />
                                </div>
                            </div>
                            <div class="form-group row">
                                <label
                                    for="exampleInputUsername2"
                                    class="col-sm-3 col-form-label"
                                    >Total:</label
                                >
                                <div class="col-sm-9">
                                    <Field
                                        type="number"
                                        name="total"
                                        class="form-control"
                                        v-model="total"
                                        readonly
                                    />
                                    <ErrorMessage
                                        name="total"
                                        as="p"
                                        class="text-danger"
                                    />
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-3">
                                <button
                                    :disabled="VendorService.submitting.value"
                                    type="submit"
                                    class="btn btn-secondary m-0"
                                >
                                    Save
                                </button>
                                <a class="cancel">Cancel</a>
                                <spinner
                                    :visible="VendorService.submitting.value"
                                ></spinner>
                            </div>
                            <Field
                                type="hidden"
                                name="book_id"
                                :value="basketItem.id"
                            />
                        </Form>
                    </fieldset>
                </div>
            </div>
        </div>
    </template>
</template>
