<script setup>
import { onMounted, ref } from "vue";
import { Field, Form, ErrorMessage } from "vee-validate";
import { BookService } from "@root/services/BookService";
import { UserService } from "@root/services/UserService";
import { useDataStore } from "@root/stores/data";
import { RouterLink, useRoute } from "vue-router";
import Loading from "@admin/common/Loading.vue";
import Spinner from "@admin/common/Spinner.vue";
const book = ref(null);
const route = useRoute();
const dataStore = useDataStore();
const searchResults = ref([]);
const bookItem = ref(null);
const patron = ref(null);
const holdMethod = ref("available-item");
onMounted(() => {
    BookService.getById(
        route.query.bookid,
        (res) => {
            book.value = res.data;
        },
        { getItems: true }
    );
});
function search(values) {
    UserService.getList(
        (res) => {
            searchResults.value = res.data.data;
        },
        { type: "patron", q: values.q }
    );
}
function reserve(values) {
    values.method = holdMethod.value;
    if (values.method == "specific-item") {
        values.pickup_library = bookItem.value.current_location.id;
    }
    UserService.reserve(patron.value.id, book.value.id, { values }, (res) => {
        console.log(res);
    });
}
</script>
<template>
    <Loading :visible="BookService.loading.value" />
    <template v-if="book">
        <h3>Holds</h3>
        <h2>
            Place a hold on
            <RouterLink :to="''">
                <span class="biblio-title">{{ book.title }} /</span>
            </RouterLink>
            by {{ book.authors.join(",") }}
        </h2>
        <div v-if="!patron" class="col-md-6 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="hint">
                        Enter patron card number or partial name:
                    </div>
                    <Form @submit="search" method="POST" class="forms-sample">
                        <div class="row">
                            <div class="form-group col-8 mb-0">
                                <Field
                                    type="text"
                                    name="q"
                                    class="form-control p-1"
                                    style="height: auto !important"
                                />
                                <ErrorMessage
                                    name="q"
                                    as="p"
                                    class="text-danger"
                                />
                            </div>
                            <div
                                class="d-flex align-items-center gap-3 col-4 p-0"
                            >
                                <button
                                    :disabled="UserService.loading.value"
                                    type="submit"
                                    class="btn btn-secondary m-0"
                                >
                                    Search
                                </button>
                                <spinner
                                    :visible="UserService.loading.value"
                                ></spinner>
                            </div>
                        </div>
                    </Form>
                </div>
            </div>
        </div>
        <table
            class="table table-bordered table-striped selections-table"
            v-if="searchResults.length > 0 && !patron"
        >
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Card</th>
                    <th>Date of birth</th>
                    <th>Library</th>
                    <th>Address</th>
                </tr>
            </thead>
            <tbody>
                <tr
                    @click="patron = item"
                    class="clickable"
                    v-for="item in searchResults"
                >
                    <td>{{ item.name }}</td>
                    <td>{{ item.user_meta.card_number }}</td>
                    <td>{{ item.user_meta.birthdate }}</td>
                    <td>
                        {{ item.user_meta.library?.name ?? "No limitation" }}
                    </td>
                    <td>{{ item.user_meta.address }}</td>
                </tr>
            </tbody>
        </table>
        <Form
            v-if="patron"
            @submit="reserve"
            method="POST"
            class="forms-sample"
        >
            <div class="row">
                <div class="col-md-6 grid-margin stretch-card">
                    <div class="card">
                        <legend>Hold details</legend>
                        <div class="card-body">
                            <fieldset class="rows">
                                <ol>
                                    <li>
                                        <span class="label">Patron:</span>
                                        <div>
                                            <RouterLink
                                                :to="{
                                                    name: 'patron',
                                                    params: { id: patron.id },
                                                }"
                                            >
                                                {{ patron.name }}
                                            </RouterLink>
                                            ({{ patron.user_meta.card_number }})
                                        </div>
                                    </li>
                                    <li>
                                        <span class="label"
                                            >Estimated priority:</span
                                        >
                                        <span>1</span>
                                    </li>
                                    <li>
                                        <span class="label">Notes:</span>
                                        <Field
                                            type="text"
                                            name="notes"
                                            class="form-control p-1"
                                            style="height: auto !important"
                                        />
                                    </li>

                                    <li>
                                        <span class="label"
                                            >Hold expires on:</span
                                        >
                                        <Field
                                            type="date"
                                            name="expires_on"
                                            class="form-control p-1"
                                            style="height: auto !important"
                                        />
                                    </li>
                                </ol>
                            </fieldset>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 grid-margin stretch-card">
                    <div
                        :class="{
                            enable: holdMethod == 'available-item',
                            disable: holdMethod != 'available-item',
                        }"
                        class="card"
                        @click="holdMethod = 'available-item'"
                    >
                        <legend>
                            <input
                                type="radio"
                                name="request"
                                :checked="holdMethod == 'available-item'"
                            />

                            <label for="requestany" class="fw-bold">
                                Hold next available item
                            </label>
                        </legend>
                        <div class="card-body">
                            <fieldset class="row">
                                <ol>
                                    <li>
                                        <span class="label fw-bold"
                                            >Pickup at:</span
                                        >
                                        <Field
                                            name="pickup_library"
                                            class="form-control"
                                            as="select"
                                            v-if="!patron.user_meta.library"
                                        >
                                            <option
                                                :value="l.id"
                                                v-for="l in dataStore.libraries"
                                                :key="l.id"
                                            >
                                                {{ l.name }}
                                            </option>
                                        </Field>
                                        <div v-else>
                                            <span>{{
                                                patron.user_meta.library.name
                                            }}</span>
                                            <Field
                                                name="pickup_library"
                                                type="hidden"
                                                :value="
                                                    patron.user_meta.library.id
                                                "
                                            ></Field>
                                        </div>
                                    </li>
                                </ol>
                            </fieldset>
                            <div
                                class="d-flex align-items-center mt-3 gap-3 col-4 p-0"
                            >
                                <button
                                    :disabled="UserService.submitting.value"
                                    type="submit"
                                    class="btn btn-secondary-bold m-0"
                                >
                                    Place Hold
                                </button>
                                <spinner
                                    :visible="UserService.submitting.value"
                                ></spinner>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="grid-margin stretch-card">
                    <div
                        :class="{
                            enable: holdMethod == 'specific-item',
                            disable: holdMethod != 'specific-item',
                        }"
                        @click="holdMethod = 'specific-item'"
                        class="card"
                    >
                        <legend>
                            <input
                                type="radio"
                                name="request"
                                :checked="holdMethod == 'specific-item'"
                            />

                            <label for="requestany" class="fw-bold">
                                Hold a specific item
                            </label>
                        </legend>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table
                                    class="table table-bordered table-striped"
                                >
                                    <thead>
                                        <tr>
                                            <th>Hold</th>
                                            <th>Current Library</th>
                                            <th>Status</th>
                                            <th>Barcode</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr
                                            v-for="item in book.items"
                                            :key="item.id"
                                        >
                                            <td>
                                                <span
                                                    v-if="
                                                        patron.user_meta
                                                            .library &&
                                                        patron.user_meta.library
                                                            .id !=
                                                            item
                                                                .current_location
                                                                .id
                                                    "
                                                    class="error"
                                                >
                                                    <i
                                                        class="fa fa-times fa-lg"
                                                        title="Cannot be put on hold"
                                                    ></i>
                                                    <span
                                                        >Patron is from
                                                        different library</span
                                                    >
                                                </span>
                                                <Field
                                                    v-else
                                                    @change="bookItem = item"
                                                    type="radio"
                                                    name="book_item_id"
                                                    :value="item.id"
                                                />
                                            </td>
                                            <td>
                                                {{ item.current_location.name }}
                                            </td>
                                            <td>available</td>
                                            <td class="problem">
                                                {{ item.barcode }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div
                                class="d-flex align-items-center mt-3 gap-3 col-4 p-0"
                            >
                                <button
                                    :disabled="UserService.submitting.value"
                                    type="submit"
                                    class="btn btn-secondary-bold m-0"
                                >
                                    Place Hold
                                </button>
                                <spinner
                                    :visible="UserService.submitting.value"
                                ></spinner>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </Form>
    </template>
</template>
<style>
.stretch-card > .card.enable {
    border: 1px solid #408540 !important;
}
.stretch-card > .card.disable {
    cursor: pointer;
}
.stretch-card > .card.disable > .card-body {
    opacity: 0.5;
}
.error {
    color: #cc0000;
}
</style>
