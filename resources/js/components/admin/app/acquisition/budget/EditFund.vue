<script setup>
import { onMounted, ref } from "vue";
import { Field, Form, ErrorMessage } from "vee-validate";
import { useDataStore } from "@root/stores/data";
import { useRoute, useRouter } from "vue-router";
import { BudgetService } from "@root/services/Acquisition/BudgetService";
import Spinner from "@admin/common/Spinner.vue";
import Loading from "@admin/common/Loading.vue";
import * as yup from "yup";
const dataStore = useDataStore();
const fund = ref(null);
const budget = ref(null);
const route = useRoute();
const router = useRouter();
const schema = yup.object({
    // name: yup.string().required("vui lòng nhập tên phân loại"),
});
onMounted(() => {
    BudgetService.getFund(route.params.id, route.params.fundId, (res) => {
        console.log(res);
        fund.value = res.data.fund;
        budget.value = res.data.budget;
    });
});
function updateFund(values, actions) {
    // const totalFundsAmount = budget.value.funds.reduce((o, i) => {
    //     return o + i.amount;
    // }, 0);
    // if (totalFundsAmount + values.amount > budget.value.total_amount) {
    //     return alert("Fund amount exceeds period allocation");
    // }
    // values.users = fund.value?.users.map((item) => item.id);
    BudgetService.updateFund(
        budget.value.id,
        fund.value.id,
        { values },
        (res) => {
            router.push({ name: "budget", params: { id: budget.value.id } });
        }
    );
}
</script>
<template>
    <Loading :visible="BudgetService.loading.value" />
    <template v-if="fund && budget">
        <div id="toolbar" class="btn-toolbar">
            <RouterLink :to="`/admin/acquisition/budget`">
                <button class="btn btn-default">
                    <i class="fa fa-plus"></i> New budget
                </button>
            </RouterLink>
            <RouterLink :to="`/admin/acquisition/budget/${budget.id}/addfund`">
                <button class="btn btn-default">
                    <i class="fa fa-plus"></i> New fund for {{ budget.name }}
                </button>
            </RouterLink>
            <RouterLink :to="`/admin/acquisition/budget/${budget.id}/edit`">
                <button class="btn btn-default">
                    <i class="fa fa-pencil"></i> Edit
                </button>
            </RouterLink>
        </div>
        <h3 class="mb-3">
            Modify fund '{{ fund.name }}' for budget '{{ budget.name }}'
        </h3>
        <div class="row">
            <div class="col-md-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body row">
                        <Form
                            method="POST"
                            @submit="updateFund"
                            :validation-schema="schema"
                            :initialValues="{
                                code: fund.code,
                                name: fund.name,
                                amount: fund.amount,
                                warning_at: fund.warning_at,
                                library: fund.library,
                                permission: fund.permission,
                                notes: fund.notes,
                                owner: fund.owner,
                            }"
                            class="forms-sample row"
                        >
                            <div class="form-group row">
                                <label
                                    for="exampleInputUsername2"
                                    class="col-sm-3 col-form-label required"
                                    >Fund code:</label
                                >
                                <div class="col-sm-9">
                                    <Field
                                        type="text"
                                        name="code"
                                        class="form-control"
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
                                    class="col-sm-3 col-form-label required"
                                    >Fund name:</label
                                >
                                <div class="col-sm-9">
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
                            </div>
                            <div class="form-group row">
                                <label
                                    for="exampleInputUsername2"
                                    class="col-sm-3 col-form-label required"
                                    >Amount:</label
                                >
                                <div class="col-sm-9">
                                    <Field
                                        type="number"
                                        name="amount"
                                        class="form-control"
                                    />
                                    <ErrorMessage
                                        name="amount"
                                        as="p"
                                        class="text-danger"
                                    />
                                </div>
                            </div>
                            <div class="form-group row">
                                <label
                                    for="exampleInputUsername2"
                                    class="col-sm-3 col-form-label"
                                    >Warning at:</label
                                >
                                <div class="col-sm-9">
                                    <Field
                                        type="number"
                                        name="warning_at"
                                        class="form-control"
                                    />
                                    <ErrorMessage
                                        name="warning_at"
                                        as="p"
                                        class="text-danger"
                                    />
                                </div>
                            </div>
                            <div class="form-group row">
                                <label
                                    for="exampleInputUsername2"
                                    class="col-sm-3 col-form-label"
                                    >Users:</label
                                >
                                <div class="col-sm-9 group-center">
                                    <ul
                                        style="
                                            float: left;
                                            list-style-type: none;
                                            margin: 0;
                                            padding-left: 0;
                                        "
                                        id="budget_users"
                                    >
                                        <li v-for="user in fund.users">
                                            <a href="#">{{ user.name }}</a>
                                            •
                                            <a
                                                @click.prevent="
                                                    removeUser(user)
                                                "
                                                href="#"
                                                ><i class="fa fa-trash-o"></i>
                                                Remove</a
                                            >
                                        </li>
                                        <li>
                                            <a
                                                @click.prevent="
                                                    openModal = true
                                                "
                                                href="#"
                                                id="add_user_button"
                                                data-toggle="modal"
                                                ><i class="fa fa-plus"></i> Add
                                                users</a
                                            >
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label
                                    for="exampleInputUsername2"
                                    class="col-sm-3 col-form-label"
                                    >Library:</label
                                >
                                <div class="col-sm-9">
                                    <Field
                                        name="library"
                                        as="select"
                                        class="form-control"
                                    >
                                        <option selected value="">none</option>
                                        <option
                                            :value="item.id"
                                            v-for="item in dataStore.libraries"
                                        >
                                            {{ item.name }}
                                        </option>
                                    </Field>
                                    <ErrorMessage
                                        name="library"
                                        as="p"
                                        class="text-danger"
                                    />
                                </div>
                            </div>
                            <div class="form-group row">
                                <label
                                    for="exampleInputUsername2"
                                    class="col-sm-3 col-form-label"
                                    >Restrict access to:</label
                                >
                                <div class="col-sm-9">
                                    <Field
                                        name="permission"
                                        as="select"
                                        class="form-control"
                                    >
                                        <option selected value="0">none</option>
                                        <option value="1">Owner</option>
                                        <option value="2">
                                            Owner and users
                                        </option>
                                        <option value="3">
                                            Owner and users and library
                                        </option>
                                    </Field>
                                    <ErrorMessage
                                        name="permission"
                                        as="p"
                                        class="text-danger"
                                    />
                                </div>
                            </div>
                            <div class="form-group row">
                                <label
                                    for="exampleInputUsername2"
                                    class="col-sm-3 col-form-label"
                                    >Notes:</label
                                >
                                <div class="col-sm-9">
                                    <Field
                                        name="notes"
                                        as="textarea"
                                        class="form-control"
                                    />

                                    <ErrorMessage
                                        name="notes"
                                        as="p"
                                        class="text-danger"
                                    />
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-3">
                                <button
                                    :disabled="BudgetService.submitting.value"
                                    type="submit"
                                    class="btn btn-secondary m-0"
                                >
                                    Update fund
                                </button>
                                <spinner
                                    :visible="BudgetService.submitting.value"
                                ></spinner>
                            </div>
                            <Field
                                type="hidden"
                                name="owner"
                                class="form-control"
                            />
                        </Form>
                    </div>
                </div>
            </div>
        </div>
    </template>
</template>
