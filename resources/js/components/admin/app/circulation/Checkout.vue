<script setup>
import { ref } from "vue";
import { Field, Form, ErrorMessage } from "vee-validate";
import { UserService } from "@root/services/user/UserService";
import { CirculationService } from "@root/services/circulation/CirculationService";
import { useMessage } from "@root/functions";
import Toast from "primevue/toast";
import Spinner from "@admin/common/Spinner.vue";
const patron = ref(null);
const message = useMessage();
const circulation = ref(null);
function getPatron(values, actions) {
    UserService.reset(false).getByCard({ values, actions }, (res) => {
        patron.value = res.data;
    });
}
CirculationService.setError((error) => {
    if (error.status === 400) {
        patron.value = null;
        message(error.response.data.message, "error");
    }
});
function checkout(values) {
    values.patron_id = patron.value.id;
    CirculationService.checkout({ values }, (res) => {
        circulation.value = res.data.circulation;
        patron.value.loans = res.data.circulation.patron.loans;
    });
}
</script>
<template>
    <Toast />
    <div class="col-md-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <Form @submit="getPatron">
                    <div class="row align-items-start">
                        <div class="form-group col-8 mb-0">
                            <Field
                                type="text"
                                name="card"
                                class="form-control p-1"
                                placeholder="Enter patron card number"
                                style="height: auto !important"
                            />
                            <ErrorMessage
                                name="card"
                                as="p"
                                class="text-danger"
                            />
                        </div>
                        <div class="d-flex align-items-center gap-3 col-4 p-0">
                            <button
                                :disabled="UserService.loading.value"
                                type="submit"
                                class="btn btn-primary m-0"
                            >
                                <i
                                    style="font-size: 0.75rem"
                                    class="fa fa-arrow-right"
                                ></i>
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
    <template v-if="patron">
        <div class="col-md-6 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h5>
                        Checking out to {{ patron.name }}({{
                            patron.user_meta.card_number
                        }})
                    </h5>
                    <Form @submit="checkout" method="POST" class="forms-sample">
                        <div class="row">
                            <div class="form-group col-8 mb-0">
                                <Field
                                    type="text"
                                    name="barcode"
                                    class="form-control p-1"
                                    placeholder="Enter item barcode"
                                    style="height: auto !important"
                                />
                                <ErrorMessage
                                    name="name"
                                    as="p"
                                    class="text-danger"
                                />
                            </div>
                            <div class="d-flex align-items-center gap-3 col-4">
                                <button
                                    :disabled="
                                        CirculationService.submitting.value
                                    "
                                    type="submit"
                                    style="color: black"
                                    class="btn btn-secondary m-0"
                                >
                                    Checkout
                                </button>
                                <spinner
                                    :visible="
                                        CirculationService.submitting.value
                                    "
                                ></spinner>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="form-group col-8 mb-0">
                                <label>Specify due date (MM/DD/YYYY) :</label>
                                <Field
                                    type="date"
                                    name="due_date"
                                    class="form-control p-1"
                                    placeholder="Enter item barcode"
                                    style="height: auto !important"
                                />
                                <ErrorMessage
                                    name="due_date"
                                    as="p"
                                    class="text-danger"
                                />
                            </div>
                        </div>
                    </Form>
                </div>
            </div>
        </div>
        <div v-if="circulation" class="lastchecked col-md-6 mb-2">
            <p class="mb-0">
                <strong>Checked out: </strong>
                {{ circulation.book_item.book.title }} ({{
                    circulation.book_item.barcode
                }}). Due on {{ circulation.due_date }}
            </p>
        </div>
        <div class="home-tab tabs">
            <div class="d-sm-flex align-items-center justify-content-between">
                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation">
                        <a
                            class="nav-link v-link active"
                            id="home-tab"
                            data-bs-toggle="tab"
                            href="#checkouts"
                            role="tab"
                            aria-controls="checkouts"
                            aria-selected="true"
                            >Checkouts ({{ patron.loans.length }})</a
                        >
                    </li>
                    <li role="presentation">
                        <a
                            class="nav-link v-link"
                            id="profile-tab"
                            data-bs-toggle="tab"
                            href="#audiences"
                            role="tab"
                            aria-selected="false"
                            tabindex="-1"
                            >Holds ({{ patron.holds.length }})</a
                        >
                    </li>
                </ul>
            </div>
            <div class="tab-content tab-content-basic mt-0">
                <div
                    class="tab-pane fade active show"
                    id="checkouts"
                    role="tabpanel"
                    aria-labelledby="checkouts"
                >
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Due date</th>
                                    <th>Title</th>
                                    <th>Checked out on</th>
                                    <th>Checked out from</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="(item, index) in patron.loans"
                                    :key="item.id"
                                >
                                    <td>{{ item.due_date }}</td>
                                    <td>{{ item.book_item.book.title }}</td>
                                    <td>{{ item.checkout_date }}</td>
                                    <td>
                                        {{ item.book_item.home_library.name }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div
                    class="tab-pane fade show"
                    id="audiences"
                    role="tabpanel"
                    aria-labelledby="audiences"
                >
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Placed on</th>
                                    <th>Expires on</th>
                                    <th>Pickup location</th>
                                    <th>Status</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="(item, index) in patron.holds"
                                    :key="item.id"
                                >
                                    <td>
                                        <RouterLink
                                            >{{ item.book.title }} /
                                        </RouterLink>
                                        {{ item.book.authors.join(",") }}
                                    </td>
                                    <td>{{ item.placed_at }}</td>
                                    <td>Never expires</td>
                                    <td>
                                        {{ item.pickup_location.name }}
                                    </td>
                                    <td>{{ item.status }}</td>
                                    <td>
                                        <button class="btn btn-default action">
                                            action
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </template>
</template>
