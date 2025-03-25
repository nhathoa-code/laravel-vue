<script setup>
import { onMounted, ref } from "vue";
import { Field, Form, ErrorMessage } from "vee-validate";
import { BudgetService } from "@root/services/acquisition/BudgetService";
import { useMessage } from "@root/functions";
import Loading from "@admin/common/Loading.vue";
import Spinner from "@admin/common/Spinner.vue";
import Toast from "primevue/toast";
import * as yup from "yup";
const activeBudgets = ref(null);
const inActiveBudgets = ref(null);
const addBudget = ref(false);
const message = useMessage();
const schema = yup.object({});
onMounted(() => {
    BudgetService.getList((res) => {
        activeBudgets.value = res.data.filter((item) => item.active === 1);
        inActiveBudgets.value = res.data.filter((item) => item.active === 0);
    });
});
function insertBudget(values, actions) {
    BudgetService.insert({ values, actions }, (res) => {
        console.log(res.data.budget);
        let newBudget = res.data.budget;
        if (newBudget.active) {
            activeBudgets.value.push(newBudget);
        } else {
            inActiveBudgets.value.push(newBudget);
        }
        message(res.data.message);
    });
}
function deleteBudget(budget) {
    BudgetService.delete(budget.id, (res) => {
        if (budget.active == true) {
            activeBudgets.value = activeBudgets.value.filter(
                (item) => item.id != budget.id
            );
        } else {
            inActiveBudgets.value = inActiveBudgets.value.filter(
                (item) => item.id != budget.id
            );
        }
        message(res.data.message);
    });
}
</script>
<template>
    <Loading
        :visible="BudgetService.loading.value || BudgetService.deleting.value"
    />
    <Toast />
    <template v-if="activeBudgets && inActiveBudgets">
        <div id="toolbar" class="btn-toolbar">
            <div class="btn-group">
                <button
                    type="button"
                    class="btn btn-default"
                    @click="addBudget = true"
                >
                    <i class="pi pi-plus"></i> New budget
                </button>
                <button
                    v-if="addBudget"
                    type="button"
                    class="btn btn-default"
                    @click="addBudget = !addBudget"
                >
                    <i class="pi pi-times"></i> Cancel
                </button>
            </div>
        </div>
        <template v-if="addBudget">
            <div class="row">
                <div class="col-md-6 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body row">
                            <Form
                                method="POST"
                                @submit="insertBudget"
                                :validation-schema="schema"
                                :initialValues="{
                                    active: false,
                                    lock: false,
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
                                        :disabled="
                                            BudgetService.submitting.value
                                        "
                                        type="submit"
                                        class="btn btn-secondary m-0"
                                    >
                                        Save
                                    </button>
                                    <spinner
                                        :visible="
                                            BudgetService.submitting.value
                                        "
                                    ></spinner>
                                </div>
                            </Form>
                        </div>
                    </div>
                </div>
            </div>
        </template>
        <div v-if="!addBudget" class="home-tab tabs">
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
                            >Active budgets</a
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
                            >Inactive budgets</a
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
                                    <th>Budget name</th>
                                    <th>Start date</th>
                                    <th>End date</th>
                                    <th>Locked</th>
                                    <th>Total</th>
                                    <th style="width: 20%">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="item in activeBudgets"
                                    :key="item.id"
                                >
                                    <td>
                                        <RouterLink
                                            :to="{
                                                name: 'budget',
                                                params: { id: item.id },
                                            }"
                                        >
                                            {{ item.name }}
                                        </RouterLink>
                                    </td>
                                    <td>{{ item.start_date }}</td>
                                    <td>{{ item.end_date }}</td>
                                    <td>
                                        <span v-if="item.lock == 1">
                                            <i class="fa fa-lock"></i>
                                            Locked
                                        </span>
                                    </td>
                                    <td style="text-align: center">
                                        {{
                                            new Intl.NumberFormat({
                                                style: "currency",
                                            }).format(item.total_amount) + " đ"
                                        }}
                                    </td>
                                    <td>
                                        <RouterLink
                                            :to="`/admin/acquisition/budget/${item.id}/addfund`"
                                        >
                                            <button
                                                type="button"
                                                class="btn btn-outline-light btn-fw action"
                                            >
                                                <i class="pi pi-plus"></i>
                                                Add fund
                                            </button>
                                        </RouterLink>
                                        <button
                                            type="button"
                                            class="btn btn-outline-light btn-fw action"
                                        >
                                            <i class="pi pi-times"></i>
                                            Close
                                        </button>
                                        <RouterLink
                                            :to="`/admin/acquisition/budget/${item.id}/edit`"
                                        >
                                            <button
                                                type="button"
                                                class="btn btn-outline-light btn-fw action"
                                            >
                                                <i class="pi pi-pencil"></i>
                                                Edit
                                            </button>
                                        </RouterLink>
                                        <button
                                            @click="deleteBudget(item)"
                                            type="button"
                                            class="btn btn-outline-light btn-fw action"
                                        >
                                            <i
                                                title="Delete library"
                                                class="pi pi-trash"
                                            ></i>
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
                >
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Budget name</th>
                                <th>Start date</th>
                                <th>End date</th>
                                <th>Total</th>
                                <th style="width: 20%">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="item in inActiveBudgets" :key="item.id">
                                <td>
                                    <RouterLink
                                        :to="{
                                            name: 'budget',
                                            params: { id: item.id },
                                        }"
                                    >
                                        {{ item.name }}
                                    </RouterLink>
                                </td>
                                <td>{{ item.start_date }}</td>
                                <td>{{ item.end_date }}</td>
                                <td>
                                    {{
                                        new Intl.NumberFormat({
                                            style: "currency",
                                        }).format(item.total_amount) + " đ"
                                    }}
                                </td>
                                <td>
                                    <RouterLink
                                        :to="`/admin/acquisition/budget/${item.id}/addfund`"
                                    >
                                        <button
                                            type="button"
                                            class="btn btn-outline-light btn-fw action"
                                        >
                                            <i class="pi pi-plus"></i>
                                            Add fund
                                        </button>
                                    </RouterLink>
                                    <button
                                        type="button"
                                        class="btn btn-outline-light btn-fw action"
                                    >
                                        <i class="pi pi-times"></i>
                                        Close
                                    </button>
                                    <RouterLink
                                        :to="`/admin/acquisition/budget/${item.id}/edit`"
                                    >
                                        <button
                                            type="button"
                                            class="btn btn-outline-light btn-fw action"
                                        >
                                            <i class="pi pi-pencil"></i>
                                            Edit
                                        </button>
                                    </RouterLink>
                                    <button
                                        @click="deleteBudget(item)"
                                        type="button"
                                        class="btn btn-outline-light btn-fw action"
                                    >
                                        <i
                                            title="Delete library"
                                            class="pi pi-trash"
                                        ></i>
                                        Delete
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </template>
</template>
