<script setup>
import { onMounted, ref } from "vue";
import { Field, Form, ErrorMessage } from "vee-validate";
import { useRoute } from "vue-router";
import { useMessage } from "@root/functions";
import { VendorService } from "@root/services/acquisition/VendorService";
import Toast from "primevue/toast";
import Loading from "@admin/common/Loading.vue";
import Spinner from "@admin/common/Spinner.vue";
const vendor = ref(null);
const message = useMessage();
const route = useRoute();
onMounted(() => {
    VendorService.getById(route.params.id, (res) => {
        vendor.value = res.data;
    });
});
function updateVendor(values, actions) {
    VendorService.reset(false).update(
        vendor.value.id,
        { values, actions },
        (res) => {
            message(res.data.message);
        }
    );
}
</script>
<template>
    <Loading :visible="VendorService.loading.value" />
    <Toast />
    <template v-if="vendor">
        <h3>Modify vendor '{{ vendor.name }}'</h3>
        <div class="row">
            <div class="col-md-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body row">
                        <Form
                            @submit="updateVendor"
                            :initialValues="{
                                name: vendor.name,
                                email: vendor.email,
                                phone: vendor.phone,
                                address: vendor.address,
                            }"
                        >
                            <div class="form-group col-12">
                                <label>Name:</label>
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
                                <label>Email:</label>
                                <Field
                                    type="text"
                                    class="form-control"
                                    name="email"
                                />
                                <ErrorMessage
                                    name="email"
                                    as="p"
                                    class="text-danger"
                                />
                            </div>
                            <div class="form-group col-12">
                                <label>Phone:</label>
                                <Field
                                    type="text"
                                    class="form-control"
                                    name="phone"
                                />
                                <ErrorMessage
                                    name="phone"
                                    as="p"
                                    class="text-danger"
                                />
                            </div>
                            <div class="form-group col-12">
                                <label>Address:</label>
                                <Field
                                    as="textarea"
                                    class="form-control"
                                    name="address"
                                />
                                <ErrorMessage
                                    name="address"
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
                        </Form>
                    </div>
                </div>
            </div>
        </div>
    </template>
</template>
