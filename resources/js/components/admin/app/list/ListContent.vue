<script setup>
import { onMounted, ref } from "vue";
import { useRoute } from "vue-router";
import { ListService } from "@root/services/book/ListService";
import { Toolbar, Button } from "@admin/common/toolbar";
import { useMessage } from "@root/functions";
import { Field, Form, ErrorMessage } from "vee-validate";
import Modal from "@admin/common/Modal.vue";
import Loading from "@admin/common/Loading.vue";
import Spinner from "@admin/common/Spinner.vue";
import Toast from "primevue/toast";
const list = ref(null);
const openModal = ref(false);
const message = useMessage();
const route = useRoute();
onMounted(() => {
    ListService.getById(route.params.id, (res) => {
        list.value = res.data;
    });
});
function addItem(values, actions) {
    ListService.addItem(list.value.id, { values, actions }, (res) => {
        message(res.data.message);
    });
}
function removeItem(book) {
    ListService.removeItem(list.value.id, book.id, (res) => {
        message(res.data.message);
    });
}
</script>
<template>
    <Loading :visible="ListService.loading.value" />
    <Toast />
    <template v-if="list">
        <Toolbar>
            <Button
                @click="openModal = true"
                name="Add items"
                icon="fa-plus"
            ></Button>
            <Button
                name="Edit"
                icon="fa-pencil"
                :dropdown="[
                    {
                        name: 'Edit list',
                    },
                    {
                        name: 'Delete list',
                    },
                ]"
            ></Button>
            <Button name="Send list" icon="fa-envelope"></Button>
            <Button name="Print list" icon="fa-print"></Button>
        </Toolbar>
        <h3>Contents of {{ list.name }}</h3>
        <p>
            This list contains
            <span class="fw-bold">{{ list.items.length }}</span> titles
        </p>
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th style="width: 10%">Author</th>
                                <th style="width: 10%">Date added</th>
                                <th style="width: 20%">Locations</th>
                                <th style="width: 5%"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="item in list.items" :key="item.id">
                                <td>
                                    <RouterLink
                                        class="fw-bold"
                                        :to="`/admin/cataloging/book/${item.book.id}`"
                                    >
                                        {{ item.book.title }}
                                    </RouterLink>
                                    <p class="author">
                                        <span class="byAuthor">By </span>
                                        <a href="">{{
                                            item.book.authors.join(",")
                                        }}</a>
                                    </p>
                                    <p class="results_summary isbn">
                                        <span class="label">ISBN: </span
                                        ><span property="isbn">{{
                                            item.book.isbn
                                        }}</span>
                                    </p>
                                    <p class="results_summary rda264">
                                        <span class="label">Publisher: </span>
                                        <span
                                            property="rda264_name"
                                            typeof="Organization"
                                            ><span
                                                property="name"
                                                class="rda264_name"
                                                >{{ item.book.publisher }},
                                            </span></span
                                        >
                                        <span
                                            property="date"
                                            class="rda264_date"
                                            >{{ item.publish_date }}</span
                                        >
                                    </p>
                                    <p class="results_summary description">
                                        <span class="label">Description: </span
                                        >{{ item.book.page_count }} pages.
                                    </p>
                                    <p class="hold text-end mb-0">
                                        <a>Holds</a>

                                        |
                                        <RouterLink
                                            :to="{
                                                name: 'book-edit',
                                                params: { id: item.book.id },
                                            }"
                                        >
                                            Edit record
                                        </RouterLink>
                                        |
                                        <RouterLink
                                            :to="{
                                                name: 'book-items',
                                                params: { id: item.book.id },
                                            }"
                                            >Edit items</RouterLink
                                        >
                                    </p>
                                </td>
                                <td>{{ item.book.authors.join(",") }}</td>
                                <td>{{ item.added_on }}</td>
                                <td></td>
                                <td>
                                    <button
                                        @click="removeItem(item)"
                                        type="button"
                                        class="btn btn-outline-light btn-fw action"
                                    >
                                        <i class="fa fa-trash-o"></i>
                                        Remove
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </template>
    <Form @submit="addItem" class="forms-sample">
        <Modal @close="openModal = false" :open="openModal">
            <template v-slot:header>
                <h4 class="modal-title">Add items</h4>
            </template>
            <template v-slot:body>
                <Field
                    type="text"
                    name="barcode"
                    class="form-control p-1"
                    placeholder="Enter item's barcode here"
                    style="height: auto !important"
                />
                <ErrorMessage name="barcode" as="p" class="text-danger" />
            </template>
            <template v-slot:footer>
                <spinner :visible="ListService.submitting.value"></spinner>
                <button
                    :disabled="ListService.submitting.value"
                    type="submit"
                    class="btn btn-secondary m-0 ms-2"
                >
                    Save
                </button>
                <a @click="openModal = false" class="cancel"> Close</a>
            </template>
        </Modal>
    </Form>
</template>
