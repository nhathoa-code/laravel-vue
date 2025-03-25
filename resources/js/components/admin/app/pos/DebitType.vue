<script setup>
import { onMounted, ref, computed, watch } from "vue";
import { Field, Form, ErrorMessage } from "vee-validate";
import { RouterLink, useRoute } from "vue-router";
import { DebitTypeService } from "@root/services/pos/DebitTypeService";
import Loading from "@admin/common/Loading.vue";
import Spinner from "@admin/common/Spinner.vue";
import * as yup from "yup";
const debitTypes = ref([]);
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
    DebitTypeService.getList((res) => {
        debitTypes.value = res.data;
    });
});
function insertDebitType(values, actions) {
    DebitTypeService.create({ values, actions }, (res) => {
        debitTypes.value.unshift(response.data.data);
    });
}
</script>
<template>
    <Loading :visible="DebitTypeService.loading.value" />
    <div id="toolbar" class="btn-toolbar">
        <RouterLink to="?op=add_form" class="btn btn-default">
            <i class="fa fa-plus"> </i> New debit type
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
                        @submit="insertDebitType"
                        :validation-schema="schema"
                        class="forms-sample"
                    >
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label required"
                                >Debit type code:</label
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
                            <label class="col-sm-3 col-form-label"
                                >Default amount</label
                            >
                            <div class="col-sm-9">
                                <Field
                                    type="number"
                                    name="default_amount"
                                    class="form-control"
                                />
                                <ErrorMessage
                                    name="default_amount"
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
                                >Can be sold?</label
                            >
                            <div class="col-sm-9">
                                <Field
                                    v-slot="{ field }"
                                    name="can_be_sold"
                                    type="checkbox"
                                    :value="true"
                                    :unchecked-value="false"
                                >
                                    <input
                                        type="checkbox"
                                        name="can_be_sold"
                                        v-bind="field"
                                        :value="true"
                                    />
                                </Field>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label"
                                >Library limitation:</label
                            >
                            <div class="col-sm-9">
                                <Field
                                    class="form-control"
                                    name="library"
                                    as="select"
                                >
                                    <option value="">All libraries</option>
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
                        <div class="d-flex align-items-center gap-3">
                            <button
                                :disabled="DebitTypeService.submitting.value"
                                type="submit"
                                class="btn btn-secondary m-0"
                            >
                                Submit
                            </button>
                            <spinner
                                :visible="DebitTypeService.submitting.value"
                            ></spinner>
                        </div>
                    </Form>
                </div>
            </div>
        </div>
    </div>
    <div v-else>
        <h3 class="mb-3">Debit types</h3>
        <div class="row">
            <div class="grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Code</th>
                                        <th>Description</th>
                                        <th>Default amount</th>
                                        <th>Available for</th>
                                        <th>Library limitations</th>
                                        <th style="width: 20%">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr
                                        v-for="item in debitTypes"
                                        :key="item.id"
                                    >
                                        <td>
                                            {{ item.code }}
                                        </td>
                                        <td>{{ item.description }}</td>
                                        <td>
                                            {{
                                                new Intl.NumberFormat({
                                                    style: "currency",
                                                }).format(item.default_amount) +
                                                " đ"
                                            }}
                                        </td>
                                        <td>
                                            <i class="fa fa-shopping-cart"></i>
                                            Sale
                                        </td>
                                        <td>
                                            {{
                                                item.library
                                                    ? ""
                                                    : "No limitation"
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
