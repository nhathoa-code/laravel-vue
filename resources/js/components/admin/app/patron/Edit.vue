<script setup>
import { onMounted, ref } from "vue";
import { Field, Form, ErrorMessage } from "vee-validate";
import { UserService } from "@root/services/user/UserService";
import { useDataStore } from "@root/stores/data";
import { useRoute } from "vue-router";
import { formattedDate } from "@root/functions";
import Spinner from "@admin/common/Spinner.vue";
import Loading from "@admin/common/Loading.vue";
import Toast from "primevue/toast";
import * as yup from "yup";
const route = useRoute();
const patron = ref(null);
const dataStore = useDataStore();
const schema = yup.object({
    // isbn: yup.string().required("vui lòng nhập isbn"),
    // title: yup.string().required("vui lòng nhập tiêu đề sách"),
});

onMounted(() => {
    UserService.getById(route.params.id, (res) => {
        console.log(res);
        patron.value = res.data;
    });
});
function updatePatron(values, actions) {
    UserService.update(patron.value.id, { values, actions }, (res) => {
        console.log(res);
    });
}
</script>
<template>
    <Loading :visible="UserService.loading.value" />
    <Toast />
    <Form
        v-if="patron"
        method="POST"
        @submit="updatePatron"
        :validation-schema="schema"
        :initial-values="{
            name: patron.name,
            birthdate: patron.user_meta.birthdate,
            address: patron.user_meta.address,
            card_number: patron.user_meta.card_number,
            email: patron.email,
            phone: patron.user_meta.phone,
            username: patron.username,
            gender: patron.user_meta.gender,
            birthdate: formattedDate(patron.user_meta.birthdate),
            registration_date: formattedDate(
                patron.user_meta.registration_date
            ),
            expiration_date: formattedDate(patron.user_meta.expiration_date),
            library: patron.user_meta.library?.id ?? null,
            role: patron.role,
        }"
        class="forms-sample row"
    >
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body row">
                    <div class="form-group col-6">
                        <label>Name</label>
                        <Field
                            type="text"
                            name="name"
                            class="form-control"
                            placeholder="name"
                        />
                        <ErrorMessage name="name" as="p" class="text-danger" />
                    </div>
                    <div class="form-group col-3">
                        <label>Birth date</label>
                        <Field
                            type="date"
                            name="birthdate"
                            class="form-control"
                            placeholder="Birth date"
                        />
                        <ErrorMessage
                            name="birthdate"
                            as="p"
                            class="text-danger"
                        />
                    </div>
                    <div class="form-group col-3">
                        <label>Gender</label>
                        <div>
                            <Field name="gender" type="radio" value="male" />
                            Male
                            <Field name="gender" type="radio" value="female" />
                            Female
                        </div>
                        <ErrorMessage
                            name="gender"
                            as="p"
                            class="text-danger"
                        />
                    </div>
                    <div class="form-group col-6">
                        <label>Address</label>
                        <Field
                            type="text"
                            name="address"
                            class="form-control"
                            placeholder="address"
                        />
                        <ErrorMessage
                            name="address"
                            as="p"
                            class="text-danger"
                        />
                    </div>
                    <div class="form-group col-3">
                        <label>Card number</label>
                        <Field
                            type="text"
                            name="card_number"
                            class="form-control"
                            placeholder="card number"
                        />
                        <ErrorMessage
                            name="card_number"
                            as="p"
                            class="text-danger"
                        />
                    </div>
                    <div class="form-group col-3">
                        <label>Library</label>
                        <Field name="library" as="select" class="form-control">
                            <option value="">All</option>
                            <option
                                :value="item.id"
                                v-for="item in dataStore.libraries"
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
                    <div class="form-group col-3">
                        <label>Email</label>
                        <Field type="text" name="email" class="form-control" />
                        <ErrorMessage name="email" as="p" class="text-danger" />
                    </div>
                    <div class="form-group col-3">
                        <label>Phone</label>
                        <Field type="text" name="phone" class="form-control" />
                        <ErrorMessage name="phone" as="p" class="text-danger" />
                    </div>
                    <div class="form-group col-3">
                        <label>Registration date</label>
                        <Field
                            type="date"
                            name="registration_date"
                            class="form-control"
                        />
                        <ErrorMessage
                            name="registration_date"
                            as="p"
                            class="text-danger"
                        />
                    </div>
                    <div class="form-group col-3">
                        <label>Expiration date</label>
                        <Field
                            type="date"
                            name="expiration_date"
                            class="form-control"
                        />
                        <ErrorMessage
                            name="expiration_date"
                            as="p"
                            class="text-danger"
                        />
                    </div>
                    <div class="form-group col-3">
                        <label>Username</label>
                        <Field
                            type="text"
                            name="username"
                            class="form-control"
                        />
                        <ErrorMessage
                            name="username"
                            as="p"
                            class="text-danger"
                        />
                    </div>
                    <div class="form-group col-3">
                        <label>Password</label>
                        <Field
                            type="password"
                            name="password"
                            class="form-control"
                        />
                        <ErrorMessage
                            name="password"
                            as="p"
                            class="text-danger"
                        />
                    </div>
                    <Field
                        type="hidden"
                        name="role"
                        value="patron"
                        class="form-control"
                    />
                    <div class="d-flex align-items-center gap-3">
                        <button
                            :disabled="UserService.submitting.value"
                            type="submit"
                            class="btn btn-secondary m-0"
                        >
                            Update
                        </button>
                        <spinner
                            :visible="UserService.submitting.value"
                        ></spinner>
                    </div>
                </div>
            </div>
        </div>
    </Form>
</template>
