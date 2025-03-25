<script setup>
import { defineProps } from "vue";
import { Field, Form, ErrorMessage } from "vee-validate";
import { UserService } from "@root/services/user/UserService";
import Spinner from "@admin/common/Spinner.vue";
const props = defineProps(["change", "patron"]);
function changePassword(values, actions) {
    UserService.changePassword(props.patron.id, { values, actions }, (res) => {
        console.log(res);
    });
}
</script>
<template>
    <div class="col-md-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <Form
                    @submit="changePassword"
                    :initial-values="{
                        username: patron.username,
                    }"
                    :validation-schema="{}"
                >
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Username:</label>
                        <div class="col-sm-9">
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
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label"
                            >New password:</label
                        >
                        <div class="col-sm-9">
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
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label"
                            >Confirm new password:</label
                        >
                        <div class="col-sm-9">
                            <Field
                                type="password"
                                name="password_confirmation"
                                class="form-control"
                            />
                            <ErrorMessage
                                name=" password_confirmation"
                                as="p"
                                class="text-danger"
                            />
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-3">
                        <button
                            :disabled="UserService.submitting.value"
                            type="submit"
                            class="btn btn-secondary m-0"
                        >
                            Submit
                        </button>
                        <a
                            @click.prevent="$emit('changePassword')"
                            class="cancel"
                            >Cancel</a
                        >
                        <spinner
                            :visible="UserService.submitting.value"
                        ></spinner>
                    </div>
                </Form>
            </div>
        </div>
    </div>
</template>
