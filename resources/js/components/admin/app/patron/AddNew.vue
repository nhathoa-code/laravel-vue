<script setup>
import { onMounted, ref } from "vue";
import { Field, Form, ErrorMessage } from "vee-validate";
import Spinner from "@admin/common/Spinner.vue";
import * as yup from "yup";
const submmitted = ref(false);
const libraries = ref([]);
const selectedLibrary = ref("");
const today = ref(new Date().toISOString().split("T")[0]);
const schema = yup.object({
    // isbn: yup.string().required("vui lòng nhập isbn"),
    // title: yup.string().required("vui lòng nhập tiêu đề sách"),
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
function insertPatron(values, actions) {
    submmitted.value = true;
    axios
        .post("api/users", values)
        .then((response) => {
            console.log(response);
            //$toast.success(response.data.message);
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
    <Form
        method="POST"
        @submit="insertPatron"
        :validation-schema="schema"
        :initial-values="{ registration_date: today }"
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
                        <Field
                            v-model="selectedLibrary"
                            name="library"
                            as="select"
                            class="form-control"
                        >
                            <option value="">All</option>
                            <option :value="item.id" v-for="item in libraries">
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
                            :disabled="submmitted"
                            type="submit"
                            class="btn btn-secondary m-0"
                        >
                            Save
                        </button>
                        <spinner :visible="submmitted"></spinner>
                    </div>
                </div>
            </div>
        </div>
    </Form>
</template>
