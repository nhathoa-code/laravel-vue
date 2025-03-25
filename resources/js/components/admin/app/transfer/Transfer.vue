<script setup>
import { onMounted, ref } from "vue";
import { Field, Form, ErrorMessage } from "vee-validate";
import "vue-toast-notification/dist/theme-sugar.css";
import Spinner from "@admin/common/Spinner.vue";
import * as yup from "yup";
const submmitted = ref(false);
const libraries = ref(null);
const schema = yup.object({
    destination_library: yup
        .number()
        .required("Please choose destination library"),
    barcode: yup.string().required("Please enter item's barcode"),
});
onMounted(() => {
    axios
        .get("api/libraries")
        .then((response) => {
            libraries.value = response.data;
        })
        .catch((error) => {
            console.log("Error:", error);
        })
        .finally(() => {});
});
function transfer(values) {
    submmitted.value = true;
    axios
        .post("api/transfers", values)
        .then((response) => {
            console.log(response);
        })
        .catch((error) => {
            console.log("Error:", error);
        })
        .finally(() => {
            submmitted.value = false;
        });
}
</script>
<template>
    <div v-if="libraries" class="row">
        <div class="col-md-6 grid-margin stretch-card">
            <div class="card">
                <h3 class="mt-2 ms-3 mb-0">Transfers</h3>
                <div class="card-body">
                    <Form
                        method="POST"
                        @submit="transfer"
                        :validation-schema="schema"
                        class="forms-sample"
                    >
                        <div class="form-group">
                            <label>Destination library:</label>
                            <Field
                                class="form-control"
                                name="destination_library"
                                as="select"
                            >
                                <option
                                    v-for="item in libraries"
                                    :value="item.id"
                                >
                                    {{ item.name }}
                                </option>
                            </Field>
                            <ErrorMessage
                                name="destination_library"
                                as="p"
                                class="text-danger"
                            />
                        </div>
                        <div class="form-group">
                            <label>Enter item barcode:</label>
                            <Field
                                type="text"
                                name="barcode"
                                class="form-control"
                            />
                            <ErrorMessage
                                name="barcode"
                                as="p"
                                class="text-danger"
                            />
                        </div>

                        <div class="d-flex align-items-center gap-3">
                            <button
                                :disabled="submmitted"
                                type="submit"
                                class="btn btn-secondary m-0"
                            >
                                Transfer
                            </button>
                            <spinner :visible="submmitted"></spinner>
                        </div>
                    </Form>
                </div>
            </div>
        </div>
    </div>
</template>
