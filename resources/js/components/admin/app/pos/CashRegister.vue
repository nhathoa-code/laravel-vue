<script setup>
import { onMounted, ref, computed, watch } from "vue";
import { Field, Form, ErrorMessage } from "vee-validate";
import { RouterLink, useRoute } from "vue-router";
import { CashRegisterService } from "@root/services/pos/CashRegisterService";
import Spinner from "@admin/common/Spinner.vue";
import Loading from "@admin/common/Loading.vue";
import * as yup from "yup";
const submmitted = ref(false);
const cashRegisters = ref([]);
const libraries = ref(null);
const route = useRoute();
const schema = yup.object({
    // name: yup.string().required("vui lòng nhập tên phân loại"),
});
const op = computed(() => {
    return route.query.op;
});
watch(op, async (newOp) => {
    if (newOp === "add_form" && !libraries.value) {
        axios
            .get("api/libraries")
            .then((response) => {
                console.log(response.data);
                libraries.value = response.data;
            })
            .catch((error) => {
                console.log("Error:", error);
            })
            .finally(() => {});
    }
});
onMounted(() => {
    CashRegisterService.getList((res) => {
        cashRegisters.value = res.data;
    });
});
function insertCashRegister(values, actions) {
    CashRegisterService.create({ values, actions }, (res) => {
        console.log(res);
    });
}
</script>
<template>
    <Loading :visible="CashRegisterService.loading.value" />
    <div id="toolbar" class="btn-toolbar">
        <RouterLink to="?op=add_form" class="btn btn-default">
            <i class="fa fa-plus"> </i> New cash register
        </RouterLink>
        <RouterLink v-if="op === 'add_form'" to="" class="btn btn-default">
            <i class="fa fa-ban"></i> Cancel
        </RouterLink>
    </div>
    <div v-if="op === 'add_form' && libraries" class="row">
        <div class="col-md-8 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <Form
                        method="POST"
                        @submit="insertCashRegister"
                        :validation-schema="schema"
                        class="forms-sample"
                    >
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label required"
                                >Name:</label
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
                            <label class="col-sm-3 col-form-label"
                                >Description</label
                            >
                            <div class="col-sm-9">
                                <Field
                                    type="text"
                                    name="description"
                                    class="form-control"
                                />
                                <ErrorMessage
                                    name="description"
                                    as="p"
                                    class="text-danger"
                                />
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label"
                                >Library:</label
                            >
                            <div class="col-sm-9">
                                <Field
                                    class="form-control"
                                    name="library"
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
                                    name="library"
                                    as="p"
                                    class="text-danger"
                                />
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label"
                                >Initial amount</label
                            >
                            <div class="col-sm-9">
                                <Field
                                    type="number"
                                    name="initial_amount"
                                    class="form-control"
                                />
                                <ErrorMessage
                                    name="initial_amount"
                                    as="p"
                                    class="text-danger"
                                />
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-3">
                            <button
                                :disabled="CashRegisterService.submitting.value"
                                type="submit"
                                class="btn btn-secondary m-0"
                            >
                                Submit
                            </button>
                            <spinner
                                :visible="CashRegisterService.submitting.value"
                            ></spinner>
                        </div>
                    </Form>
                </div>
            </div>
        </div>
    </div>
    <div v-else>
        <h3 class="mb-3">Cash registers</h3>
        <div class="row">
            <div class="grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Description</th>
                                        <th>Library</th>
                                        <th>Library default</th>
                                        <th>Initial amount</th>
                                        <th style="width: 5%">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr
                                        v-for="item in cashRegisters"
                                        :key="item.id"
                                    >
                                        <td>
                                            {{ item.name }}
                                        </td>
                                        <td>{{ item.description }}</td>
                                        <td>
                                            {{ item.to_library.name }}
                                        </td>
                                        <td>yes</td>
                                        <td>
                                            {{
                                                new Intl.NumberFormat({
                                                    style: "currency",
                                                }).format(item.initial_amount) +
                                                " đ"
                                            }}
                                        </td>
                                        <td>
                                            <button
                                                type="button"
                                                class="btn btn-outline-light btn-fw action"
                                            >
                                                <i
                                                    class="fa fa-solid fa-pencil"
                                                ></i>
                                                Edit
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
