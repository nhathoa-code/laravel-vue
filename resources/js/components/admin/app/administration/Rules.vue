<script setup>
import { onMounted, ref } from "vue";
import { Field, Form, ErrorMessage } from "vee-validate";
import Spinner from "@admin/common/Spinner.vue";
import * as yup from "yup";
const rules = ref(null);
const schema = yup.object({
    // name: yup.string().required("vui lòng nhập tên phân loại"),
});
onMounted(() => {
    axios
        .get("api/options?key=circulation_rules")
        .then((response) => {
            rules.value = response.data;
            console.log(rules.value.current_checkouts_allowed);
        })
        .catch((error) => {
            console.log("Error:", error);
        })
        .finally(() => {});
});
function insertDebitType(values, actions) {
    submmitted.value = true;
    console.log(values);
    axios
        .post("/api/circulations/rules", values)
        .then((response) => {
            console.log(response);
        })
        .catch((error) => {
            console.error(error);
        })
        .finally(() => {
            submmitted.value = false;
            // actions.resetForm();
        });
}
</script>
<template>
    <div class="col-md-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <Form
                    method="POST"
                    @submit="insertDebitType"
                    :validation-schema="schema"
                    class="forms-sample"
                >
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label"
                            >Current checkouts allowed:</label
                        >
                        <div class="col-sm-9">
                            <Field
                                type="number"
                                name="current_checkouts_allowed"
                                class="form-control"
                                :value="rules?.current_checkouts_allowed ?? ''"
                            />
                            <ErrorMessage
                                name="current_checkouts_allowed"
                                as="p"
                                class="text-danger"
                            />
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label"
                            >Loan period:</label
                        >
                        <div class="col-sm-9">
                            <Field
                                type="number"
                                name="loan_period"
                                class="form-control"
                                :value="rules?.loan_period ?? ''"
                            />
                            <ErrorMessage
                                name="loan_period"
                                as="p"
                                class="text-danger"
                            />
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label"
                            >Renewals allowed</label
                        >
                        <div class="col-sm-9">
                            <Field
                                type="number"
                                name="renewals_allowed"
                                class="form-control"
                                :value="rules?.renewals_allowed ?? ''"
                            />
                            <ErrorMessage
                                name="renewals_allowed"
                                as="p"
                                class="text-danger"
                            />
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label"
                            >Total holds allowed</label
                        >
                        <div class="col-sm-9">
                            <Field
                                type="number"
                                name="total_holds_allowed"
                                class="form-control"
                                :value="rules?.total_holds_allowed ?? ''"
                            />
                            <ErrorMessage
                                name="total_holds_allowed"
                                as="p"
                                class="text-danger"
                            />
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label"
                            >Fine amount</label
                        >
                        <div class="col-sm-9">
                            <Field
                                type="number"
                                name="fine_amount"
                                class="form-control"
                                :value="rules?.fine_amount ?? ''"
                            />
                            <ErrorMessage
                                name="fine_amount"
                                as="p"
                                class="text-danger"
                            />
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label"
                            >Fine charging interval:</label
                        >
                        <div class="col-sm-9">
                            <Field
                                type="number"
                                name="fine_charging_interval"
                                class="form-control"
                                :value="rules?.fine_charging_interval ?? ''"
                            />
                            <ErrorMessage
                                name="fine_charging_interval"
                                as="p"
                                class="text-danger"
                            />
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label"
                            >Overdue fines cap:</label
                        >
                        <div class="col-sm-9">
                            <Field
                                type="number"
                                name="overdue_fines_cap"
                                class="form-control"
                                :value="rules?.overdue_fines_cap ?? ''"
                            />
                            <ErrorMessage
                                name="overdue_fines_cap"
                                as="p"
                                class="text-danger"
                            />
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-3">
                        <button
                            :disabled="submmitted"
                            type="submit"
                            style="color: white"
                            class="btn btn-primary m-0"
                        >
                            Submit
                        </button>
                        <spinner :visible="submmitted"></spinner>
                    </div>
                </Form>
            </div>
        </div>
    </div>
</template>
