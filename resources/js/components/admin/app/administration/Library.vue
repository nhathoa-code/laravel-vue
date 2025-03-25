<script setup>
import { onMounted, ref } from "vue";
import { Field, Form, ErrorMessage } from "vee-validate";
import { LibraryService } from "@root/services/administration/LibraryService";
import Spinner from "@admin/common/Spinner.vue";
import Loading from "@admin/common/Loading.vue";
import Popup from "@admin/common/Popup.vue";
const libraries = ref(null);
const isEdit = ref(false);
const editedLibrary = ref(null);
onMounted(() => {
    LibraryService.getList((res) => {
        libraries.value = res.data;
    });
});
function insertLibrary(values, actions) {
    LibraryService.insert({ values, actions }, (res) => {
        libraries.value.push(res.data.data);
    });
}
function updateLibrary(values) {
    LibraryService.update(editedLibrary.value.id, { values }, (res) => {
        let returnedLibrary = res.data.data;
        editedLibrary.value.name = returnedLibrary.name;
        editedLibrary.value.address = returnedLibrary.address;
        editedLibrary.value.contact = returnedLibrary.contact;
    });
}
function deleteLibrary(id, index) {
    LibraryService.delete(id, (res) => {
        console.log(res);
        libraries.value.splice(index, 1);
    });
}
</script>
<template>
    <Loading
        :visible="LibraryService.loading.value || LibraryService.deleting.value"
    />
    <div v-if="libraries" class="row">
        <div class="col-md-6 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Add new library</h4>
                    <Form @submit="insertLibrary">
                        <div class="form-group">
                            <label>Name</label>
                            <Field
                                type="text"
                                name="name"
                                class="form-control"
                                placeholder="Name of library"
                            />
                            <ErrorMessage
                                name="name"
                                as="p"
                                class="text-danger"
                            />
                        </div>
                        <div class="form-group">
                            <label>Address</label>
                            <Field
                                type="text"
                                name="address"
                                class="form-control"
                                placeholder="Address of library"
                            />
                            <ErrorMessage
                                name="address"
                                as="p"
                                class="text-danger"
                            />
                        </div>
                        <div class="form-group">
                            <label>Contact</label>
                            <Field
                                type="text"
                                name="contact"
                                class="form-control"
                                placeholder="Contact of library"
                            />
                            <ErrorMessage
                                name="contact"
                                as="p"
                                class="text-danger"
                            />
                        </div>
                        <div class="d-flex align-items-center gap-3">
                            <button
                                :disabled="LibraryService.submitting.value"
                                type="submit"
                                class="btn btn-secondary m-0"
                            >
                                Submit
                            </button>
                            <spinner
                                :visible="LibraryService.submitting.value"
                            ></spinner>
                        </div>
                    </Form>
                </div>
            </div>
        </div>
        <div class="col-lg-6 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">List of libraries</h4>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Address</th>
                                    <th>Contact</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(item, index) in libraries">
                                    <td>{{ item.name }}</td>
                                    <td>{{ item.address }}</td>
                                    <td>{{ item.contact }}</td>
                                    <td>
                                        <button
                                            @click="
                                                isEdit = true;
                                                editedLibrary = item;
                                            "
                                            type="button"
                                            class="btn btn-outline-light btn-fw action"
                                        >
                                            <i class="pi pi-pencil"></i>
                                            Edit
                                        </button>
                                        <button
                                            @click="
                                                deleteLibrary(item.id, index)
                                            "
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
    <popup :isOpen="isEdit">
        <Form
            @submit="updateLibrary"
            class="forms-sample material-form edit-form"
            :validation-schema="schema"
        >
            <div class="form-group">
                <Field name="name" type="text" :value="editedLibrary.name" />
                <label class="control-label">Name</label>
                <i class="bar"></i>
            </div>
            <ErrorMessage name="name" as="p" class="text-danger" />
            <div class="form-group">
                <Field
                    name="address"
                    type="text"
                    :value="editedLibrary.address"
                />
                <label class="control-label">Address</label>
                <i class="bar"></i>
            </div>
            <ErrorMessage name="address" as="p" class="text-danger" />
            <div class="form-group">
                <Field
                    name="contact"
                    type="text"
                    :value="editedLibrary.contact"
                />
                <label class="control-label">Contact</label>
                <i class="bar"></i>
            </div>
            <ErrorMessage name="contact" as="p" class="text-danger" />
            <div class="button-container text-align-end">
                <button type="submit" class="btn btn-secondary mb-0">
                    <i class="fa fa-save"></i><span>Update</span></button
                ><button
                    type="button"
                    class="btn btn-dark mb-0"
                    style="color: white"
                    @click="
                        isEdit = false;
                        editedLibrary = null;
                    "
                >
                    <i class="fa fa-ban"></i><span>Cancel</span>
                </button>
            </div>
        </Form>
    </popup>
</template>
<style scoped>
td i.fa {
    font-size: 1.25rem;
    cursor: pointer;
}
.edit-form p[role="alert"] {
    margin-top: -1.75rem;
}
</style>
