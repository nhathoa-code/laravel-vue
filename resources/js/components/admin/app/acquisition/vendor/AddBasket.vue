<script setup>
import { onMounted, ref } from "vue";
import { useRoute } from "vue-router";
import { useDataStore } from "@root/stores/data";
import { useUserStore } from "@root/stores/user";
import { Field, Form, ErrorMessage } from "vee-validate";
import { VendorService } from "@root/services/acquisition/VendorService";
import { useMessage } from "@root/functions";
import Loading from "@admin/common/Loading.vue";
import Spinner from "@admin/common/Spinner.vue";
import Toast from "primevue/toast";
const message = useMessage();
const route = useRoute();
const userStore = useUserStore();
const dataStore = useDataStore();
const vendor = ref(null);
onMounted(() => {
    VendorService.getById(route.params.id, (res) => {
        vendor.value = res.data;
    });
});
function addBasket(values, actions) {
    VendorService.addBasket(vendor.value.id, { values, actions }, (res) => {
        message(res.data.message);
    });
}
</script>
<template>
    <Loading :visible="VendorService.loading.value" />
    <Toast />
    <div class="row">
        <template v-if="vendor">
            <h3 class="mb-3">Add a basket to "{{ vendor.name }}" Vendor</h3>
            <div class="col-md-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body row">
                        <Form @submit="addBasket">
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
                            <div class="form-group col-12">
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
                            </div>
                            <div class="d-flex align-items-center gap-3">
                                <button
                                    :disabled="VendorService.submitting.value"
                                    type="submit"
                                    class="btn btn-secondary m-0"
                                >
                                    Save
                                </button>
                                <spinner
                                    :visible="VendorService.submitting.value"
                                ></spinner>
                            </div>
                            <Field
                                type="hidden"
                                name="created_by"
                                :value="userStore.user.id"
                            />
                        </Form>
                    </div>
                </div>
            </div>
        </template>
    </div>
</template>
