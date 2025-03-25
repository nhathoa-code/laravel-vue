<script setup>
import { onMounted, ref, reactive } from "vue";
import { useToast } from "vue-toast-notification";
import { Field, Form, ErrorMessage } from "vee-validate";
import Spinner from "@admin/common/Spinner.vue";
import "vue-toast-notification/dist/theme-sugar.css";
import * as yup from "yup";
import { RouterLink, useRoute } from "vue-router";
const $toast = useToast();
const submmitted = ref(false);
const borrower = reactive({
    cardNumber: null,
    pickLibrary: null,
});
const libraries = ref(null);
const route = useRoute();
const items = ref(null);
const patron = ref(null);
const schema = yup.object({
    // name: yup.string().required("vui lòng nhập tên phân loại"),
});
onMounted(() => {
    axios
        .get("api/libraries")
        .then((response) => {
            libraries.value = response.data;
        })
        .catch((error) => {
            console.log("Error:", error);
        })
        .finally(() => {});
});
function searchPartners(values) {
    submmitted.value = true;
    console.log(values);
    axios
        .get("api/ill-requests/partners", {
            params: values,
        })
        .then((response) => {
            console.log(response);
            items.value = response.data.partners;
            patron.value = response.data.patron;
        })
        .catch((error) => {
            console.error(error);
        })
        .finally(() => {
            submmitted.value = false;
        });
}
function select(item) {
    const data = {
        book_id: item.book_id,
        patron: patron.value.id,
        lending_library: item.current_location,
        borrowing_library: borrower.pickLibrary,
    };
    axios
        .post("api/ill-requests", data)
        .then((response) => {
            console.log(response);
        })
        .catch((error) => {
            console.error(error);
        })
        .finally(() => {
            submmitted.value = false;
        });
}
</script>
<template>
    <Form
        v-if="libraries && !items"
        method="GET"
        @submit="searchPartners"
        :validation-schema="schema"
        class="forms-sample"
    >
        <div class="row">
            <div class="col-md-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body pb-0">
                        <h4 class="mb-3">Search partners</h4>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label required"
                                >Keyword:</label
                            >
                            <div class="col-sm-9">
                                <Field
                                    type="text"
                                    name="keyword"
                                    class="form-control"
                                    placeholder="title, author or ISBN"
                                />
                                <ErrorMessage
                                    name="keyword"
                                    as="p"
                                    class="text-danger"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body pb-0">
                        <h4 class="mb-3">Borrower options</h4>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label required"
                                >Patron's card number:</label
                            >
                            <div class="col-sm-9">
                                <Field
                                    type="text"
                                    name="card_number"
                                    class="form-control"
                                    v-model="borrower.cardNumber"
                                />
                                <ErrorMessage
                                    name="card_number"
                                    as="p"
                                    class="text-danger"
                                />
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label required"
                                >Pickup library:</label
                            >
                            <div class="col-sm-9">
                                <Field
                                    class="form-control"
                                    name="pickup_library"
                                    as="select"
                                    v-model="borrower.pickLibrary"
                                >
                                    <option
                                        v-for="item in libraries"
                                        :value="item.id"
                                    >
                                        {{ item.name }}
                                    </option>
                                </Field>
                                <ErrorMessage
                                    name="pickup_library"
                                    as="p"
                                    class="text-danger"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="d-flex align-items-center gap-3">
                <button
                    :disabled="submmitted"
                    type="submit"
                    class="btn btn-secondary m-0"
                >
                    Submit
                </button>
                <spinner :visible="submmitted"></spinner>
            </div>
        </div>
    </Form>
    <template v-if="items">
        <h3>Select an item to request</h3>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Partner</th>
                        <th>Title</th>
                        <th>Author</th>
                        <th>ISBN</th>
                        <th>Select?</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="item in items" :key="item.id">
                        <td>{{ item.library }}</td>
                        <td>{{ item.title }}</td>
                        <td>{{ item.author }}</td>
                        <td>{{ item.isbn }}</td>
                        <td>
                            <button
                                @click="select(item)"
                                type="button"
                                class="btn btn-outline-light btn-fw action"
                            >
                                Select this item
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </template>
</template>
