<script setup>
import { onMounted, ref } from "vue";
import { Field, Form } from "vee-validate";
import { useRoute, useRouter } from "vue-router";
import { UserService } from "@root/services/user/UserService";
import { useDataStore } from "@root/stores/data";
import Paginator from "primevue/paginator";
import Loading from "@admin/common/Loading.vue";
const data = ref(null);
const dataStore = useDataStore();
const route = useRoute();
const router = useRouter();
const libraries = ref([]);
onMounted(() => {
    UserService.getList(
        (res) => {
            data.value = res.data;
        },
        { type: "patron", page: route.query.page ?? 1 }
    );
});
function onPageChange(event) {
    UserService.getList(
        function (res) {
            console.log(res);
            data.value = res.data;
        },
        { type: "patron", ...route.query, page: event.page + 1 }
    );
    router.push({
        name: "patrons",
        query: { ...route.query, page: event.page + 1 },
    });
}
function search(values) {
    UserService.getList(
        (res) => {
            console.log(res);
            data.value = res.data;
        },
        { type: "patron", ...values }
    );
    router.push({ name: "patrons", query: { q: values.q } });
}
</script>
<template>
    <Loading
        :visible="UserService.loading.value || UserService.submitting.value"
    />
    <div v-if="data" class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <Form method="GET" @submit="search">
                        <div class="row">
                            <div class="form-group col-3 mb-0">
                                <Field
                                    type="text"
                                    name="q"
                                    class="form-control"
                                    placeholder="name or card"
                                />
                            </div>
                            <div class="form-group col-3 mb-0">
                                <Field
                                    name="library"
                                    as="select"
                                    class="form-control"
                                >
                                    <option value="">All</option>
                                    <option
                                        :value="item.id"
                                        v-for="item in dataStore.libraries"
                                    >
                                        {{ item.name }}
                                    </option>
                                </Field>
                            </div>
                            <div class="form-group col-3 mb-0">
                                <div
                                    class="form-group col-3 d-flex align-items-center gap-3"
                                >
                                    <button
                                        :disabled="UserService.submitting.value"
                                        type="submit"
                                        class="btn btn-secondary m-0"
                                    >
                                        Search
                                    </button>
                                </div>
                            </div>
                        </div>
                    </Form>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Card</th>
                                    <th>Name</th>
                                    <th>Date of birth</th>
                                    <th>Library</th>
                                    <th>Expires on</th>
                                    <th>Checkouts</th>
                                    <th>Fines</th>
                                    <th>Registers on</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="item in data.data" :key="item.id">
                                    <td>
                                        {{ item.user_meta.card_number }}
                                    </td>
                                    <td>
                                        <RouterLink
                                            :to="`/admin/patron/${item.id}`"
                                        >
                                            {{ item.name }}
                                        </RouterLink>
                                        <div class="address">
                                            <span>Email: </span>
                                            <a :href="`mailto:${item.email}`">{{
                                                item.email
                                            }}</a>
                                        </div>
                                    </td>
                                    <td>{{ item.user_meta.birthdate }}</td>
                                    <td>
                                        {{
                                            item.user_meta.library?.name ??
                                            "No limitation"
                                        }}
                                    </td>
                                    <td>
                                        {{ item.user_meta.expiration_date }}
                                    </td>
                                    <td>0 / 0</td>
                                    <td>0.00</td>
                                    <td>
                                        {{ item.user_meta.registration_date }}
                                    </td>
                                    <td>
                                        <RouterLink
                                            :to="{
                                                name: 'patron-edit',
                                                params: { id: item.id },
                                            }"
                                        >
                                            <button
                                                type="button"
                                                class="btn btn-outline-light btn-fw action"
                                            >
                                                <i
                                                    title="Edit library"
                                                    class="fa fa-pencil"
                                                ></i>
                                                Edit
                                            </button>
                                        </RouterLink>
                                        <button
                                            type="button"
                                            class="btn btn-outline-light btn-fw action"
                                        >
                                            <i
                                                title="Delete library"
                                                class="fa fa-trash-o"
                                            ></i>
                                            Delete
                                        </button>
                                        <br />
                                        <button
                                            type="button"
                                            class="btn btn-outline-light btn-fw action"
                                        >
                                            <i class="fa fa-barcode"></i>
                                            Checkout
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <Paginator
                            :rows="data.per_page"
                            :totalRecords="data.total"
                            :first="(data.current_page - 1) * data.per_page"
                            @page="onPageChange"
                        ></Paginator>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<style scoped>
table tbody td a:hover {
    text-decoration: underline;
}
.edit-form p[role="alert"] {
    margin-top: -1.75rem;
}
.table td img {
    border-radius: 0 !important;
}
</style>
