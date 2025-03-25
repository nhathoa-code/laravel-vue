<script setup>
import { onMounted } from "vue";
import { Field, Form, ErrorMessage } from "vee-validate";
import { useRouter } from "vue-router";
import { useDataStore } from "@root/stores/data";
import { useUserStore } from "@root/stores/user";
import { StaffService } from "@root/services/user/StaffService";
import { LibraryService } from "@root/services/administration/LibraryService";
import Spinner from "@admin/common/Spinner.vue";
import * as yup from "yup";
const dataStore = useDataStore();
const userStore = useUserStore();
const router = useRouter();
onMounted(() => {
    LibraryService.getList((res) => {
        dataStore.setLibraries(res.data);
    });
});
function login(values, actions) {
    StaffService.login({ values, actions }, (res) => {
        userStore.setUser(res.data);
        router.push("/admin");
    });
}
</script>
<template>
    <div class="d-flex justify-content-center">
        <div
            v-if="dataStore.libraries"
            id="login"
            class="col-md-3 grid-margin stretch-card"
        >
            <div class="card">
                <div class="card-body">
                    <h1><a href="http://koha-community.org"></a></h1>
                    <Form
                        method="POST"
                        @submit="login"
                        :validation-schema="
                            yup.object({
                                email: yup
                                    .string()
                                    .required('Please enter an email'),
                                password: yup
                                    .string()
                                    .required('Please enter your password'),
                            })
                        "
                        class="forms-sample"
                    >
                        <div class="form-group">
                            <label>Username:</label>
                            <Field
                                type="text"
                                name="email"
                                class="form-control"
                            />
                            <ErrorMessage
                                name="email"
                                as="p"
                                class="text-danger"
                            />
                        </div>
                        <div class="form-group">
                            <label>Password:</label>
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
                        <div class="form-group">
                            <label>Library:</label>
                            <select class="form-control" name="library">
                                <option selected>Lựa chọn</option>
                                <option
                                    v-for="item in dataStore.libraries"
                                    :key="item.id"
                                >
                                    {{ item.name }}
                                </option>
                            </select>
                        </div>
                        <div
                            class="d-flex justify-content-end align-items-center gap-3"
                        >
                            <spinner
                                :visible="StaffService.submitting.value"
                            ></spinner>
                            <button
                                :disabled="StaffService.submitting.value"
                                type="submit"
                                class="btn btn-secondary m-0"
                            >
                                Log in
                            </button>
                        </div>
                    </Form>
                </div>
            </div>
        </div>
    </div>
</template>
<style scoped>
#login {
    box-shadow: 8px 8px 12px rgb(170 170 170);
}
#login h1 {
    background: url(https://intranet.bywatersolutions.com/intranet-tmpl/prog/img/koha-logo.gif)
        no-repeat top center;
    margin-top: 0;
    margin-bottom: 0.5em;
}
#login h1 a {
    display: block;
    height: 74px;
    text-indent: 100%;
    white-space: nowrap;
    overflow: hidden;
}
.btn {
    border: none;
    font-weight: bold;
}
#login {
    margin-top: 5%;
}
</style>
