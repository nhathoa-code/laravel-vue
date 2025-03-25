<script setup>
import { onMounted, ref } from "vue";
import { Field, Form, ErrorMessage } from "vee-validate";
import { QuillEditor } from "@vueup/vue-quill";
import Spinner from "@admin/common/Spinner.vue";
import CategoryItem from "@admin/common/CategoryItem.vue";
import "@vueup/vue-quill/dist/vue-quill.snow.css";
import * as yup from "yup";
const submmitted = ref(false);
const imagePreview = ref(null);
const description = ref(null);
const categories = ref([]);
const schema = yup.object({
    // isbn: yup.string().required("Please enter this field"),
    // title: yup.string().required("Please enter this field"),
    // author: yup.string().required("Please enter this field"),
    // publisher: yup.string().required("Please enter this field"),
});
onMounted(() => {
    axios
        .get("api/categories")
        .then((response) => {
            categories.value = response.data;
        })
        .catch((error) => {
            console.log("Error:", error);
        })
        .finally(() => {});
});
function insertBook(values, actions) {
    submmitted.value = true;
    const checkboxes = document.querySelectorAll("input[name=cats]:checked");
    const categories = Array.from(checkboxes).map((item) => {
        return item.value;
    });
    values.categories = categories;
    values.description = description.value;
    console.log(values);
    axios
        .post("api/books", values, {
            headers: {
                "Content-Type": "multipart/form-data",
            },
        })
        .then((response) => {
            console.log(response);
            // actions.resetForm();
            // imagePreview.value = null;
            // description.value = "<p></p>";
            // $toast.success(response.data.message);
        })
        .catch((error) => {
            console.log("Error:", error);
            if (error.status == 422) {
                let errors = error.response.data.errors;
                for (let key in errors) {
                    actions.setFieldError(key, errors[key][0]);
                }
            }
        })
        .finally(() => {
            submmitted.value = false;
        });
}
function handleImageUpload(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = () => {
            imagePreview.value = reader.result;
        };
        reader.readAsDataURL(file);
    }
}
</script>
<template>
    <Form
        method="POST"
        @submit="insertBook"
        :validation-schema="schema"
        class="forms-sample row"
    >
        <div class="col-md-8 grid-margin column-section">
            <div class="card">
                <div class="card-body row">
                    <div class="form-group col-12">
                        <label>Title</label>
                        <Field
                            type="text"
                            name="title"
                            class="form-control"
                            placeholder="title"
                            rules="required"
                        />
                        <ErrorMessage name="title" as="p" class="text-danger" />
                    </div>
                    <div class="form-group col-6">
                        <label>ISBN</label>
                        <Field
                            type="text"
                            name="isbn"
                            class="form-control"
                            placeholder="ISBN"
                        />
                        <ErrorMessage name="isbn" as="p" class="text-danger" />
                    </div>
                    <div class="form-group col-6">
                        <label>Author</label>
                        <Field
                            type="text"
                            name="author"
                            class="form-control"
                            placeholder="Author"
                        />
                        <ErrorMessage
                            name="author"
                            as="p"
                            class="text-danger"
                        />
                    </div>
                    <div class="form-group col-6">
                        <label>Publisher</label>
                        <Field
                            type="text"
                            name="publisher"
                            class="form-control"
                            placeholder="Publisher"
                        />
                        <ErrorMessage
                            name="publisher"
                            as="p"
                            class="text-danger"
                        />
                    </div>
                    <div class="form-group col-6 row pe-0">
                        <div class="form-group col-6 pe-0">
                            <label>Publish date</label>
                            <Field
                                type="text"
                                name="publish_date"
                                class="form-control"
                                placeholder="Date of publish"
                            />
                            <ErrorMessage
                                name="publish_date"
                                as="p"
                                class="text-danger"
                            />
                        </div>
                        <div class="form-group col-6 pe-0">
                            <label>Pages</label>
                            <Field
                                type="number"
                                name="pages"
                                class="form-control"
                                placeholder="number of pages"
                            />
                            <ErrorMessage
                                name="pages"
                                as="p"
                                class="text-danger"
                            />
                        </div>
                    </div>
                    <div class="form-group col-12">
                        <label>Description</label>
                        <div>
                            <QuillEditor
                                theme="snow"
                                v-model:content="description"
                                contentType="html"
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
                </div>
            </div>
        </div>
        <div class="col-md-4 grid-margin">
            <div class="card">
                <div style="flex: none" class="card-body row">
                    <div class="form-group col-12">
                        <label>Cover image</label>
                        <Field
                            type="file"
                            name="cover_image"
                            class="form-control"
                            placeholder="image"
                            @change="handleImageUpload"
                        />
                        <div v-if="imagePreview">
                            <img
                                :src="imagePreview"
                                alt="Cover Image Preview"
                                style="
                                    max-width: 300px;
                                    max-height: 300px;
                                    margin-top: 10px;
                                "
                            />
                        </div>
                        <ErrorMessage
                            name="cover_image"
                            as="p"
                            class="text-danger"
                        />
                    </div>
                </div>
            </div>
            <div class="card mt-3">
                <div class="card-body row">
                    <div
                        style="max-height: 200px; overflow-y: auto"
                        class="form-group col-12 mb-0"
                    >
                        <label>Categories</label>
                        <ul class="m-2 ps-0 my-0">
                            <category-item
                                v-for="(item, index) in categories"
                                :key="item.id"
                                :item="item"
                                :index="index"
                            ></category-item>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </Form>
</template>
