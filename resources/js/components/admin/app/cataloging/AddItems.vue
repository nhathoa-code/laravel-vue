<script setup>
import { useRoute } from "vue-router";
import { onMounted, ref } from "vue";
import { Field, Form, ErrorMessage } from "vee-validate";
import { BookService } from "@root/services/BookService";
import { AuthorizedCategoryService } from "@root/services/AuthorizedCategoryService";
import { useDataStore } from "@root/stores/data";
import { useMessage } from "@root/functions";
import Toast from "primevue/toast";
import Spinner from "@admin/common/Spinner.vue";
import Loading from "@admin/common/Loading.vue";
import * as yup from "yup";
const route = useRoute();
const editedItem = ref(null);
const editMode = ref(false);
const book = ref(null);
const locations = ref(null);
const dataStore = useDataStore();
const message = useMessage();
const schema = yup.object({
    barcode: yup.string().required("please enter barcode"),
    home_library: yup.string().required("please select library"),
});
onMounted(() => {
    BookService.getById(
        route.params.id,
        (res) => {
            console.log(res.data);
            book.value = res.data;
        },
        { getItems: true }
    );
    AuthorizedCategoryService.getList(
        (res) => {
            locations.value = res.data;
        },
        { category: "LOC" }
    );
});
function insertBookItem(values, actions) {
    BookService.insertItem(book.value.id, { values, actions }, (res) => {
        book.value.items.push(res.data.data);
        message(res.data.message);
    });
}
function updateBookItem(values, actions) {
    BookService.updateItem(
        book.value.id,
        editedItem.value.id,
        {
            values,
            actions,
        },
        (res) => {
            editedItem.value.barcode = res.data.data.barcode;
            editedItem.value.location = res.data.data.location;
            editMode.value = false;
            message(res.data.message);
        }
    );
}
function deleteBookItem(id, index) {
    if (confirm("you do really want to delete this item ?")) {
        BookService.deleteItem(book.value.id, id, (res) => {
            book.value.items.splice(index, 1);
            editMode.value = false;
            message(res.data.message);
        });
    }
}
</script>
<template>
    <Loading :visible="BookService.loading.value" />
    <Toast />
    <template v-if="book && locations">
        <h4 class="fw-bold">
            Items for {{ book.title }} (Record #{{ book.id }})
        </h4>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Home Library</th>
                    <th>Current Location</th>
                    <th>Barcode</th>
                    <th>Date acquired</th>
                    <th>Shelving location</th>
                    <th>Total checkouts</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <tr
                    :class="{
                        edited:
                            editedItem && editMode && editedItem.id == item.id,
                    }"
                    v-for="(item, index) in book.items"
                    :key="item.id"
                >
                    <td>{{ item.home_library.name }}</td>
                    <td>{{ item.current_location.name }}</td>
                    <td>{{ item.barcode }}</td>
                    <td>{{ item.date_acquired }}</td>
                    <td>{{ item.location.value }}</td>
                    <td>{{ item.checkouts }}</td>
                    <td>
                        <button
                            type="button"
                            class="btn btn-outline-light btn-fw action m-0"
                            @click="
                                editedItem = item;
                                editMode = true;
                            "
                        >
                            <i title="Edit library" class="fa fa-pencil"></i>
                            Edit
                        </button>
                        <button
                            type="button"
                            class="btn btn-outline-light btn-fw action m-0"
                            @click="deleteBookItem(item.id, index)"
                        >
                            <i title="Delete library" class="fa fa-trash-o"></i>
                            Delete
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
        <div class="row mt-5">
            <div class="col-md-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <Form
                            v-if="!editMode"
                            method="POST"
                            @submit="insertBookItem"
                            :validation-schema="schema"
                            class="forms-sample"
                        >
                            <div class="form-group">
                                <label>Barcode</label>
                                <Field
                                    type="text"
                                    name="barcode"
                                    class="form-control"
                                    placeholder="barcode"
                                />
                                <ErrorMessage
                                    name="barcode"
                                    as="p"
                                    class="text-danger"
                                />
                            </div>
                            <div class="form-group">
                                <label>Library</label>
                                <Field
                                    name="home_library"
                                    class="form-control"
                                    as="select"
                                >
                                    <option
                                        :value="l.id"
                                        v-for="l in dataStore.libraries"
                                        :key="l.id"
                                    >
                                        {{ l.name }}
                                    </option>
                                </Field>
                                <ErrorMessage
                                    name="home_library"
                                    as="p"
                                    class="text-danger"
                                />
                            </div>
                            <div class="form-group">
                                <label>Shelving location</label>
                                <Field
                                    name="shelving_location"
                                    class="form-control"
                                    as="select"
                                >
                                    <option
                                        :value="l.id"
                                        v-for="l in locations"
                                        :key="l.id"
                                    >
                                        {{ l.value }}
                                    </option>
                                </Field>
                                <ErrorMessage
                                    name="shelving_location"
                                    as="p"
                                    class="text-danger"
                                />
                            </div>
                            <Field type="hidden" name="book" :value="book.id" />
                            <div class="d-flex align-items-center gap-3">
                                <button
                                    :disabled="BookService.submitting.value"
                                    type="submit"
                                    class="btn btn-secondary m-0"
                                >
                                    Add Item
                                </button>
                                <spinner
                                    :visible="BookService.submitting.value"
                                ></spinner>
                            </div>
                        </Form>
                        <Form
                            v-if="editMode"
                            method="POST"
                            @submit="updateBookItem"
                            class="forms-sample"
                        >
                            <div class="form-group">
                                <label>Barcode</label>
                                <Field
                                    type="text"
                                    name="barcode"
                                    class="form-control"
                                    placeholder="barcode"
                                    :value="editedItem.barcode"
                                />
                                <ErrorMessage
                                    name="barcode"
                                    as="p"
                                    class="text-danger"
                                />
                            </div>
                            <div class="form-group">
                                <label>Shelving location</label>
                                <Field
                                    name="shelving_location"
                                    class="form-control"
                                    as="select"
                                    v-model="editedItem.shelving_location"
                                >
                                    <option
                                        :value="l.id"
                                        v-for="l in locations"
                                        :key="l.id"
                                    >
                                        {{ l.value }}
                                    </option>
                                </Field>
                                <ErrorMessage
                                    name="shelving_location"
                                    as="p"
                                    class="text-danger"
                                />
                            </div>
                            <div class="d-flex align-items-center gap-3">
                                <button
                                    :disabled="BookService.submitting.value"
                                    type="submit"
                                    style="color: black"
                                    class="btn btn-secondary m-0"
                                >
                                    Save changes
                                </button>
                                <a
                                    href="javascript:void(0)"
                                    v-if="editMode"
                                    @click.prevent="
                                        editedItem = null;
                                        editMode = false;
                                    "
                                >
                                    cancel
                                </a>
                                <spinner
                                    :visible="BookService.submitting.value"
                                ></spinner>
                            </div>
                        </Form>
                    </div>
                </div>
            </div>
        </div>
    </template>
</template>
<style scoped>
tr.edited td {
    background-color: #ffffcc !important;
}
</style>
