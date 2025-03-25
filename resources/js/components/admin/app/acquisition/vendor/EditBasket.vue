<script setup>
import { onMounted, ref } from "vue";
import { useToast } from "vue-toast-notification";
import Spinner from "@admin/common/Spinner.vue";
import { Field, Form, ErrorMessage } from "vee-validate";
import { useDataStore } from "@admin/stores/data";
import "vue-toast-notification/dist/theme-sugar.css";
import { RouterLink, useRoute } from "vue-router";
import * as yup from "yup";
const $toast = useToast();
const dataStore = useDataStore();
const submitted = ref(false);
const vendor = ref(null);
const basket = ref(null);
const route = useRoute();
const schema = yup.object({});
onMounted(() => {
    axios
        .get(`api/vendors/${route.params.id}/baskets/${route.params.basketId}`)
        .then((response) => {
            console.log(response);
            vendor.value = response.data.vendor;
            basket.value = response.data.basket;
        })
        .catch((error) => {
            console.log("Error:", error);
        })
        .finally(() => {});
});
function updateBasket(values) {
    submitted.value = true;
    axios
        .put(
            `api/vendors/${route.params.id}/baskets/${route.params.basketId}`,
            values
        )
        .then((response) => {
            console.log(response);
        })
        .catch((error) => {
            console.log("Error:", error);
        })
        .finally(() => {
            submitted.value = false;
        });
}
</script>
<template>
    <div class="row">
        <template v-if="basket">
            <h3 class="mb-3">Edit basket {{ basket.name }}</h3>
            <div class="col-md-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body row">
                        <Form
                            method="POST"
                            @submit="updateBasket"
                            :validation-schema="schema"
                            :initialValues="{
                                name: basket.name,
                                billing_place: basket.billing_place.id,
                            }"
                            class="forms-sample row"
                        >
                            <div class="form-group col-12">
                                <label>Basket name:</label>
                                <Field
                                    type="text"
                                    name="name"
                                    class="form-control"
                                />
                                <ErrorMessage
                                    name="name"
                                    as="p"
                                    class="text-danger"
                                />
                            </div>
                            <div class="form-group col-12">
                                <label>Billing place:</label>
                                <Field
                                    name="billing_place"
                                    as="select"
                                    class="form-control"
                                >
                                    <option
                                        :value="item.id"
                                        v-for="item in dataStore.libraries"
                                    >
                                        {{ item.name }}
                                    </option>
                                </Field>
                                <ErrorMessage
                                    name="billing_place"
                                    as="p"
                                    class="text-danger"
                                />
                            </div>
                            <!-- <div class="form-group col-12">
                                <label>Vendor notes:</label>
                                <Field
                                    as="textarea"
                                    class="form-control"
                                    name="notes"
                                />
                                <ErrorMessage
                                    name="notes"
                                    as="p"
                                    class="text-danger"
                                />
                            </div> -->
                            <div class="d-flex align-items-center gap-3">
                                <button
                                    :disabled="submitted"
                                    type="submit"
                                    class="btn btn-secondary m-0"
                                >
                                    Update
                                </button>
                                <spinner :visible="submitted"></spinner>
                            </div>
                        </Form>
                    </div>
                </div>
            </div>
        </template>
    </div>
</template>
