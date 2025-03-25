<script setup>
import { Field, Form, ErrorMessage } from "vee-validate";
import { VendorService } from "@root/services/acquisition/VendorService";
import { useMessage } from "@root/functions";
import Toast from "primevue/toast";
import Spinner from "@admin/common/Spinner.vue";
const message = useMessage();
function insertVendor(values, actions) {
    VendorService.insert({ values, actions }, (res) => {
        message(res.data.message);
    });
}
</script>
<template>
    <Toast />
    <div class="row">
        <div class="col-md-6 grid-margin stretch-card">
            <div class="card">
                <div class="card-body row">
                    <Form @submit="insertVendor">
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
