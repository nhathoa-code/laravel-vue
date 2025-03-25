<script setup>
import { onMounted, ref } from "vue";
import { Field, Form, ErrorMessage } from "vee-validate";
import { BudgetService } from "@root/services/acquisition/BudgetService";
import { formattedDate } from "@root/functions";
import { useRoute, useRouter } from "vue-router";
import Loading from "@admin/common/Loading.vue";
import Spinner from "@admin/common/Spinner.vue";
const budget = ref(null);
const route = useRoute();
const router = useRouter();
onMounted(() => {
    BudgetService.getById(route.params.id, (res) => {
        budget.value = res.data;
    });
});
function updateBudget(values, actions) {
    BudgetService.update(budget.value.id, { values, actions }, (res) => {
        router.push({ name: "budget-list" });
    });
}
</script>
<template>
    <Loading :visible="BudgetService.loading.value" />
    <template v-if="budget">
        <h3 class="title">Modify budget '{{ budget.name }}'</h3>
        <div class="row">
            <div class="col-md-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body row">
                        <Form
                            method="POST"
                            @submit="updateBudget"
                            :initialValues="{
                                start_date: formattedDate(budget.start_date),
                                end_date: formattedDate(budget.end_date),
                                name: budget.name,
                                total_amount: budget.total_amount,
                                active: budget.active == 1 ? true : false,
                                lock: budget.lock == 1 ? true : false,
                            }"
                            class="forms-sample row"
                        >
                            <div class="form-group col-12">
                                <label>Start date:</label>
                                <Field
                                    type="date"
                                    name="start_date"
                                    class="form-control"
                                />
                                <ErrorMessage
                                    name="start_date"
                                    as="p"
                                    class="text-danger"
                                />
                            </div>
                            <div class="form-group col-12">
                                <label>End date:</label>
                                <Field
                                    type="date"
                                    class="form-control"
                                    name="end_date"
                                />
                                <ErrorMessage
                                    name="end_date"
                                    as="p"
                                    class="text-danger"
                                />
                            </div>
                            <div class="form-group col-12">
                                <label>Name:</label>
                                <Field
                                    type="text"
                                    class="form-control"
                                    name="name"
                                />
                                <ErrorMessage
                                    name="name"
                                    as="p"
                                    class="text-danger"
                                />
                            </div>
                            <div class="form-group col-12">
                                <label>Total amount:</label>
                                <Field
                                    type="number"
                                    class="form-control"
                                    name="total_amount"
                                />
                                <ErrorMessage
                                    name="total_amount"
                                    as="p"
                                    class="text-danger"
                                />
                            </div>
                            <div class="form-group col-12">
                                <label>Make budget active:</label>
                                <Field
                                    name="active"
                                    type="checkbox"
                                    :value="true"
                                />
                            </div>
                            <div class="form-group col-12">
                                <label>Lock budget:</label>
                                <Field
                                    name="lock"
                                    type="checkbox"
                                    :value="true"
                                />
                            </div>
                            <div class="d-flex align-items-center gap-3">
                                <button
                                    :disabled="BudgetService.submitting.value"
                                    type="submit"
                                    class="btn btn-secondary m-0"
                                >
                                    update
                                </button>
                                <RouterLink
                                    :to="{ name: 'budget-list' }"
                                    class="cancel"
                                    >Cancel</RouterLink
                                >
                                <spinner
                                    :visible="BudgetService.submitting.value"
                                ></spinner>
                            </div>
                        </Form>
                    </div>
                </div>
            </div>
        </div>
    </template>
</template>
