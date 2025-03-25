<script setup>
import { onMounted, ref } from "vue";
import { Field, Form, ErrorMessage } from "vee-validate";
import { QuillEditor } from "@vueup/vue-quill";
import { useRoute } from "vue-router";
import { BookService } from "@root/services/BookService";
import { CategoryService } from "@root/services/CategoryService";
import Spinner from "@admin/common/Spinner.vue";
import Loading from "@admin/common/Loading.vue";
import CategoryItem from "@admin/common/CategoryItem.vue";
import "@vueup/vue-quill/dist/vue-quill.snow.css";
import * as yup from "yup";
const submmitted = ref(false);
const imagePreview = ref(null);
const categories = ref(null);
const book = ref(null);
const route = useRoute();
const schema = yup.object({
    // name: yup.string().required("vui lòng nhập tên phân loại"),
});
onMounted(() => {
    BookService.getById(route.params.id, (res) => {
        console.log(res);
        book.value = res.data;
    });
    CategoryService.getList((res) => {
        categories.value = res.data;
    });
});
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
function updateBook(values, actions) {
    submmitted.value = true;
    const checkboxes = document.querySelectorAll("input[name=cats]:checked");
    const categories = Array.from(checkboxes).map((item) => {
        return item.value;
    });
    values.categories = categories;
    values.description = book.value.description;
    console.log(values);
    axios
        .post(`api/books/${book.value.id}`, values, {
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
</script>
<template>
    <Loading :visible="BookService.loading.value" />
    <Form
        v-if="book && categories"
        method="POST"
        @submit="updateBook"
        :validation-schema="schema"
        :initial-values="{
            title: book.title,
            isbn: book.isbn,
            author: book.authors.join(','),
            publisher: book.publisher,
            published_date: book.published_date,
            page_count: book.page_count,
        }"
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
                                name="published_date"
                                class="form-control"
                                placeholder="Date of publish"
                            />
                            <ErrorMessage
                                name="published_date"
                                as="p"
                                class="text-danger"
                            />
                        </div>
                        <div class="form-group col-6 pe-0">
                            <label>Pages</label>
                            <Field
                                type="number"
                                name="page_count"
                                class="form-control"
                                placeholder="number of pages"
                            />
                            <ErrorMessage
                                name="page_count"
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
                                v-model:content="book.description"
                                contentType="html"
                            />
                        </div>
                    </div>
                    <Field type="hidden" name="_method" value="PUT" />
                    <div class="d-flex align-items-center gap-3">
                        <button
                            :disabled="submmitted"
                            type="submit"
                            class="btn btn-secondary m-0"
                        >
                            update
                        </button>
                        <spinner :visible="submmitted"></spinner>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 grid-margin">
            <div class="card">
                <div style="flex: none" class="card-body row">
                    <div class="form-group col-12 mb-0">
                        <label>Cover image</label>
                        <Field
                            type="file"
                            name="cover_image"
                            class="form-control"
                            placeholder="image"
                            @change="handleImageUpload"
                        />
                        <div v-if="imagePreview || book">
                            <img
                                :src="
                                    imagePreview
                                        ? imagePreview
                                        : book.cover_image
                                "
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
                                :categories="book.categories"
                            ></category-item>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </Form>
</template>
