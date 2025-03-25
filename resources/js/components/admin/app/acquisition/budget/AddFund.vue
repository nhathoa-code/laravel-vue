<script setup>
import { onMounted, ref } from "vue";
import { useRoute, useRouter } from "vue-router";
import { useUserStore } from "@root/stores/user";
import { useDataStore } from "@root/stores/data";
import { Field, Form } from "vee-validate";
import { FormInput, SubmitButton } from "@admin/common/form";
import { BudgetService } from "@root/services/acquisition/BudgetService";
import { UserService } from "@root/services/user/UserService";
import Loading from "@admin/common/Loading.vue";
import Modal from "@admin/common/Modal.vue";
import * as yup from "yup";
const userStore = useUserStore();
const dataStore = useDataStore();
const openModal = ref(false);
const searchedUsers = ref([]);
const users = ref([]);
const route = useRoute();
const router = useRouter();
const budget = ref(null);
const schema = yup.object({});
onMounted(() => {
    BudgetService.getById(route.params.id, (res) => {
        budget.value = res.data;
    });
});
function insertFund(values, actions) {
    const totalFundsAmount = budget.value.funds.reduce((o, i) => {
        return o + i.amount;
    }, 0);
    if (totalFundsAmount + values.amount > budget.value.total_amount) {
        return alert("Fund amount exceeds period allocation");
    }
    values.users = users.value.map((item) => item.id);
    BudgetService.addFund(budget.value.id, { values, actions }, (res) => {
        router.push({ name: "budget", params: { id: budget.value.id } });
    });
}
function searchUsers(values) {
    UserService.getList(
        (res) => {
            searchedUsers.value = res.data.data;
        },
        {
            type: "patron",
            ...values,
        }
    );
}
function addUser(user) {
    if (users.value.find((item) => item.id == user.id)) {
        return alert(`User '${user.name}' is already in the list.`);
    }
    users.value.push(user);
}
function removeUser(user) {
    users.value = users.value.filter((item) => item.id != user.id);
}
</script>
<template>
    <Loading :visible="BudgetService.loading.value" />
    <template v-if="budget">
        <h3 class="title">Add new fund for budget "{{ budget.name }}"</h3>
        <div class="row">
            <div class="col-md-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body row">
                        <Form
                            method="POST"
                            @submit="insertFund"
                            :validation-schema="schema"
                            class="forms-sample row"
                        >
                            <FormInput
                                name="code"
                                label="Fund code:"
                                :red="true"
                            />
                            <FormInput
                                name="name"
                                label="Fund name:"
                                :red="true"
                            />
                            <FormInput
                                name="amount"
                                type="number"
                                label="Amount:"
                                :red="true"
                            />
                            <FormInput
                                name="warning_at"
                                type="number"
                                label="Warning at:"
                            />
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label"
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
                                        <li v-for="user in users">
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
                            <FormInput
                                name="warning_at"
                                type="select"
                                :options="dataStore.libraries"
                                label="Library:"
                            />
                            <FormInput
                                name="permission"
                                type="select"
                                :options="[
                                    { id: 0, name: 'none' },
                                    { id: 1, name: 'Owner' },
                                    { id: 2, name: 'Owner and users' },
                                    {
                                        id: 3,
                                        name: 'Owner and users and library',
                                    },
                                ]"
                                label="Restrict access to:"
                            />
                            <FormInput
                                name="notes"
                                type="textarea"
                                label="Notes:"
                            />
                            <SubmitButton
                                name="Add fund"
                                :submitting="BudgetService.submitting.value"
                            />
                            <Field
                                type="hidden"
                                name="owner"
                                :value="userStore.user.id"
                                class="form-control"
                            />
                        </Form>
                    </div>
                </div>
            </div>
        </div>
    </template>
    <Modal @close="openModal = false" :open="openModal">
        <template v-slot:header>
            <h4 class="modal-title">Add users</h4>
        </template>
        <template v-slot:body>
            <Form
                @submit="searchUsers"
                :validation-schema="
                    yup.object({
                        q: yup.string().required('please enter search query'),
                    })
                "
            >
                <div class="row">
                    <div class="col-8">
                        <FormInput name="q" />
                    </div>
                    <div class="col-4">
                        <SubmitButton
                            name="Search"
                            :submitting="UserService.loading.value"
                            :cancel="false"
                        />
                    </div>
                </div>
            </Form>
            <table
                v-if="searchedUsers.length > 0"
                class="table table-bordered table-striped"
            >
                <thead>
                    <tr>
                        <th>Card</th>
                        <th>Name</th>
                        <th>Role</th>
                        <th>Library</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="user in searchedUsers" :key="user.id">
                        <td>123</td>
                        <td>{{ user.name }}</td>
                        <td>{{ user.role }}</td>
                        <td>library</td>
                        <td>
                            <button
                                @click="addUser(user)"
                                type="button"
                                class="btn btn-outline-light btn-fw action"
                            >
                                Add
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </template>
        <template v-slot:footer>
            <button
                @click="openModal = false"
                type="button"
                class="btn btn-default m-0"
            >
                Close
            </button>
        </template>
    </Modal>
</template>
