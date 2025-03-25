<script setup>
import { onMounted, ref, computed, watch } from "vue";
import { Field, Form, ErrorMessage } from "vee-validate";
import { RouterLink, useRoute } from "vue-router";
import { AuthorizedCategoryService } from "@root/services/administration/AuthorizedCategoryService";
import { LibraryService } from "@root/services/administration/LibraryService";
import Spinner from "@admin/common/Spinner.vue";
import Loading from "@admin/common/Loading.vue";
import * as yup from "yup";
const values = ref([]);
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
        LibraryService.getList((res) => {
            libraries.value = res.data;
        });
    }
});
onMounted(() => {
    AuthorizedCategoryService.getValueList(
        (res) => {
            values.value = res.data;
        },
        { category: "LOC" }
    );
});
function insertLocation(values, actions) {
    values.category = "LOC";
    AuthorizedCategoryService.insertValue({ values, actions }, (res) => {
        console.log(res);
    });
}
function deleteLocation(locationId) {
    AuthorizedCategoryService.deleteValue(locationId, (res) => {
        console.log(res);
    });
}
</script>
<template>
    <Loading
        :visible="
            LibraryService.loading.value ||
            AuthorizedCategoryService.loading.value ||
            AuthorizedCategoryService.deleting.value
        "
    />
    <div id="toolbar" class="btn-toolbar">
        <RouterLink to="?op=add_form" class="btn btn-default">
            <i class="fa fa-plus"> </i> New location
        </RouterLink>
        <RouterLink v-if="op === 'add_form'" to="" class="btn btn-default">
            <i class="fa fa-ban"></i> Cancel
        </RouterLink>
    </div>
    <div v-if="op === 'add_form' && values" class="row">
        <div class="col-md-8 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <Form
                        method="POST"
                        @submit="insertLocation"
                        :validation-schema="schema"
                        class="forms-sample"
                    >
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label"
                                >Authorized value:</label
                            >
                            <div class="col-sm-9">
                                <Field
                                    type="text"
                                    name="value"
                                    class="form-control"
                                />
                                <ErrorMessage
                                    name="value"
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
                                    <option selected value>
                                        All libraries
                                    </option>
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
                                :disabled="
                                    AuthorizedCategoryService.submitting.value
                                "
                                type="submit"
                                class="btn btn-secondary m-0"
                            >
                                Save
                            </button>
                            <spinner
                                :visible="
                                    AuthorizedCategoryService.submitting.value
                                "
                            ></spinner>
                        </div>
                    </Form>
                </div>
            </div>
        </div>
    </div>
    <div v-else>
        <h3 class="mb-3">Shelve locations</h3>
        <div class="row">
            <div class="grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Authorized value</th>
                                        <th>Description</th>
                                        <th>Library</th>
                                        <th style="width: 20%">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="item in values" :key="item.id">
                                        <td>
                                            {{ item.value }}
                                        </td>
                                        <td>{{ item.description }}</td>
                                        <td>
                                            {{
                                                item.library
                                                    ? item.library.name
                                                    : "No limitation"
                                            }}
                                        </td>
                                        <td>
                                            <button
                                                type="button"
                                                class="btn btn-outline-light btn-fw action me-1"
                                            >
                                                <i class="pi pi-pencil"></i>
                                                Edit
                                            </button>
                                            <button
                                                @click="deleteLocation(item.id)"
                                                type="button"
                                                class="btn btn-outline-light btn-fw action"
                                            >
                                                <i class="pi pi-trash"></i>
                                                Delete
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
