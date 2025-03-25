<script setup>
import { Field, Form, ErrorMessage } from "vee-validate";
import { ListService } from "@root/services/book/ListService";
import { useUserStore } from "@root/stores/user";
import Spinner from "@admin/common/Spinner.vue";
import * as yup from "yup";
const userStore = useUserStore();
const schema = yup.object({
    // isbn: yup.string().required("vui lòng nhập isbn"),
    // title: yup.string().required("vui lòng nhập tiêu đề sách"),
});
function insertList(values, actions) {
    ListService.create({ values, actions }, (res) => {
        console.log(res);
    });
}
</script>
<template>
    <div class="row">
        <h3>
            Owner: <RouterLink :to="''">{{ userStore.user.name }}</RouterLink>
        </h3>
        <div class="col-md-6 grid-margin stretch-card">
            <div class="card">
                <div class="card-body row">
                    <Form
                        method="POST"
                        @submit="insertList"
                        :validation-schema="schema"
                        class="forms-sample row"
                    >
                        <div class="form-group col-12">
                            <label>Name:</label>
                            <Field
                                type="text"
                                name="name"
                                class="form-control"
                            />
                            <ErrorMessage
                                name="name"
                                as="p"
                                class="text-danger"
                            />
                        </div>
                        <div class="form-group col-12">
                            <label>List type:</label>
                            <Field name="type" as="select" class="form-control">
                                <option value="private">private</option>
                                <option value="public">public</option>
                            </Field>
                            <ErrorMessage
                                name="type"
                                as="p"
                                class="text-danger"
                            />
                        </div>
                        <div class="form-group col-12">
                            <label>Sort by:</label>
                            <Field
                                name="sort_by"
                                as="select"
                                class="form-control"
                            >
                                <option value="title">Title</option>
                                <option value="author">Author</option>
                                <option value="date_added">Date added</option>
                            </Field>
                            <ErrorMessage
                                name="sort_by"
                                as="p"
                                class="text-danger"
                            />
                        </div>
                        <div class="form-group col-12">
                            <label>Allow changes to contents from:</label>
                            <Field
                                name="allow_changes_from"
                                as="select"
                                class="form-control"
                            >
                                <option value="0">Nobody</option>
                                <option value="1">Owner only</option>
                                <option value="2">
                                    Anyone seeing this list
                                </option>
                            </Field>
                            <ErrorMessage
                                name="allow_changes_from"
                                as="p"
                                class="text-danger"
                            />
                        </div>
                        <Field
                            type="hidden"
                            name="owner"
                            :value="userStore.user.id"
                        />
                        <div class="d-flex align-items-center gap-3">
                            <button
                                :disabled="ListService.submitting.value"
                                type="submit"
                                class="btn btn-secondary m-0"
                            >
                                Save
                            </button>
                            <spinner
                                :visible="ListService.submitting.value"
                            ></spinner>
                        </div>
                    </Form>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <ul>
                <li>
                    <p>
                        A <strong>Private</strong> list is managed by you and
                        can be seen only by you.
                    </p>
                </li>
                <li>
                    <p>
                        A <strong>Public</strong> list can be seen only by
                        everyone.
                        <br />
                        But managed only by you
                    </p>
                </li>
                <li>
                    <p>
                        The <strong>Owner</strong> of a list is always allowed
                        to add or remove entries.
                    </p>
                </li>
            </ul>
        </div>
    </div>
</template>
